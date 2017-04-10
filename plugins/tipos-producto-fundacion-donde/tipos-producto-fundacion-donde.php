<?php
/**
 * Plugin Name: 	Tipos de producto Fundación Dondé
 * Description:		Este plugin contiene tipos de producto a la medida para los productos de Fundación Dondé.
 * Version: 1.0.0
 * Author: Miguel Cabral
 * Author URI: http://pcuervo.com
 */

if( ! defined( 'TIPOS_PRODUCTO_FD_PLUGIN_DIR' ) ){
	define( 'TIPOS_PRODUCTO_FD_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

/**
 * Register the custom product type after init
 */
function register_simple_custom_product_types() {
	require_once( TIPOS_PRODUCTO_FD_PLUGIN_DIR . 'src/wc-fd-reloj-product-type.php' ); //relojes
	require_once( TIPOS_PRODUCTO_FD_PLUGIN_DIR . 'src/wc-fd-joyeria-product-type.php' ); //joyería
	require_once( TIPOS_PRODUCTO_FD_PLUGIN_DIR . 'src/wc-fd-electronico-product-type.php' ); //electrónicos
}
add_action( 'plugins_loaded', 'register_simple_custom_product_types' );


/**
 * Add to product type drop down.
 */
function fd_add_product_types( $types ){

	// El "type" debe ser el mismo que la clase que cargas arriba.
	$types[ 'simple_reloj' ] = __( 'Reloj' ); //Relojes
	$types[ 'simple_joya' ] = __( 'Joya' ); //Joyería
	$types[ 'simple_electronico' ] = __( 'Electrónico' ); //Electrónicos

	return $types;

}
add_filter( 'product_type_selector', 'fd_add_product_types' );


/**
 * Show pricing fields for our custom product types.
 */
function fd_product_types_custom_js() {

	if ( 'product' != get_post_type() ) :
		return;
	endif;

	?><script type='text/javascript'>

		jQuery( document ).ready( function() {
			// Por default muestra shipping así que lo forzamos a mostrar
			// primero las opciones generales
			jQuery( '.shipping_options' ).removeClass( 'active' ).show();
			jQuery( '#shipping_product_data' ).hide();

			// Mostrar opciones generales para nuestros tipos de producto custom

			//Relojes
			jQuery( '.general_options' ).addClass( 'show_if_simple_reloj' ).show();
			jQuery( '.options_group.pricing' ).addClass( 'show_if_simple_reloj' ).show();

			//Joyería
			jQuery( '.general_options' ).addClass( 'show_if_simple_joya' ).show();
			jQuery( '.options_group.pricing' ).addClass( 'show_if_simple_joya' ).show();

			//Elextrónicos
			jQuery( '.general_options' ).addClass( 'show_if_simple_electronico' ).show();
			jQuery( '.options_group.pricing' ).addClass( 'show_if_simple_electronico' ).show();


			jQuery( '.general_options' ).addClass( 'active' ).show();
			jQuery( '#general_product_data' ).show();
		});

	</script><?php

}
add_action( 'admin_footer', 'fd_product_types_custom_js' );


/**
 * Add a custom product tab.
 */
function fd_custom_product_tabs( $tabs) {

	$tabs['reloj'] = array(
		'label'		=> __( 'Atributos Reloj', 'woocommerce' ),
		'target'	=> 'reloj_options',
		'class'		=> array( 'show_if_simple_reloj', 'show_if_variable_reloj'  ),
	);

	$tabs['joya'] = array(
		'label'		=> __( 'Atributos Joyería', 'woocommerce' ),
		'target'	=> 'joya_options',
		'class'		=> array( 'show_if_simple_joya', 'show_if_variable_joya'  ),
	);

	$tabs['electronico'] = array(
		'label'		=> __( 'Atributos Electrónicos', 'woocommerce' ),
		'target'	=> 'electronico_options',
		'class'		=> array( 'show_if_simple_electronico', 'show_if_variable_electronico'  ),
	);

	return $tabs;

}
add_filter( 'woocommerce_product_data_tabs', 'fd_custom_product_tabs' );

/**
 * Contents of the joyería options product tab.
 */
function joya_options_product_tab_content() {
	global $post;
	?><div id='joya_options' class='panel woocommerce_options_panel'><?php

		?><div class='options_group'><?php
			// Marca
			woocommerce_wp_text_input( array(
				'id'			=> '_joya_text_marca',
				'label'			=> __( 'Marca', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// Modelo
			woocommerce_wp_text_input( array(
				'id'			=> '_joya_text_modelo',
				'label'			=> __( 'Modelo', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// Año
			woocommerce_wp_text_input( array(
				'id'			=> '_joya_text_ano',
				'label'			=> __( 'Año', 'woocommerce' ),
				'desc_tip'		=> 'true',
				'description'	=> __( 'Año debe ser un número', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// Tipo de piedra
			woocommerce_wp_text_input( array(
				'id'			=> '_joya_text_tipo_de_piedra',
				'label'			=> __( 'Tipo de piedra', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// Cantidad de piedras
			woocommerce_wp_text_input( array(
				'id'			=> '_joya_text_cantidad_de_piedras',
				'label'			=> __( 'Cantidad de piedras', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// Corte de piedras
			woocommerce_wp_text_input( array(
				'id'			=> '_joya_text_corte_de_piedras',
				'label'			=> __( 'Corte de piedras', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// Descripción de piedras
			woocommerce_wp_text_input( array(
				'id'			=> '_joya_text_descripcion_de_piedras',
				'label'			=> __( 'Descripción de piedras', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// Metal
			woocommerce_wp_text_input( array(
				'id'			=> '_joya_text_metal',
				'label'			=> __( 'Metal', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// Medida altura
			woocommerce_wp_text_input( array(
				'id'			=> '_joya_text_medida_altura',
				'label'			=> __( 'Medida altura', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// Medida ancho
			woocommerce_wp_text_input( array(
				'id'			=> '_joya_text_medida_ancho',
				'label'			=> __( 'Medida ancho', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// Peso
			woocommerce_wp_text_input( array(
				'id'			=> '_joya_text_peso',
				'label'			=> __( 'Peso', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// Talla
			woocommerce_wp_text_input( array(
				'id'			=> '_joya_text_talla',
				'label'			=> __( 'Talla', 'woocommerce' ),
				'type' 			=> 'text',
			));

		?></div>

	</div><?php
}
add_action( 'woocommerce_product_data_panels', 'joya_options_product_tab_content' );


/**
 * Contents of the reloj options product tab.
 */
function reloj_options_product_tab_content() {
	global $post;
	?><div id='reloj_options' class='panel woocommerce_options_panel'><?php

		?><div class='options_group'><?php
			// Marca
			woocommerce_wp_text_input( array(
				'id'			=> '_reloj_text_marca',
				'label'			=> __( 'Marca', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// Modelo
			woocommerce_wp_text_input( array(
				'id'			=> '_reloj_text_modelo',
				'label'			=> __( 'Modelo', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// Año
			woocommerce_wp_text_input( array(
				'id'			=> '_reloj_text_ano',
				'label'			=> __( 'Año', 'woocommerce' ),
				'desc_tip'		=> 'true',
				'description'	=> __( 'Año debe ser un número', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// Caja
			woocommerce_wp_text_input( array(
				'id'			=> '_reloj_text_caja',
				'label'			=> __( 'Caja', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// Bisel
			woocommerce_wp_text_input( array(
				'id'			=> '_reloj_text_bisel',
				'label'			=> __( 'Bisel', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// Corona
			woocommerce_wp_text_input( array(
				'id'			=> '_reloj_text_corona',
				'label'			=> __( 'Corona', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// Protector
			woocommerce_wp_text_input( array(
				'id'			=> '_reloj_text_protector',
				'label'			=> __( 'Protector', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// Pulso
			woocommerce_wp_text_input( array(
				'id'			=> '_reloj_text_pulso',
				'label'			=> __( 'Pulso', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// Movimiento
			woocommerce_wp_text_input( array(
				'id'			=> '_reloj_text_movimiento',
				'label'			=> __( 'Movimiento', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// Caratula
			woocommerce_wp_text_input( array(
				'id'			=> '_reloj_text_caratula',
				'label'			=> __( 'Caratula', 'woocommerce' ),
				'type' 			=> 'text',
			));

			
		?></div>

	</div><?php
}
add_action( 'woocommerce_product_data_panels', 'reloj_options_product_tab_content' );



/**
 * Contents of the electrónicos options product tab.
 */
function electronico_options_product_tab_content() {
	global $post;
	?><div id='electronico_options' class='panel woocommerce_options_panel'><?php

		?><div class='options_group'><?php
			// Marca
			woocommerce_wp_text_input( array(
				'id'			=> '_electronico_text_marca',
				'label'			=> __( 'Marca', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// Modelo
			woocommerce_wp_text_input( array(
				'id'			=> '_electronico_text_modelo',
				'label'			=> __( 'Modelo', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// Año
			woocommerce_wp_text_input( array(
				'id'			=> '_electronico_text_ano',
				'label'			=> __( 'Año', 'woocommerce' ),
				'desc_tip'		=> 'true',
				'description'	=> __( 'Año debe ser un número', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// Generación
			woocommerce_wp_text_input( array(
				'id'			=> '_electronico_text_generacion',
				'label'			=> __( 'Generación', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// Tipo de pantalla
			woocommerce_wp_text_input( array(
				'id'			=> '_electronico_text_tipo_de_pantalla',
				'label'			=> __( 'Tipo de pantalla', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// Medidas alto
			woocommerce_wp_text_input( array(
				'id'			=> '_electronico_text_medidas_alto',
				'label'			=> __( 'Medidas alto', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// Medidas ancho
			woocommerce_wp_text_input( array(
				'id'			=> '_electronico_text_medidas_ancho',
				'label'			=> __( 'Medidas ancho', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// Medidas profundo
			woocommerce_wp_text_input( array(
				'id'			=> '_electronico_text_medidas_profundo',
				'label'			=> __( 'Medidas profundo', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// Pulgadas
			woocommerce_wp_text_input( array(
				'id'			=> '_electronico_text_pulgadas',
				'label'			=> __( 'Pulgadas', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// Sistema operativo
			woocommerce_wp_text_input( array(
				'id'			=> '_electronico_text_sistema_operativo',
				'label'			=> __( 'Sistema operativo', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// Color
			woocommerce_wp_text_input( array(
				'id'			=> '_electronico_text_color',
				'label'			=> __( 'Color', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// Compañía celular
			woocommerce_wp_text_input( array(
				'id'			=> '_electronico_text_compania_celular',
				'label'			=> __( 'Compañía celular', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// Cámara delantera
			woocommerce_wp_text_input( array(
				'id'			=> '_electronico_text_camara_delantera',
				'label'			=> __( 'Cámara delantera', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// Cámara trasera
			woocommerce_wp_text_input( array(
				'id'			=> '_electronico_text_camara_trasera',
				'label'			=> __( 'Cámara trasera', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// Capacidad
			woocommerce_wp_text_input( array(
				'id'			=> '_electronico_text_capacidad',
				'label'			=> __( 'Capacidad', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// Resolución
			woocommerce_wp_text_input( array(
				'id'			=> '_electronico_text_resolucion',
				'label'			=> __( 'Resolución', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// Peso
			woocommerce_wp_text_input( array(
				'id'			=> '_electronico_text_peso',
				'label'			=> __( 'Peso', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// Contenidos de la caja
			woocommerce_wp_text_input( array(
				'id'			=> '_electronico_text_contenidos_de_la_caja',
				'label'			=> __( 'Contenidos de la caja', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// Lector de tarjetas
			woocommerce_wp_text_input( array(
				'id'			=> '_electronico_text_lector_de_tarjetas',
				'label'			=> __( 'Lector de tarjetas', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// Wi fi
			woocommerce_wp_text_input( array(
				'id'			=> '_electronico_text_wi_fi',
				'label'			=> __( 'Wi fi', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// Bluetooth
			woocommerce_wp_text_input( array(
				'id'			=> '_electronico_text_bluetooth',
				'label'			=> __( 'Bluetooth', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// Procesador
			woocommerce_wp_text_input( array(
				'id'			=> '_electronico_text_procesador',
				'label'			=> __( 'Procesador', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// Chip
			woocommerce_wp_text_input( array(
				'id'			=> '_electronico_text_chip',
				'label'			=> __( 'Chip', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// RAM
			woocommerce_wp_text_input( array(
				'id'			=> '_electronico_text_ram',
				'label'			=> __( 'RAM', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// Número de puertos USB 2.0
			woocommerce_wp_text_input( array(
				'id'			=> '_electronico_text_numero_de_puertos_usb_20',
				'label'			=> __( 'Número de puertos USB 2.0', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// Número de procesadores
			woocommerce_wp_text_input( array(
				'id'			=> '_electronico_text_numero_de_procesadores',
				'label'			=> __( 'Número de procesadores', 'woocommerce' ),
				'type' 			=> 'text',
			));

			// VIDEOJUEGOS
			woocommerce_wp_text_input( array(
				'id'			=> 'videojuegos_consola',
				'label'			=> __( 'Videojuegos Consola', 'woocommerce' ),
				'type' 			=> 'text',
			));
			woocommerce_wp_text_input( array(
				'id'			=> 'videojuegos_almacenamiento',
				'label'			=> __( 'Videojuego Alamacenamiento', 'woocommerce' ),
				'type' 			=> 'text',
			));
			woocommerce_wp_text_input( array(
				'id'			=> 'videojuegos_controles_cantidad',
				'label'			=> __( 'Videojuego Cantidad de controles', 'woocommerce' ),
				'type' 			=> 'text',
			));
			woocommerce_wp_text_input( array(
				'id'			=> 'videojuegos_juegos_incluidos',
				'label'			=> __( 'Videojuegos Juegos incluidos', 'woocommerce' ),
				'type' 			=> 'text',
			));
			woocommerce_wp_text_input( array(
				'id'			=> 'videojuegos_cables',
				'label'			=> __( 'Videojuegos Cables', 'woocommerce' ),
				'type' 			=> 'text',
			));
		?></div>

	</div><?php
}
add_action( 'woocommerce_product_data_panels', 'electronico_options_product_tab_content' );


/**
 * Save the custom fields.
**/

function save_joya_option_field( $post_id ) {

	if ( isset( $_POST['_joya_text_marca'] ) ) {
		update_post_meta( $post_id, '_joya_text_marca', sanitize_text_field( $_POST['_joya_text_marca'] ) );
	}
	if ( isset( $_POST['_joya_text_modelo'] ) ) {
		update_post_meta( $post_id, '_joya_text_modelo', sanitize_text_field( $_POST['_joya_text_modelo'] ) );
	}
	if ( isset( $_POST['_joya_text_ano'] ) ) {
		update_post_meta( $post_id, '_joya_text_ano', sanitize_text_field( $_POST['_joya_text_ano'] ) );
	}
	if ( isset( $_POST['_joya_text_tipo_de_piedra'] ) ) {
		update_post_meta( $post_id, '_joya_text_tipo_de_piedra', sanitize_text_field( $_POST['_joya_text_tipo_de_piedra'] ) );
	}
	if ( isset( $_POST['_joya_text_cantidad_de_piedras'] ) ) {
		update_post_meta( $post_id, '_joya_text_cantidad_de_piedras', sanitize_text_field( $_POST['_joya_text_cantidad_de_piedras'] ) );
	}
	if ( isset( $_POST['_joya_text_corte_de_piedras'] ) ) {
		update_post_meta( $post_id, '_joya_text_corte_de_piedras', sanitize_text_field( $_POST['_joya_text_corte_de_piedras'] ) );
	}
	if ( isset( $_POST['_joya_text_descripcion_de_piedras'] ) ) {
		update_post_meta( $post_id, '_joya_text_descripcion_de_piedras', sanitize_text_field( $_POST['_joya_text_descripcion_de_piedras'] ) );
	}
	if ( isset( $_POST['_joya_text_metal'] ) ) {
		update_post_meta( $post_id, '_joya_text_metal', sanitize_text_field( $_POST['_joya_text_metal'] ) );
	}
	if ( isset( $_POST['_joya_text_medida_altura'] ) ) {
		update_post_meta( $post_id, '_joya_text_medida_altura', sanitize_text_field( $_POST['_joya_text_medida_altura'] ) );
	}
	if ( isset( $_POST['_joya_text_medida_ancho'] ) ) {
		update_post_meta( $post_id, '_joya_text_medida_ancho', sanitize_text_field( $_POST['_joya_text_medida_ancho'] ) );
	}
	if ( isset( $_POST['_joya_text_peso'] ) ) {
		update_post_meta( $post_id, '_joya_text_peso', sanitize_text_field( $_POST['_joya_text_peso'] ) );
	}
	if ( isset( $_POST['_joya_text_talla'] ) ) {
		update_post_meta( $post_id, '_joya_text_talla', sanitize_text_field( $_POST['_joya_text_talla'] ) );
	}
	

}
add_action( 'woocommerce_process_product_meta_simple_joya', 'save_joya_option_field' );


function save_reloj_option_field( $post_id ) {

	if ( isset( $_POST['_reloj_text_marca'] ) ) {
		update_post_meta( $post_id, '_reloj_text_marca', sanitize_text_field( $_POST['_reloj_text_marca'] ) );
	}
	if ( isset( $_POST['_reloj_text_modelo'] ) ) {
		update_post_meta( $post_id, '_reloj_text_modelo', sanitize_text_field( $_POST['_reloj_text_modelo'] ) );
	}
	if ( isset( $_POST['_reloj_text_ano'] ) ) {
		update_post_meta( $post_id, '_reloj_text_ano', sanitize_text_field( $_POST['_reloj_text_ano'] ) );
	}
	if ( isset( $_POST['_reloj_text_caja'] ) ) {
		update_post_meta( $post_id, '_reloj_text_caja', sanitize_text_field( $_POST['_reloj_text_caja'] ) );
	}
	if ( isset( $_POST['_reloj_text_bisel'] ) ) {
		update_post_meta( $post_id, '_reloj_text_bisel', sanitize_text_field( $_POST['_reloj_text_bisel'] ) );
	}
	if ( isset( $_POST['_reloj_text_corona'] ) ) {
		update_post_meta( $post_id, '_reloj_text_corona', sanitize_text_field( $_POST['_reloj_text_corona'] ) );
	}
	if ( isset( $_POST['_reloj_text_protector'] ) ) {
		update_post_meta( $post_id, '_reloj_text_protector', sanitize_text_field( $_POST['_reloj_text_protector'] ) );
	}
	if ( isset( $_POST['_reloj_text_pulso'] ) ) {
		update_post_meta( $post_id, '_reloj_text_pulso', sanitize_text_field( $_POST['_reloj_text_pulso'] ) );
	}
	if ( isset( $_POST['_reloj_text_movimiento'] ) ) {
		update_post_meta( $post_id, '_reloj_text_movimiento', sanitize_text_field( $_POST['_reloj_text_movimiento'] ) );
	}
	if ( isset( $_POST['_reloj_text_caratula'] ) ) {
		update_post_meta( $post_id, '_reloj_text_caratula', sanitize_text_field( $_POST['_reloj_text_caratula'] ) );
	}
	

}
add_action( 'woocommerce_process_product_meta_simple_reloj', 'save_reloj_option_field' );



function save_electronico_option_field( $post_id ) {

	if ( isset( $_POST['_electronico_text_marca'] ) ) {
		update_post_meta( $post_id, '_electronico_text_marca', sanitize_text_field( $_POST['_electronico_text_marca'] ) );
	}
	if ( isset( $_POST['_electronico_text_modelo'] ) ) {
		update_post_meta( $post_id, '_electronico_text_modelo', sanitize_text_field( $_POST['_electronico_text_modelo'] ) );
	}
	if ( isset( $_POST['_electronico_text_ano'] ) ) {
		update_post_meta( $post_id, '_electronico_text_ano', sanitize_text_field( $_POST['_electronico_text_ano'] ) );
	}
	if ( isset( $_POST['_electronico_text_generacion'] ) ) {
		update_post_meta( $post_id, '_electronico_text_generacion', sanitize_text_field( $_POST['_electronico_text_generacion'] ) );
	}
	if ( isset( $_POST['_electronico_text_tipo_de_pantalla'] ) ) {
		update_post_meta( $post_id, '_electronico_text_tipo_de_pantalla', sanitize_text_field( $_POST['_electronico_text_tipo_de_pantalla'] ) );
	}
	if ( isset( $_POST['_electronico_text_medidas_alto'] ) ) {
		update_post_meta( $post_id, '_electronico_text_medidas_alto', sanitize_text_field( $_POST['_electronico_text_medidas_alto'] ) );
	}
	if ( isset( $_POST['_electronico_text_medidas_ancho'] ) ) {
		update_post_meta( $post_id, '_electronico_text_medidas_ancho', sanitize_text_field( $_POST['_electronico_text_medidas_ancho'] ) );
	}
	if ( isset( $_POST['_electronico_text_medidas_profundo'] ) ) {
		update_post_meta( $post_id, '_electronico_text_medidas_profundo', sanitize_text_field( $_POST['_electronico_text_medidas_profundo'] ) );
	}
	if ( isset( $_POST['_electronico_text_pulgadas'] ) ) {
		update_post_meta( $post_id, '_electronico_text_pulgadas', sanitize_text_field( $_POST['_electronico_text_pulgadas'] ) );
	}
	if ( isset( $_POST['_electronico_text_sistema_operativo'] ) ) {
		update_post_meta( $post_id, '_electronico_text_sistema_operativo', sanitize_text_field( $_POST['_electronico_text_sistema_operativo'] ) );
	}
	if ( isset( $_POST['_electronico_text_color'] ) ) {
		update_post_meta( $post_id, '_electronico_text_color', sanitize_text_field( $_POST['_electronico_text_color'] ) );
	}
	if ( isset( $_POST['_electronico_text_compania_celular'] ) ) {
		update_post_meta( $post_id, '_electronico_text_compania_celular', sanitize_text_field( $_POST['_electronico_text_compania_celular'] ) );
	}
	if ( isset( $_POST['_electronico_text_camara_delantera'] ) ) {
		update_post_meta( $post_id, '_electronico_text_camara_delantera', sanitize_text_field( $_POST['_electronico_text_camara_delantera'] ) );
	}
	if ( isset( $_POST['_electronico_text_camara_trasera'] ) ) {
		update_post_meta( $post_id, '_electronico_text_camara_trasera', sanitize_text_field( $_POST['_electronico_text_camara_trasera'] ) );
	}
	if ( isset( $_POST['_electronico_text_capacidad'] ) ) {
		update_post_meta( $post_id, '_electronico_text_capacidad', sanitize_text_field( $_POST['_electronico_text_capacidad'] ) );
	}
	if ( isset( $_POST['_electronico_text_resolucion'] ) ) {
		update_post_meta( $post_id, '_electronico_text_resolucion', sanitize_text_field( $_POST['_electronico_text_resolucion'] ) );
	}
	if ( isset( $_POST['_electronico_text_peso'] ) ) {
		update_post_meta( $post_id, '_electronico_text_peso', sanitize_text_field( $_POST['_electronico_text_peso'] ) );
	}
	if ( isset( $_POST['_electronico_text_contenidos_de_la_caja'] ) ) {
		update_post_meta( $post_id, '_electronico_text_contenidos_de_la_caja', sanitize_text_field( $_POST['_electronico_text_contenidos_de_la_caja'] ) );
	}
	if ( isset( $_POST['_electronico_text_lector_de_tarjetas'] ) ) {
		update_post_meta( $post_id, '_electronico_text_lector_de_tarjetas', sanitize_text_field( $_POST['_electronico_text_lector_de_tarjetas'] ) );
	}
	if ( isset( $_POST['_electronico_text_wi_fi'] ) ) {
		update_post_meta( $post_id, '_electronico_text_wi_fi', sanitize_text_field( $_POST['_electronico_text_wi_fi'] ) );
	}
	if ( isset( $_POST['_electronico_text_bluetooth'] ) ) {
		update_post_meta( $post_id, '_electronico_text_bluetooth', sanitize_text_field( $_POST['_electronico_text_bluetooth'] ) );
	}
	if ( isset( $_POST['_electronico_text_procesador'] ) ) {
		update_post_meta( $post_id, '_electronico_text_procesador', sanitize_text_field( $_POST['_electronico_text_procesador'] ) );
	}
	if ( isset( $_POST['_electronico_text_chip'] ) ) {
		update_post_meta( $post_id, '_electronico_text_chip', sanitize_text_field( $_POST['_electronico_text_chip'] ) );
	}
	if ( isset( $_POST['_electronico_text_ram'] ) ) {
		update_post_meta( $post_id, '_electronico_text_ram', sanitize_text_field( $_POST['_electronico_text_ram'] ) );
	}
	if ( isset( $_POST['_electronico_text_numero_de_puertos_usb_20'] ) ) {
		update_post_meta( $post_id, '_electronico_text_numero_de_puertos_usb_20', sanitize_text_field( $_POST['_electronico_text_numero_de_puertos_usb_20'] ) );
	}
	if ( isset( $_POST['_electronico_text_numero_de_procesadores'] ) ) {
		update_post_meta( $post_id, '_electronico_text_numero_de_procesadores', sanitize_text_field( $_POST['_electronico_text_numero_de_procesadores'] ) );
	}
	
	//VIDEOJUEGOS
	if ( isset( $_POST['videojuegos_consola'] ) ) {
		update_post_meta( $post_id, 'videojuegos_consola', sanitize_text_field( $_POST['videojuegos_consola'] ) );
	}
	if ( isset( $_POST['videojuegos_almacenamiento'] ) ) {
		update_post_meta( $post_id, 'videojuegos_almacenamiento', sanitize_text_field( $_POST['videojuegos_almacenamiento'] ) );
	}
	if ( isset( $_POST['videojuegos_controles_cantidad'] ) ) {
		update_post_meta( $post_id, 'videojuegos_controles_cantidad', sanitize_text_field( $_POST['videojuegos_controles_cantidad'] ) );
	}
	if ( isset( $_POST['videojuegos_juegos_incluidos'] ) ) {
		update_post_meta( $post_id, 'videojuegos_juegos_incluidos', sanitize_text_field( $_POST['videojuegos_juegos_incluidos'] ) );
	}
	if ( isset( $_POST['videojuegos_cables'] ) ) {
		update_post_meta( $post_id, 'videojuegos_cables', sanitize_text_field( $_POST['videojuegos_cables'] ) );
	}
	

}

add_action( 'woocommerce_process_product_meta_simple_electronico', 'save_electronico_option_field' );

/**
 * Hide Attributes data panel.
 */
function hide_attributes_data_panel( $tabs) {

	$tabs['attribute']['class'][] = 'hide_if_simple_reloj';
	$tabs['attribute']['class'][] = 'hide_if_simple_joya';
	$tabs['attribute']['class'][] = 'hide_if_simple_electronico';

	$tabs['linked_product']['class'][] = 'hide_if_simple_reloj';
	$tabs['linked_product']['class'][] = 'hide_if_simple_joya';
	$tabs['linked_product']['class'][] = 'hide_if_simple_electronico';

	$tabs['advanced']['class'][] = 'hide_if_simple_reloj';
	$tabs['advanced']['class'][] = 'hide_if_simple_joya';
	$tabs['advanced']['class'][] = 'hide_if_simple_electronico';

	return $tabs;

}
add_filter( 'woocommerce_product_data_tabs', 'hide_attributes_data_panel' );