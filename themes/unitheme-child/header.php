<?php

	global $nz_ninzio, $post;

	$blank_class = "";

	/*	MIX
	/*----------------------------------------------------------------*/

		$nz_layout         = ($nz_ninzio['layout'] ) ? $nz_ninzio['layout'] : "wide";
		$nz_button_style   = ($nz_ninzio['button-style']) ? $nz_ninzio['button-style'] : "normal";
		$nz_button_shape   = ($nz_ninzio['button-shape']) ? $nz_ninzio['button-shape'] : "square";
		$nz_color          = ($nz_ninzio['main-color']) ? $nz_ninzio['main-color'] : "#08ade4";

	/*	DESK
	/*----------------------------------------------------------------*/

		$nz_desk_sidebar        = ($nz_ninzio['sidebar'] && $nz_ninzio['sidebar'] == 1) ? "true" : "false";
		$nz_desk_height         = ($nz_ninzio['desk-height']) ? $nz_ninzio['desk-height'] : "90";
		$nz_desk_top            = ($nz_ninzio['desk-top'] && $nz_ninzio['desk-top'] == 1) ? "true" : "false";
		$nz_desk_sl             = ($nz_ninzio['desk-sl'] && $nz_ninzio['desk-sl'] == 1) ? "true" : "false";
		$nz_desk_ind            = ($nz_ninzio['desk-ind'] && $nz_ninzio['desk-ind'] == 1) ? "true" : "false";
		$nz_desk_ls             = ($nz_ninzio['desk-ls'] && $nz_ninzio['desk-ls'] == 1) ? "true" : "false";
		$nz_desk_fixed          = ($nz_ninzio['fixed'] && $nz_ninzio['fixed'] == 1) ? "true" : "false";
		$nz_desk_menu_effect    = ($nz_ninzio['desk-menu-effect']) ? $nz_ninzio['desk-menu-effect'] : "none";
		$nz_desk_submenu_effect = ($nz_ninzio['desk-submenu-effect']) ? $nz_ninzio['desk-submenu-effect'] : "fade";
		$nz_fixed_height        = ($nz_ninzio['fixed-height']) ? $nz_ninzio['fixed-height'] : "90";
		$nz_stuck               = ($nz_ninzio['stuck'] && $nz_ninzio['stuck'] == 1) ? "true" : "false";
		$nz_stuck_top           = ($nz_ninzio['stuck-top'] && $nz_ninzio['stuck-top'] == 1) ? "true" : "false";
		$nz_stuck_height        = ($nz_ninzio['stuck-height']) ? $nz_ninzio['stuck-height'] : "90";

		if (is_page()) {
			$values      = get_post_custom( get_the_ID() );
			$nz_stuck    = (isset( $values['header_stuck'][0]) && !empty($values['header_stuck'][0])) ? $values["header_stuck"][0] : $nz_stuck;
			$blank       = (isset( $values['blank'][0]) && !empty($values['blank'][0])) ? $values["blank"][0] : 'false';
			$blank_class = "blank-".$blank;
		}

		if (is_home() || is_author() || is_archive() || is_day() || is_tag() || is_category() || is_month() || is_day() || is_year()) {
			$nz_stuck  = ($nz_ninzio['blog-hs'] && $nz_ninzio['blog-hs'] == 1) ? "true" : "false";
		}

		if (is_archive() && 'portfolio' == get_post_type( $post )) {
			$nz_stuck  = ($nz_ninzio['port-hs'] && $nz_ninzio['port-hs'] == 1) ? "true" : "false";
		}

		if (function_exists('is_woocommerce')) {
			if (is_woocommerce()) {
				$nz_stuck  = ($nz_ninzio['shop-hs'] && $nz_ninzio['shop-hs'] == 1) ? "true" : "false";
			}
		}

		if (is_single()) {
			$values    = get_post_custom( get_the_ID() );
			$nz_stuck  = (isset( $values['header_stuck'][0]) && !empty($values['header_stuck'][0])) ? $values["header_stuck"][0] : $nz_stuck;
		}

		if (is_404() || is_search()) {
			$nz_stuck = "false";
		}

	$nz_desk_logo = (isset($nz_ninzio['desk-logo']['url']) && !empty($nz_ninzio['desk-logo']['url'])) ? esc_url($nz_ninzio['desk-logo']['url']) : "";
	if (isset($nz_ninzio['desk-logo-retina']['url']) && !empty($nz_ninzio['desk-logo-retina']['url'])) {$nz_desk_logo = esc_url($nz_ninzio['desk-logo-retina']['url']);}

	$nz_desk_logo_w = (isset($nz_ninzio['desk-logo']['url']) && !empty($nz_ninzio['desk-logo']['url'])) ? $nz_ninzio['desk-logo']['width']: "";
	$nz_desk_logo_h = (isset($nz_ninzio['desk-logo']['url']) && !empty($nz_ninzio['desk-logo']['url'])) ? $nz_ninzio['desk-logo']['height'] : "";

	$nz_fixed_logo = (isset($nz_ninzio['fixed-logo']['url']) && !empty($nz_ninzio['fixed-logo']['url'])) ? esc_url($nz_ninzio['fixed-logo']['url']) : "";
	if (isset($nz_ninzio['fixed-logo-retina']['url']) && !empty($nz_ninzio['fixed-logo-retina']['url'])) {$nz_fixed_logo = esc_url($nz_ninzio['fixed-logo-retina']['url']);}

	$nz_fixed_logo_w = (isset($nz_ninzio['fixed-logo']['url']) && !empty($nz_ninzio['fixed-logo']['url'])) ? $nz_ninzio['fixed-logo']['width'] : $nz_desk_logo_w;
	$nz_fixed_logo_h = (isset($nz_ninzio['fixed-logo']['url']) && !empty($nz_ninzio['fixed-logo']['url'])) ? $nz_ninzio['fixed-logo']['height'] : $nz_desk_logo_h;

	$nz_stuck_logo = (isset($nz_ninzio['stuck-logo']['url']) && !empty($nz_ninzio['stuck-logo']['url'])) ? esc_url($nz_ninzio['stuck-logo']['url']) : "";
	if (isset($nz_ninzio['stuck-logo-retina']['url']) && !empty($nz_ninzio['stuck-logo-retina']['url'])) {$nz_stuck_logo = esc_url($nz_ninzio['stuck-logo-retina']['url']);}

	$nz_stuck_logo_w = (isset($nz_ninzio['stuck-logo']['url']) && !empty($nz_ninzio['stuck-logo']['url'])) ? $nz_ninzio['stuck-logo']['width'] : $nz_desk_logo_w;
	$nz_stuck_logo_h = (isset($nz_ninzio['stuck-logo']['url']) && !empty($nz_ninzio['stuck-logo']['url'])) ? $nz_ninzio['stuck-logo']['height'] : $nz_desk_logo_h;

	$nz_mob_logo = (isset($nz_ninzio['mob-logo']['url']) && !empty($nz_ninzio['mob-logo']['url'])) ? esc_url($nz_ninzio['mob-logo']['url']) : $nz_desk_logo;
	if (isset($nz_ninzio['mob-logo-retina']['url']) && !empty($nz_ninzio['mob-logo-retina']['url'])) {$nz_mob_logo = esc_url($nz_ninzio['mob-logo-retina']['url']);}

	$nz_mob_logo_w = (isset($nz_ninzio['mob-logo']['url']) && !empty($nz_ninzio['mob-logo']['url'])) ? $nz_ninzio['mob-logo']['width'] : $nz_desk_logo_w;
	$nz_mob_logo_h = (isset($nz_ninzio['mob-logo']['url']) && !empty($nz_ninzio['mob-logo']['url'])) ? $nz_ninzio['mob-logo']['height'] : $nz_desk_logo_h;

	/*	MOB
	/*----------------------------------------------------------------*/

		$nz_mob_height = ($nz_ninzio['mob-height']) ? $nz_ninzio['mob-height'] : "90";
		$nz_mob_int    = ($nz_ninzio['mob-int'] && $nz_ninzio['mob-int'] == 1) ? "true" : "false";
		$nz_mob_search = ($nz_ninzio['mob-search'] && $nz_ninzio['mob-search'] == 1) ? "true" : "false";


	/*	HEADER ATTRS
	/*----------------------------------------------------------------*/

		$mob_class  = "header mob-header";
		$mob_class .= " mob-height-".$nz_mob_height;
		$mob_class .= " mob-int-".$nz_mob_int;
		$mob_class .= " mob-sidebar-".$nz_desk_sidebar;
		$mob_class .= " mob-search-".$nz_mob_search;

		$desk_class  = "header desk";
		$desk_class .= " desk-height-".$nz_desk_height;
		$desk_class .= " desk-sl-".$nz_desk_sl;
		$desk_class .= " desk-ls-".$nz_desk_ls;
		$desk_class .= " desk-di-".$nz_desk_ind;
		$desk_class .= " desk-fixed-".$nz_desk_fixed;
		$desk_class .= " fixed-height-".$nz_fixed_height;
		$desk_class .= " stuck-height-".$nz_stuck_height;
		$desk_class .= " stuck-".$nz_stuck;
		$desk_class .= " effect-".$nz_desk_menu_effect;
		$desk_class .= " sub-effect-".$nz_desk_submenu_effect;
		$desk_class .= " desk-sidebar-".$nz_desk_sidebar;
		$desk_class .= " top-".$nz_desk_top;
		$desk_class .= " stuck-top-".$nz_stuck_top;

