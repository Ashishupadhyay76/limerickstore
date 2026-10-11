<?php
function thb_filter_radio_images( $array, $field_id ) {

	if ( in_array( $field_id, array( 'footer_color', 'subfooter_color', 'subheader_color', 'global_notification_color' ) ) ) {
		$array = array(
			array(
				'value' => 'light',
				'label' => esc_html__( 'Light - White Background', 'north' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/light_dark/light.png',
			),
			array(
				'value' => 'dark',
				'label' => esc_html__( 'Dark - Black Background', 'north' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/light_dark/dark.png',
			),
		);
	}

	if ( $field_id === 'mobile_menu_icon_style' ) {
		$array = array(
			array(
				'value' => 'style1',
				'label' => esc_html__( 'Style 1', 'north' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/menuicon/m1.png',
			),
			array(
				'value' => 'style2',
				'label' => esc_html__( 'Style 2', 'north' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/menuicon/m2.png',
			),
			array(
				'value' => 'style3',
				'label' => esc_html__( 'Style 3', 'north' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/menuicon/m3.png',
			),
			array(
				'value' => 'style4',
				'label' => esc_html__( 'Style 4', 'north' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/menuicon/m4.png',
			),
		);
	}

	if ( 'header_style' === $field_id ) {
		$array = array(
			array(
				'value' => 'style1',
				'label' => esc_html__( 'Style - 1', 'north' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/header_styles/style1.png',
			),
			array(
				'value' => 'style2',
				'label' => esc_html__( 'Style - 2', 'north' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/header_styles/style2.png',
			),
			array(
				'value' => 'style3',
				'label' => esc_html__( 'Style - 3', 'north' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/header_styles/style3.png',
			),
			array(
				'value' => 'style4',
				'label' => esc_html__( 'Style - 4', 'north' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/header_styles/style4.png',
			),
			array(
				'value' => 'style5',
				'label' => esc_html__( 'Style - 5', 'north' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/header_styles/style5.png',
			),
		);
	}

	if ( 'footer_style' === $field_id ) {
		$array = array(
			array(
				'value' => 'style1',
				'label' => esc_html__( 'Style - 1', 'north' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/footer_styles/style1.png',
			),
			array(
				'value' => 'style2',
				'label' => esc_html__( 'Style - 2', 'north' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/footer_styles/style2.png',
			),
			array(
				'value' => 'style3',
				'label' => esc_html__( 'Style - 3', 'north' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/footer_styles/style3.png',
			),
		);
	}

	if ( 'shop_product_listing_button' === $field_id ) {
		$array = array(
			array(
				'value' => 'style0',
				'label' => esc_html__( 'Style - 0', 'north' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/product_list_button_styles/style0.png',
			),
			array(
				'value' => 'style1',
				'label' => esc_html__( 'Style - 1', 'north' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/product_list_button_styles/style1.png',
			),
			array(
				'value' => 'style2',
				'label' => esc_html__( 'Style - 2', 'north' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/product_list_button_styles/style2.png',
			),
			array(
				'value' => 'style4',
				'label' => esc_html__( 'Style - 4', 'north' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/product_list_button_styles/style4.png',
			),
		);
	}
	if ( in_array( $field_id, array( 'header_color', 'shop_menu_color' ) ) ) {
		$array = array(
			array(
				'value' => 'light-title',
				'label' => esc_html__( 'Light', 'north' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/light_dark/light.png',
			),
			array(
				'value' => 'dark-title',
				'label' => esc_html__( 'Dark', 'north' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/light_dark/dark.png',
			),
		);
	}
	if ( 'shop_product_listing' === $field_id ) {
		$array = array(
			array(
				'value' => 'style1',
				'label' => esc_html__( 'Style - 1', 'north' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/product_list_styles/style1.png',
			),
			array(
				'value' => 'style2',
				'label' => esc_html__( 'Style - 2', 'north' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/product_list_styles/style2.png',
			),
		);
	}

	if ( 'shop_product_style' === $field_id ) {
		$array = array(
			array(
				'value' => 'style1',
				'label' => esc_html__( 'Style - 1', 'north' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/product_styles/style1.png',
			),
			array(
				'value' => 'style2',
				'label' => esc_html__( 'Style - 2', 'north' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/product_styles/style2.png',
			),
			array(
				'value' => 'style3',
				'label' => esc_html__( 'Style - 3', 'north' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/product_styles/style3.png',
			),
			array(
				'value' => 'style4',
				'label' => esc_html__( 'Style - 4', 'north' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/product_styles/style4.png',
			),
			array(
				'value' => 'style5',
				'label' => esc_html__( 'Style - 5', 'north' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/product_styles/style5.png',
			),

		);
	}

	if ( 'shop_product_thumbnail_layout' === $field_id ) {
		$array = array(
			array(
				'value' => 'style1',
				'label' => esc_html__( 'Style - 1', 'north' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/product_thumbnail_layout/style1.png',
			),
			array(
				'value' => 'style2',
				'label' => esc_html__( 'Style - 2', 'north' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/product_thumbnail_layout/style2.png',
			),

		);
	}

	if ( 'shop_product_tabs_style' === $field_id ) {
		$array = array(
			array(
				'value' => 'style1',
				'label' => esc_html__( 'Style - 1', 'north' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/product_tabs_style/style1.png',
			),
			array(
				'value' => 'style2',
				'label' => esc_html__( 'Style - 2', 'north' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/product_tabs_style/style2.png',
			),

		);
	}

	if ( 'footer_columns' === $field_id ) {
		$array = array(
			array(
				'value' => 'fourcolumns',
				'label' => esc_html__( 'Four Columns', 'north' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/columns/four-columns.png',
			),
			array(
				'value' => 'threecolumns',
				'label' => esc_html__( 'Three Columns', 'north' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/columns/three-columns.png',
			),
			array(
				'value' => 'twocolumns',
				'label' => esc_html__( 'Two Columns', 'north' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/columns/two-columns.png',
			),
			array(
				'value' => 'doubleleft',
				'label' => esc_html__( 'Double Left Columns', 'north' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/columns/doubleleft-columns.png',
			),
			array(
				'value' => 'doubleright',
				'label' => esc_html__( 'Double Right Columns', 'north' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/columns/doubleright-columns.png',
			),
			array(
				'value' => 'fivecolumns',
				'label' => esc_html__( 'Five Columns', 'north' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/columns/five-columns.png',
			),
			array(
				'value' => 'onecolumns',
				'label' => esc_html__( 'Single Column', 'north' ),
				'src'   => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/columns/one-columns.png',
			),
		);
	}
	return $array;
}
add_filter( 'ot_radio_images', 'thb_filter_radio_images', 10, 2 );

function thb_filter_options_name() {
	return esc_html__( 'Fuel Themes', 'north' );
}
add_filter( 'ot_header_version_text', 'thb_filter_options_name', 10, 2 );


function thb_filter_upload_name() {
	return esc_html__( 'Send to Theme Options', 'north' );
}
add_filter( 'ot_upload_text', 'thb_filter_upload_name', 10, 2 );

function thb_header_list() {
	echo '<li class="theme_link"><a href="http://fuelthemes.ticksy.com/" target="_blank">Support Forum</a></li>';
}
add_filter( 'ot_header_list', 'thb_header_list' );

function thb_filter_ot_recognized_font_families( $array, $field_id ) {
	$array['helveticaneue'] = "'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif";
	ot_fetch_google_fonts( true, false );
	$ot_google_fonts = wp_list_pluck( get_theme_mod( 'ot_google_fonts', array() ), 'family' );
	$array           = array_merge( $array, $ot_google_fonts );

	if ( ot_get_option( 'typekit_id' ) ) {
		$typekit_fonts = trim( ot_get_option( 'typekit_fonts' ), ' ' );
		$typekit_fonts = explode( ',', $typekit_fonts );

		$array = array_merge( $array, $typekit_fonts );
	}
	$self_hosted_names = array();
	if ( ot_get_option( 'self_hosted_fonts' ) ) {
		$self_hosted_fonts = ot_get_option( 'self_hosted_fonts' );

		foreach ( $self_hosted_fonts as $font ) {
			$self_hosted_names[] = $font['font_name'];
		}

		$array = array_merge( $array, $self_hosted_names );
	}
	foreach ( $array as $font => $value ) {
		$thb_font_array[ $value ] = $value;
	}
	return $thb_font_array;
}
add_filter( 'ot_recognized_font_families', 'thb_filter_ot_recognized_font_families', 10, 2 );

function thb_filter_spacing_fields( $array, $field_id ) {

	if ( in_array( $field_id, array( 'header_padding', 'header_padding_fixed', 'header_padding_mobile', 'footer_padding', 'subfooter_padding', 'footerbar_padding' ) ) ) {
		$array = array( 'top', 'bottom' );
	}
	return $array;

}

add_filter( 'ot_recognized_spacing_fields', 'thb_filter_spacing_fields', 10, 2 );

function thb_filter_typography_fields( $array, $field_id ) {
	if ( in_array( $field_id, array( 'title_type', 'body_type', 'menu_font', 'button_font', 'widget_title_font' ) ) ) {
		$array = array( 'font-family' );
	} elseif ( in_array( $field_id, array( 'em_font', 'label_font' ) ) ) {
		$array = array( 'font-family', 'font-style', 'font-weight' );
	} elseif ( in_array( $field_id, array( 'menu_left_type', 'menu_left_submenu_type', 'menu_right_type', 'menu_mobile_type', 'menu_mobile_submenu_type', 'menu_mobile_secondary_type', 'global_notification_type', 'ajax_notification_type', 'widget_title_type', 'footer_copyright_type', 'footer_menu_type' ) ) ) {
		$array = array( 'font-size', 'font-style', 'font-weight', 'text-transform', 'line-height', 'letter-spacing' );
	} elseif ( in_array( $field_id, array( 'h1_type', 'h2_type', 'h3_type', 'h4_type', 'h5_type', 'h6_type' ) ) ) {
		$array = array( 'font-family', 'font-size', 'font-style', 'font-weight', 'text-transform', 'line-height', 'letter-spacing' );
	} elseif ( in_array( $field_id, array( 'shop_title', 'shop_product_title', 'shop_product_category_title', 'shop_product_title', 'shop_product_price', 'shop_product_excerpt', 'shop_product_button', 'shop_product_detail_title', 'shop_product_detail_price', 'shop_product_detail_excerpt' ) ) ) {
		$array = array( 'font-family', 'font-size', 'font-style', 'font-weight', 'text-transform', 'line-height', 'letter-spacing' );
	} elseif ( in_array( $field_id, array( 'footer_social_type' ) ) ) {
		$array = array( 'font-size' );
	}
	return $array;
}

add_filter( 'ot_recognized_typography_fields', 'thb_filter_typography_fields', 10, 2 );

function thb_social_links_settings( $id ) {

	$settings = array(
		array(
			'label'   => 'Social Networks to display',
			'id'      => 'social_network',
			'type'    => 'select',
			'desc'    => 'Select your social network',
			'choices' => array(
				array(
					'label' => 'Facebook',
					'value' => 'facebook',
				),
				array(
					'label' => 'Twitter',
					'value' => 'twitter',
				),
				array(
					'label' => 'Pinterest',
					'value' => 'pinterest',
				),
				array(
					'label' => 'Linkedin',
					'value' => 'linkedin',
				),
				array(
					'label' => 'Instagram',
					'value' => 'instagram',
				),
				array(
					'label' => 'Flickr',
					'value' => 'flickr',
				),
				array(
					'label' => 'VK',
					'value' => 'vk',
				),
				array(
					'label' => 'Tumblr',
					'value' => 'tumblr',
				),
				array(
					'label' => 'Spotify',
					'value' => 'spotify',
				),
				array(
					'label' => 'Youtube',
					'value' => 'youtube',
				),
				array(
					'label' => 'Vimeo',
					'value' => 'vimeo',
				),
				array(
					'label' => 'Dribbble',
					'value' => 'dribbble',
				),
				array(
					'label' => '500px',
					'value' => '500px',
				),
				array(
					'label' => 'Behance',
					'value' => 'behance',
				),
				array(
					'label' => 'Medium',
					'value' => 'medium',
				),
			),
		),
		array(
			'id'    => 'href',
			'label' => 'Link',
			'desc'  => sprintf( __( 'Enter a link to the profile or page on the social website. Remember to add the %s part to the front of the link.', 'north' ), '<code>http://</code>' ),
			'type'  => 'text',
		),
	);
	return $settings;
}
add_filter( 'ot_social_links_settings', 'thb_social_links_settings' );
add_filter( 'ot_type_social_links_load_defaults', '__return_false' );

function thb_ot_line_height_unit_type( $array, $field_id ) {
	return 'em';
}
add_filter( 'ot_line_height_unit_type', 'thb_ot_line_height_unit_type', 10, 2 );

function thb_ot_line_height_high_range( $array, $field_id ) {
	return 3;
}
add_filter( 'ot_line_height_high_range', 'thb_ot_line_height_high_range', 10, 2 );

function thb_ot_line_height_range_interval( $array, $field_id ) {
	return 0.05;
}
add_filter( 'ot_line_height_range_interval', 'thb_ot_line_height_range_interval', 10, 2 );

function thb_ot_letter_spacing_high_range( $array, $field_id ) {
	return '0.2';
}
add_filter( 'ot_letter_spacing_high_range', 'thb_ot_letter_spacing_high_range', 10, 2 );

function thb_ot_letter_spacing_low_range( $array, $field_id ) {
	return '-0.2';
}
add_filter( 'ot_letter_spacing_low_range', 'thb_ot_letter_spacing_low_range', 10, 2 );

function thb_filter_ot_recognized_link_color_fields( $array, $field_id ) {
	$array = array(
		'link'  => esc_html_x( 'Standard', 'color picker', 'north' ),
		'hover' => esc_html_x( 'Hover', 'color picker', 'north' ),
	);
	return $array;
}
add_filter( 'ot_recognized_link_color_fields', 'thb_filter_ot_recognized_link_color_fields', 10, 2 );

function thb_clear_font_cache() {
	$clear = filter_input( INPUT_GET, 'thb_clear_font_cache', FILTER_VALIDATE_BOOLEAN );
	if ( $clear && current_user_can( 'manage_options' ) ) {
		delete_transient( 'ot_google_fonts_cache' );
	}

}
add_action( 'admin_init', 'thb_clear_font_cache' );
