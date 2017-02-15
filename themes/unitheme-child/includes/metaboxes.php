<?php
	//SUCURSALES METABOX
	function show_metabox_sucursal($post){
		
		$numero_sucursal = get_post_meta($post->ID, 'numero_sucursal', true);
		$email_responsable_principal = get_post_meta($post->ID, 'email_responsable_principal', true);
		$email_responsable_secundario = get_post_meta($post->ID, 'email_responsable_secundario', true);
		$email_responsable_terciario = get_post_meta($post->ID, 'email_responsable_terciario', true);
		$ubicacion = get_post_meta($post->ID, 'ubicacion', true);
		
		wp_nonce_field(__FILE__, '_numero_sucursal_nonce');
		wp_nonce_field(__FILE__, '_email_responsable_principal_nonce');
		wp_nonce_field(__FILE__, '_email_responsable_secundario_nonce');
		wp_nonce_field(__FILE__, '_email_responsable_terciario_nonce');
		wp_nonce_field(__FILE__, '_ubicacion_nonce');
		echo '<div class="row">';
			echo '<div class="md-12" style="width:100%; float:left;">';
				echo '<strong>Numero de Sucursal: </strong>';
				echo '<input type="number" class="" name="numero_sucursal" id="numero_sucursal" value="'.$numero_sucursal.'"  />';
			echo '</div>';
			echo '<br>';
		echo '<br>';
		echo '</div>';
		echo '<div class="row">';
			echo '<div class="md-6" style="width:33%; float:left;">';
				echo '<strong>Email responsable principal: </strong>';
				echo '<input type="email" class="" name="email_responsable_principal" id="email_responsable_principal" value="'.$email_responsable_principal.'" style="width: 100%;" />';
			echo '</div>';
			echo '<div class="md-6" style="width:33%; float:left;">';
				echo '<strong>Email responsable secundario: </strong>';
				echo '<input type="email" class="" name="email_responsable_secundario" id="email_responsable_secundario" value="'.$email_responsable_secundario.'" style="width: 100%;" />';
			echo '</div>';
			echo '<div class="md-6" style="width:33%; float:left;">';
				echo '<strong>Email responsable Terciario: </strong>';
				echo '<input type="email" class="" name="email_responsable_terciario" id="email_responsable_terciario" value="'.$email_responsable_terciario.'" style="width: 100%;" />';
			echo '</div>';
		echo '</div>';
		echo '<br>';
		echo '<br>';
		echo '<br>';
		echo '<br>';
		
		
		
	}

	/**
	* Save the metaboxes for post type "Actividad"
	* */
	add_action( 'save_post', function ( $post_id ){
		if ( isset($_POST['numero_sucursal']) and check_admin_referer(__FILE__, '_numero_sucursal_nonce') ){
			update_post_meta($post_id, 'numero_sucursal', $_POST['numero_sucursal']);
		}
		if ( isset($_POST['email_responsable_principal']) and check_admin_referer(__FILE__, '_email_responsable_principal_nonce') ){
			update_post_meta($post_id, 'email_responsable_principal', $_POST['email_responsable_principal']);
		}
		if ( isset($_POST['email_responsable_secundario']) and check_admin_referer(__FILE__, '_email_responsable_secundario_nonce') ){
			update_post_meta($post_id, 'email_responsable_secundario', $_POST['email_responsable_secundario']);
		}
		if ( isset($_POST['email_responsable_terciario']) and check_admin_referer(__FILE__, '_email_responsable_terciario_nonce') ){
			update_post_meta($post_id, 'email_responsable_terciario', $_POST['email_responsable_terciario']);
		}
				
	});// save_meta_boxes_biblioteca

	function show_products_meta($post) {
		$sucursal = get_post_meta($post->ID, 'sucursal', true);
		$estado = get_post_meta($post->ID, 'estado', true);

		wp_nonce_field(__FILE__, '_sucursal_nonce');
		wp_nonce_field(__FILE__, '_estado_nonce');

		echo '<div class="row">';
			echo '<div class="md-12" style="width:100%; float:left;">';
				echo '<strong>Numero de Sucursal: </strong>';
				echo '<input type="number" class="" name="sucursal" id="sucursal" value="'.$numero_sucursal.'"  />';
				echo '<strong>Estado del Artículo: </strong>';
				echo '<input type="text" class="" name="estado" id="estado" value="'.$estado.'"  />';
			echo '</div>';
			echo '<br>';
		echo '<br>';
		echo '</div>';
	}

	add_action( 'save_post', function ( $post_id ){
		if ( isset($_POST['sucursal']) and check_admin_referer(__FILE__, '_sucursal_nonce') ){
			update_post_meta($post_id, 'sucursal', $_POST['sucursal']);
		}
		if ( isset($_POST['estado']) and check_admin_referer(__FILE__, '_estado_nonce') ){
			update_post_meta($post_id, 'estado', $_POST['estado']);
		}
		
				
	});// save_meta_boxes_biblioteca

	add_action('add_meta_boxes', function(){
		global $post;
		add_meta_box( 'meta-box-sucursal', 'Datos adicionales', 'show_metabox_sucursal', 'sucursal');
		add_meta_box( 'meta-box-articulo', 'Datos adicionales', 'show_products_meta', 'product');
	});

?>