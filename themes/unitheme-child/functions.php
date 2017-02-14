<?php
	function additionals_functions() {
		// https://developer.wordpress.org/reference/functions/locate_template/
		// locate_template( $nombres_de_plantilla, $cargar, $requerir_una_vez )
		// Con esta función se carga un archivo y se sobreescribe para el child y el parent theme.
		locate_template( array( 'includes/metaboxes.php' ), TRUE, TRUE );
		locate_template( array( 'includes/post-types.php' ), TRUE, TRUE );
	}
	add_action( 'after_setup_theme', 'additionals_functions' );
?>