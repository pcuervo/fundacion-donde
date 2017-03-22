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

	// Nosotros
	if( ! get_page_by_path('nosotros') ){
		$page = array(
			'post_author' => 1,
			'post_status' => 'publish',
			'post_title'  => 'Nosotros',
			'post_name'   => 'nosotros',
			'post_type'   => 'page'
		);
		wp_insert_post( $page, true );
	}

	// Sucursales
	if( ! get_page_by_path('sucursales') ){
		$page = array(
			'post_author' => 1,
			'post_status' => 'publish',
			'post_title'  => 'Sucursales',
			'post_name'   => 'sucursales',
			'post_type'   => 'page'
		);
		wp_insert_post( $page, true );
	}

	// calidad garantizada
	if( ! get_page_by_path('calidad-garantizada') ){
		$page = array(
			'post_author' => 1,
			'post_status' => 'publish',
			'post_title'  => 'Calidad garantizada',
			'post_name'   => 'calidad-garantizada',
			'post_type'   => 'page'
		);
		wp_insert_post( $page, true );
	}

	// Envío express
	if( ! get_page_by_path('envio-express') ){
		$page = array(
			'post_author' => 1,
			'post_status' => 'publish',
			'post_title'  => 'Envío express',
			'post_name'   => 'envio-express',
			'post_type'   => 'page'
		);
		wp_insert_post( $page, true );
	}

	// Envío gratis
	if( ! get_page_by_path('envio-gratis') ){
		$page = array(
			'post_author' => 1,
			'post_status' => 'publish',
			'post_title'  => 'Envío gratis',
			'post_name'   => 'envio-gratis',
			'post_type'   => 'page'
		);
		wp_insert_post( $page, true );
	}

});