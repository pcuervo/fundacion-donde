<?php
/**
 * AIT Import Plugin
 *
 * @package   AitImport
 * @author    AitThemes.com <info@ait-themes.com>
 * @copyright 2013 AitThemes
 * @link      http://www.AitThemes.com/
 */

/**
 * Plugin class.
 *
 * @package AitImport
 * @author  AitThemes.com <info@ait-themes.com>
 */

class AitImport {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	protected $version = '1.6';

	/**
	 * Unique identifier for your plugin.
	 *
	 * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
	 * match the Text Domain file header in the main plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'ait-import';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * If save empty string values
	 *
	 * @since 1.1
	 * 
	 * @var boolean
	 */
	protected $save_empty_values = false;

	/**
	 * Is current theme from AIT
	 * @var boolean
	 */
	protected $ait_theme;

	/**
	 * List of custom types
	 * @var array of AitImportType
	 */
	public $post_types = array();

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );
		add_action( 'admin_menu', array( $this, 'add_sales_reports_admin_menu' ) );
		
		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		$this->ait_theme = (isset($GLOBALS['aitThemeCustomTypes'])) ? true : false;

		$this->post_types = $this->get_theme_custom_types();

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	 */
	public static function activate( $network_wide ) {
		$carga_csv = AitImport::get_instance();
		$carga_csv->create_bulk_load_table();
		$carga_csv->create_bulk_load_detail_table();
	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {
		global $wpdb;
		$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}bulk_load" );
		$wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}bulk_load_detail" );
	}

	/**
	 * Create table "bulk_load"
	 */
	private function create_bulk_load_table(){
		global $wpdb;
		$table_name = $wpdb->prefix . 'bulk_load';
		if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
			$charset_collate = $wpdb->get_charset_collate();
			$sql = "CREATE TABLE $table_name (
					  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					  `date` datetime DEFAULT NULL,
					  `file` varchar(500) DEFAULT NULL,
					  `comentarios` varchar(500) DEFAULT NULL,
					  `status` varchar(45) DEFAULT NULL,
					  `ajuste` varchar(45) DEFAULT '0',
					  PRIMARY KEY (`id`)
					)
					$charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}
	}// create_bulk_load_table

	/**
	 * Create table "bulk_load_detail"
	 */
	private function create_bulk_load_detail_table(){
		global $wpdb;

		$table_name = $wpdb->prefix . 'bulk_load_detail';
		if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
			$charset_collate = $wpdb->get_charset_collate();
			$sql = "CREATE TABLE $table_name (
					  	`bulk_load_id` int(10) unsigned DEFAULT NULL,
						`post_id` int(10) unsigned DEFAULT NULL,
						`sku` varchar(100) DEFAULT NULL,
						`cantidad` int(11) DEFAULT NULL,
						`comentarios` varchar(500) DEFAULT NULL,
						`status` varchar(3) DEFAULT NULL
					)
					$charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}
	}// create_bulk_load_detail_table

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $screen->id == $this->plugin_screen_hook_suffix ) {
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'css/admin.css', __FILE__ ), $this->version );
		}

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $screen->id == $this->plugin_screen_hook_suffix ) {
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery' ), $this->version );
		}

	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		if( is_admin() && current_user_can("manage_options") ) {
			$this->plugin_screen_hook_suffix = add_menu_page(
				__('Plugin para Import CSV a WordPress', 'ait-import'),
				__('Importar CSV', 'ait-import'),
				'read',
				$this->plugin_slug,
				array( $this, 'display_plugin_admin_page' ),
				'dashicons-nametag'
			);
		}

	}

	/**
	 * Register the administration menu for sales reports.
	 *
	 * @since    1.0.0
	 */
	public function add_sales_reports_admin_menu() {

		if( is_admin() && current_user_can("manage_options") ) {
			$this->plugin_screen_hook_suffix = add_menu_page(
				__('Reportes de ventas', 'sales-report'),
				__('Reportes de Ventas', 'sales-report'),
				'read',
				'sales-report',
				array( $this, 'display_reports_admin_page' ),
				'dashicons-index-card',
				2
			);
		}

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_reports_admin_page() {
		include_once( 'views/reports.php' );
	}

	/**
	 * Get post types and custom post types for this theme
	 *
	 * @since 1.0.0
	 * 
	 * @return array array of AitImportType objects
	 */
	public function get_theme_custom_types() {
		$post_types_import = array();
		$post_types_import[] = new AitImportType('product', false);
		//$post_types_import[] = new AitImportType('post', false);
		//$post_types_import[] = new AitImportType('page', false);
		
		if($this->ait_theme){
			global $aitThemeCustomTypes;
			$post_types = array_keys($aitThemeCustomTypes);
			// if(in_array("dir-item-tour", $post_types)){
			// 	$post_types[] = "dir-item-tour-offer";
			// }
			foreach ($post_types as $key => $type) {
				$id_type = "ait-" . $type;
				if($type == "dir-item-tour") $id_type = "ait-dir-item";
				// if($type == "dir-item-tour-offer") $id_type = "ait-dir-item-offer";
				$post_types_import[] = new AitImportType($id_type, true, $type);
			}
		}
		//var_dump($post_types_import);
		return $post_types_import;
	}

	/**
	 * Get attachment ID
	 *
	 * @since 1.0.0
	 * 
	 * @param  string $image_url      url of the image to find
	 * 
	 */
	public function pippin_get_image_id($image_url) {
	    global $wpdb;
	    $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url )); 
	        return $attachment[0]; 
	}

	/**
	 * Validate Row
	 *
	 * @since 1.0.0
	 * 
	 * @param  string $data      row in array format
	 * 
	 */
	/*
	public function validate_row($data) {
		//VALIDAR QUE LOS CAMPOS REQUERIDOS ESTEN SETEADOS Y NO ESTEN VACIOS
		if(!isset($data[0]) || empty($data[0]) || !isset($data[1]) || empty($data[1]) || !isset($data[2]) || empty($data[2]) || !isset($data[4]) || empty($data[4]) || !isset($data[5]) || empty($data[5]) || !isset($data[6]) || empty($data[6]) || !isset($data[7]) || empty($data[7]) || !isset($data[13]) || empty($data[13]) ) {
	    	return true;
	    }
	    //VALIDAR QUE LA CANTIDAD SEA UN VALOR NUMERICO >= 1
	    if($data[4] < 0 || !is_numeric($data[4])) {
	    	return true;
	    }
	    //VALIDAR QUE SI EL AÑO NO ESTA VACIO, QUE CONTENGA UN VALOR NUMERICO
	    if(!is_numeric($data[16]) ) {
		    if(!is_numeric($data[16])) {
		    	return true;
		    }
		}

		//VALIDACION PARA LA CATEGORIA Relojes
		if($data[5] == 'Relojes') {
			//VALIDAR QUE LAS SUBCATEGORIAS SEAN SOLO LAS PERMITIDAS
			if($data[6] != 'dama' && $data[6] != 'caballero') {
				return true;
			}
		}
		//VALIDACION PARA LA CATEGORIA Relojes
		if($data[5] == 'Joyas') {
			//VALIDAR QUE LAS SUBCATEGORIAS SEAN SOLO LAS PERMITIDAS
			if($data[6] != 'aretes' && $data[6] != 'cadenas' && $data[6] != 'dijes' && $data[6] != 'pulseras' && $data[6] != 'anillos' && $data[6] != 'broqueles' ) {
				return true;
			}
		}
		//VALIDACION PARA LA CATEGORIA Relojes
		if($data[5] == 'Electrónicos') {
			//VALIDAR QUE LAS SUBCATEGORIAS SEAN SOLO LAS PERMITIDAS
			if($data[6] != 'celulares' && $data[6] != 'tablets' && $data[6] != 'pantallas') {
				return true;
			}
		}
		return false;
	}
	*/
	function sanitize_txt ( $text ) {
        $san_text = filter_var($text, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_HIGH | FILTER_FLAG_STRIP_LOW ) ;
        return $san_text;
    }
    

    function deshacer_carga($bulk_id) {
    	global $wpdb;
    	$actu = true;
    	$actu_detail = true;
    	$bulk_detail = $wpdb->get_results("SELECT bld.* FROM ".$wpdb->prefix."bulk_load_detail bld WHERE bld.status = '1' and bld.bulk_load_id = ".$_POST["bulk_id"]);
    	foreach ($bulk_detail as $detail) {
    		$ventas = $wpdb->get_results("SELECT p.* FROM ".$wpdb->prefix."woocommerce_order_itemmeta p WHERE meta_key = '_product_id' and meta_value = ".$detail->post_id);

    		$produ = get_post($detail->post_id);
    		$meta_existe = get_post_meta($detail->post_id);
			$stock = $meta_existe['_stock'][0] - $detail->cantidad;
			update_post_meta( $detail->post_id, '_stock', $stock );
			$papa_id = wp_get_post_parent_id( $detail->post_id );
			echo $detail->post_id.' ventas('.count($ventas).') ['.$meta_existe['_stock'][0].' - '.$detail->cantidad.'] STOCK('.$stock.')<br>';
			if(count($ventas) <= 0 && $stock <= 0) {
				
				wp_delete_post($detail->post_id);
				echo '<div class="error"><p><h5>PRODUCTO ELIMINADO: <strong>['.$produ->post_title.' - SKU : '.$detail->sku.']</strong></h5>'.$respues.'</p></div>';
				//ELIMINAR EL PADRE NI NO TIENE HIJOS
				if($papa_id) {
					$args = array(
						'post_parent' => $papa_id,
						'post_type'   => 'product_variation', 
						'numberposts' => -1,
						'post_status' => 'publish' 
					);
					$children = get_children( $args );
					//echo "hijos del padre -> ".count($children).'<br>';
					if(count($children) <= 0) {
						wp_delete_post($papa_id);
					}
				}
			}
			else {
				if($stock <= 0) {
					update_post_meta( $detail->post_id, '_stock_status', 'outofstock' );

					$enstock = false;
					if($papa_id) {
						$args = array(
							'post_parent' => $papa_id,
							'post_type'   => 'product_variation', 
							'numberposts' => -1,
							'post_status' => 'publish' 
						);
						$children = get_children( $args );
						foreach ($children as $child ) {
							$stock_hijo = get_post_meta( $child->ID, '_stock', true );
							if($stock_hijo > 0) {
								$enstock = true;
								break;
							}
						}
						if(!$enstock) {
							update_post_meta( $papa_id, '_stock_status', 'outofstock' );
						}
					}
				}
				else {
					if(count($ventas) > 0) {
						echo '<div class="notice"><p><h5>EL PRODUCTO NO SE PUEDE ELIMINAR PORQUE YA SE REGISTRO EN AL MENOS UNA COMPRA: <strong>['.$produ->post_title.' - SKU : '.$detail->sku.']</strong></h5>'.$respues.'</p></div>';
						$actu_detail = false;
						$actu = false;
					}
					else {
						update_post_meta( $detail->post_id, '_stock_status', 'instock' );
						update_post_meta( $papa_id, '_stock_status', 'instock' );
					}
				}
			}

			if($actu_detail) {
				$wpdb->update(
							$wpdb->prefix . 'bulk_load_detail',
							array( 'status' => '0' ),
							array( 'bulk_load_id' => $bulk_id, 'post_id' => $detail->post_id ),
							array( '%s' ),
							array( '%d', '%d' )
						);
			}
    	}

    	if($actu) {
	    	$wpdb->update(
							$wpdb->prefix . 'bulk_load',
							array( 'status' => '0' ),
							array( 'id' => $bulk_id ),
							array( '%s' ),
							array( '%d' )
						);
	    }

    }

    function eliminar_detalle($bulk_id, $post_id, $cant) {
    	global $wpdb;
    	$meta_existe = get_post_meta($post_id);
		$stock = $meta_existe['_stock'][0] - $cant;
		update_post_meta( $post_id, '_stock', $stock );

		$wpdb->update(
					$wpdb->prefix . 'bulk_load_detail',
					array( 'status' => '0' ),
					array( 'bulk_load_id' => $bulk_id, 'post_id' => $post_id ),
					array( '%s' ),
					array( '%d', '%d' )
				);
    }

    function restaurar_detalle($bulk_id, $post_id, $cant) {
    	global $wpdb;
    	$meta_existe = get_post_meta($post_id);
		$stock = $meta_existe['_stock'][0] + $cant;
		update_post_meta( $post_id, '_stock', $stock );

		$wpdb->update(
					$wpdb->prefix . 'bulk_load_detail',
					array( 'status' => '1' ),
					array( 'bulk_load_id' => $bulk_id, 'post_id' => $post_id ),
					array( '%s' ),
					array( '%d', '%d' )
				);
    }

    public function validate_headers($data) {
		$i = 0;
		$ok = true;
		$respu = '';
		if($data[$i] != 'sucursal') {
			$respu .= 'Columna '.$i.' ('.$data[$i].') debe llamarse "sucursal"<br>';
			$ok = false;
		}
		$i++;
		if($data[$i] != 'estado') {
			$respu .= 'Columna '.$i.' ('.$data[$i].') debe llamarse "estado"<br>';
			$ok = false;
		}
		$i++;
		if($data[$i] != 'sku') {
			$respu .= 'Columna '.$i.' ('.$data[$i].') debe llamarse "sku"<br>';
			$ok = false;
		}
		$i++;
		if($data[$i] != 'nombre') {
			$respu .= 'Columna '.$i.' ('.$data[$i].') debe llamarse "nombre"<br>';
			$ok = false;
		}
		$i++;
		if($data[$i] != 'precio') {
			$respu .= 'Columna '.$i.' ('.$data[$i].') debe llamarse "precio"<br>';
			$ok = false;
		}
		$i++;
		if($data[$i] != 'precio_oferta') {
			$respu .= 'Columna '.$i.' ('.$data[$i].') debe llamarse "precio_oferta"<br>';
			$ok = false;
		}
		$i++;
		if($data[$i] != 'precio_normal') {
			$respu .= 'Columna '.$i.' ('.$data[$i].') debe llamarse "precio_oferta"<br>';
			$ok = false;
		}
		$i++;
		if($data[$i] != 'descripcion') {
			$respu .= 'Columna '.$i.' ('.$data[$i].') debe llamarse "descripcion"<br>';
			$ok = false;
		}
		$i++;
		if($data[$i] != 'cantidad') {
			$respu .= 'Columna '.$i.' ('.$data[$i].') debe llamarse "cantidad"<br>';
			$ok = false;
		}
		$i++;
		if($data[$i] != 'categoria') {
			$respu .= 'Columna '.$i.' ('.$data[$i].') debe llamarse "categoria"<br>';
			$ok = false;
		}
		$i++;
		if($data[$i] != 'subcategoria') {
			$respu .= 'Columna '.$i.' ('.$data[$i].') debe llamarse "subcategoria"<br>';
			$ok = false;
		}
		$i++;
		if($data[$i] != 'foto1') {
			$respu .= 'Columna '.$i.' ('.$data[$i].') debe llamarse "foto1"<br>';
			$ok = false;
		}
		$i++;
		if($data[$i] != 'foto2') {
			$respu .= 'Columna '.$i.' ('.$data[$i].') debe llamarse "foto2"<br>';
			$ok = false;
		}
		$i++;
		if($data[$i] != 'foto3') {
			$respu .= 'Columna '.$i.' ('.$data[$i].') debe llamarse "foto3"<br>';
			$ok = false;
		}
		$i++;
		if($data[$i] != 'foto4') {
			$respu .= 'Columna '.$i.' ('.$data[$i].') debe llamarse "foto4"<br>';
			$ok = false;
		}
		$i++;
		if($data[$i] != 'foto5') {
			$respu .= 'Columna '.$i.' ('.$data[$i].') debe llamarse "foto5"<br>';
			$ok = false;
		}
		$i++;
		if($data[$i] != 'foto6') {
			$respu .= 'Columna '.$i.' ('.$data[$i].') debe llamarse "foto6"<br>';
			$ok = false;
		}
		$i++;
		if($ok) {
			$respu .= "OK";
		}
		return $respu;
	}

	public function validate_row($data) {
		global $wpdb;
		$i = 0;
		$ok = true;
		$respu = '';
		for($i = 0; $i<=19; $i++) {
			//VALIDAR CAMPOS OBLIGAROTIOS
			$col = $i+1;
			if(in_array($i, array(0,1,2,3,4,8,9,10,11 ))) {
				if(!isset($data[$i]) || empty($data[$i])) {
					$respu .= 'La columna '.$col.' es obligatoria, no debe estar vacia.<br>';
					$ok = false;
				}	
			}

			//VALIDAR QUE LA SUCURSAL EXISTA
			if($i == 0) {
				$sucu = $wpdb->get_var("SELECT meta_value FROM $wpdb->postmeta WHERE meta_value = '".$data[$i]."' and meta_key = 'numero_sucursal'");
				if(is_null($sucu)) {
					$respu .= 'No se encontro la "sucursal" con numero de sucursal <strong>'.$data[$i].'</strong>.<br>';
					$ok = false;
				}
			}

			//VALIDAR QUE EL ESTADO DEL PRODUCTO ESTE ENTRE LOS VALORES ACCEPTADOS
			if($i == 1) {
				if($data[$i] != 'Excelente' && $data[$i] != 'Muy bueno' && $data[$i] != 'Bueno' && $data[$i] != 'Nuevo') {
					$respu .= 'La columna "estado" solo admite los valores "Excelente|Muy bueno|Bueno|Nuevo". Valor recibido ['.$data[$i].']<br>';
					$ok = false;
				}
			}

			//VALIDAR QUE LA COLUMNA UPC NO CONTENGA +
			if($i == 2) {
				if( strpos( $data[$i], '+' ) !== false ) {
				    $respu .= 'La columna '.$col.' no debe tener el caracter "+".<br>';
					$ok = false;
				}
			}

			//VALIDAR CAMPOS NUMERICOS
			if(in_array($i, array(4,8))) {
				if($data[$i] < 0 || !is_numeric($data[$i])) {
			    	$respu .= 'La columna '.$col.' debe tener un valor numerico mayor que 0.<br> Valor recibido ('.$data[$i].')';
			    	$ok = false;
			    }
			}
			if($i==19) {
				//VALIDAR QUE SI EL AÑO NO ESTA VACIO, QUE CONTENGA UN VALOR NUMERICO
			    if(!empty($data[$i]) && $data[$i] != '' ) {
				    if(!is_numeric($data[$i])) {
				    	$respu .= 'La columna '.$col.' (año) debe tener un valor numerico.<br>';
			    		$ok = false;
				    }
				}
			}
			if($i==9) {
				//VALIDACION PARA LA CATEGORIA Relojes
				//$cate = $data[$i];
				$cate = $this->sanitize_txt($data[$i]);
				
				$catsText = '';
				$catsArray = array();

				$args = array(
			       'hierarchical' => 1,
			       'show_option_none' => '',
			       'hide_empty' => 0,
			       'parent' => 0,
			       'taxonomy' => 'product_cat'
			    );
			  	$categorias = get_categories($args);
				foreach ($categorias as $cat) {
					if ($cat->category_parent == 0) {
				  		$catsArray[] = $cat->slug;
				  		$catsText .= $cat->slug.'|';
				  	}
			  	}
			  	
			  	if(!in_array($cate, $catsArray)) {
			  		$respu .= 'La columna categoria solo admite los valores "'.$catsText.'". Valor recibido ['.$cate.']';
					$ok = false;
				}
				else {
					if($data[$i] == 'Relojes' || $data[$i] == 'relojes') {
						//VALIDAR QUE LAS SUBCATEGORIAS SEAN SOLO LAS PERMITIDASF
						$category = get_term_by('name', 'Relojes', 'product_cat');
						//var_dump($category);
						$args = array(
					       'hierarchical' => 1,
					       'show_option_none' => '',
					       'hide_empty' => 0,
					       'parent' => $category->term_id,
					       'taxonomy' => 'product_cat'
					    );
					  	$subcats = get_categories($args);
					  	$subsArray = array();
					  	$subsText = '';
					  	foreach ($subcats as $sub) {
					  		$subsArray[] = $sub->slug;
					  		$subsText .= $sub->slug.'|';
					  	}
					  	
						if(!in_array($data[10], $subsArray)) {
							$respu .= 'La categoria "'.$data[$i].'" solo permite los valores "'.$subsText.'". Valor recibido ['.$data[10].']';
							$ok = false;
						}
					}
					//VALIDACION PARA LA CATEGORIA Relojes
					if($data[$i] == 'Joyas' || $data[$i] == 'joyas') {
						//VALIDAR QUE LAS SUBCATEGORIAS SEAN SOLO LAS PERMITIDAS
						$category = get_term_by('name', 'Joyas', 'product_cat');
						//var_dump($category);
						$args = array(
					       'hierarchical' => 1,
					       'show_option_none' => '',
					       'hide_empty' => 0,
					       'parent' => $category->term_id,
					       'taxonomy' => 'product_cat'
					    );
					  	$subcats = get_categories($args);
					  	$subsArray = array();
					  	$subsText = '';
					  	foreach ($subcats as $sub) {
					  		$subsArray[] = $sub->slug;
					  		$subsText .= $sub->slug.'|';
					  	}
						if(!in_array($data[10], $subsArray) ) {
							$respu .= 'La categoria "'.$data[$i].'" solo permite los valores "'.$subsText.'". Valor recibido ['.$data[10].']';
							$ok = false;
						}
					}
					//VALIDACION PARA LA CATEGORIA Relojes

					if($this->sanitize_txt($data[$i]) == 'Electronicos' || $this->sanitize_txt($data[$i]) == 'electronicos' || $this->sanitize_txt($data[$i]) == 'Electrónicos') {
						//VALIDAR QUE LAS SUBCATEGORIAS SEAN SOLO LAS PERMITIDAS
						$category = get_term_by('name', 'Electronicos', 'product_cat');
						//var_dump($category);
						$args = array(
					       'hierarchical' => 1,
					       'show_option_none' => '',
					       'hide_empty' => 0,
					       'parent' => $category->term_id,
					       'taxonomy' => 'product_cat'
					    );
					  	$subcats = get_categories($args);
					  	$subsArray = array();
					  	$subsText = '';
					  	foreach ($subcats as $sub) {
					  		$subsArray[] = $sub->slug;
					  		$subsText .= $sub->slug.'|';
					  	}
						if(!in_array($data[10], $subsArray)) {
							$respu .= 'La categoria "'.$data[$i].'" solo permite los valores "'.$subsText.'". Valor recibido ['.$this->sanitize_txt($data[10]).']';
							$ok = false;
						}
					}
				}
			}
		}
		
		if($ok) {
			$respu .= "OK";
		}
		return $respu;
		
	}

	/**
	 * Insert an row at bulk_load_detail
	 */
	public function insert_row_detail($bulk_load_id, $post_id, $sku, $cantidad, $comentarios) {
		global $wpdb;
		$list_data = array(
			'bulk_load_id'		=> $bulk_load_id,
			'post_id'			=> $post_id,
			'sku' 				=> $sku,
			'cantidad' 			=> $cantidad,
			'comentarios' 		=> $comentarios,
			'status'			=> '1'
		);
		$wpdb->insert(
			$wpdb->prefix . 'bulk_load_detail',
			$list_data,
			array( '%d', '%d', '%s', '%s', '%s', '%d', '%s', '%s' )
		);
	}

	/**
	 * Import items from CSV file
	 *
	 * @since 1.0.0
	 * 
	 * @param  string $type      post type id
	 * @param  sting $file      url to temp csv file
	 * @param  string $duplicate how to handle duplicate items
	 * @param  string $name 		name of the file uploaded
	 * 
	 */
	public function import_csv($type, $file, $duplicate, $name, $statusProductos, $porcentaje) {

		global $wpdb;

		$encoding_id = intval( get_option( 'ait_import_plugin_encoding', '25' ) );
		$encoding_list = mb_list_encodings();
		$encoding = $encoding_list[$encoding_id];

		$header_line = 1;
		$cols = 0;

		$default_options = array();
		$meta_options = array();
		$taxonomies = array();
		$sku_invalidos = '';
		$sku_existentes = '';
		$tax_pre = 'tax-';

		$post_type = new AitImportType($type);

		$num_imported = 0;
		$num_updated = 0;
		$num_ignored = 0;
		$num_existente = 0;

		$ignore = false;
		$row = 1;
		
		if (isset ($_POST ['delim']) )
			$delim = $_POST ['delim'];
		
		if (($handle = fopen($file, "r")) !== FALSE) {
			// $handle = Encoding::toUTF8($handle);
			while (($data_row = fgetcsv($handle, 10000, $delim, '"')) !== FALSE) {
				//var_dump($data_row);
				$ignore = false;
				// if first line define separator for microsoft office
				if ($row == 1 && isset($data_row[0]) && trim($data_row[0]) == 'sep=;') {
					$header_line = 2;
				}
				if ($row == $header_line) {
					$msj = $this->validate_headers($data_row);
					if($msj != 'OK') {
						echo '<div class="error"><p>Errores DDD en las cabeceras del archivo, Por favor intente de nuevo con un archivo correcto. <a href="admin.php?page=ait-import" class="btn"> INTENTARLO DE NUEVO</a><br><strong>'.$msj.'</strong> </p></div>';
						exit();
					}

					$bulk_load_id = 0;
					$list_data = array(
						'date'			=> current_time( 'mysql' ),
						'file'			=> $name,
						'comentarios' 	=> '',
						'status'		=> '1',
						'ajuste'		=> '0',
					);
					$wpdb->insert(
						$wpdb->prefix . 'bulk_load',
						$list_data,
						array( '%s', '%s', '%s', '%s', '%s' )
					);
					$bulk_load_id = $wpdb->insert_id;

					$cols = count($data_row);
					for ($c=0; $c < $cols; $c++) {
						if (in_array($data_row[$c],array_keys($post_type->default_options))){
							$default_options[$c] = $data_row[$c];
						} elseif (!strncmp($data_row[$c], $tax_pre, strlen($tax_pre))) {
							$taxonomies[$c] = substr($data_row[$c], strlen($tax_pre));
						} else {
							$meta_options[$c] = $data_row[$c];
						}
					}
				} 
				if ($row > $header_line) {
					// default options
					$attrs = array();
					foreach ($default_options as $key => $opt) {
						$attrs[$opt] = ($encoding == 'UTF-8') ? $data_row[$key] : mb_convert_encoding($data_row[$key],'UTF-8',$encoding);
					}
					// define post type
					$attrs['post_type'] = $type;
					// remove image attr
					if (isset($attrs['post_image'])) {
						$image_slug = $attrs['post_image'];
						unset($attrs['post_image']);
					}

					// find existing post
					global $wpdb;
					if ($duplicate == '2' || $duplicate == '3') {
						if (isset($attrs['post_name']) && !empty($attrs['post_name'])) {
							$slug = $attrs['post_name'];
							$finded_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '$slug'");
						}
						if (isset($finded_id) && $finded_id) {
							if ($duplicate == '3') {
								// igonre this row
								$num_ignored++;
								$ignore = true;
							}
							$attrs['ID'] = $finded_id;
						}
					}

					//VALIDATE DUPLICATE SKU
					//echo "SELECT post_id FROM $wpdb->postmeta WHERE meta_value = '".$data_row[0]."' and meta_key = '_sku'";
					if (isset($data_row[2]) && !empty($data_row[2])) {
						$sku = $data_row[2];
						$existente_id = $wpdb->get_var("SELECT post_id FROM $wpdb->postmeta WHERE meta_value = '".$sku."' and meta_key = '_sku'");
					}
					if (isset($existente_id) && $existente_id) {
						$num_existente++;
						$ignore = true;
						$attrs['ID'] = $existente_id;
						$sku_existentes .= $sku.', ';
						echo '<div class="error"><p><h5>Artículo ignorado por ya existir <strong>[SKU : '.$sku.' - Nombre : '.$data_row[3].']</strong><code>Revise la papelera si no lo encuentra</code></h5>'.$respues.'</p></div>';
					}

					//VALIDAR DATOS DE ENTRADA
					if(!$ignore) { 
						$respues = $this->validate_row($data_row); 
						if($respues != 'OK') { 
							$ignore = true; 
							echo '<div class="error"><p><h5>ERRORES PARA <strong>[SKU : '.$data_row[2].']</strong></h5>'.$respues.'</p></div>';
						}
						if($ignore) { $num_ignored++; $sku_invalidos .= $sku.', '; }
					}
					
					if (!$ignore) {
						// parent
						if (isset($attrs['post_parent']) && !empty($attrs['post_parent'])) {
							$parent = $attrs['post_parent'];
							$parent_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '$parent'");
						}
						if (isset($parent_id)) {
							$attrs['post_parent'] = $parent_id;
						}
						// author
						if (isset($attrs['post_author']) && !empty($attrs['post_author'])) {
							$author = get_user_by( 'login', $attrs['post_author'] );
						}
						if (isset($author)) {
							if ($author){
								$attrs['post_author'] = $author->ID;
							} else {
								unset($attrs['post_author']);
							}
						}

						$attrs['post_title'] = $this->sanitize_txt($data_row[3]); //columna 'nombre' del archivo csv
						$attrs['post_content'] = $this->sanitize_txt($data_row[7]);	//columna 'descripcion' del archivo csv		
						$attrs['post_status'] = $statusProductos;			
						// insert or update
						$post_id = wp_insert_post( $attrs, true );
						update_post_meta( $post_id, '_visibility', 'visible' );
						
						if ( is_wp_error($post_id) ){
							echo '<div class="error"><p>' . $post_id->get_error_message() . '</p></div>';
						} else {
							// incerment count
							if(isset($finded_id) && $finded_id) {
								$num_updated++;
							} else {
								$num_imported++;
							}
							// set featured image
							if (isset($image_slug) && !empty($image_slug)) {
								$slug = trim($image_slug);
								$finded_image_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '$slug'");
								if (isset($finded_image_id) && $finded_image_id) {
									update_post_meta( $post_id, '_thumbnail_id', $finded_image_id);
								}
							}
							// insert meta
							if(count($meta_options) > 0){
								//$meta_key = '_'.$type;
								$meta_key = $type;
								$meta_attrs = array();
								$uploads_dir = wp_upload_dir();
								$img_galery = '';
								foreach ($meta_options as $key => $opt) {
									/*
									if(!$this->save_empty_values) { 
										if(!empty($data_row[$key])) {
											$meta_attrs[$opt] = ($encoding == 'UTF-8') ? $data_row[$key] : mb_convert_encoding($data_row[$key],'UTF-8',$encoding);
										}
									} else {
											$meta_attrs[$opt] = ($encoding == 'UTF-8') ? $data_row[$key] : mb_convert_encoding($data_row[$key],'UTF-8',$encoding);
									}
									*/
									switch ($opt) {
										case 'sku':
												$opt = '_sku';
												update_post_meta( $post_id, $opt, $data_row[$key] );
											break;
										case 'nombre':
												$opt = 'post_title';
												update_post_meta( $post_id, $opt, $data_row[$key] );
											break;
										case 'precio':
												$opt = '_price';
												if($porcentaje > 0) {
													$precio = round($data_row[$key]*(1+$porcentaje/100),2);
												}
												else {
													$precio = $data_row[$key];
												}
												update_post_meta( $post_id, $opt, $precio );
												update_post_meta( $post_id, '_regular_price', $precio );
											break;
										case 'precio_oferta':
												$opt = '_sale_price';
												if(is_numeric($data_row[$key]) && $data_row[$key] > 0) {
													if($porcentaje > 0) {
														$precio_oferta = round($data_row[$key]*(1+$porcentaje/100),2);
													}
													else {
														$precio_oferta = $data_row[$key];
													}
													update_post_meta( $post_id, $opt, $precio_oferta );
													update_post_meta( $post_id, '_price', $precio_oferta );
												}
											break;
										case 'precio_normal':
												$opt = '_precio_normal';
												if(is_numeric($data_row[$key]) && $data_row[$key] > 0) {
													if($porcentaje > 0) {
														$precio_normal = round($data_row[$key]*(1+$porcentaje/100),2);
													}
													else {
														$precio_normal = $data_row[$key];
													}
													update_post_meta( $post_id, $opt, $precio_normal );
													update_post_meta( $post_id, '_precio_normal', $precio_normal );
												}
											break;
										case 'descripcion':
												$opt = 'post_content';
												update_post_meta( $post_id, $opt, $data_row[$key] );
											break;
										case 'cantidad':
												update_post_meta( $post_id, '_manage_stock', 'yes' );
												update_post_meta( $post_id, '_stock_status', 'instock' );
												update_post_meta( $post_id, '_stock', $data_row[$key] );
											break;
										case 'categoria':
												$term_id = get_term_by('slug', $data_row[$key], 'product_cat');
												if ($term_id){
													$result = wp_set_post_terms($post_id, $term_id->term_id, 'product_cat', true);
												}
											break;
										case 'subcategoria':
												$term_id = get_term_by('slug', $data_row[$key], 'product_cat');
												if ($term_id){
													$result = wp_set_post_terms($post_id, $term_id->term_id, 'product_cat', true);
												}
											break;
										case 'foto1':
												if(substr($data_row[$key], -4) != '.jpg' && substr($data_row[$key], -4) != '.png') {
													$data_row[$key] .= '.jpg';
												}
												$ruta_imagen = $uploads_dir['url'].'/'.$data_row[$key];
												$image_id = $this->pippin_get_image_id($ruta_imagen);
												update_post_meta( $post_id, '_thumbnail_id', $image_id );
												update_post_meta( $post_id, '_ruta', $ruta_imagen );
												set_post_thumbnail( $post_id, $image_id );
											break;											
										default:
												if($opt == 'foto2' || $opt == 'foto3' || $opt == 'foto4' || $opt == 'foto5' || $opt == 'foto6') {
													if(substr($data_row[$key], -4) != '.jpg' && substr($data_row[$key], -4) != '.png') {
														$data_row[$key] .= '.jpg';
													}
													$ruta_imagen = $uploads_dir['url'].'/'.$data_row[$key];
													$image_id = $this->pippin_get_image_id($ruta_imagen);
													update_post_meta( $post_id, $opt, $ruta_imagen );
													$img_galery .= 	$image_id .', ';
												}
												else {
													update_post_meta( $post_id, $opt, $data_row[$key] );	
												}
												
											break;
									}
									
								}
								$this->insert_row_detail($bulk_load_id, $post_id, $this->sanitize_txt($data_row[2]), $data_row[8], 'Nuevo articulo');
								$img_galery = substr($img_galery, 0, -2);
								update_post_meta( $post_id, '_product_image_gallery', $img_galery );
							}
							// set terms
							foreach ($taxonomies as $key => $tax) {
								$terms = explode(",",trim($data_row[$key]));
								foreach ($terms as $key_term => $term) {
									$term_id = get_term_by('slug', $term, $tax);
									if ($term_id){
										$result = wp_set_post_terms($post_id, $term_id->term_id, $tax, true);
									}
								}
							}
						}
					}
				}
				$row++;
			}
			fclose($handle);
			echo '<div class="updated"><p>' . $num_imported . __(' productos fueron importados exitosamente. ').'</p><p>'. $num_existente .  __(' productos tienen un SKU que ya exite y no fueron agregados. ').' Listado de SKUs ['.$sku_existentes.']</p><p>' . $num_ignored . __(' productos no fueron insertados por no cumplir la validación.') .' Listado de SKUs ['.$sku_invalidos.']</p></div>';
		}		
	}

	public function import_csv_precios($type, $file) {

		global $wpdb;
		$header_line = 1;
		$num_exitosos = 0;
		$num_invalidos = 0;
		$num_noencontrados = 0;
		$sku_exitosos = '';
		$sku_invalidos = '';
		$sku_noencontrados = '';

		if (($handle = fopen($file, "r")) !== FALSE) {
			while (($data_row = fgetcsv($handle, 10000, ',', '"')) !== FALSE ) {
				
				if (isset($data_row[0]) && $data_row[0] != '' && isset($data_row[1]) && is_numeric($data_row[1]) && $data_row[1] != '' && $header_line > 1) {
					$post_ids = $wpdb->get_results("SELECT pm.post_id FROM $wpdb->postmeta pm, $wpdb->posts p where pm.meta_key = '_sku' and pm.meta_value = '".$data_row[0]."' and (p.post_type = 'product' or p.post_type = 'product_variation') and p.post_status IN ('publish', 'draft') and pm.post_id = p.ID;");
					
					foreach ($post_ids as $post_id) {
						if(isset($post_id) && is_numeric($post_id->post_id)) {
						
							update_post_meta( $post_id->post_id, '_price', $data_row[1] );
							update_post_meta( $post_id->post_id, '_regular_price', $data_row[1] );
							delete_transient( 'wc_var_prices_' . $post_id->post_id );
							$num_exitosos++;
							$sku_exitosos .= $data_row[0].', ';
						}
						else {
							$num_noencontrados++;
							$sku_noencontrados .= $data_row[0].', ';
						}
					}
					
				}
				else {
					if($header_line > 1) {
						$num_invalidos++;
						$sku_invalidos .= $data_row[0].', ';
					}
				}
				$header_line++;
			}
			echo '<div class="updated"><p>' . $num_exitosos . __(' productos con precio actualizado exitosamente. ').' Listado de SKUs ['.$sku_exitosos.']</p><p>' . $num_invalidos . __(' productos no fueron actualizados por no cumplir la validación.') .' Listado de SKUs ['.$sku_invalidos.']</p><p>' . $num_noencontrados . __(' no encontrados. ').' Listado de SKUs ['.$sku_noencontrados.']</p></div>';
		}
		else {
			echo '<div class="error"><p>Error al intentar leer el archivo. Por favor revisa el archivo e intentalo de nuevo.</p></div>';
		}

	}

	/**
	 * Import categories from CSV file
	 *
	 * @since 1.0.0
	 * 
	 * @param  string $type      taxonomy id
	 * @param  sting $file      url to temp csv file
	 * @param  string $duplicate how to handle duplicate categories
	 * 
	 */
	public function import_terms_csv($type, $file, $duplicate) {

		$encoding_id = intval( get_option( 'ait_import_plugin_encoding', '25' ) );
		$encoding_list = mb_list_encodings();
		$encoding = $encoding_list[$encoding_id];

		$header_line = 1;
		$cols = 0;
		
		$default_options = array();
		$meta_options = array();

		$taxonomy = new AitImportTaxonomy($type);

		$num_imported = 0;
		$num_updated = 0;
		$num_ignored = 0;

		$ignore = false;
		$row = 1;
		
		if (isset ($_POST ['delim']) )
			$delim = $_POST ['delim'];
			
		if (($handle = fopen($file, "r")) !== FALSE) {
			while (($data_row = fgetcsv($handle, 10000, $delim, '"')) !== FALSE) {
				$ignore = false;
				// if first line define separator for microsoft office
				if ($row == 1 && isset($data_row[0]) && trim($data_row[0]) == 'sep=;') {
					$header_line = 2;
				}
				if ($row == $header_line) {
					$cols = count($data_row);
					for ($c=0; $c < $cols; $c++) {
						if (in_array($data_row[$c],array_keys($taxonomy->default_options))){
							$default_options[$c] = $data_row[$c];
						} else {
							$meta_options[$c] = $data_row[$c];
						}
					}
				}
				if ($row > $header_line) {
					// default options
					$attrs = array();
					foreach ($default_options as $key => $opt) {
						$attrs[$opt] = ($encoding == 'UTF-8') ? $data_row[$key] : mb_convert_encoding($data_row[$key],'UTF-8',$encoding);
					}
					if ($duplicate == '2' || $duplicate == '3') {
						// find existing term
						if (isset($attrs['slug']) && !empty($attrs['slug'])) {
							$finded_term = get_term_by( 'slug', $attrs['slug'], $type );
						}
					}
					if ($duplicate == '3' && isset($finded_term) && $finded_term) {
						$num_ignored++;
					} else {
						// find parent term
						if (isset($attrs['parent']) && !empty($attrs['parent'])) {
							$parent_term = get_term_by( 'slug', $attrs['parent'], $type );
						}
						if (isset($parent_term) && $parent_term) {
							$attrs['parent'] = $parent_term->term_id;
						}
						
						// title
						if (isset($attrs['title'])) {
							$title = $attrs['title'];
							if($duplicate == '2') {
								$attrs['name'] = $title;
							}
						} else {
							$title = __('Category');
						}

						$tax = $type;
						if (isset($finded_term) && $finded_term) {
							unset($attrs['slug']);
							$term_id = wp_update_term($finded_term->term_id, $tax, $attrs);
						} else {
							$term_id = wp_insert_term($title, $tax, $attrs);
						}

						if (is_wp_error($term_id)){
							echo '<div class="error"><p>' . $term_id->get_error_message() . '</p></div>';
						} else {
							if (isset($finded_term) && $finded_term) {
								$num_updated++;
							} else {
								$num_imported++;
							}
							// insert meta
							if(count($meta_options) > 0){
								if($taxonomy->storage_type = 2) {
									$meta_type = str_replace("-","_",$type);
									foreach ($meta_options as $key => $opt) {
										$meta_key = $meta_type . "_" . $term_id['term_id'] . '_' . $opt ;
										$meta_value = ($encoding == 'UTF-8') ? $data_row[$key] : mb_convert_encoding($data_row[$key],'UTF-8',$encoding);
										update_option( $meta_key, $meta_value );
									}
								} else {

								}
							}
						}
					}
				}
				$row++;
			}
			
			fclose($handle);

			// wordpress cache bugfix
			delete_option("{$type}_children");

			echo '<div class="updated"><p>' . $num_imported . __(' categories was successfully imported. ') . $num_updated .  __(' categories updated. ') . $num_ignored . __(' categories ignored.') . 'delimiter' .$delim .'</p></div>';
		}

	}

}