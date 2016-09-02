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
function register_simple_reloj_product_type() {
	require_once( TIPOS_PRODUCTO_FD_PLUGIN_DIR . 'src/wc-fd-reloj-product-type.php' );
}
add_action( 'plugins_loaded', 'register_simple_reloj_product_type' );


/**
 * Add to product type drop down.
 */
function fd_add_product_types( $types ){

	// El "type" debe ser el mismo que la clase que cargas arriba.
	$types[ 'simple_reloj' ] = __( 'Reloj' );

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
			jQuery( '.general_options' ).addClass( 'show_if_simple_reloj' ).show();
			jQuery( '.options_group.pricing' ).addClass( 'show_if_simple_reloj' ).show();
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
		'label'		=> __( 'Atributos Relojes', 'woocommerce' ),
		'target'	=> 'reloj_options',
		'class'		=> array( 'show_if_simple_reloj', 'show_if_variable_reloj'  ),
	);

	return $tabs;

}
add_filter( 'woocommerce_product_data_tabs', 'fd_custom_product_tabs' );


/**
 * Contents of the reloj options product tab.
 */
function reloj_options_product_tab_content() {
	global $post;
	?><div id='reloj_options' class='panel woocommerce_options_panel'><?php

		?><div class='options_group'><?php

			// El campo de desc_tip y descripción son opcionales, úsalos
			// cuando creas que no se entiende bien el nombre del campo.
			woocommerce_wp_text_input( array(
				'id'			=> '_text_ano',
				'label'			=> __( 'Año', 'woocommerce' ),
				'desc_tip'		=> 'true',
				'description'	=> __( 'Año debe ser un número', 'woocommerce' ),
				'type' 			=> 'text',
			) );

			woocommerce_wp_text_input( array(
				'id'			=> '_text_marca',
				'label'			=> __( 'Marca', 'woocommerce' ),
				'type' 			=> 'text',
			) );

		?></div>

	</div><?php
}
add_action( 'woocommerce_product_data_panels', 'reloj_options_product_tab_content' );


/**
 * Save the custom fields. 
 */
function save_reloj_option_field( $post_id ) {

	if ( isset( $_POST['_text_ano'] ) ) {
		update_post_meta( $post_id, '_text_ano', sanitize_text_field( $_POST['_text_ano'] ) );
	}
	if ( isset( $_POST['_text_marca'] ) ) {
		update_post_meta( $post_id, '_text_marca', sanitize_text_field( $_POST['_text_marca'] ) );
	}

}
add_action( 'woocommerce_process_product_meta_simple_reloj', 'save_reloj_option_field'  );
add_action( 'woocommerce_process_product_meta_variable_reloj', 'save_reloj_option_field'  );


/**
 * Hide Attributes data panel.
 */
function hide_attributes_data_panel( $tabs) {

	$tabs['attribute']['class'][] = 'hide_if_simple_reloj hide_if_variable_reloj';

	return $tabs;

}
add_filter( 'woocommerce_product_data_tabs', 'hide_attributes_data_panel' );