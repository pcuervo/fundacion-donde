<?php


/**
* Define paths to javascript, styles, theme and site.
**/
define( 'JSPATH', get_template_directory_uri() . '/js/' );
define( 'CSSPATH', get_template_directory_uri() . '/css/' );
define( 'THEMEPATH', get_template_directory_uri() . '/' );

/*------------------------------------*\
    #SNIPPETS
\*------------------------------------*/

require_once( 'inc/pages.php' );
require_once( 'inc/post-types.php' );


	function additionals_functions() {
		// https://developer.wordpress.org/reference/functions/locate_template/
		// locate_template( $nombres_de_plantilla, $cargar, $requerir_una_vez )
		// Con esta función se carga un archivo y se sobreescribe para el child y el parent theme.
		locate_template( array( 'includes/metaboxes.php' ), TRUE, TRUE );
		locate_template( array( 'includes/post-types.php' ), TRUE, TRUE );
	}
	add_action( 'after_setup_theme', 'additionals_functions' );

	function pago_completado( $order_id ) {
		global $wpdb;
		error_log( "%%%%%%%%%%%%%%%%%%%%%%%%%%% Order PAYMENT COMPLETED for order $order_id", 0 );
		$articulos = '';
		$order = new WC_Order( $order_id );
		$order_item = $order->get_items();
		foreach ($order_item as $item ) {
			//var_dump($item);
			if(!isset($item['product_id'])) {
				foreach ($item['item_meta_array'] as $m) {
					if($m->key == '_product_id') {
						$meta = get_post_meta($m->value);
						$sucu = $meta['sucursal'][0];
					}
					if($m->key == '_qty') {
						$qty = $m->value;
					}

				}
				$s = get_the_title($sucu);
				$articulos .= $item['name'].' - Cantidad:'.$qty.' <br>Sucursal:'.$sucu.'.- '.$s.' <br>';
			}
			else {
				$meta = get_post_meta($item['product_id']);
				$sucu = $meta['sucursal'][0];
				$s = get_the_title($sucu);
				$articulos .= $item['name'].' - Cantidad:'.$item['qty'].' <br>Sucursal: '.$sucu.'.- '.$s.' <br>';
			}
			error_log(print_r($s, true));
		}
		//error_log("===---".$sucu, 0);
		$meta_orden = get_post_meta($order_id);
		$total = $meta_orden['_order_total'][0];

		$subject = 'Fundación Donde - Orden completada #'.$order_id.'';
		$headers = array('Content-Type: text/html; charset=UTF-8');
		//$headers = 'From: Pixan <' . $ud->user_email . '>' . "\r\n";
		$message = '<html><body>';
		$message .= 'La orden '.$order_id.' fue completada exitosamente<br>';
		$message .= 'Monto: $'.$total.' <br>';
		$message .= 'Artículos: <br>';
		$message .= $articulos;
		$message .= '</body></html>';

		add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));

		//SEND EMAIL CONFIRMATION
		$emails = '';
		$sucu_id = $wpdb->get_var("SELECT post_id FROM $wpdb->postmeta WHERE meta_value = '".$sucu."' and meta_key = 'numero_sucursal'");
		//error_log("ooooooooooooo>".$sucu_id, 0);
		$sucu_metas = get_post_meta($sucu_id);

		//error_log(print_r($sucu_metas, true));
		if(isset($sucu_metas['email_responsable_principal'][0]) && $sucu_metas['email_responsable_principal'][0] != '') {
			$emails .= $sucu_metas['email_responsable_principal'][0].',';
		}
		if(isset($sucu_metas['email_responsable_secundario'][0]) && $sucu_metas['email_responsable_secundario'][0] != '') {
			$emails .= $sucu_metas['email_responsable_secundario'][0].',';
		}
		if(isset($sucu_metas['email_responsable_terciario'][0]) && $sucu_metas['email_responsable_terciario'][0] != '') {
			$emails .= $sucu_metas['email_responsable_terciario'][0].',';
		}
		$emails = substr($emails, 0, -1);
		error_log("==============>".$emails, 0);
		$resp = wp_mail( $emails, $subject, $message, $headers );
		error_log('CORREO -> '.$resp, 0);

	}
	add_action( 'woocommerce_payment_complete', 'pago_completado' );
	add_action( 'woocommerce_order_status_processing', 'pago_completado' );


