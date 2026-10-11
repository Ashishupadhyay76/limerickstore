<?php
/**
 * Initialize the meta boxes.
 */
add_action( 'admin_init', 'thb_custom_meta_boxes' );

/**
 * Meta Boxes demo code.
 *
 * You can find all the available option types
 * in demo-theme-options.php.
 *
 * @return    void
 *
 * @access    private
 * @since     2.0
 */


function thb_custom_meta_boxes() {
	/**
	 * Create a custom meta boxes array that we pass to
	 * the OptionTree Meta Box API Class.
	 */
	$post_meta_box = array(
		'id'       => 'post_meta_video',
		'title'    => esc_html__( 'Post Format Settings', 'north' ),
		'pages'    => array( 'post' ),
		'context'  => 'normal',
		'priority' => 'high',
		'fields'   => array(
			array(
				'id'    => 'tab1',
				'label' => esc_html__( 'Post Format Settings', 'north' ),
				'type'  => 'tab',
			),
			array(
				'label'     => esc_html__( 'Gallery Images', 'north' ),
				'id'        => 'pp_gallery_slider',
				'type'      => 'gallery',
				'post_type' => 'post',
				'desc'      => esc_html__( 'Images you would like to show inside the gallery slider.', 'north' ),
			),
			array(
				'label' => esc_html__( 'Video URL', 'north' ),
				'id'    => 'post_video',
				'type'  => 'textarea-simple',
				'desc'  => esc_html__( 'Video URL. You can find a list of websites you can embed here: <a href="http://codex.wordpress.org/Embeds">Wordpress Embeds</a>', 'north' ),
				'rows'  => '5',
			),
		),
	);

	$product_meta_box = array(
		'id'       => 'product_settings',
		'title'    => esc_html__( 'Product Page Settings', 'north' ),
		'pages'    => array( 'product' ),
		'context'  => 'normal',
		'priority' => 'high',
		'fields'   => array(
			array(
				'id'    => 'tab2',
				'label' => esc_html__( 'Sizing Guide', 'north' ),
				'type'  => 'tab',
			),
			array(
				'label' => esc_html__( 'Enable Sizing Guide', 'north' ),
				'id'    => 'sizing_guide',
				'type'  => 'on_off',
				'desc'  => esc_html__( 'Enabling the sizing guide will add a link to the product page that will open the below content in a lightbox.', 'north' ),
				'std'   => 'off',
			),
			array(
				'label'     => esc_html__( 'Sizing Guide Content', 'north' ),
				'id'        => 'sizing_guide_content',
				'type'      => 'textarea',
				'desc'      => esc_html__( 'You can insert your sizin guide content here. Preferablly an image.', 'north' ),
				'rows'      => '5',
				'condition' => 'sizing_guide:is(on)',
			),
		),
	);
	$page_metabox     = array(
		'id'       => 'post_metaboxes_combined',
		'title'    => esc_html__( 'Page Settings', 'north' ),
		'pages'    => array( 'page' ),
		'context'  => 'normal',
		'priority' => 'high',
		'fields'   => array(
			array(
				'id'    => 'tab2',
				'label' => esc_html__( 'Page Settings', 'north' ),
				'type'  => 'tab',
			),
			array(
				'label' => esc_html__( 'Main Header Color', 'north' ),
				'id'    => 'header_color',
				'type'  => 'radio-image',
				'desc'  => esc_html__( 'Overrides the main header color of the theme. Changes the logo and menu colors', 'north' ),
				'std'   => 'dark-title',
			),
			array(
				'label' => esc_html__( 'Enable Page Padding', 'north' ),
				'id'    => 'page_padding',
				'type'  => 'on_off',
				'desc'  => esc_html__( 'This adds padding to the top & bottom of the page so the footer and header does not overlap with content', 'north' ),
				'std'   => 'off',
			),
			array(
				'label' => esc_html__( 'Disable Footer', 'north' ),
				'id'    => 'disable_footer',
				'type'  => 'on_off',
				'desc'  => esc_html__( 'This disables the footer on this page.', 'north' ),
				'std'   => 'off',
			),
		),
	);

	/**
	 * Register our meta boxes using the
	 * ot_register_meta_box() function.
	 */

	ot_register_meta_box( $post_meta_box );
	ot_register_meta_box( $page_metabox );
	ot_register_meta_box( $product_meta_box );
}
