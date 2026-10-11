<?php
function thb_blog_posts() {
	check_ajax_referer( 'thb_blog_ajax', 'security' );
	$page       = filter_input( INPUT_POST, 'page', FILTER_VALIDATE_INT );
	$ppp        = get_option( 'posts_per_page' );
	$blog_style = ot_get_option( 'blog_style', 'style3' );

	$args = array(
		'posts_per_page' => $ppp,
		'paged'          => $page,
		'post_status'    => 'publish',
	);

	$more_query = new WP_Query( $args );
	set_query_var( 'thb_excerpt', true );
	if ( $more_query->have_posts() ) :
		while ( $more_query->have_posts() ) :
			$more_query->the_post();
			get_template_part( 'inc/templates/blogbit/' . $blog_style );
		endwhile;
	endif;
	wp_die();
}
add_action( 'wp_ajax_nopriv_thb_blog_ajax', 'thb_blog_posts' );
add_action( 'wp_ajax_thb_blog_ajax', 'thb_blog_posts' );

function thb_quick_shop_ajax() {
	check_ajax_referer( 'thb_quick_shop', 'security' );
	$term_slug				= !empty($_POST['term_slug']) ? sanitize_text_field( $_POST['term_slug'] ) : '';
	$quick_shop_count = ot_get_option( 'quick_shop_count', 8 );

	$args = array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => $quick_shop_count,
		'no_found_rows'  => true,
	);

	if ( '' !== $term_slug ) {
		$args['product_cat'] = $term_slug;
	}

	$quick_products = new WP_Query( $args );
	set_query_var( 'thb_columns', 'medium-6' );
	if ( $quick_products->have_posts() ) :
		while ( $quick_products->have_posts() ) :
			$quick_products->the_post();
			wc_get_template_part( 'content', 'product' );
	endwhile;
endif;
	remove_query_arg( 'thb_columns' );
	wp_die();
}

add_action( 'wp_ajax_nopriv_thb_quick_shop_ajax', 'thb_quick_shop_ajax' );
add_action( 'wp_ajax_thb_quick_shop_ajax', 'thb_quick_shop_ajax' );


function thb_ajax_search_products() {
	check_ajax_referer( 'thb_autocomplete_ajax', 'security' );
	$search_keyword              = !empty($_GET['query']) ? sanitize_text_field( $_GET['query'] ) : '';
	$time_start                  = microtime( true );
	$product_visibility_term_ids = wc_get_product_visibility_term_ids();
	$ordering_args               = WC()->query->get_catalog_ordering_args( 'title', 'asc' );
	$suggestions                 = array();

	$args = array(
		's'                   => $search_keyword,
		'post_type'           => 'product',
		'post_status'         => 'publish',
		'ignore_sticky_posts' => 1,
		'posts_per_page'      => 6,
		'orderby'             => $ordering_args['orderby'],
		'order'               => $ordering_args['order'],
		'suppress_filters'    => false,
		'tax_query'           => array(
			array(
				'taxonomy' => 'product_visibility',
				'field'    => 'term_taxonomy_id',
				'terms'    => $product_visibility_term_ids['exclude-from-search'],
				'operator' => 'NOT IN',
			),
		),
	);

	$products = get_posts( $args );

	if ( ! empty( $products ) ) {
		foreach ( $products as $post ) {
			$product = wc_get_product( $post );

			$suggestions[] = array(
				'id'        => $product->get_id(),
				'value'     => wp_strip_all_tags( $product->get_title() ),
				'url'       => $product->get_permalink(),
				'thumbnail' => $product->get_image(),
				'price'     => $product->get_price_html(),
			);
		}
	} else {
		$suggestions = false;
	}

	$time_end = microtime( true );
	$time     = $time_end - $time_start;

	$suggestions = array(
		'suggestions' => $suggestions,
		'time'        => $time,
	);
	echo wp_json_encode( $suggestions );
	wp_die();
}

add_action( 'wp_ajax_nopriv_thb_ajax_search_products', 'thb_ajax_search_products' );
add_action( 'wp_ajax_thb_ajax_search_products', 'thb_ajax_search_products' );

function thb_posts_ajax() {
	check_ajax_referer( 'thb_posts_ajax', 'security' );
	$count    = filter_input( INPUT_POST, 'count', FILTER_VALIDATE_INT );
	$columns	= !empty($_POST['columns']) ? sanitize_text_field( $_POST['columns'] ) : '';
	$excerpts	= !empty($_POST['excerpts']) ? sanitize_text_field( $_POST['excerpts'] ) : '';

	$loop = !empty($_POST['loop']) ? sanitize_text_field( $_POST['loop'] ) : '';
	$page = filter_input( INPUT_POST, 'page', FILTER_VALIDATE_INT );

	$source_data          = VcLoopSettings::parseData( $loop );
	$source_data['paged'] = $page;
	$source_data          = thb_moveKeyBefore( $source_data, 'offset', 'paged' );
	$query_builder        = new ThbLoopQueryBuilder( $source_data );
	$posts                = $query_builder->build();
	$more_query           = $posts[1];

	if ( $more_query->have_posts() ) :
		while ( $more_query->have_posts() ) :
			$more_query->the_post();
			set_query_var( 'columns', $columns );
			set_query_var( 'thb_excerpt', $excerpts );
			get_template_part( 'inc/templates/blogbit/style4' );
	endwhile;
	endif;
	wp_die();
}
add_action( 'wp_ajax_nopriv_thb_posts_ajax', 'thb_posts_ajax' );
add_action( 'wp_ajax_thb_posts_ajax', 'thb_posts_ajax' );

/* Email Subscribe */
function thb_subscribe_emails() {
	check_ajax_referer( 'thb_subscription', 'security' );
	// the email
	$email   = filter_input( INPUT_POST, 'email', FILTER_SANITIZE_EMAIL );
	$privacy = filter_input( INPUT_POST, 'privacy', FILTER_VALIDATE_BOOLEAN );
	$checked = filter_input( INPUT_POST, 'checked', FILTER_VALIDATE_BOOLEAN );

	if ( $privacy && ! $checked ) {
		echo '<div class="woocommerce-error">' . __( 'Please accept the terms of our newsletter.', 'north' ) . '</div>';
		wp_die();
	}
	// if the email is valid
	if ( is_email( $email ) ) {

		// get all the current emails
		$stack = get_option( 'subscribed_emails' );

		// if there are no emails in the database
		if ( ! $stack ) {
			// update the option with the first email as an array
			update_option( 'subscribed_emails', array( $email ) );
		} else {
			// if the email already exists in the array
			if ( in_array( $email, $stack ) ) {
				echo '<div class="woocommerce-error">' . __( '<strong>Oh snap!</strong> That email address is already subscribed!', 'north' ) . '</div>';
			} else {

				// If there is more than one email, add the new email to the array
				array_push( $stack, $email );

				// update the option with the new set of emails
				update_option( 'subscribed_emails', $stack );

				echo '<div class="woocommerce-message">' . __( '<strong>Well done!</strong> Your address has been added.', 'north' ) . '</div>';
			}
		}
	} else {
		echo '<div class="woocommerce-error">' . __( '<strong>Oh snap!</strong> Please enter a valid email address.', 'north' ) . '</div>';
	}
	wp_die();
}
add_action( 'wp_ajax_nopriv_thb_subscribe_emails', 'thb_subscribe_emails' );
add_action( 'wp_ajax_thb_subscribe_emails', 'thb_subscribe_emails' );
