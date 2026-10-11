<?php
if ( ! is_admin() ) {
	return;
}
if ( ! thb_wc_supported() ) {
	return;
}
// Add Tab.
add_filter( 'woocommerce_product_data_tabs', 'thb_product_settings_tabs' );
function thb_product_settings_tabs( $tabs ) {
	$tabs['north'] = array(
		'label'    => esc_html__( 'North', 'north' ),
		'target'   => 'thb_product_data',
		'priority' => 61,
	);
	return $tabs;

}

// Tab Content.
add_action( 'woocommerce_product_data_panels', 'thb_product_panels' );
function thb_product_panels() {

	echo '<div id="thb_product_data" class="panel woocommerce_options_panel hidden">';

	woocommerce_wp_text_input(
		array(
			'id'          => 'thb_product_video',
			'value'       => get_post_meta( get_the_ID(), 'thb_product_video', true ),
			'label'       => 'Video URL',
			'description' => esc_html__( 'Opens the video in lightbox. Accepts youtube, video and mp4 urls.', 'north' ),
		)
	);
	echo '</div>';
}

// Save Content.
add_action( 'woocommerce_process_product_meta', 'thb_save_product_fields', 10, 2 );
function thb_save_product_fields( $id, $post ) {
	$thb_product_video = !empty($_POST['thb_product_video']) ? sanitize_text_field( $_POST['thb_product_video'] ) : '';

	update_post_meta( $id, 'thb_product_video', $thb_product_video );
}
