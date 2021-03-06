<?php

// CUSTOM POST TYPES /////////////////////////////////////////////////////////////////


	add_action('init', function(){

		// Preguntas frecuentes
		$labels = array(
			'name'          => 'Preguntas',
			'singular_name' => 'Pregunta',
			'add_new'       => 'Nueva preguntas',
			'add_new_item'  => 'Nuevo preguntas',
			'edit_item'     => 'Editar preguntas',
			'new_item'      => 'Nuevo preguntas',
			'all_items'     => 'Todo',
			'view_item'     => 'Ver preguntas',
			'search_items'  => 'Buscar preguntas',
			'not_found'     => 'No hay preguntas.',
			'menu_name'     => 'Preguntas'
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'preguntas' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 6,
			'supports'           => array( 'title', 'editor' )
		);
		register_post_type( 'preguntas', $args );

		// Valores
		$labels = array(
			'name'          => 'Valores',
			'singular_name' => 'Pregunta',
			'add_new'       => 'Nuevo valor',
			'add_new_item'  => 'Nuevo valor',
			'edit_item'     => 'Editar valor',
			'new_item'      => 'Nuevo valor',
			'all_items'     => 'Todo',
			'view_item'     => 'Ver valor',
			'search_items'  => 'Buscar valor',
			'not_found'     => 'No hay valores.',
			'menu_name'     => 'Valores'
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'valores' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 6,
			'supports'           => array( 'title' )
		);
		register_post_type( 'valores', $args );

	});