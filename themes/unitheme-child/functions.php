<?php
	function additionals_functions() {
		// https://developer.wordpress.org/reference/functions/locate_template/
		// locate_template( $nombres_de_plantilla, $cargar, $requerir_una_vez )
		// Con esta función se carga un archivo y se sobreescribe para el child y el parent theme.
		locate_template( array( 'includes/metaboxes.php' ), TRUE, TRUE );
		locate_template( array( 'includes/post-types.php' ), TRUE, TRUE );
	}
	add_action( 'after_setup_theme', 'additionals_functions' );

	function pago_completado( $order_id ) {
		global $wpdb;
		error_log( "%%%%%%%%%%%%%%%%%%%%%%%%%%% Order PAYMENT COMPLETED for order $order_id", 0 );
		$articulos = '';
		$order = new WC_Order( $order_id );
		$order_item = $order->get_items();
		foreach ($order_item as $item ) {
			if(!isset($item['variation_id'])) {
				foreach ($item['item_meta_array'] as $m) {
					if($m->key == '_variation_id') {
						$meta = get_post_meta($m->value);
						$sucu = $meta['sucursal'][0];
					}
					if($m->key == '_qty') {
						$qty = $m->value;
					}
					
				}
				$articulos .= $item['name'].' Cant:'.$qty.' Sucursal:'.$sucu.' <br>';
			}
			else {
				$meta = get_post_meta($item['variation_id']);
				$sucu = $meta['sucursal'][0];
				$articulos .= $item['name'].' Cant:'.$item['qty'].' Sucursal:'.$sucu.' <br>';
			}
		}
		$meta_orden = get_post_meta($order_id);
		$total = $meta_orden['_order_total'][0];
		
		$subject = 'Fundación Donde - Orden completada #'.$order_id.'';
		$headers = array('Content-Type: text/html; charset=UTF-8');
		//$headers = 'From: Pixan <' . $ud->user_email . '>' . "\r\n";
		$message = '<html><body>';
		$message .= 'La orden '.$order_id.' fue completada exitosamente<br>';
		$message .= 'Monto: $'.$total.' <br>';
		$message .= 'Artículos: <br>';
		$message .= $articulos;
		$message .= '</body></html>';

		add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));

		//SEND EMAIL CONFIRMATION
		$emails = '';
		$sucu_id = $wpdb->get_var("SELECT post_id FROM $wpdb->postmeta WHERE meta_value = '".$sucu."' and meta_key = 'numero_sucursal'");
		$sucu_metas = get_post_meta($sucu_id);
		if(isset($meta['email_responsable_principal'][0]) && $meta['email_responsable_principal'][0] != '') {
			$emails .= $meta['email_responsable_principal'][0].',';
		}
		if(isset($meta['email_responsable_secundario'][0]) && $meta['email_responsable_secundario'][0] != '') {
			$emails .= $meta['email_responsable_secundario'][0].',';
		}
		if(isset($meta['email_responsable_terciario'][0]) && $meta['email_responsable_terciario'][0] != '') {
			$emails .= $meta['email_responsable_terciario'][0].',';
		}
		$emails = substr($emails, 0, -1);
		$resp = wp_mail( $emails, $subject, $message, $headers );
		error_log('CORREO -> '.$resp, 0);
			
	}
	add_action( 'woocommerce_payment_complete', 'pago_completado' );
	add_action( 'woocommerce_order_status_processing', 'pago_completado' );

?>