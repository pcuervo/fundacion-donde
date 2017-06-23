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

function get_image_id($image_url) {
    global $wpdb;
    $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ));
        return $attachment[0];
}

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

	if(isset($_FILES["posts_csv_precios"]) && isset($_POST["type"])) {
		$ext = explode('.', $_FILES["posts_csv_precios"]['name']);
		if ($_FILES["posts_csv_precios"]["error"] > 0 || $ext[count($ext)-1] != 'csv') {
			echo '<div class="error"><p>'.__('Incorrect CSV file').'. Por favor intente de nuevo con un archivo correcto.</p></div>';
		} else {
			$import->import_csv_precios($_POST["type"],$_FILES["posts_csv_precios"]['tmp_name']);
		}
		
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
			$bulks = $wpdb->get_results("SELECT bl.*, (select count(bulk_load_id) from ".$wpdb->prefix."bulk_load_detail where bulk_load_id = bl.id) as registros FROM ".$wpdb->prefix."bulk_load bl WHERE bl.status = '1' order by bl.id desc");
			
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

		$no_img = $wpdb->get_results("select distinct(p.post_id) from fd_postmeta p where (p.meta_key = '_thumbnail_id' and (p.meta_value = '' OR p.meta_value is NULL)) and (select count(post_id) from fd_postmeta where meta_key = 'FOTO6' and post_id = p.post_id) = 1");
		//$no_img = $wpdb->get_results("select distinct(pm.post_id), (select post_parent from fd_posts where id = pm.post_id) as parent, from fd_postmeta pm where (pm.meta_key = '_thumbnail_id' and (pm.meta_value = '' OR pm.meta_value is NULL)) OR (pm.meta_key = '_product_image_gallery' AND replace(replace(pm.meta_value, ',', ''), ' ', '') = '' ) ");

		if(isset($_POST["buscarasignar"])) {
			foreach ($no_img as $row) {
				$img_galery = '';
				//var_dump($row);
				$meta = get_post_meta( $row->post_id );
				//var_dump($meta);
				if(isset($meta['_ruta']) && $meta['_ruta'] != '') {
					$image_id = get_image_id($meta['_ruta']);
					update_post_meta( $row->post_id, '_thumbnail_id', $image_id );
				}
				
				if(isset($meta['htheme_meta_product_image_featured']) && $meta['htheme_meta_product_image_featured'] != '') {
					//$image_id = get_image_id($meta['htheme_meta_product_image_featured']);
					//update_post_meta( $row->post_id, '_thumbnail_id', $image_id );
				}

				if(isset($meta['FOTO1']) && $meta['FOTO1'] != '') {
					$image_id = get_image_id($meta['FOTO1']);
					$img_galery .= 	$image_id .', ';
				}

				if(isset($meta['FOTO2']) && $meta['FOTO2'] != '') {
					$image_id = get_image_id($meta['FOTO2']);
					$img_galery .= 	$image_id .', ';
				}

				if(isset($meta['FOTO3']) && $meta['FOTO3'] != '') {
					$image_id = get_image_id($meta['FOTO3']);
					$img_galery .= 	$image_id .', ';
				}

				if(isset($meta['FOTO4']) && $meta['FOTO4'] != '') {
					$image_id = get_image_id($meta['FOTO4']);
					$img_galery .= 	$image_id .', ';
				}

				if(isset($meta['FOTO5']) && $meta['FOTO5'] != '') {
					$image_id = get_image_id($meta['FOTO5']);
					$img_galery .= 	$image_id .', ';
				}

				if(isset($meta['FOTO6']) && $meta['FOTO6'] != '') {
					$image_id = get_image_id($meta['FOTO6']);
					$img_galery .= 	$image_id .', ';
				}

				$img_galery = substr($img_galery, 0, -2);
				update_post_meta( $row->post_id, '_product_image_gallery', $img_galery );

					/*
					$args = array(
						'post_parent' => $row->id,
						'post_type'   => 'product_variation', 
						'numberposts' => -1,
						'post_status' => 'publish' 
					);
					$children = get_children( $args );
					foreach ($children as $child ) {
						$stock_hijo = get_post_meta( $child->ID, '_stock', true );
						if($stock_hijo > 0) {
							$enstock = true;
							break;
						}
					}
					*/
				
				
				//break;
			}
		}

		$no_img = $wpdb->get_results("select distinct(p.post_id) from fd_postmeta p where (p.meta_key = '_thumbnail_id' and (p.meta_value = '' OR p.meta_value is NULL)) and (select count(post_id) from fd_postmeta where meta_key = 'FOTO6' and post_id = p.post_id) = 1");
		
		if(!empty($no_img)) {
			echo '<div class="error"><p>Existen '.count($no_img).' productos sin imagenes asignadas </p>
					<form action="admin.php?page=ait-import" method="post">';
			echo '<input class="btn button " id="" type="submit" value="BUSCAR Y ASIGNAR IMAGENES">';
			echo '<input type="hidden" name="buscarasignar" >';
			echo '</form>';
			echo '</div>';

			echo '<div class="import-custom-type metabox-holder">';		
			echo '<div class="postbox">';
			echo '<button type="button" class="handlediv button-link" aria-expanded="true"><span class="screen-reader-text">Alternar panel: Actividad</span><span class="toggle-indicator" aria-hidden="true"></span></button>';
			echo '<h3 class="hndle"><span>DETALLE DE LAS IMAGENES FALTANTES</span></h3>';
			echo '<div class="inside">';
			echo '<table style="width:100%;">';
			echo '<thead>
						<th width="25%" style="text-align:left;">Nombre</th>
						<th width="5%" style="text-align:center;">MID</th>
						<th width="10%" style="text-align:center;">Destacada</th>
						<th width="10%" style="text-align:center;">FOTO1</th>
						<th width="10%" style="text-align:center;">FOTO2</th>
						<th width="10%" style="text-align:center;">FOTO3</th>
						<th width="10%" style="text-align:center;">FOTO4</th>
						<th width="10%" style="text-align:center;">FOTO5</th>
						<th width="10%" style="text-align:center;">FOTO6</th>
					</thead>';
			echo '<tbody>';
			foreach ($no_img as $detail) {
				$produ = get_post($detail->post_id);
				$stilo = '';
				$meta_img = get_post_meta($detail->post_id);
				//var_dump($meta_img);
				//if($detail->status == '1') { $stilo = 'background-color:#dc3232'; }
				//if($detail->status == '0') { $stilo = 'background-color:#ffb900'; }
				//$link 
				echo '<tr >';

				if(isset($produ->post_title)) { echo '<td><a href="post.php?post='.$detail->post_id.'&action=edit"><small>'.$produ->post_title.'</small></td>'; } else { echo '<td>post_id = '.$detail->post_id.'</a></td>'; }
				if(isset($meta_img['_sku'][0])) { echo '<td><small>'.$meta_img['_sku'][0] .'</small></td>'; } else { echo '<td>'.'-'.'</td>'; }

				$image_id = get_image_id($meta_img['_ruta']);
				if(isset($image_id) && is_numeric($image_id) && $image_id != 0) { $d = '<br><small style="color: green;">(OK)</small>'; }
				else { $d = '<br><small style="color: red;">(NO SUBIDA AUN)</small>'; }
				if(isset($meta_img['_ruta'][0])) { echo '<td><small>'.$meta_img['_ruta'][0].$d.'</small></td>'; } else { echo '<td>'. '-'.'</td>'; }

				$image_id = get_image_id($meta_img['FOTO1']);
				if(isset($image_id) && is_numeric($image_id) && $image_id != 0) { $d = '<br><small style="color: green;">(OK)</small>'; }
				else { $d = '<br><small style="color: red;">(NO SUBIDA AUN)</small>'; }
				if(isset($meta_img['FOTO1'][0])) { echo '<td><small>'.$meta_img['FOTO1'][0].$d.'</small></td>'; } else { echo '<td>'. '-'.'</td>'; }

				$image_id = get_image_id($meta_img['FOTO2']);
				if(isset($image_id) && is_numeric($image_id) && $image_id != 0) { $d = '<br><small style="color: green;">(OK)</small>'; }
				else { $d = '<br><small style="color: red;">(NO SUBIDA AUN)</small>'; }
				if(isset($meta_img['FOTO2'][0])) { echo '<td><small>'.$meta_img['FOTO2'][0].$d.'</small></td>'; } else { echo '<td>'. '-'.'</td>'; }

				$image_id = get_image_id($meta_img['FOTO3']);
				if(isset($image_id) && is_numeric($image_id) && $image_id != 0) { $d = '<br><small style="color: green;">(OK)</small>'; }
				else { $d = '<br><small style="color: red;">(NO SUBIDA AUN)</small>'; }
				if(isset($meta_img['FOTO3'][0])) { echo '<td><small>'.$meta_img['FOTO3'][0].$d.'</small></td>'; } else { echo '<td>'. '-'.'</td>'; }

				$image_id = get_image_id($meta_img['FOTO4']);
				if(isset($image_id) && is_numeric($image_id) && $image_id != 0) { $d = '<br><small style="color: green;">(OK)</small>'; }
				else { $d = '<br><small style="color: red;">(NO SUBIDA AUN)</small>'; }
				if(isset($meta_img['FOTO4'][0])) { echo '<td><small>'.$meta_img['FOTO4'][0].$d.'</small></td>'; } else { echo '<td>'. '-'.'</td>'; }

				$image_id = get_image_id($meta_img['FOTO5']);
				if(isset($image_id) && is_numeric($image_id) && $image_id != 0) { $d = '<br><small style="color: green;">(OK)</small>'; }
				else { $d = '<br><small style="color: red;">(NO SUBIDA AUN)</small>'; }
				if(isset($meta_img['FOTO5'][0])) { echo '<td><small>'.$meta_img['FOTO5'][0].$d.'</small></td>'; } else { echo '<td>'. '-'.'</td>'; }

				$image_id = get_image_id($meta_img['FOTO6']);
				if(isset($image_id) && is_numeric($image_id) && $image_id != 0) { $d = '<br><small style="color: green;">(OK)</small>'; }
				else { $d = '<br><small style="color: red;">(NO SUBIDA AUN)</small>'; }
				if(isset($meta_img['FOTO6'][0])) { echo '<td><small>'.$meta_img['FOTO6'][0].$d.'</small></td>'; } else { echo '<td>'. '-'.'</td>'; }
				echo '</tr>';
			}
			echo '</tbody></table></div></div></div>';
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
				<br>
				<div style="border: 1px solid; padding: 10px;">
					
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
				<!-- AJUSTE DE PRECIOS MASIVOS -->
				<div style="border: 1px solid; padding: 10px;">
					<form  action="<?php echo AIT_IMPORT_PLUGIN_URL . 'download.php'; ?>" method="post">
						
						<h3>Actualización de precios masivos</h3>
						
						<table style="display: none;">
							<tr>
								<th><?php _e('Attribute'); ?></th>
								<th><?php _e('Column name in CSV file'); ?></th>
								<th><?php _e('Notice'); ?></th>
							</tr>
							
							<!-- AGREGADOS EN HARDCODE PARA OBTENERE EL FORMATO REQUEIRDO POR EL CLIENTE -->
							<tr>
								<td><input type="checkbox" name="SKU" checked="checked"> SKU </td>
								<td>SKU</td>
								<td>Valor numerico</td>
							</tr>
							<tr>
								<td><input type="checkbox" name="PRECIO" checked="checked"> PRECIO </td>
								<td>PRECIO</td>
								<td>Valor numerico</td>
							</tr>
							

						</table>

						<input type="hidden" name="ait-import-post-type" value="<?php echo $type->id; ?>">
						<input type="hidden" name="ait-import-is-ait-type" value="yes">

						<input type="submit" value="<?php _e('Descargar CSV de ejemplo para ajustar precios'); ?>" class="download button">
					
					</form>

					<form action="admin.php?page=ait-import" method="post" enctype="multipart/form-data">
						
						<h4>Actualizar precios</h4>
						<div style="display:none;">
						Delimiter: <select name="delim" id="delim">
	                                        <option value=",">,</option>
	                                        <option value=";">;</option>
	                     </select><br>

						<input type="hidden" name="type" value="<?php echo $type->id; ?>">

						<input type="radio" name="duplicate" value="1" checked="checked"> <?php _e("Rename item's name (slug) if item with name (slug) already exists"); ?> <br>
						<input type="radio" name="duplicate" value="2"> <?php _e("Update old item's data if item with name (slug) already exists"); ?> <br>
						<input type="radio" name="duplicate" value="3"> <?php _e("Ignore item if item with name (slug) already exists"); ?> <br>
						</div>
						
						<input type="file" name="posts_csv_precios">
						<input type="submit" value="Actualizar precios" class="upload button-primary">
						
					
					</form>
				</div>
				

			</div>

		</div>

	</div>
	<?php } ?>
	
</div>