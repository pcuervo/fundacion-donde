<?php
	get_header();
	the_post();
?>
<section class="[ container ]">
	<div class="nz-section horizontal animate-false full-width-true [ pading-top-bottom ]" data-animation-speed="35000" data-parallax="false">
		<div class="nz-row">
			<div class="col col12 colm8 col-animate-true active" data-align="left" data-effect="fade-bottom">
				<div class="col-inner">
					<h2 class="[ color-primary ]"><?php the_title( ); ?></h2>
					<div class="nz-column-text nz-clearfix"><?php the_content( ); ?></div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php get_footer( ); ?>