// Breadcrumbs
function custom_breadcrumbs() {

    // Settings
    $separator          = '&gt;';
    $breadcrums_id      = 'breadcrumbs';
    $breadcrums_class   = 'breadcrumbs';
    $home_title         = 'Inicio';

    // If you have any custom post types with custom taxonomies, put the taxonomy name below (e.g. product_cat)
    $custom_taxonomy    = 'product_cat';

    // Get the query & post information
    global $post,$wp_query;

    // Do not display on the homepage
    if ( !is_front_page() ) {

        // Build the breadcrums
        echo '<ul id="' . $breadcrums_id . '" class="' . $breadcrums_class . '">';

        // Home page
        echo '<li class="item-home"><a class="bread-link bread-home" href="' . get_home_url() . '" title="' . $home_title . '">' . $home_title . '</a></li>';
        echo '<li class="separator separator-home"> ' . $separator . ' </li>';

        if ( is_archive() && !is_tax() && !is_category() && !is_tag() ) {

            echo '<li class="item-current item-archive"><strong class="bread-current bread-archive">' . post_type_archive_title($prefix, false) . '</strong></li>';

        } else if ( is_archive() && is_tax() && !is_category() && !is_tag() ) {

            // If post is a custom post type
            $post_type = get_post_type();

            // If it is a custom post type display name and link
            if($post_type != 'post') {

                $post_type_object = get_post_type_object($post_type);
                $post_type_archive = get_post_type_archive_link($post_type);

                echo '<li class="item-cat item-custom-post-type-' . $post_type . '"><a class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a></li>';
                echo '<li class="separator"> ' . $separator . ' </li>';

            }

            $custom_tax_name = get_queried_object()->name;

            if( is_product_category(array( 'aretes', 'cadenas', 'dijes', 'pulseras', 'anillos', 'broqueles'))) {
                echo '<li class="item-current item-archive"><a href="' . site_url('/categoria-producto/joyas') . '" class="bread-cat bread-custom-post-type-product">Joyas</a></li>';
                echo '<li class="separator"> ' . $separator . ' </li>';
            }

           //if($custom_tax_name == 'Caballero' || $custom_tax_name == 'Dama') {
           if( is_product_category(array('dama', 'caballero', 'alta-relojeria')) ) {
                echo '<li class="item-current item-archive"><a href="' . site_url('/categoria-producto/relojes') . '" class="bread-cat bread-custom-post-type-product">Relojes</a></li>';
                echo '<li class="separator"> ' . $separator . ' </li>';
            }

            if( is_product_category(array('celulares', 'tablets', 'ipads', 'videojuegos', 'camaras', 'laptops', 'electrodomesticos', 'pantallas')) ) {
                echo '<li class="item-current item-archive"><a href="' . site_url('/categoria-producto/electronicos') . '" class="bread-cat bread-custom-post-type-product">Electrónicos</a></li>';
                echo '<li class="separator"> ' . $separator . ' </li>';
            }

            echo '<li class="item-current item-archive"><strong class="bread-current bread-archive">' . $custom_tax_name . '</strong></li>';

        } else if ( is_single() ) {

            // If post is a custom post type
            $post_type = get_post_type();

            // If it is a custom post type display name and link
            if($post_type != 'post') {

                $post_type_object = get_post_type_object($post_type);
                $post_type_archive = get_post_type_archive_link($post_type);

                echo '<li class="item-cat item-custom-post-type-' . $post_type . '"><a class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a></li>';
                echo '<li class="separator"> ' . $separator . ' </li>';

            }

            // Get post category info
            $category = get_the_category();

            if(!empty($category)) {

                // Get last category post is in
                $last_category = end(array_values($category));

                // Get parent any categories and create array
                $get_cat_parents = rtrim(get_category_parents($last_category->term_id, true, ','),',');
                $cat_parents = explode(',',$get_cat_parents);

                // Loop through parent categories and store in variable $cat_display
                $cat_display = '';
                foreach($cat_parents as $parents) {
                    $cat_display .= '<li class="item-cat">'.$parents.'</li>';
                    $cat_display .= '<li class="separator"> ' . $separator . ' </li>';
                }

            }

            // If it's a custom post type within a custom taxonomy
            $taxonomy_exists = taxonomy_exists($custom_taxonomy);
            if(empty($last_category) && !empty($custom_taxonomy) && $taxonomy_exists) {

                $taxonomy_terms = get_the_terms( $post->ID, $custom_taxonomy );
                $cat_id         = $taxonomy_terms[0]->term_id;
                $cat_nicename   = $taxonomy_terms[0]->slug;
                $cat_link       = get_term_link($taxonomy_terms[0]->term_id, $custom_taxonomy);
                $cat_name       = $taxonomy_terms[0]->name;

            }

            global $product;
            if( has_term('aretes', 'product_cat') || has_term('cadenas', 'product_cat') || has_term('dijes', 'product_cat') || has_term('pulseras', 'product_cat') || has_term('broqueles', 'product_cat')) {
                echo '<li class="item-current item-archive"><a href="' . site_url('/categoria-producto/joyas') . '" class="bread-cat bread-custom-post-type-product">Joyas</a></li>';
                echo '<li class="separator"> ' . $separator . ' </li>';
            }

           if( has_term('dama', 'product_cat') || has_term('caballero', 'product_cat') ) {
                echo '<li class="item-current item-archive"><a href="' . site_url('/categoria-producto/relojes') . '" class="bread-cat bread-custom-post-type-product">Relojes</a></li>';
                echo '<li class="separator"> ' . $separator . ' </li>';
            }

            if( has_term('celulares', 'product_cat') || has_term('pantallas', 'product_cat') || has_term('tablets', 'product_cat')) {
                echo '<li class="item-current item-archive"><a href="' . site_url('/categoria-producto/electronicos') . '" class="bread-cat bread-custom-post-type-product">Electrónicos</a></li>';
                echo '<li class="separator"> ' . $separator . ' </li>';
            }

            // Check if the post is in a category
            if(!empty($last_category)) {
                echo $cat_display;
                echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>';

            // Else if post is in a custom taxonomy
            } else if(!empty($cat_id)) {

                echo '<li class="item-cat item-cat-' . $cat_id . ' item-cat-' . $cat_nicename . '"><a class="bread-cat bread-cat-' . $cat_id . ' bread-cat-' . $cat_nicename . '" href="' . $cat_link . '" title="' . $cat_name . '">' . $cat_name . '</a></li>';
                echo '<li class="separator"> ' . $separator . ' </li>';
                echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>';

            } else {

                echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>';

            }

        } else if ( is_category() ) {

            // Category page
            echo '<li class="item-current item-cat"><strong class="bread-current bread-cat">' . single_cat_title('', false) . '</strong></li>';

        } else if ( is_page() ) {

            // Standard page
            if( $post->post_parent ){

                // If child page, get parents
                $anc = get_post_ancestors( $post->ID );

                // Get parents in the right order
                $anc = array_reverse($anc);

                // Parent page loop
                if ( !isset( $parents ) ) $parents = null;
                foreach ( $anc as $ancestor ) {
                    $parents .= '<li class="item-parent item-parent-' . $ancestor . '"><a class="bread-parent bread-parent-' . $ancestor . '" href="' . get_permalink($ancestor) . '" title="' . get_the_title($ancestor) . '">' . get_the_title($ancestor) . '</a></li>';
                    $parents .= '<li class="separator separator-' . $ancestor . '"> ' . $separator . ' </li>';
                }

                // Display parent pages
                echo $parents;

                // Current page
                echo '<li class="item-current item-' . $post->ID . '"><strong title="' . get_the_title() . '"> ' . get_the_title() . '</strong></li>';

            } else {

                // Just display current page if not parents
                echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '"> ' . get_the_title() . '</strong></li>';

            }

        } else if ( is_tag() ) {

            // Tag page

            // Get tag information
            $term_id        = get_query_var('tag_id');
            $taxonomy       = 'post_tag';
            $args           = 'include=' . $term_id;
            $terms          = get_terms( $taxonomy, $args );
            $get_term_id    = $terms[0]->term_id;
            $get_term_slug  = $terms[0]->slug;
            $get_term_name  = $terms[0]->name;

            // Display the tag name
            echo '<li class="item-current item-tag-' . $get_term_id . ' item-tag-' . $get_term_slug . '"><strong class="bread-current bread-tag-' . $get_term_id . ' bread-tag-' . $get_term_slug . '">' . $get_term_name . '</strong></li>';

        } elseif ( is_day() ) {

            // Day archive

            // Year link
            echo '<li class="item-year item-year-' . get_the_time('Y') . '"><a class="bread-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</a></li>';
            echo '<li class="separator separator-' . get_the_time('Y') . '"> ' . $separator . ' </li>';

            // Month link
            echo '<li class="item-month item-month-' . get_the_time('m') . '"><a class="bread-month bread-month-' . get_the_time('m') . '" href="' . get_month_link( get_the_time('Y'), get_the_time('m') ) . '" title="' . get_the_time('M') . '">' . get_the_time('M') . ' Archives</a></li>';
            echo '<li class="separator separator-' . get_the_time('m') . '"> ' . $separator . ' </li>';

            // Day display
            echo '<li class="item-current item-' . get_the_time('j') . '"><strong class="bread-current bread-' . get_the_time('j') . '"> ' . get_the_time('jS') . ' ' . get_the_time('M') . ' Archives</strong></li>';

        } else if ( is_month() ) {

            // Month Archive

            // Year link
            echo '<li class="item-year item-year-' . get_the_time('Y') . '"><a class="bread-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</a></li>';
            echo '<li class="separator separator-' . get_the_time('Y') . '"> ' . $separator . ' </li>';

            // Month display
            echo '<li class="item-month item-month-' . get_the_time('m') . '"><strong class="bread-month bread-month-' . get_the_time('m') . '" title="' . get_the_time('M') . '">' . get_the_time('M') . ' Archives</strong></li>';

        } else if ( is_year() ) {

            // Display year archive
            echo '<li class="item-current item-current-' . get_the_time('Y') . '"><strong class="bread-current bread-current-' . get_the_time('Y') . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</strong></li>';

        } else if ( is_author() ) {

            // Auhor archive

            // Get the author information
            global $author;
            $userdata = get_userdata( $author );

            // Display author name
            echo '<li class="item-current item-current-' . $userdata->user_nicename . '"><strong class="bread-current bread-current-' . $userdata->user_nicename . '" title="' . $userdata->display_name . '">' . 'Author: ' . $userdata->display_name . '</strong></li>';

        } else if ( get_query_var('paged') ) {

            // Paginated archives
            echo '<li class="item-current item-current-' . get_query_var('paged') . '"><strong class="bread-current bread-current-' . get_query_var('paged') . '" title="Page ' . get_query_var('paged') . '">'.__('Page') . ' ' . get_query_var('paged') . '</strong></li>';

        } else if ( is_search() ) {

            // Search results page
            echo '<li class="item-current item-current-' . get_search_query() . '"><strong class="bread-current bread-current-' . get_search_query() . '" title="Search results for: ' . get_search_query() . '">Search results for: ' . get_search_query() . '</strong></li>';

        } elseif ( is_404() ) {

            // 404 page
            echo '<li>' . 'Error 404' . '</li>';
        }

        echo '</ul>';

    }

}

