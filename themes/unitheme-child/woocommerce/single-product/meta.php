<?php
/**
 * Single Product Meta
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/meta.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $product;

$cat_count = sizeof( get_the_terms( $post->ID, 'product_cat' ) );
$tag_count = sizeof( get_the_terms( $post->ID, 'product_tag' ) );

?>

<h5 class="[ hidden ]">Lorem ipsum dolor sit</h5>

<div class="product_meta">

	<?php do_action( 'woocommerce_product_meta_start' ); ?>

	<?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>

		<span class="sku_wrapper"><?php _e( 'SKU:', 'woocommerce' ); ?> <span class="sku" itemprop="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : __( 'N/A', 'woocommerce' ); ?></span></span>

	<?php endif; ?>

	<?php echo $product->get_categories( ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', $cat_count, 'woocommerce' ) . ' ', '</span>' ); ?>

	<?php echo $product->get_tags( ', ', '<span class="tagged_as">' . _n( 'Tag:', 'Tags:', $tag_count, 'woocommerce' ) . ' ', '</span>' ); ?>

	<br />

	<?php

		//Joyas
		$joya_marca = get_post_meta($post->ID, '_joya_text_marca', true);
		$joya_modelo = get_post_meta($post->ID, '_joya_text_modelo', true);
		$joya_ano = get_post_meta($post->ID, '_joya_text_ano', true);
		$joya_tipo_de_piedra = get_post_meta($post->ID, '_joya_text_tipo_de_piedra', true);
		$joya_cantidad_de_piedras = get_post_meta($post->ID, '_joya_text_cantidad_de_piedras', true);
		$joya_corte_de_piedras = get_post_meta($post->ID, '_joya_text_corte_de_piedras', true);
		$joya_descripcion_de_piedras = get_post_meta($post->ID, '_joya_text_descripción_de_piedras', true);
		$joya_metal = get_post_meta($post->ID, '_joya_text_metal', true);
		$joya_medida_altura = get_post_meta($post->ID, '_joya_text_medida_altura', true);
		$joya_medida_ancho = get_post_meta($post->ID, '_joya_text_medida_ancho', true);
		$joya_peso = get_post_meta($post->ID, '_joya_text_peso', true);
		$joya_talla = get_post_meta($post->ID, '_joya_text_talla', true);
		$joya_estado = get_post_meta($post->ID, '_joya_text_estado', true);

		echo (!empty($joya_marca) ? '<span class="posted_in">Marca: '.$joya_marca.'</span>' : '');
		echo (!empty($joya_modelo) ? '<span class="posted_in">Modelo: '.$joya_modelo.'</span>' : '');
		echo (!empty($joya_ano) ? '<span class="posted_in">Año: '.$joya_ano.'</span>' : '');
		echo (!empty($joya_tipo_de_piedra) ? '<span class="posted_in">Tipo de piedra: '.$joya_tipo_de_piedra.'</span>' : '');
		echo (!empty($joya_cantidad_de_piedras) ? '<span class="posted_in">Cantidad de piedras: '.$joya_cantidad_de_piedras.'</span>' : '');
		echo (!empty($joya_corte_de_piedras) ? '<span class="posted_in">Corte de pierdras: '.$joya_corte_de_piedras.'</span>' : '');
		echo (!empty($joya_descripcion_de_piedras) ? '<span class="posted_in">Descripción de piedras: '.$joya_descripcion_de_piedras.'</span>' : '');
		echo (!empty($joya_metal) ? '<span class="posted_in">Metal: '.$joya_metal.'</span>' : '');
		echo (!empty($joya_medida_altura) ? '<span class="posted_in">Altura: '.$joya_medida_altura.'</span>' : '');
		echo (!empty($joya_medida_ancho) ? '<span class="posted_in">Ancho: '.$joya_medida_ancho.'</span>' : '');
		echo (!empty($joya_peso) ? '<span class="posted_in">Peso: '.$joya_peso.'</span>' : '');
		echo (!empty($joya_talla) ? '<span class="posted_in">Talla: '.$joya_talla.'</span>' : '');
		echo (!empty($joya_estado) ? '<span class="posted_in">Estado: '.$joya_estado.'</span>' : '');

		//Relojes
		$reloj_marca = get_post_meta($post->ID, '_reloj_text_marca', true);
		$reloj_modelo = get_post_meta($post->ID, '_reloj_text_modelo', true);
		$reloj_ano = get_post_meta($post->ID, '_reloj_text_ano', true);
		$reloj_caja = get_post_meta($post->ID, '_reloj_text_caja', true);
		$reloj_bisel = get_post_meta($post->ID, '_reloj_text_bisel', true);
		$reloj_corona = get_post_meta($post->ID, '_reloj_text_corona', true);
		$reloj_protector = get_post_meta($post->ID, '_reloj_text_protector', true);
		$reloj_pulso = get_post_meta($post->ID, '_reloj_text_pulso', true);
		$reloj_movimiento = get_post_meta($post->ID, '_reloj_text_movimiento', true);
		$reloj_caratula = get_post_meta($post->ID, '_reloj_text_caratula', true);
		$reloj_estado = get_post_meta($post->ID, '_reloj_text_estado', true);

		echo (!empty($reloj_marca) ? '<span class="posted_in">Marca: '.$reloj_marca.'</span>' : '');
		echo (!empty($reloj_modelo) ? '<span class="posted_in">Modelo: '.$reloj_modelo.'</span>' : '');
		echo (!empty($reloj_ano) ? '<span class="posted_in">Año: '.$reloj_ano.'</span>' : '');
		echo (!empty($reloj_caja) ? '<span class="posted_in">Caja: '.$reloj_caja.'</span>' : '');
		echo (!empty($reloj_bisel) ? '<span class="posted_in">Bisel: '.$reloj_bisel.'</span>' : '');
		echo (!empty($reloj_corona) ? '<span class="posted_in">Corona: '.$reloj_corona.'</span>' : '');
		echo (!empty($reloj_protector) ? '<span class="posted_in">Protector: '.$reloj_protector.'</span>' : '');
		echo (!empty($reloj_pulso) ? '<span class="posted_in">Pulso: '.$reloj_pulso.'</span>' : '');
		echo (!empty($reloj_movimiento) ? '<span class="posted_in">Movimiento: '.$reloj_movimiento.'</span>' : '');
		echo (!empty($reloj_caratula) ? '<span class="posted_in">Carátula: '.$reloj_caratula.'</span>' : '');
		echo (!empty($reloj_estado) ? '<span class="posted_in">Estado: '.$reloj_estado.'</span>' : '');

		//Electrónicos
		$electronico_marca = get_post_meta($post->ID, '_electronico_text_marca', true);
		$electronico_modelo = get_post_meta($post->ID, '_electronico_text_modelo', true);
		$electronico_ano = get_post_meta($post->ID, '_electronico_text_ano', true);
		$electronico_generacion = get_post_meta($post->ID, '_electronico_text_generacion', true);
		$electronico_tipo_de_pantalla = get_post_meta($post->ID, '_electronico_text_tipo_de_pantalla', true);
		$electronico_medidas_alto = get_post_meta($post->ID, '_electronico_text_medidas_alto', true);
		$electronico_medidas_ancho = get_post_meta($post->ID, '_electronico_text_medidas_ancho', true);
		$electronico_medidas_profundo = get_post_meta($post->ID, '_electronico_text_medidas_profundo', true);
		$electronico_pulgadas = get_post_meta($post->ID, '_electronico_text_pulgadas', true);
		$electronico_sistema_operativo = get_post_meta($post->ID, '_electronico_text_sistema_operativo', true);
		$electronico_color = get_post_meta($post->ID, '_electronico_text_color', true);
		$electronico_compania_celular = get_post_meta($post->ID, '_electronico_text_compania_celular', true);
		$electronico_camara_delantera = get_post_meta($post->ID, '_electronico_text_camara_delantera', true);
		$electronico_camara_trasera = get_post_meta($post->ID, '_electronico_text_camara_trasera', true);
		$electronico_capacidad = get_post_meta($post->ID, '_electronico_text_capacidad', true);
		$electronico_resolucion = get_post_meta($post->ID, '_electronico_text_resolucion', true);
		$electronico_peso = get_post_meta($post->ID, '_electronico_text_peso', true);
		$electronico_contenidos_de_la_caja = get_post_meta($post->ID, '_electronico_text_contenidos_de_la_caja', true);
		$electronico_lector_de_tarjetas = get_post_meta($post->ID, '_electronico_text_lector_de_tarjetas', true);
		$electronico_wi_fi = get_post_meta($post->ID, '_electronico_text_wi_fi', true);
		$electronico_bluetooth = get_post_meta($post->ID, '_electronico_text_bluetooth', true);
		$electronico_procesador = get_post_meta($post->ID, '_electronico_text_procesador', true);
		$electronico_chip = get_post_meta($post->ID, '_electronico_text_chip', true);
		$electronico_ram = get_post_meta($post->ID, '_electronico_text_ram', true);
		$electronico_numero_de_puertos_usb_20 = get_post_meta($post->ID, '_electronico_text_numero_de_puertos_usb_20', true);
		$electronico_numero_de_procesadores = get_post_meta($post->ID, '_electronico_text_numero_de_procesadores', true);
		$electronico_estado = get_post_meta($post->ID, '_electronico_text_estado', true);
		$estado = get_post_meta($post->ID, 'estado', true);

		echo (!empty($electronico_marca) ? '<span class="posted_in">Marca: '.$electronico_marca.'</span>' : '');
		echo (!empty($electronico_modelo) ? '<span class="posted_in">Modelo: '.$electronico_modelo.'</span>' : '');
		echo (!empty($electronico_ano) ? '<span class="posted_in">Año: '.$electronico_ano.'</span>' : '');
		echo (!empty($electronico_generacion) ? '<span class="posted_in">Generación: '.$electronico_generacion.'</span>' : '');
		echo (!empty($electronico_tipo_de_pantalla) ? '<span class="posted_in">Tipo de pantalla: '.$electronico_tipo_de_pantalla.'</span>' : '');
		echo (!empty($electronico_medidas_alto) ? '<span class="posted_in">Alto: '.$electronico_medidas_alto.'</span>' : '');
		echo (!empty($electronico_medidas_ancho) ? '<span class="posted_in">Ancho: '.$electronico_medidas_ancho.'</span>' : '');
		echo (!empty($electronico_medidas_profundo) ? '<span class="posted_in">Profundo: '.$electronico_medidas_profundo.'</span>' : '');
		echo (!empty($electronico_pulgadas) ? '<span class="posted_in">Pulgadas: '.$electronico_pulgadas.'</span>' : '');
		echo (!empty($electronico_sistema_operativo) ? '<span class="posted_in">Sistema operativo: '.$electronico_sistema_operativo.'</span>' : '');
		echo (!empty($electronico_color) ? '<span class="posted_in">Color: '.$electronico_color.'</span>' : '');
		echo (!empty($electronico_compania_celular) ? '<span class="posted_in">Compañía celular: '.$electronico_compania_celular.'</span>' : '');
		echo (!empty($electronico_camara_delantera) ? '<span class="posted_in">Cámara delantera: '.$electronico_camara_delantera.'</span>' : '');
		echo (!empty($electronico_camara_trasera) ? '<span class="posted_in">Cámara trasera: '.$electronico_camara_trasera.'</span>' : '');
		echo (!empty($electronico_capacidad) ? '<span class="posted_in">Capacidad: '.$electronico_capacidad.'</span>' : '');
		echo (!empty($electronico_resolucion) ? '<span class="posted_in">Resolución: '.$electronico_resolucion.'</span>' : '');
		echo (!empty($electronico_peso) ? '<span class="posted_in">Peso: '.$electronico_peso.'</span>' : '');
		echo (!empty($electronico_contenidos_de_la_caja) ? '<span class="Contenidos de la caja">Estado: '.$electronico_contenidos_de_la_caja.'</span>' : '');
		echo (!empty($electronico_lector_de_tarjetas) ? '<span class="posted_in">Lecto de tarjeta SIM: '.$electronico_lector_de_tarjetas.'</span>' : '');
		echo (!empty($electronico_wi_fi) ? '<span class="posted_in">Wi-fi: '.$electronico_wi_fi.'</span>' : '');
		echo (!empty($electronico_bluetooth) ? '<span class="posted_in">Bluetooth: '.$electronico_bluetooth.'</span>' : '');
		echo (!empty($electronico_numero_de_procesadores) ? '<span class="posted_in">Número de procesadores: '.$electronico_numero_de_procesadores.'</span>' : '');
		echo (!empty($electronico_procesador) ? '<span class="posted_in">Procesador: '.$electronico_procesador.'</span>' : '');
		echo (!empty($electronico_chip) ? '<span class="posted_in">Chip: '.$electronico_chip.'</span>' : '');
		echo (!empty($electronico_ram) ? '<span class="posted_in">RAM: '.$electronico_ram.'</span>' : '');
		echo (!empty($electronico_numero_de_puertos_usb_20) ? '<span class="posted_in">Puertos USB 2.0: '.$electronico_numero_de_puertos_usb_20.'</span>' : '');

		echo (!empty($electronico_estado) ? '<span class="posted_in">Comentarios: '.$electronico_estado.'</span>' : '');
		echo "<br>";
		echo "<div class='[ row ] estado-articulo'>";
			echo "<div class='[ col s6 ]'>";
				echo (!empty($estado) ? '<span class="posted_in">Estado del artículo: '.$estado.'</span>' : '');
			echo "</div>";
			echo "<div class='[ col s6 ][ text-right ][ tooltip ]'><span class='btn'>¿Qué significa esto?</span>
						<span class='tooltiptext'>";
							if( $estado == "Nuevo" ) {
								echo "Producto nuevo (sin uso)";
							} elseif( $estado == "Excelente" ) {
								echo "El artículo se encuentra en muy buenas condiciones físicas y presenta ligeros signos de uso. Sin golpes ni rayones en la pantalla o display, sin golpes, abolladuras ni rayones profundos en carcasa o cuerpo. ";
							} elseif( $estado == "Bueno" ) {
								echo "El artículo tiene detalles de uso evidentes a simple vista. Sin golpes ni rayones en la pantalla o display, se encuentran en buenas condiciones con signos de uso en carcasa y funciona correctamente. ";
							}
						echo "</span>";
			echo "</div>";
		echo "</div>";

	?>

	<?php do_action( 'woocommerce_product_meta_end' ); ?>

</div>

