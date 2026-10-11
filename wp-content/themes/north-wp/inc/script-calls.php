<?php
// De-register Contact Form 7 styles.
add_filter( 'wpcf7_load_js', '__return_false' );
add_filter( 'wpcf7_load_css', '__return_false' );

// Main Styles.
function thb_main_styles() {
	global $post;
	$i                       = 0;
	$self_hosted_fonts       = ot_get_option( 'self_hosted_fonts' );
	$thb_theme_directory_uri = Thb_Theme_Admin::$thb_theme_directory_uri;
	$thb_theme_version       = Thb_Theme_Admin::$thb_theme_version;

	// Enqueue.
	wp_enqueue_style( 'thb-fa', esc_url( get_theme_file_uri( 'assets/css/font-awesome.min.css' ) ), null, '4.7.0' );
	if ( is_page_template( 'template-snap.php' ) ) {
		wp_enqueue_style( 'thb-fullpage', esc_url( get_theme_file_uri( 'assets/css/plugins/plugin-fullPage.css' ) ), null, esc_attr( $thb_theme_version ) );
	}
	wp_enqueue_style( 'thb-app', esc_url( get_theme_file_uri( 'assets/css/app.css' ) ), null, esc_attr( $thb_theme_version ) );

	wp_dequeue_style( 'vc_lte_ie9' );
	wp_deregister_style( 'vc_lte_ie9' );

	if ( ! defined( 'THB_DEMO_SITE' ) ) {
		wp_enqueue_style( 'thb-style', get_stylesheet_uri(), null, esc_attr( $thb_theme_version ) );
	}
	wp_enqueue_style( 'thb-google-fonts', thb_google_webfont(), null, esc_attr( $thb_theme_version ) );
	wp_add_inline_style( 'thb-app', thb_selection() );

	if ( $self_hosted_fonts ) {
		foreach ( $self_hosted_fonts as $font ) {
			$i++;
			wp_enqueue_style( 'thb-self-hosted-' . $i, $font['font_url'], null, esc_attr( $thb_theme_version ) );
		}
	}

	if ( $post ) {
		if ( has_shortcode( $post->post_content, 'contact-form-7' ) && function_exists( 'wpcf7_enqueue_styles' ) ) {
			wpcf7_enqueue_styles();
		}
	}
	// Remove Dynamic front-end CSS.
	remove_action( 'wp_enqueue_scripts', 'ot_load_dynamic_css', 999 );
}

add_action( 'wp_enqueue_scripts', 'thb_main_styles' );