/**
 * Add new register fields for WooCommerce registration.
 */
function wooc_extra_register_fields() {
    ?>

    <p class="form-row form-row-first">
    <label for="reg_billing_first_name"><?php _e( 'First name', 'woocommerce' ); ?> <span class="required">*</span></label>
    <input type="text" class="input-text" name="billing_first_name" id="reg_billing_first_name" value="<?php if ( ! empty( $_POST['billing_first_name'] ) ) esc_attr_e( $_POST['billing_first_name'] ); ?>" />
    </p>

    <p class="form-row form-row-last">
    <label for="reg_billing_last_name"><?php _e( 'Last name', 'woocommerce' ); ?> <span class="required">*</span></label>
    <input type="text" class="input-text" name="billing_last_name" id="reg_billing_last_name" value="<?php if ( ! empty( $_POST['billing_last_name'] ) ) esc_attr_e( $_POST['billing_last_name'] ); ?>" />
    </p>

    <?php
}
add_action( 'woocommerce_register_form_start', 'wooc_extra_register_fields' );


/**
 * Validate the extra register fields.
 *
 * @param WP_Error $validation_errors Errors.
 * @param string   $username          Current username.
 * @param string   $email             Current email.
 *
 * @return WP_Error
 */
function wooc_validate_extra_register_fields( $errors, $username, $email ) {
    if ( isset( $_POST['billing_first_name'] ) && empty( $_POST['billing_first_name'] ) ) {
        $errors->add( 'billing_first_name_error', __( '<strong>Error</strong>: First name is required!', 'woocommerce' ) );
    }
    if ( isset( $_POST['billing_last_name'] ) && empty( $_POST['billing_last_name'] ) ) {
        $errors->add( 'billing_last_name_error', __( '<strong>Error</strong>: Last name is required!.', 'woocommerce' ) );
    }
    return $errors;
}
add_filter( 'woocommerce_registration_errors', 'wooc_validate_extra_register_fields', 10, 3 );


