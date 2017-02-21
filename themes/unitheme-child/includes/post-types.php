<?php
/*------------------------------------*\
	CUSTOM POST TYPES
\*------------------------------------*/

add_action('init', function(){

	// Sucursales
	$labels = array(
		'name'          => 'Sucursales',
		'singular_name' => 'Sucursal',
		'add_new'       => 'Nueva Sucursal',
		'add_new_item'  => 'Nueva Sucursal',
		'edit_item'     => 'Editar Sucursal',
		'new_item'      => 'Nueva Sucursal',
		'all_items'     => 'Sucursales',
		'view_item'     => 'Ver Sucursal',
		'search_items'  => 'Buscar Sucursales',
		'not_found'     => 'No se encontró',
		'menu_name'     => 'Sucursales'
	);
	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'sucursal' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 6,
		'supports'           => array( 'title', 'thumbnail' ),
		'taxonomies'         => array( 'post_tag' ),
	);
	register_post_type( 'sucursal', $args );

});

?>
