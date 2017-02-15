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
			//var_dump($item);
			if(!isset($item['product_id'])) {
				foreach ($item['item_meta_array'] as $m) {
					if($m->key == '_product_id') {
						$meta = get_post_meta($m->value);
						$sucu = $meta['sucursal'][0];
					}
					if($m->key == '_qty') {
						$qty = $m->value;
					}
					
				}
				$s = get_the_title($sucu);
				$articulos .= $item['name'].' - Cantidad:'.$qty.' <br>Sucursal:'.$sucu.'.- '.$s.' <br>';
			}
			else {
				$meta = get_post_meta($item['product_id']);
				$sucu = $meta['sucursal'][0];
				$s = get_the_title($sucu);
				$articulos .= $item['name'].' - Cantidad:'.$item['qty'].' <br>Sucursal: '.$sucu.'.- '.$s.' <br>';
			}
			error_log(print_r($s, true));
		}
		//error_log("===---".$sucu, 0);
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
		//error_log("ooooooooooooo>".$sucu_id, 0);
		$sucu_metas = get_post_meta($sucu_id);
		
		//error_log(print_r($sucu_metas, true));
		if(isset($sucu_metas['email_responsable_principal'][0]) && $sucu_metas['email_responsable_principal'][0] != '') {
			$emails .= $sucu_metas['email_responsable_principal'][0].',';
		}
		if(isset($sucu_metas['email_responsable_secundario'][0]) && $sucu_metas['email_responsable_secundario'][0] != '') {
			$emails .= $sucu_metas['email_responsable_secundario'][0].',';
		}
		if(isset($sucu_metas['email_responsable_terciario'][0]) && $sucu_metas['email_responsable_terciario'][0] != '') {
			$emails .= $sucu_metas['email_responsable_terciario'][0].',';
		}
		$emails = substr($emails, 0, -1);
		error_log("==============>".$emails, 0);
		$resp = wp_mail( $emails, $subject, $message, $headers );
		error_log('CORREO -> '.$resp, 0);
			
	}
	add_action( 'woocommerce_payment_complete', 'pago_completado' );
	add_action( 'woocommerce_order_status_processing', 'pago_completado' );

?>