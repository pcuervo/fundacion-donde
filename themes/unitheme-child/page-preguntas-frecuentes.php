<?php
	get_header( );
	the_post();
?>
<div class="col md-8 offsett-md2 [ margin-bottom--large margin-top ]">
	<h2 class="[ text-center ]"><?php the_title(); ?></h2>
	<?php
		$faqs_args = array(
			'post_type' => 'preguntas',
			'posts_per_page' => -1,
			'order'=> 'ASC',
		);
		$faqs_query = new WP_Query( $faqs_args );
		if ( $faqs_query->have_posts() ) :
		while ( $faqs_query->have_posts() ) : $faqs_query->the_post(); ?>

			<div class="accordion">
				<h4><?php the_title( ); ?></h4>
			</div>
			<div class="panel">
				<p><?php the_content( ); ?></p>
			</div>

		<?php endwhile;
			wp_reset_postdata();
		else : ?>
			<p><?php echo _e( 'Lo sentimos, por el momento no hay preguntas.' ); ?></p>
		<?php endif; ?>

</div>

<script>
	var acc = document.getElementsByClassName("accordion");
	var i;

	for (i = 0; i < acc.length; i++) {
	    acc[i].onclick = function(){
	        this.classList.toggle("active");
	        var panel = this.nextElementSibling;
	        if (panel.style.display === "block") {
	            panel.style.display = "none";
	        } else {
	            panel.style.display = "block";
	        }
	    }
	}
</script>
<?php get_footer( ); ?>