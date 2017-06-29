<?php
	get_header();
	the_post();
?>
<section class="[ container ]">
	<div class="nz-section horizontal animate-false full-width-true [ pading-top-bottom ]" data-animation-speed="35000" data-parallax="false">
		<div class="nz-row">
			<div class="col col12  col-animate-true active" data-align="left" data-effect="fade-bottom">
				<div class="col-inner">
					<h2 class="[ color-primary ]"><?php the_title( ); ?></h2>
					<div class="nz-column-text nz-clearfix"><?php the_content( ); ?></div>
				</div>
			</div>
		</div>
	</div>
	<div id="info-nosotros" class="nz-section horizontal animate-false full-width-false [ pading-top-bottom ] " data-animation-speed="35000" data-parallax="false">
		<div class="nz-row">
			<div class="col col6  col-animate-true active" data-align="left" data-effect="fade-left">
				<div class="col-inner">
					<div class="nz-content-box nz-clearfix v1 fade" data-columns="1">
						<div id="nz-box-1" class="border-active  nz-box active">
							<div class="box-icon-wrap">
								<div class="box-icon icon-badge"></div>
							</div>
							<div class="box-data">
								<?php $mision = get_post(1853); ?>
								<?php //$mision = get_post(759); ?>
								<h3 class="[ text-center ]"><?php echo $mision->post_title; ?></h3>
								<p><?php echo $mision->post_content; ?></p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col col6  col-animate-true active" data-align="left" data-effect="fade-right">
				<div class="col-inner">
					<div class="nz-content-box nz-clearfix v1 fade" data-columns="1">
						<div id="nz-box-2" class="border-active  nz-box active">
							<div class="box-icon-wrap">
								<div class="box-icon icon-badge"></div>
							</div>
							<div class="box-data">
								<?php //$vision = get_post(760); ?>
								<?php $vision = get_post(1854); ?>
								<h3 class="[ text-center ]"><?php echo $vision->post_title; ?></h3>
								<p><?php echo $vision->post_content; ?></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="nz-section horizontal animate-false full-width-false " data-animation-speed="35000" data-parallax="false">
		<div class="nz-row">
			<div class="col col12  col-animate-true active" data-align="left" data-effect="fade-bottom">
				<div class="col-inner">
					<h2 class="[ border-bottom--secondary ][ color-primary ]">Valores</h2>
				</div>
			</div>
		</div>
	</div>
	<div class="nz-section horizontal animate-false full-width-false [ pading-top-bottom ]" data-animation-speed="35000" data-parallax="false">
		<div class="nz-row">
		<?php
			$valores_args = array(
				'post_type' => 'valores',
				'posts_per_page' => -1,
				'order'=> 'ASC',
			);
			$valores_query = new WP_Query( $valores_args );
			if ( $valores_query->have_posts() ) :
			while ( $valores_query->have_posts() ) : $valores_query->the_post(); ?>
				<div class="col col4 [ no-margin-bottom ]">
					<div><span class="icon icon-checkmark [ color-primary ]"></span> <?php the_title(); ?></div>
				</div>
			<?php endwhile;
				wp_reset_postdata();
			else : ?>
				<p><?php echo _e( 'Lo sentimos, por el momento no hay preguntas.' ); ?></p>
			<?php endif; ?>
		</div>
	</div>
	<div class="[ clearfix ]"></div>
</section>
<?php get_footer( ); ?>