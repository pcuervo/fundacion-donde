<?php


// CUSTOM PAGES //////////////////////////////////////////////////////////////////////

add_action('init', function(){

	// Comprar plan
	if( ! get_page_by_path('inicio') ){
		$page = array(
			'post_author' => 1,
			'post_status' => 'publish',
			'post_title'  => 'Inicio',
			'post_name'   => 'inicio',
			'post_type'   => 'page'
		);
		wp_insert_post( $page, true );
	}

	// Preguntas frecuentes
	if( ! get_page_by_path('preguntas-frecuentes') ){
		$page = array(
			'post_author' => 1,
			'post_status' => 'publish',
			'post_title'  => 'Preguntas frecuentes',
			'post_name'   => 'preguntas-frecuentes',
			'post_type'   => 'page'
		);
		wp_insert_post( $page, true );
	}

});