/**
 * Save the extra register fields.
 *
 * @param int $customer_id Current customer ID.
 */
function wooc_save_extra_register_fields( $customer_id ) {
    if ( isset( $_POST['billing_first_name'] ) ) {
        // WordPress default first name field.
        update_user_meta( $customer_id, 'first_name', sanitize_text_field( $_POST['billing_first_name'] ) );
        // WooCommerce billing first name.
        update_user_meta( $customer_id, 'billing_first_name', sanitize_text_field( $_POST['billing_first_name'] ) );
    }
    if ( isset( $_POST['billing_last_name'] ) ) {
        // WordPress default last name field.
        update_user_meta( $customer_id, 'last_name', sanitize_text_field( $_POST['billing_last_name'] ) );
        // WooCommerce billing last name.
        update_user_meta( $customer_id, 'billing_last_name', sanitize_text_field( $_POST['billing_last_name'] ) );
    }
}
add_action( 'woocommerce_created_customer', 'wooc_save_extra_register_fields' );


/* email new account for admin*/
add_filter( 'woocommerce_email_recipient_customer_new_account', 'your_email_recipient_filter_function', 10, 2);

function your_email_recipient_filter_function($recipient, $object) {
    $recipient = $recipient . ', tienda_en_linea@frd.org.mx';
    return $recipient;
}


