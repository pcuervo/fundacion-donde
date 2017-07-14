<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
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
	exit;
}

global $product, $woocommerce_loop;

if ( empty( $product ) || ! $product->exists() ) {
	return;
}

if ( ! $related = $product->get_related( $posts_per_page ) ) {
	return;
}
$terms = get_the_terms( $product->ID, 'product_cat' );
$cat_name = $terms[0]->name;
//echo $cat_name;

	$args = array(
		'post_type' => 'product',
		'product_cat' => $cat_name,
		'posts_per_page'   => 3,
		'post__not_in'         => array( $product->id )
		);
?>
<?php $query = new WP_Query( $args ); ?>
<?php if ( $query->have_posts() ) :?>

	<div class="related products">
		<h2>Productos relacionados</h2>
		<?php woocommerce_product_loop_start(); ?>
		<?php woocommerce_product_subcategories(); ?>
			<?php while ( $query->have_posts() ) : $query->the_post(); ?>
				<?php wc_get_template_part( 'content', 'product' ); ?>
			<?php endwhile; ?>
		<?php woocommerce_product_loop_end(); ?>
	</div>
<?php wp_reset_postdata();  else : ?>
	<p><?php _e( 'Perdón, por ahora no hay promociones.' ); ?></p>
<?php endif; ?><!-- blok subcategory product -->
