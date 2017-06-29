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
header("Content-Disposition: attachment; filename=orders-report-".$_POST['date-since']."___".$_POST['date-to'].".csv");
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
					'# ORDEN',
					'FECHA',
					'RAZON SOCIAL',
					'CONTACTO',
					'DIRECCION 1',
					'DIRECCION 2',
					'CIUDAD',
					'CP',
					'ESTADO',
					'TELEFONO',
					'REFERENCIA 1',
					'REFERENCIA 2',
					'CORREO',
					'NUMERO DE PAQUETES'
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
$orders = $wpdb->get_results("SELECT p.ID, p.post_date FROM $wpdb->posts p where post_type = 'shop_order' and post_status IN ('wc-completed', 'wc-processing') ".$desde." ".$hasta.";");

foreach ($orders as $order) {
	//echo "ORDEN <br />";
	//var_dump($order);
	
	

	$sale = new WC_Order( $order->ID );
	//echo "WC ORDEN <br />";
	//var_dump($sale);

	$meta = get_post_meta($order->ID);
	//echo "===============ORDEN META<br />";
	//var_dump($meta);

	$razon_social = $meta['_shipping_first_name'][0].' '.$meta['_shipping_last_name'][0];
	$contacto = $meta['_shipping_first_name'][0].' '.$meta['_shipping_last_name'][0];
	$telefono = '5543367589';
	if(isset($meta['_billing_phone'][0]) && $meta['_billing_phone'][0] != '') {
		$telefono = $meta['_billing_phone'][0];
	}


	$data[$n] =  array(
						$order->ID,
						$order->post_date,
						utf8_decode($razon_social),
						utf8_decode($contacto),
						utf8_decode($meta['_shipping_address_1'][0]),
						utf8_decode($meta['_shipping_address_2'][0]),
						utf8_decode($meta['_shipping_city'][0]), 
						$meta['_shipping_postcode'][0], 
						utf8_decode($meta['_shipping_state'][0]), 
						$telefono, 
						utf8_decode($sale->customer_note),
						utf8_decode($sale->customer_message),
						$meta['_billing_email'][0],
						1,
						);
			
			$n++;
	
		
	
}



outputCSV($data);