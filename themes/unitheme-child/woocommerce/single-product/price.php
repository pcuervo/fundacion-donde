<?php
/**
 * Single Product Price, including microdata for SEO
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $product;
$precio_normal = get_post_meta($post->ID, '_precio_normal', true);
?>
<div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
<?php if (!empty($precio_normal)): ?>
	<div class="[ precio-comparativo ]">
		<table class="[ width--100p ]">
			<tr>
				<th>Precio Tienda Dondé</th>
				<th>Precio Comparativo</th>
			</tr>
			<tr>
				<td>
<?php endif; ?>
					<p class="price"><?php echo $product->get_price_html(); ?></p>
					<meta itemprop="price" content="<?php echo esc_attr( $product->get_display_price() ); ?>" />
					<meta itemprop="priceCurrency" content="<?php echo esc_attr( get_woocommerce_currency() ); ?>" />
					<link itemprop="availability" href="http://schema.org/<?php echo $product->is_in_stock() ? 'InStock' : 'OutOfStock'; ?>" />
<?php
	if (!empty($precio_normal)):
	$format_precio_normal = number_format($precio_normal);
?>
				</td>
				<td class="[ relative ]"><span>$<?php echo $format_precio_normal; ?>.00</span></td>
			</tr>
		</table>
	</div>
<?php endif; ?>
</div>
