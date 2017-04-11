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

	//$fecha = " and (p.post_date >= '".$_POST['date']." 00:00:00' and p.post_date <= '".$_POST['date']." 23:59:59')"; 

	header("Content-type: text/csv");
	header("Content-Disposition: attachment; filename=loads-undone-report.csv");
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
						'ID DE CARGA',
						'SKU',
						'CANT'
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
	$canttotal = 0;
	$subtotal = 0;


	$bulk_detail = $wpdb->get_results("SELECT bld.* FROM ".$wpdb->prefix."bulk_load_detail bld WHERE bld.status = '0'");
	foreach ($bulk_detail as $detail) {
		
		$data[$n] =  array(
					$detail->bulk_load_id,
					'="'.$detail->sku.'"',
					$detail->cantidad,
					);
		
		$n++;

	}
		
	outputCSV($data);

