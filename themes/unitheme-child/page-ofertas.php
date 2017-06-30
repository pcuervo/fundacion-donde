<?php get_header( ); ?>
<section class="[ container ]">
	<h2 class="[ color-primary ]">Productos en oferta</h2>
	<?php echo do_shortcode('[sale_products per_page="100" columns="3"]'); ?>
</section>
<?php get_footer( ); ?>