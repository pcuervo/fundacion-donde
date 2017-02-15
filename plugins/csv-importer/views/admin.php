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
		if ($_FILES["posts_csv"]["error"] > 0) {
			echo '<div class="error"><p>'.__('Incorrect CSV file').'.</p></div>';
		} else {
			$import->import_csv($_POST["type"],$_FILES["posts_csv"]['tmp_name'],$_POST["duplicate"], $_POST["statusProductos"]);
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
	foreach ($import->post_types as $type) { ?>
	<div class="import-custom-type metabox-holder">
		
		<div class="import-options postbox">

			<div class="handlediv" title="Click to toggle"><br></div>

			<h3 class="hndle"><span><?php echo $type->name; ?></span></h3>

			<div class="inside">
			
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
					<input type="checkbox" name="limpiarnuevos"> <label><strong>Limpiar productos "Nuevos" de la tienda</strong></label><br>
					<input type="file" name="posts_csv">
					<input type="submit" value="<?php _e('Import from CSV'); ?>" class="upload button-primary">
				</form>

				

			</div>

		</div>

	</div>
	<?php } ?>
</div>