?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html data-color="<?php echo $nz_color; ?>" class="no-js ie6 oldie btn-<?php echo $nz_button_shape; ?> btn-<?php echo $nz_button_style; ?> <?php echo $blank_class; ?>" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>    <html data-color="<?php echo $nz_color; ?>" class="no-js ie7 oldie btn-<?php echo $nz_button_shape; ?> btn-<?php echo $nz_button_style; ?> <?php echo $blank_class; ?>" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>    <html data-color="<?php echo $nz_color; ?>" class="no-js ie8 oldie btn-<?php echo $nz_button_shape; ?> btn-<?php echo $nz_button_style; ?> <?php echo $blank_class; ?>" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9]>    <html data-color="<?php echo $nz_color; ?>" class="no-js ie9 oldie btn-<?php echo $nz_button_shape; ?> btn-<?php echo $nz_button_style; ?> <?php echo $blank_class; ?>" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 9]><!--> <html data-color="<?php echo $nz_color; ?>" class="no-js btn-<?php echo $nz_button_shape; ?> btn-<?php echo $nz_button_style; ?> <?php echo $blank_class; ?>" <?php language_attributes(); ?>> <!--<![endif]-->
<head>

	<!-- META TAGS -->
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- SEO -->
	<meta name="keywords" content="novedades, articulos destacados, envio gratis, descuentos, artículos nuevos y usados, cálidad, compra segura, promociones y novedades">
    <meta name="description" content="<?php bloginfo('description'); ?>">

    <meta name="robots" content="index, follow" />
	<meta name="googlebot" content="index, follow" />

	<!-- Facebook, Twitter metas -->
	<meta property="og:title" content="<?php bloginfo('name'); ?>">
	<meta name="og:description" content="<?php bloginfo('description'); ?>" />
	<meta property="og:image" content="<?php echo get_stylesheet_directory_uri(); ?>/images/share-fdonde.png" alt="logo fundación dondé">
	<meta property="og:image:width" content="210" />
	<meta property="og:image:height" content="110" />
	<meta property="fb:app_id" content="1109660165770884" />
	<meta name="twitter:card" content="summary" />
	<meta name="twitter:site" content="@FundacionDonde1" />
	<meta name="twitter:title" content="<?php bloginfo('name'); ?>" />
	<meta name="twitter:description" content="<?php bloginfo('description'); ?>" />
	<meta name="twitter:image" content="<?php echo get_stylesheet_directory_uri(); ?>/images/share-fdonde.png" alt="logo fundación dondé" />
	<!-- Compatibility -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta http-equiv="cleartype" content="on">
	<!-- Sitemap Google Verify -->
	<meta name="google-site-verification" content="4Bt7KHVG0kzwetxi_LnrYR8QUCkKFdSNGA4PU2hpaDs" />

	<!-- Canonical URL -->
	<link rel="canonical" href="<?php echo site_url(); ?>" />

	<!-- Noscript -->
	<noscript>Tu navegador no soporta JavaScript!</noscript>

	<!-- Hotjar Tracking Code for https://tiendadonde.com -->
	<script>
		(function(h,o,t,j,a,r){
		h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
		h._hjSettings={hjid:469337,hjsv:5};
		a=o.getElementsByTagName('head')[0];
		r=o.createElement('script');r.async=1;
		r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
		a.appendChild(r);
		})(window,document,'//static.hotjar.com/c/hotjar-','.js?sv=');
	</script>

	<!-- google analytics -->
	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-97012949-1', 'auto');
		ga('send', 'pageview');
	</script>

	<!-- LINK TAGS -->
	<link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>" type="text/css" media="screen, print" />
	<link type="text/css" rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/css/print.css" media="print" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
    <?php if(isset($nz_ninzio['favicon']['url'])): ?>
	<link rel="shortcut icon" href="<?php echo $nz_ninzio['favicon']['url']; ?>" type="image/x-icon" />
	<?php endif; ?>
	<title><?php bloginfo('name'); ?></title>
	<?php include(locate_template("includes/dynamic-styles.php"));?>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '1109660165770884',
      xfbml      : true,
      version    : 'v2.7'
    });
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>
<!-- general wrap start -->
<div id="gen-wrap">
	<!-- wrap start -->
	<div id="wrap" class="nz-<?php echo $nz_layout; ?>">

		<div class="[ js-header ]">
			<header class="<?php echo $mob_class; ?> ">

				<div class="logo-toggle">

					<div class="container nz-clearfix">

						<?php if (!empty($nz_mob_logo)): ?>

							<div class="logo logo-mob">
								<a href="<?php echo home_url(); ?>" title="<?php bloginfo('name'); ?>">
									<img style="max-width:<?php echo $nz_mob_logo_w; ?>px;max-height:<?php echo $nz_mob_logo_h; ?>px;" src="<?php echo $nz_mob_logo; ?>" alt="<?php bloginfo('name'); ?>">
								</a>
							</div>

						<?php endif ?>

						<div class="sidebar-toggle" title="<?php echo __('Toggle sidebar',TEMPNAME); ?>">
							<span>&nbsp;</span>
							<span>&nbsp;</span>
							<span>&nbsp;</span>
						</div>

						<div class="menu-toggle" title="<?php echo __('Toggle menu',TEMPNAME); ?>">
							<span>&nbsp;</span>
							<span>&nbsp;</span>
							<span>&nbsp;</span>
						</div>

					</div>

				</div>

				<div class="header-content">

					<nav class="header-menu mob-menu nz-clearfix" itemscope>
						<?php

							$mobarg = array(
								'theme_location' => 'header-menu',
								'depth'          => 3,
								'container'      => false,
								'menu_id'        => 'mob-header-menu',
								'link_before'    => '<span class="mi"></span><span class="txt">',
								'link_after'     => '</span><span class="di icon-arrow-down8"></span>'
							);
							if(has_nav_menu("header-menu")){wp_nav_menu($mobarg);}

						?>
					</nav>

					<?php if ($nz_ninzio['desk-ls'] && $nz_ninzio['desk-ls'] == 1): ?>

						<div class="ls nz-clearfix">
							<?php do_action('icl_language_selector'); ?>
						</div>

					<?php endif ?>

					<?php if ($nz_mob_search == "true"): ?>
						<div class="search nz-clearfix">
							<?php get_search_form(); ?>
						</div>
					<?php endif ?>

				</div>

			</header>

			<header class="<?php echo $desk_class; ?>">

				<?php if ($nz_desk_top == "true"): ?>

					<div class="header-top">

						<div class="container nz-clearfix">

							<?php if ($nz_ninzio['desk-slogan']): ?>

							<?php if ( is_user_logged_in() ){ ?>
								<div class="desk-slogan log-in">
							<?php } else { ?>
								<div class="desk-slogan log-out">
							<?php } ?>
									<?php
										echo do_action('wpml_add_language_selector');
										echo do_shortcode(wp_kses_post($nz_ninzio['desk-slogan']));
									?>
								</div>
							<?php endif ?>

							<?php if ($nz_ninzio['desk-ls'] && $nz_ninzio['desk-ls'] == 1): ?>

								<div class="ls nz-clearfix">
									<?php do_action('icl_language_selector'); ?>
								</div>

							<?php endif ?>

							<?php if ($nz_ninzio['desk-sl'] && $nz_ninzio['desk-sl'] == 1): ?>

								<div class="social-links nz-clearfix">
									<?php include(locate_template('includes/social-links.php' )); ?>
								</div>

							<?php endif ?>

						</div>

					</div>

				<?php endif ?>

				<div class="header-content">
					<div class="[ fondo-header ]"> <!-- unicamente contiene ribbon -->
						<div class="[ ribbon-left ]"></div>
					</div>
					<div class="[ fondo-header ]"></div>
					<div class="container nz-clearfix">
						<div class="header-cont">

							<?php if (!empty($nz_desk_logo)): ?>

								<div class="logo logo-desk">
									<a href="<?php echo home_url(); ?>" title="<?php bloginfo('name'); ?>">
										<img style="max-width:<?php echo $nz_desk_logo_w; ?>px;max-height:<?php echo $nz_desk_logo_h; ?>px;" src="<?php echo $nz_desk_logo; ?>" alt="<?php bloginfo('name'); ?>">
									</a>
								</div>

							<?php endif ?>

							<?php if (!empty($nz_fixed_logo)): ?>

								<div class="logo logo-fixed">
									<a href="<?php echo home_url(); ?>" title="<?php bloginfo('name'); ?>">
										<img style="max-width:<?php echo $nz_fixed_logo_w; ?>px;max-height:<?php echo $nz_fixed_logo_h; ?>px;"  src="<?php echo $nz_fixed_logo; ?>" alt="<?php bloginfo('name'); ?>">
									</a>
								</div>

							<?php endif ?>

							<?php if (!empty($nz_stuck_logo)): ?>

								<div class="logo logo-stuck">
									<a href="<?php echo home_url(); ?>" title="<?php bloginfo('name'); ?>">
										<img style="max-width:<?php echo $nz_stuck_logo_w; ?>px;max-height:<?php echo $nz_stuck_logo_h; ?>px;" src="<?php echo $nz_stuck_logo; ?>" alt="<?php bloginfo('name'); ?>">
									</a>
								</div>

							<?php endif ?>

							<div class="sidebar-toggle" title="<?php echo __('Toggle sidebar',TEMPNAME); ?>">
								<span>&nbsp;</span>
								<span>&nbsp;</span>
								<span>&nbsp;</span>
							</div>

							<?php if ($nz_ninzio['desk-search'] && $nz_ninzio['desk-search'] == 1): ?>
								<div class="search-toggle icon-search2"></div>
							<?php endif ?>

							<?php if ($nz_ninzio['desk-shop-cart'] && $nz_ninzio['desk-shop-cart'] == 1): ?>

								<?php if (class_exists('Woocommerce')): ?>
									<?php global $woocommerce;?>
									<div class="cart-toggle">
										<a class="cart-contents" href="<?php echo esc_url($woocommerce->cart->get_cart_url()); ?>" title="<?php esc_html__('View your shopping cart', TEMPNAME); ?>">
							                <span class="icon-cart3"></span>
							                <span class="cart-info"><?php echo $woocommerce->cart->cart_contents_count; ?></span>
							            </a>
											<div class="cart-dropdown nz-clearfix">
											<?php
												if ( version_compare( WOOCOMMERCE_VERSION, "2.0.0" ) >= 0 ) {
													echo get_the_widget( 'WC_Widget_Cart', 'title=Cart' );
												} else {
													echo get_the_widget( 'WooCommerce_Widget_Cart', 'title=Cart' );
												}
											?>
											</div>
									</div>
								<?php endif; ?>

							<?php endif ?>

							<nav class="header-menu desk-menu nz-clearfix" itemscope>
								<?php
									$arg = array(
										'theme_location' => 'header-menu',
										'depth'          => 3,
										'container'      => false,
										'menu_id'        => 'header-menu',
										'link_before'    => '<span class="mi"></span><span class="txt">',
										'link_after'     => '</span><span class="di icon-arrow-down8"></span>'
									);
									if(has_nav_menu("header-menu")){wp_nav_menu($arg);}
								?>
							</nav>

						</div>

						<?php if ($nz_ninzio['desk-search'] && $nz_ninzio['desk-search'] == 1): ?>
							<div class="search nz-clearfix">
								<?php get_search_form(); ?>
							</div>
						<?php endif ?>

					</div>

				</div>

			</header>
		</div>

		<div class="[ main-body ]">
			<div class="page-wrap">

			<?php if ( !is_front_page() ) { ?>
				<div class="container">
					<?php custom_breadcrumbs(); ?>
				</div>
			<?php } ?>
