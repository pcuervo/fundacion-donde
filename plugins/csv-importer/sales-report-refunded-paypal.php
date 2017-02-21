<?php


$wp_root = dirname(__FILE__) .'/../../../';
if(file_exists($wp_root . 'wp-load.php')) {
	require_once($wp_root . "wp-load.php");
} else if(file_exists($wp_root . 'wp-config.php')) {
	require_once($wp_root . "wp-config.php");
} else {
	exit;
}

if ( !current_user_can('manage_options') ) {
	exit(0);
}

if(file_exists($wp_root . 'wp-config.php')) {
	require_once($wp_root . "wp-config.php");
}

global $wpdb;

header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=sales-report-REEMBOLSADOS-paypal-".$_POST['date-since']."___".$_POST['date-to'].".csv");
header("Pragma: no-cache");
header("Expires: 0");

function outputCSV($data) {
	$outstream = fopen("php://output", 'w');
	function __outputCSV(&$vals, $key, $filehandler) {
		fputcsv($filehandler, $vals, ';', '"');
	}
	array_walk($data, '__outputCSV', $outstream);
	fclose($outstream);
}

$layout = array(
				'PICKING',
				'FECHA DE VENTA',
				'# ORDEN',
				'MIDS',
				'UPC',
				'NOMBRE',
				'SIZE', 
				'UNITS',
				'FECHA REEMBOLSO',
				'RAZON',
				'REEMBOLSADO',
				'METODO PAGO',
				'MSRP without VAT per UNIT',
				'MSRP without VAT',
				'DESCUENTOS DISTRIBUIDOR 2.5%',
				'SUBTOTAL',
				'VAT',
				'TOTAL ADEUDADO',
				'MSRP',
				'COMISION PAYPAL',
				'COMISION PAYPAL FIJA',
				'ENVIO POR ORDEN',
				'ENVIO FIJO',
				'1.5% FACTURACION CON IVA',
				'1.5% FACTURACION SIN IVA',
				);

$data = array();
// fix for microsoft office
$data[0] = array('sep=;');
$data[1] = $layout;
$orden_number = 0;
$i = 0;
$n = 2;
$desde = '';
$hasta = '';
if(isset($_POST['date-since']) && $_POST['date-since'] != '') { $desde = " and p.post_date >= '".$_POST['date-since']." 00:00:00'"; }
if(isset($_POST['date-to']) && $_POST['date-to'] != '') { $hasta = " and p.post_date <= '".$_POST['date-to']." 23:59:59'"; }

$reembolsos = $wpdb->get_results("SELECT p.ID, p.post_excerpt, p.post_parent, p.post_date FROM $wpdb->posts p where post_type = 'shop_order_refund' and post_status IN ('wc-completed') ".$desde." ".$hasta.";");
foreach ($reembolsos as $reem) {
	$orders = $wpdb->get_results("SELECT p.ID, p.post_date FROM $wpdb->posts p where post_type = 'shop_order' and ID = ".$reem->post_parent."");

	foreach ($orders as $order) {
		
		//var_dump($order);
		

		
		$sale = new WC_Order( $order->ID );
		//var_dump($sale->payment_method);
		
		if ($sale->payment_method == 'paypal' || $sale->payment_method == 'paypal_express') {
			$order_item = $sale->get_items();
			//var_dump($order_item);
			
			foreach ($order_item as $item ) {
				$arti = explode("=", $reem->post_excerpt);
				
				if($arti[1] == $item['variation_id']) { 
					//var_dump($item);
					$meta = get_post_meta($item['variation_id']);
					$term_talla = get_term_by('slug', $item['item_meta']['pa_talla'][0], 'pa_talla');
					//var_dump($meta);

					$comision_paypal = 0;

					$precio = $meta['_price'][0];
					$cant = $item['item_meta']['_qty'][0];

					$MSRPwithoutVATperUNIT = round($precio/1.16, 2);
					$MSRPwithoutVAT = round($MSRPwithoutVATperUNIT*$cant, 2);
					$desc_dist = round($MSRPwithoutVAT*0.025, 2);
					$subtotal = round($MSRPwithoutVAT-$desc_dist, 2);
					$VAT = round($subtotal*0.16, 2);
					$totaladeudado = round($subtotal+$VAT, 2);
					$comision_paypal = round(($cant*$precio)*0.0395, 2);
					$facturacion_iva = round($cant*$precio*0.015, 2);
					$facturacion = round($facturacion_iva/1.16, 2);
					$comision_fija_paypal = 4;
					$envio = 0;
					if($meta_orden['_order_total'][0]-round($meta_orden['_order_shipping'][0]*1.16, 2) >= 1300) { $envio = 120; }
					$envio_fijo = 20;
					//echo $orden_number.' = '.$order->ID.'<br />';
					$monto_reembolsado = get_post_meta($reem->ID);
					$mr = $monto_reembolsado['_order_total'][0];
					$razon = utf8_decode($reem->post_excerpt);
					if($orden_number == $order->ID) {
						//$mr = '';
						//$razon = '';
						$comision_fija_paypal = 0;
						$envio = 0;
						$envio_fijo = 0;
					}
					if($orden_number != $order->ID) { $orden_number = $order->ID; $i++; }
					$data[$n] =  array(
								$i,
								$order->post_date,
								$order->ID,
								$meta['_sku'][0],
								'="'.$meta['_upc'][0].'"',
								utf8_decode($item['name']),
								$term_talla->name, 
								$cant,
								$reem->post_date,
								$razon,
								$mr,
								$sale->payment_method,
								$MSRPwithoutVATperUNIT,
								$MSRPwithoutVAT,
								$desc_dist,
								$subtotal,
								$VAT,
								$totaladeudado,
								$precio,
								$comision_paypal,
								$comision_fija_paypal,
								$envio,
								$envio_fijo,
								$facturacion_iva,
								$facturacion,
								);
					
					$n++;
				}
				
				
			}
			
		}
		
		
	}
}

outputCSV($data);