/*Easy password in register*/
function wc_ninja_remove_password_strength() {
  if ( wp_script_is( 'wc-password-strength-meter', 'enqueued' ) ) {
    wp_dequeue_script( 'wc-password-strength-meter' );
  }
}
add_action( 'wp_print_scripts', 'wc_ninja_remove_password_strength', 100 );


/*Edit email New order*/
add_action( 'woocommerce_email_before_order_table', 'add_order_email_instructions', 10, 2 );

function add_order_email_instructions( $order, $sent_to_admin ) {

  if ( ! $sent_to_admin ) {

    if ( 'cod' == $order->payment_method ) {
      // cash on delivery method
      echo '<p><strong>Instrucciones:</strong> El pago completo se debe realizar inmediatamente después de la entrega: <em> sólo en efectivo, sin excepciones </em>.</p>';
      echo '<p>Para aclaraciones llamar al <a href="tel:+018000036633" class="line-height--50 margin-right" title="número de teléfono">01 800 003 6633</a></p>';
    } else {
      // other methods (ie credit card)
      //echo '<p><strong>Instrucciones:</strong> Por favor, busque "Madrigal Electromotive GmbH" en su próximo extracto de tarjeta de crédito.</p>';
      echo '<p>Para aclaraciones llamar al <a href="tel:+018000036633" class="line-height--50 margin-right" title="número de teléfono">01 800 003 6633</a></p>';
    }
  }
}


//Share faceboock - twitter
function atrib_imagen_destacada() {
    global $post;
    $thumbID = get_post_thumbnail_id( $post->ID );
    $imgDestacada = wp_get_attachment_image_src( $thumbID, 'large' ); // thumbnail, medium, large o full
    return $imgDestacada[0]; // 0 = ruta, 1 = altura, 2 = anchura, 3 = boolean
}

//label - placeholder checkout change
// Hook in
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

// Our hooked in function - $fields is passed via the filter!
function custom_override_checkout_fields( $fields ) {
    $fields['billing']['billing_address_2']['placeholder'] = 'Interior, habitación, unidad, etc (opcional)';
    $fields['shipping']['shipping_address_2']['placeholder'] = 'Interior, habitación, unidad, etc (opcional)';
    $fields['billing']['billing_state']['label'] = 'Estado';
    $fields['shipping']['shipping_state']['label'] = 'Estado';
    $fields['billing']['billing_phone'] = array(
        'label'        => __( 'Phone', 'woocommerce' ),
        'required'     => true,
        'type'         => 'tel',
        'class'        => array( 'form-row-last' ),
        'clear'        => true,
        'validate'     => array( 'phone' ),
        'autocomplete' => 'tel',
    );
     return $fields;
}

// Display 12 products per page.
add_filter( 'loop_shop_per_page', create_function( '$cols', 'return 12;' ), 20 );
