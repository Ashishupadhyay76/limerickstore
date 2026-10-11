<?php
if ( ! thb_wc_supported() ) {
	return;
}
// Quick View.
function thb_quickview_product() {
	check_ajax_referer( 'thb_quickview_ajax', 'security' );
	$product_id = filter_input( INPUT_POST, 'product_id', FILTER_VALIDATE_INT );

	if ( empty( $product_id ) ) {
		wp_send_json_error( esc_html__( 'No product.', 'north' ) );
		wp_die();
	}

	$product = wc_get_product( $product_id );
	if ( ! $product || ! in_array( $product->post_type, array( 'product', 'product_variation', true ), true ) ) {
		wp_send_json_error( esc_html__( 'Invalid product.', 'north' ) );
		wp_die();
	}
	ob_start();
	$args  = array(
		'post_type' => 'product',
		'p'         => $product_id,
	);
	$query = new WP_Query( $args );
	if ( $query->have_posts() ) :
		while ( $query->have_posts() ) :
			$query->the_post();
			wc_get_template_part( 'content', 'single-product-quickview' );
		endwhile;
	endif;
	$output = ob_get_clean();

	wp_send_json_success( $output );
	wp_die();
}
add_action( 'wc_ajax_thb_product_quickview', 'thb_quickview_product' );