// Main Scripts.
function thb_register_js() {
	if ( ! is_admin() ) {
		global $post;
		$thb_combined_libraries  = ot_get_option( 'thb_combined_libraries', 'on' );
		$thb_api_key             = ot_get_option( 'map_api_key' );
		$thb_dependency          = array( 'jquery', 'underscore' );
		$thb_theme_directory_uri = Thb_Theme_Admin::$thb_theme_directory_uri;
		$thb_theme_version       = Thb_Theme_Admin::$thb_theme_version;

		// Register.
		if ( 'on' === $thb_combined_libraries ) {
			wp_enqueue_script( 'thb-vendor', esc_url( get_theme_file_uri( 'assets/js/vendor.min.js' ) ), array( 'jquery' ), esc_attr( $thb_theme_version ), true );
			$thb_dependency[] = 'thb-vendor';
		} else {
			$thb_js_libraries = array(
				'GSAP'                 => '_0gsap.min.js',
				'GSAP-DrawSVGPlugin'   => '_2DrawSVGPlugin.min.js',
				'GSAP-ScrollToPlugin'  => '_1ScrollToPlugin.min.js',
				'GSAP-SplitText'       => '_3SplitText.min.js',
				'bezier-easing'        => 'bezier-easing.js',
				'imagesloaded'         => 'imagesloaded.pkgd.min.js',
				'isInViewport'         => 'isInViewport.min.js',
				'jquery-autocomplete'  => 'jquery.autocomplete.js',
				'jquery-hoverintent'   => 'jquery.hoverIntent.js',
				'jquery-hotspot'       => 'jquery.hotspot.js',
				'isotope'              => 'jquery.isotope.min.js',
				'isotope-packery-mode' => 'packery-mode.pkgd.min.js',
				'magnific-popup'       => 'jquery.magnific-popup.min.js',
				'vide'                 => 'jquery.vide.js',
				'js-cookie'            => 'js.cookie.js',
				'mobile-detect'        => 'mobile-detect.min.js',
				'perfect-scrollbar'    => 'perfect-scrollbar.jquery.min.js',
				'slick'                => 'slick.min.js',
			);
			foreach ( $thb_js_libraries as $handle => $value ) {
				wp_enqueue_script( $handle, esc_url( get_theme_file_uri( 'assets/js/vendor/' . esc_attr( $value ) ) ), array( 'jquery' ), esc_attr( $thb_theme_version ), true );
			}
		}
		if ( is_page_template( 'template-snap.php' ) ) {
			wp_enqueue_script( 'jquery-fullpage', esc_url( get_theme_file_uri( 'assets/js/vendor/jquery.fullPage.min.js' ) ), array( 'jquery' ), esc_attr( $thb_theme_version ), true );
		}
		if ( 'on' === ot_get_option( 'shop_product_quickview', 'on' ) ) {
			wp_enqueue_script( 'wc-add-to-cart-variation' );
		}

		wp_enqueue_script( 'thb-app', esc_url( get_theme_file_uri( 'assets/js/app.min.js' ) ), $thb_dependency, esc_attr( $thb_theme_version ), true );

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// Typekit.
		$typekit_id = ot_get_option( 'typekit_id' );
		if ( $typekit_id ) {
			wp_enqueue_script( 'thb-typekit', 'https://use.typekit.net/' . $typekit_id . '.js', null, esc_attr( $thb_theme_version ), false );
			wp_add_inline_script( 'thb-typekit', 'try{Typekit.load({ async: true });}catch(e){}' );
		}

		// Enqueue.
		if ( $post ) {
			if ( has_shortcode( $post->post_content, 'thb_map_parent' ) ) {
				wp_enqueue_script( 'gmapdep', 'https://maps.google.com/maps/api/js?key=' . esc_attr( $thb_api_key ), null, esc_attr( $thb_theme_version ), false );
			}

			if ( has_shortcode( $post->post_content, 'contact-form-7' ) && function_exists( 'wpcf7_enqueue_scripts' ) ) {
				wpcf7_enqueue_scripts();
			}
		}
		wp_localize_script(
			'thb-app',
			'themeajax',
			array(
				'url'      => admin_url( 'admin-ajax.php' ),
				'l10n'     => array(
					'loadmore'        => esc_html__( 'Load More', 'north' ),
					'loading'         => esc_html__( 'Loading ...', 'north' ),
					'nomore'          => esc_html__( 'All Posts Loaded', 'north' ),
					'nomore_products' => esc_html__( 'All Products Loaded', 'north' ),
					'results_found'   => esc_html__( 'results found.', 'north' ),
					'results_all'     => esc_html__( 'View All Results', 'north' ),
					'adding_to_cart'  => esc_html__( 'Adding to Cart', 'north' ),
					'added_to_cart'   => esc_html__( 'Added to Cart', 'north' ),
				),
				'nonce'    => array(
					'product_quickview' => wp_create_nonce( 'thb_quickview_ajax' ),
					'autocomplete_ajax' => wp_create_nonce( 'thb_autocomplete_ajax' ),
				),
				'settings' => array(
					'site_url'                        => get_home_url(),
					'shop_product_listing_pagination' => ot_get_option( 'shop_product_listing_pagination', 'style1' ),
					'posts_per_page'                  => get_option( 'posts_per_page' ),
					'newsletter'                      => ot_get_option( 'newsletter', 'on' ),
					'newsletter_length'               => ot_get_option( 'newsletter-interval', '1' ),
					'newsletter_delay'                => ot_get_option( 'newsletter_delay', '0' ),
					'is_cart'                         => thb_wc_supported() ? is_cart() : false,
					'is_checkout'                     => thb_wc_supported() ? is_checkout() : false,
					'header_quick_cart'               => ot_get_option( 'header_quick_cart', 'on' ),
				),
				'icons'    => array(
					'close' => thb_load_template_part( 'assets/img/svg/close.svg' ),
				),
			)
		);
	}
}
add_action( 'wp_enqueue_scripts', 'thb_register_js' );

/* WooCommerce */
function thb_woocommerce_scripts_styles() {

	if ( ! is_admin() ) {
		if ( thb_wc_supported() ) {
			wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
			wp_deregister_style( 'woocommerce_prettyPhoto_css' );

			wp_dequeue_style( 'yith-wcwl-font-awesome' );
			wp_deregister_style( 'yith-wcwl-font-awesome' );

			wp_dequeue_script( 'prettyPhoto' );
			wp_dequeue_script( 'prettyPhoto-init' );

			wp_dequeue_style( 'jquery-selectBox' );
			wp_dequeue_script( 'jquery-selectBox' );

			wp_dequeue_script( 'vc_woocommerce-add-to-cart-js' );
			if ( ! is_checkout() ) {
				wp_dequeue_script( 'jquery-selectBox' );
				wp_dequeue_style( 'selectWoo' );
				wp_dequeue_script( 'selectWoo' );
			}
		}
	}
}

add_action( 'wp_enqueue_scripts', 'thb_woocommerce_scripts_styles', 98 );
add_filter( 'woocommerce_enqueue_styles', '__return_false' );
