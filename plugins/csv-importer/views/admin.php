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
	// save encoding
	if(isset($_POST["encoding"])) {
		update_option( 'ait_import_plugin_encoding', $_POST["encoding"] );
		echo '<div class="updated"><p>'.__('Settings saved').'.</p></div>';
	}
	// import posts from uploaded file
	if(isset($_FILES["posts_csv"]) && isset($_POST["type"])) {
		$ext = explode('.', $_FILES["posts_csv"]['name']);
		if ($_FILES["posts_csv"]["error"] > 0 || $ext[count($ext)-1] != 'csv') {
			echo '<div class="error"><p>'.__('Incorrect CSV file').'. Por favor intente de nuevo con un archivo correcto.</p></div>';
		} else {
			$import->import_csv($_POST["type"],$_FILES["posts_csv"]['tmp_name'],$_POST["duplicate"], $_FILES["posts_csv"]['name'], $_POST["statusProductos"], $_POST["porcentaje"]);
		}
		
	}
	// import categories from uploaded file
	if(isset($_FILES["categories_csv"]) && isset($_POST["type"])) {
		if ($_FILES["categories_csv"]["error"] > 0) {
			echo '<div class="error"><p>'.__('Incorrect CSV file').'.</p></div>';
		} else {
			$import->import_terms_csv($_POST["type"],$_FILES["categories_csv"]['tmp_name'],$_POST["duplicate"]);
		}
	}
	// eliminar una carga con todos sus articulos
	if(isset($_POST["deshacer"]) && isset($_POST["bulk_id"]) && isset($_POST["deshacer_confirmar"]) ) {
		$import->deshacer_carga($_POST["bulk_id"]);
	}

	// eliminar un registro de alguna de las cargas
	if(isset($_POST["eliminar_detalle"]) && isset($_POST["bulk_id"]) && isset($_POST["post_id"]) && isset($_POST["cantidad"]) ) {
		$import->eliminar_detalle($_POST["bulk_id"], $_POST["post_id"], $_POST["cantidad"]);
	}

	// eliminar un registro de alguna de las cargas
	if(isset($_POST["restaurar_detalle"]) && isset($_POST["bulk_id"]) && isset($_POST["post_id"]) && isset($_POST["cantidad"]) ) {
		$import->restaurar_detalle($_POST["bulk_id"], $_POST["post_id"], $_POST["cantidad"]);
	}
	?>
	<!--
	<div class="import-settings metabox-holder">
		<div class="import-options postbox">

			<div class="handlediv" title="Click to toggle"><br></div>

			<h3 class="hndle"><span><?php //_e('Import settings'); ?></span></h3>

			<div class="inside">

				<?php //$saved_encoding = get_option( 'ait_import_plugin_encoding', '25' ); ?>

				<form action="admin.php?page=ait-import" method="post">
					<label for="import-encoding"><?php //_e('Encoding of imported CSV files: '); ?></label>
					<select name="encoding" id="import-encoding">
					<?php /*foreach (mb_list_encodings() as $key => $value) {
						if($key == intval($saved_encoding)) {
							echo "<option selected='selected' value='$key'>$value</option>";
						} else {
							echo "<option value='$key'>$value</option>";
						}
					}*/ ?>
					</select>
					<input type="submit" value="<?php //_e('Save settings'); ?>" class="save button">
				</form>

			</div>

		</div>
	</div>
	-->
	<?php 
		global $wpdb;
		
		if((isset($_POST["detalle"]) && isset($_POST["bulk_id"])) || (isset($_POST["eliminar_detalle"]) || isset($_POST["restaurar_detalle"]))) {
			$bulk_detail = $wpdb->get_results("SELECT bld.* FROM ".$wpdb->prefix."bulk_load_detail bld WHERE bld.bulk_load_id = ".$_POST["bulk_id"]);

			if(!empty( $bulk_detail )) {
				echo '<div class="import-custom-type metabox-holder">';		
				echo '<div class="import-options postbox">';
				echo '<button type="button" class="handlediv button-link" aria-expanded="true"><span class="screen-reader-text">Alternar panel: Actividad</span><span class="toggle-indicator" aria-hidden="true"></span></button>';
				echo '<h3 class="hndle"><span>DETALLE DE LA CARGA ID: '.$_POST["bulk_id"].'</span></h3>';
				echo '<div class="inside">';
				echo '<table style="width:100%;">';
				echo '<thead>
							<th width="30%" style="text-align:left;">Nombre</th>
							<th width="20%" style="text-align:left;">Comentarios</th>
							<th width="15%" style="text-align:center;">SKU</th>
							<th width="5%" style="text-align:center;">Cantidad</th>
							<th width="10%" style="text-align:center;">Acciones</th>
						</thead>';
				echo '<tbody>';
				foreach ($bulk_detail as $detail) {
					$produ = get_post($detail->post_id);
					$stilo = '';
					if($detail->status == '1') { $stilo = 'background-color:#dc3232'; }
					if($detail->status == '0') { $stilo = 'background-color:#ffb900'; }
					
					echo '<tr >';
					echo '<td>'.$produ->post_title.'</td>';
					echo '<td>'.$detail->comentarios.'</td>';
					echo '<td style="text-align:center;">'.$detail->sku.'</td>';
					echo '<td style="text-align:center;">'.$detail->cantidad.'</td>';
					echo '<td style="text-align:center;">
							<form action="admin.php?page=ait-import" method="post">';
								if($detail->status == '1') {  
									echo '<input style="'.$stilo.'" class="btn button " id="btnSendToSystem" type="submit" value="ELIMINAR">';
									echo '<input type="hidden" name="eliminar_detalle" >';
								}
								else {
									echo '<input style="'.$stilo.'" class="btn button " id="btnSendToSystem" type="submit" value="RESTAURAR">';
									echo '<input type="hidden" name="restaurar_detalle" >';
								}
								echo '<input type="hidden" name="bulk_id" value="'.$_POST["bulk_id"].'" >
								<input type="hidden" name="post_id" value="'.$detail->post_id.'" >
								<input type="hidden" name="cantidad" value="'.$detail->cantidad.'" >
							</form>
						</td>';
					echo '</tr>';
				}
				echo '</tbody></table></div></div></div>';
			}
			else {
				echo '<div class="error"><p>No se encontro ningun registro de productos para la carga con ID  '.$_POST["bulk_id"].'. <br /></div>';
			}

		}

		if((isset($_POST["vercargas"])) || (isset($_POST["detalle"]) && isset($_POST["bulk_id"])) || (isset($_POST["eliminar_detalle"]) || isset($_POST["restaurar_detalle"])) || isset($_POST["deshacer"]) ){
			$bulks = $wpdb->get_results("SELECT bl.*, (select count(bulk_load_id) from ".$wpdb->prefix."bulk_load_detail where bulk_load_id = bl.id) as registros FROM ".$wpdb->prefix."bulk_load bl WHERE bl.status = '1'");
			
			if(!empty( $bulks )) {
				foreach ($bulks as $bulk) {
					echo '<div class="error">
						
							<table style="width:100%;">
								<thead>
									<th width="5%">ID</th>
									<th width="10%">Fecha</th>
									<th width="40%">Archivo</th>
									<th width="10%">Registros</th>
									<th width="35%">Acciones</th>
								</thead>
								<tbody>
									<th>'.$bulk->id.'</th>
									<th>'.$bulk->date.'</th>
									<th>'.$bulk->file.'</th>
									<th>'.$bulk->registros.'</th>
									<th>';
									if(isset($_POST["deshacer"]) && isset($_POST["bulk_id"]) && $_POST["bulk_id"] == $bulk->id) {
										echo '<h4>¿Estas seguro que deseas eliminar esta carga?</h4>';
										echo '<form action="admin.php?page=ait-import" method="post">
											<input class="btn button button-primary" style="background-color:#ffb900;" id="btnSendToSystem" type="submit" value="CONFIRMAR">
											<input type="hidden" name="deshacer" >
											<input type="hidden" name="deshacer_confirmar" >
											<input type="hidden" name="bulk_id" value="'.$bulk->id.'" >
										</form>
										<form action="admin.php?page=ait-import" method="post">
											<input class="btn button button-primary" id="btnSendToSystem" type="submit" value="CANCELAR">
										</form>';
									}
									else {
										if(isset($_POST["enviar"]) && isset($_POST["bulk_id"])) {
											echo '<h4>¿Estas seguro que deseas enviar los registros de esta al SIL?</h4>';
											echo '<form action="admin.php?page=ait-import" method="post">
												<input class="btn button button-primary" style="background-color:#ffb900;" id="btnSendToSystem" type="submit" value="CONFIRMAR">
												<input type="hidden" name="enviar" >
												<input type="hidden" name="enviar_confirmar" >
												<input type="hidden" name="bulk_id" value="'.$bulk->id.'" >
											</form>
											<form action="admin.php?page=ait-import" method="post">
												<input class="btn button button-primary" id="btnSendToSystem" type="submit" value="CANCELAR">
											</form>';
										}
										else {
											echo '
											<form action="admin.php?page=ait-import" method="post">
												<input class="btn button button-primary" id="btnSendToSystem" type="submit" value="DETALLE DE LA CARGA">
												<input type="hidden" name="detalle" >
												<input type="hidden" name="bulk_id" value="'.$bulk->id.'" >
											</form>
											<form action="'.AIT_IMPORT_PLUGIN_URL . 'load-report.php" method="post">
												<input class="btn button button-primary" id="btnSendToSystem" type="submit" value="REPORTE DE LA CARGA">
												<input type="hidden" name="reporte_carga" >
												<input type="hidden" name="bulk_id" value="'.$bulk->id.'" >
											</form>
											<form action="admin.php?page=ait-import" method="post">
												<input class="btn button button-primary" id="btnSendToSystem" type="submit" value="DESHACER CARGA">
												<input type="hidden" name="deshacer" >
												<input type="hidden" name="bulk_id" value="'.$bulk->id.'" >
											</form>';
										}
									}
									echo '</th>
								</tbody>
							</table>
							</p>
							
						</form>
					</div>';
				}
				
				
			}
		}
	?>
	<?php
	foreach ($import->post_types as $type) { ?>
	<div class="import-custom-type metabox-holder">
		
		<div class="import-options postbox">

			<div class="handlediv" title="Click to toggle"><br></div>

			<h3 class="hndle"><span><?php echo $type->name; ?></span>
				<form action="admin.php?page=ait-import" method="post">
					<input type="hidden" name="vercargas" >
					<input class="btn button button-primary" id="btnVerCargas" type="submit" value="VER CARGAS ANTERIORES">
				</form>
			</h3>

			<div class="inside">
				<form action="<?php echo AIT_IMPORT_PLUGIN_URL . 'load-report-undone.php'; ?>" method="post">
					<input class="btn button button-primary" id="btnSendToSystem" type="submit" value="REPORTE DE CARGAS DESHECHAS">
				</form>
				<form action="<?php echo AIT_IMPORT_PLUGIN_URL . 'download.php'; ?>" method="post">
					
					<h4><?php _e('Importar productos masivamente desde archivos CSV'); ?></h4>
					
				
				</form>

				<form action="admin.php?page=ait-import" method="post" enctype="multipart/form-data">
					
					<h4><?php _e('Seleccione el archivo...'); ?></h4>
					<div style="display:none;" >
						Delimiter: <select name="delim" id="delim">
	                                        <option value=",">,</option>
	                                        <option value=";">;</option>
	                     </select><br>

						<input type="hidden" name="type" value="<?php echo $type->id; ?>">

						<input type="radio" name="duplicate" value="1" checked="checked"> <?php _e("Rename item's name (slug) if item with name (slug) already exists"); ?> <br>
						<input type="radio" name="duplicate" value="2"> <?php _e("Update old item's data if item with name (slug) already exists"); ?> <br>
						<input type="radio" name="duplicate" value="3"> <?php _e("Ignore item if item with name (slug) already exists"); ?> <br>

					</div>
					<h4>Status de los productos <br>
					<label>Publish</label><input type="radio" name="statusProductos" value="publish" checked="checked">
					<label>Draft</label><input type="radio" name="statusProductos" value="draft"></h4>
					<input type="number" name="porcentaje" min="0" max="100" value="0">% <label><strong>Porcentaje de incremento al precio de los artículos</strong></label><br>
					<input type="file" name="posts_csv">
					<input type="submit" value="<?php _e('Import from CSV'); ?>" class="upload button-primary">
				</form>
				

				

			</div>

		</div>

	</div>
	<?php } ?>
	
</div>