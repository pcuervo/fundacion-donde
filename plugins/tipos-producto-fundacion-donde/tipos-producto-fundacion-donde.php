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
 * Contents of the reloj options product tab.
 */
function reloj_options_product_tab_content() {
	global $post;
	?><div id='reloj_options' class='panel woocommerce_options_panel'><?php

		?><div class='options_group'><?php

			// El campo de desc_tip y descripción son opcionales, úsalos
			// cuando creas que no se entiende bien el nombre del campo.
			woocommerce_wp_text_input( array(
				'id'			=> '_reloj_text_ano',
				'label'			=> __( 'Año', 'woocommerce' ),
				'desc_tip'		=> 'true',
				'description'	=> __( 'Año debe ser un número', 'woocommerce' ),
				'type' 			=> 'text',
			) );

			woocommerce_wp_text_input( array(
				'id'			=> '_reloj_text_marca',
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

	if ( isset( $_POST['_reloj_text_ano'] ) ) {
		update_post_meta( $post_id, '_reloj_text_ano', sanitize_text_field( $_POST['_reloj_text_ano'] ) );
	}
	if ( isset( $_POST['_reloj_text_marca'] ) ) {
		update_post_meta( $post_id, '_reloj_text_marca', sanitize_text_field( $_POST['_reloj_text_marca'] ) );
	}

}
add_action( 'woocommerce_process_product_meta_simple_reloj', 'save_reloj_option_field' );


function save_joya_option_field( $post_id ) {

	if ( isset( $_POST['_joya_text_ano'] ) ) {
		update_post_meta( $post_id, '_joya_text_ano', sanitize_text_field( $_POST['_joya_text_ano'] ) );
	}
	if ( isset( $_POST['_joya_text_marca'] ) ) {
		update_post_meta( $post_id, '_joya_text_marca', sanitize_text_field( $_POST['_joya_text_marca'] ) );
	}

}
add_action( 'woocommerce_process_product_meta_simple_joya', 'save_joya_option_field' );



function save_electronico_option_field( $post_id ) {

	if ( isset( $_POST['_electronico_text_ano'] ) ) {
		update_post_meta( $post_id, '_electronico_text_ano', sanitize_text_field( $_POST['_electronico_text_ano'] ) );
	}
	if ( isset( $_POST['_electronico_text_marca'] ) ) {
		update_post_meta( $post_id, '_electronico_text_marca', sanitize_text_field( $_POST['_electronico_text_marca'] ) );
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