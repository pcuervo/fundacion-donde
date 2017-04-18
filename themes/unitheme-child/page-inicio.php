<?php get_header( ); ?>
<div id="slider-home">
	<?php echo do_shortcode('[rev_slider alias="home"]'); ?>
</div>
<div class="page-full-width">
	<!-- content start -->
	<div id="nz-content" class="content nz-clearfix padding-true">
		<!-- post start -->
		<div id="post-39" class="post-39 page type-page status-publish hentry">
			<section class="page-content nz-clearfix">
				<div class="nz-section horizontal animate-false full-width-true " data-animation-speed="35000" data-parallax="false">
					<div class="container">
						<h2 class="[ color-primary ]">Novedades</h2>
						<?php echo do_shortcode('[recent_products per_page="4" columns="4"]'); ?>
					</div>
				</div>
				<div class="nz-section horizontal animate-false full-width-true " data-animation-speed="35000" data-parallax="false">
					<div class="container">
						<h2 class="[ color-primary ]">Artículos destacados</h2>
						<?php //echo do_shortcode('[top_rated_products per_page="4"]'); ?>
						<?php echo do_shortcode('[featured_products per_page="4" columns="4"]'); ?>
					</div>
				</div>
				<div class="[ container ][ margin-bottom ]">
					<?php //echo do_shortcode('[nz_rposts version="masonry" cat="home" columns="3"]'); ?>

					<div id="nz-recent-posts-1" data-animate="false" data-bullets="true" data-autoplay="false" data-columns="3" class="lazy nz-recent-posts masonry grid_3 nz-clearfix in">
						<div class="posts-inner shuffle" style="position: relative; overflow: hidden; height: 412px; transition: height 300ms ease-out;">
						<?php
						$banners_args = array(
							'post_type' => 'post',
							'posts_per_page' => 3,
						);
						$banners_query = new WP_Query( $banners_args );
						if ( $banners_query->have_posts() ) :
						while ( $banners_query->have_posts() ) : $banners_query->the_post(); ?>

							<?php
								global $post;
								$post_slug = $post->post_name;
								//echo $post_slug;
							?>

							<div class="post format- shuffle-item filtered" data-grid="ninzio_01" style="position: absolute; top: 0px; left: 0px; visibility: visible; transition: transform 300ms ease-out, opacity 300ms ease-out;">
								<div class="post-wrap nz-clearfix">
									<a class="nz-more" href="<?php echo site_url('categoria-producto/') . $post_slug ; ?>">
										<div class="nz-thumbnail">
											<img width="100%" src="<?php the_post_thumbnail_url('full'); ?>" alt="<?php echo $post_slug; ?>">
											<div class="ninzio-overlay"></div>
										</div>
									</a>
								</div>
							</div>

						<?php endwhile;
							wp_reset_postdata();
						else : ?>
							<p><?php echo _e( 'Lo sentimos, por el momento no hay preguntas.' ); ?></p>
						<?php endif; ?>
						</div>
					</div>
				</div>

				<div class="nz-section horizontal animate-false full-width-false [ bg-testimonials ]" data-img-width="1920" data-img-height="666" data-animation-speed="35000" data-parallax="false">
					<div class="nz-row">
						<div class="col col12  col-animate-false active" data-align="left" data-effect="fade-left">
							<div class="col-inner">
							<?php echo do_shortcode('
								[nz_testimonials color="#ffffff"][nz_testimonial img="133" name="Autor Apellido" title="Estudiante"]
									Los mejores productos al mejor precio y en perfectas condiciones, no lo podría recomendar más. ¡Excelente experiencia!
								[/nz_testimonial][nz_testimonial img="135" name="Autor Apellido" title="Estudiante"]
									Los mejores productos al mejor precio y en perfectas condiciones, no lo podría recomendar más. ¡Excelente experiencia!
								[/nz_testimonial][nz_testimonial img="136" name="Autor Apellido" title="Estudiante"]
									Los mejores productos al mejor precio y en perfectas condiciones, no lo podría recomendar más. ¡Excelente experiencia!
								[/nz_testimonial][nz_testimonial img="134" name="Autor Lorem" title="Profesional"]
									Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliquaodo consequat.
								[/nz_testimonial][/nz_testimonials]
							'); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="nz-section horizontal animate-false full-width-true " data-animation-speed="35000" data-parallax="false" style="padding-top:50px;padding-bottom:10px;">
					<div class="container">
						<div class="nz-row">
							<div class="col col4  col-animate-false active" data-align="left" data-effect="fade-left">
								<div class="col-inner">
									<div class="nz-content-box nz-clearfix v1 fade" data-columns="1">
										<div id="nz-box-1" class="border-active  nz-box active">
											<a href="<?php echo home_url('/calidad-garantizada'); ?>">
												<div class="box-icon-wrap">
													<div class="box-icon icon-badge"></div>
												</div>
												<div class="box-data">
													<h3 class="text-center color-primary">Calidad garantizada</h3>
													<p class="p1 text-center">Todos nuestros artículos están revisados y<br> avalados con los más altos estándares de<br> calidad</p>
												</div>
											</a>
										</div>
									</div>
								</div>
							</div>
							<div class="col col4  col-animate-false active" data-align="left" data-effect="fade-left">
								<div class="col-inner">
									<div class="nz-content-box nz-clearfix v1 fade" data-columns="1">
										<a href="<?php echo home_url('/envio-express'); ?>">
											<div id="nz-box-2" class="border-active  nz-box active">
												<div class="box-icon-wrap">
													<div class="box-icon icon-envio"></div>
												</div>
												<div class="box-data">
													<h3 class="text-center color-primary">Envío express</h3>
													<p class="p1 text-center">Entrega en cualquier punto de la república<br> en máximo 5 días hábiles</p>
												</div>
											</div>
										</a>
									</div>
								</div>
							</div>
							<div class="col col4  col-animate-false active" data-align="left" data-effect="fade-left">
								<div class="col-inner">
									<div class="nz-content-box nz-clearfix v1 fade" data-columns="1">
										<a href="<?php echo home_url('/envio-gratis'); ?>">
											<div id="nz-box-3" class="border-active  nz-box active">
												<div class="box-icon-wrap">
													<div class="box-icon icon-envio-gratis"></div>
												</div>
												<div class="box-data">
													<h3 class="text-center color-primary">Envío gratis</h3>
													<p class="p1 text-center">Para artículos con precio mayor a<br> $1,000 dentro de la República Mexicana</p>
												</div>
											</div>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="nz-section horizontal animate-false full-width-false " data-animation-speed="35000" data-parallax="false">
					<div class="nz-row">
						<div class="col col12  col-animate-false active" data-align="left" data-effect="fade-left" data-margin="false">
							<div class="col-inner">
								<div id="nz-tagline-1">
									<a href="<?php echo site_url('/categoria-producto/relojes'); ?>" class="nz-tagline nz-clearfix">
										<div class="container">
											<div class="tagline-title">Checa todos los relojes que tenemos para ti</div>
										</div>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
		<!-- post end -->
	</div>
	<!-- content end -->
</div>
<?php get_footer( ); ?>