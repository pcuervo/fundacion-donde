<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package AitImport
 * @author  AitThemes.com <info@ait-themes.com>
 * @link    http://www.AitThemes.com/
 * @since   1.0.0
 */

$import = AitImport::get_instance();

?>
<div class="wrap">
	<?php screen_icon(); ?>
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	
	<?php 

	// import posts from uploaded file
	if(!isset($_POST["date"]) && $_POST['action'] == AIT_IMPORT_PLUGIN_URL . 'sales-report-diary.php') {
		echo '<div class="error"><p>'.__('Por favor ingresa una fecha para generar el reporte diario').'.</p></div>';
	}
	?>

	<?php
	foreach ($import->post_types as $type) { ?>
	
	<div class="import-custom-type metabox-holder">
		
		<div class="import-options postbox">

			<div class="handlediv" title="Click to toggle"><br></div>

			<h3 class="hndle"><span>Reporte PAYPAL</span></h3>

			<div class="inside">
			
				<form action="<?php echo AIT_IMPORT_PLUGIN_URL . 'sales-report-paypal.php'; ?>" method="post">
					
					<h4><?php _e('Ingrese rango de fechas para generar el reporte'); ?></h4>

					<input type="date" name="date-since">
					<input type="date" name="date-to">
					
					<input type="submit" value="<?php _e('Generar Reporte de Ventas PAYPAL'); ?>" class="download button">
				
				</form>


			</div>

		</div>

	</div>

	<div class="import-custom-type metabox-holder">
		
		<div class="import-options postbox">

			<div class="handlediv" title="Click to toggle"><br></div>

			<h3 class="hndle"><span>Reporte listado de Pedidos</span></h3>

			<div class="inside">
			
				<form action="<?php echo AIT_IMPORT_PLUGIN_URL . 'orders-report.php'; ?>" method="post">
					
					<h4><?php _e('Ingrese rango de fechas para generar el reporte'); ?></h4>

					<input type="date" name="date-since">
					<input type="date" name="date-to">
					
					<input type="submit" value="<?php _e('Generar Reporte listado de Ordenes'); ?>" class="download button">
				
				</form>


			</div>

		</div>

	</div>


	
	<div class="import-custom-type metabox-holder">
		
		<div class="import-options postbox">

			<div class="handlediv" title="Click to toggle"><br></div>

			<h3 class="hndle"><span>Reporte Reembolsados PAYPAL</span></h3>

			<div class="inside">
			
				<form action="<?php echo AIT_IMPORT_PLUGIN_URL . 'sales-report-refunded-paypal.php'; ?>" method="post">
					
					<h4><?php _e('Ingrese rango de fechas para generar el reporte'); ?></h4>

					<input type="date" name="date-since">
					<input type="date" name="date-to">
					
					<input type="submit" value="<?php _e('Generar Reporte pedidos Reembolsados de PAYPAL'); ?>" class="download button">
				
				</form>


			</div>

		</div>

	</div>

	<?php } ?>
</div>