<?php

$thb_animation_array = array(
	'type'       => 'dropdown',
	'heading'    => esc_html__( 'Animation', 'north' ),
	'param_name' => 'animation',
	'value'      => array(
		'None'         => '',
		'Left'         => 'animation right-to-left',
		'Right'        => 'animation left-to-right',
		'Left - Long'  => 'animation right-to-left-long',
		'Right - Long' => 'animation left-to-right-long',
		'Top'          => 'animation bottom-to-top',
		'Bottom'       => 'animation top-to-bottom',
		'Scale'        => 'animation scale',
		'Fade'         => 'animation fade-in',
	),
);
$thb_column_array    = array(
	'2 Columns' => 'large-6',
	'3 Columns' => 'large-4',
	'4 Columns' => 'large-3',
	'5 Columns' => 'thb-5',
	'6 Columns' => 'large-2',
);

$thb_button_style_array = array(
	''                 => Thb_Theme_Admin::$thb_theme_directory_uri . '/assets/img/admin/button_styles/style1.png',
	'alt'              => Thb_Theme_Admin::$thb_theme_directory_uri . '/assets/img/admin/button_styles/style2.png',
	'pill'             => Thb_Theme_Admin::$thb_theme_directory_uri . '/assets/img/admin/button_styles/style3.png',
	'pill-alt'         => Thb_Theme_Admin::$thb_theme_directory_uri . '/assets/img/admin/button_styles/style4.png',
	'border-fill'      => Thb_Theme_Admin::$thb_theme_directory_uri . '/assets/img/admin/button_styles/style5.png',
	'small-radius'     => Thb_Theme_Admin::$thb_theme_directory_uri . '/assets/img/admin/button_styles/style6.png',
	'small-radius alt' => Thb_Theme_Admin::$thb_theme_directory_uri . '/assets/img/admin/button_styles/style7.png',
);
$thb_offset_array       = array(
	'-200%' => '-200%',
	'-190%' => '-190%',
	'-180%' => '-180%',
	'-170%' => '-170%',
	'-160%' => '-160%',
	'-150%' => '-150%',
	'-140%' => '-140%',
	'-130%' => '-130%',
	'-120%' => '-120%',
	'-110%' => '-110%',
	'-100%' => '-100%',
	'-95%'  => '-95%',
	'-90%'  => '-90%',
	'-85%'  => '-85%',
	'-80%'  => '-80%',
	'-75%'  => '-75%',
	'-70%'  => '-70%',
	'-65%'  => '-65%',
	'-60%'  => '-60%',
	'-55%'  => '-55%',
	'-50%'  => '-50%',
	'-45%'  => '-45%',
	'-40%'  => '-40%',
	'-35%'  => '-35%',
	'-30%'  => '-30%',
	'-25%'  => '-25%',
	'-20%'  => '-20%',
	'-15%'  => '-15%',
	'-10%'  => '-10%',
	'-5%'   => '-5%',
	'0%'    => '0%',
	'5%'    => '5%',
	'10%'   => '10%',
	'15%'   => '15%',
	'20%'   => '20%',
	'25%'   => '25%',
	'30%'   => '30%',
	'35%'   => '35%',
	'40%'   => '40%',
	'45%'   => '45%',
	'50%'   => '50%',
	'55%'   => '55%',
	'60%'   => '60%',
	'65%'   => '65%',
	'70%'   => '70%',
	'75%'   => '75%',
	'80%'   => '80%',
	'85%'   => '85%',
	'90%'   => '90%',
	'95%'   => '95%',
	'100%'  => '100%',
	'110%'  => '110%',
	'120%'  => '120%',
	'130%'  => '130%',
	'140%'  => '140%',
	'150%'  => '150%',
	'160%'  => '160%',
	'170%'  => '170%',
	'180%'  => '180%',
	'190%'  => '190%',
	'200%'  => '200%',
	'210%'  => '210%',
	'220%'  => '220%',
	'230%'  => '230%',
	'240%'  => '240%',
	'250%'  => '250%',
);
/* Visual Composer Mappings */

// Adding animation to columns
vc_add_param(
	'vc_column',
	array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Column Content Color', 'north' ),
		'param_name'  => 'thb_color',
		'value'       => array(
			'Dark'  => 'thb-dark-column',
			'Light' => 'thb-light-column',
		),
		'weight'      => 1,
		'description' => esc_html__( 'If you white-colored contents for this column, select Light.', 'north' ),
	)
);
vc_add_param(
	'vc_column_inner',
	array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Column Content Color', 'north' ),
		'param_name'  => 'thb_color',
		'value'       => array(
			'Dark'  => 'thb-dark-column',
			'Light' => 'thb-light-column',
		),
		'weight'      => 1,
		'description' => esc_html__( 'If you white-colored contents for this column, select Light.', 'north' ),
	)
);

vc_remove_param( 'vc_column', 'css_animation' );
vc_remove_param( 'vc_column_inner', 'css_animation' );
vc_add_param( 'vc_column', $thb_animation_array );
vc_add_param( 'vc_column_inner', $thb_animation_array );

// Text Area
vc_remove_param( 'vc_column_text', 'css_animation' );
vc_add_param( 'vc_column_text', $thb_animation_array );

// Empty Space
vc_add_param(
	'vc_empty_space',
	array(
		'type'        => 'textfield',
		'heading'     => esc_html__( 'Mobile Height', 'north' ),
		'param_name'  => 'mobile_height',
		'admin_label' => true,
		'value'       => '',
		'weight'      => 1,
		'description' => esc_html__( 'You can change the height in mobile devices', 'north' ),
	)
);

// Inner Row
vc_remove_param( 'vc_row_inner', 'gap' );
vc_remove_param( 'vc_row_inner', 'equal_height' );
vc_remove_param( 'vc_row_inner', 'css_animation' );

vc_add_param(
	'vc_row_inner',
	array(
		'type'        => 'checkbox',
		'heading'     => esc_html__( 'Enable Max Width', 'north' ),
		'param_name'  => 'thb_max_width',
		'value'       => array(
			'Yes' => 'max_width',
		),
		'weight'      => 1,
		'description' => esc_html__( "If you enable this, the row won't exceed the max width, especially inside a full-width parent row.", 'north' ),
	)
);

vc_add_param(
	'vc_row_inner',
	array(
		'type'        => 'checkbox',
		'heading'     => esc_html__( 'Disable Padding', 'north' ),
		'param_name'  => 'thb_row_padding',
		'value'       => array(
			'Yes' => 'true',
		),
		'weight'      => 1,
		'description' => esc_html__( "If you enable this, the columns inside won't leave padding on the sides", 'north' ),
	)
);

// Row
vc_remove_param( 'vc_row', 'full_width' );
vc_remove_param( 'vc_row', 'gap' );
vc_remove_param( 'vc_row', 'equal_height' );
vc_remove_param( 'vc_row', 'css_animation' );
vc_remove_param( 'vc_row', 'video_bg' );
vc_remove_param( 'vc_row', 'video_bg_url' );
vc_remove_param( 'vc_row', 'video_bg_parallax' );
vc_remove_param( 'vc_row', 'parallax_speed_video' );

vc_add_param(
	'vc_row',
	array(
		'type'        => 'checkbox',
		'heading'     => esc_html__( 'Enable Full Width', 'north' ),
		'param_name'  => 'thb_full_width',
		'value'       => array(
			'Yes' => 'true',
		),
		'weight'      => 1,
		'description' => esc_html__( 'If you enable this, this row will fill the screen', 'north' ),
	)
);
vc_add_param(
	'vc_row',
	array(
		'type'        => 'checkbox',
		'heading'     => esc_html__( 'Disable Padding', 'north' ),
		'param_name'  => 'thb_row_padding',
		'value'       => array(
			'Yes' => 'true',
		),
		'weight'      => 1,
		'description' => esc_html__( "If you enable this, this row won't leave padding on the sides", 'north' ),
	)
);
vc_add_param(
	'vc_row',
	array(
		'type'        => 'checkbox',
		'heading'     => esc_html__( 'Full Height Row', 'north' ),
		'param_name'  => 'full_height',
		'value'       => array(
			'Yes' => 'true',
		),
		'description' => esc_html__( 'If enabled, this will cause this row to always fill the height of the window.', 'north' ),
	)
);
vc_add_param(
	'vc_row',
	array(
		'type'        => 'textfield',
		'heading'     => esc_html__( 'Video Background', 'north' ),
		'param_name'  => 'thb_video_bg',
		'weight'      => 1,
		'description' => esc_html__( 'You can specify a video background file here (mp4)', 'north' ),
	)
);
vc_add_param(
	'vc_row',
	array(
		'type'        => 'colorpicker',
		'heading'     => esc_html__( 'Video Overlay Color', 'north' ),
		'param_name'  => 'thb_video_overlay_color',
		'weight'      => 1,
		'description' => esc_html__( 'If you want, you can select an overlay color.', 'north' ),
	)
);
vc_add_param(
	'vc_row',
	array(
		'type'        => 'checkbox',
		'heading'     => esc_html__( 'Disable AutoPlay', 'north' ),
		'param_name'  => 'thb_video_play_button',
		'weight'      => 1,
		'value'       => array(
			'Yes' => 'thb_video_play_button_enabled',
		),
		'description' => esc_html__( "If enabled, the video won't start automatically and can be toggled using the Play Button Element.", 'north' ),
	)
);
vc_add_param(
	'vc_row',
	array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Header Logo Color', 'north' ),
		'param_name'  => 'thb_color',
		'value'       => array(
			'Dark'  => 'dark-title',
			'Light' => 'light-title',
		),
		'std'         => 'dark-title',
		'weight'      => 1,
		'description' => esc_html__( 'This setting affects the color of the logo when snap to scroll template is being used.', 'north' ),
	)
);

// Add to Cart Button
vc_map(
	array(
		'name'        => __( 'Add to Cart Button', 'north' ),
		'base'        => 'thb_add_to_cart',
		'icon'        => 'thb_vc_ico_add_to_cart',
		'class'       => 'thb_vc_sc_add_to_cart',
		'category'    => esc_html__( 'by Fuel Themes', 'north' ),
		'params'      => array(
			array(
				'type'        => 'textfield',
				'admin_label' => true,
				'heading'     => esc_html__( 'Product ID', 'north' ),
				'param_name'  => 'id',
				'description' => esc_html__( 'Only enter 1 Product ID', 'north' ),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Display Product Title?', 'north' ),
				'param_name'  => 'title',
				'value'       => array(
					'Yes' => 'true',
				),
				'description' => esc_html__( 'If you want to show the product title, enable this', 'north' ),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Display Product Excerpt?', 'north' ),
				'param_name'  => 'excerpt',
				'value'       => array(
					'Yes' => 'true',
				),
				'description' => esc_html__( 'If you want to show the product excerpt, enable this', 'north' ),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Display Product Price?', 'north' ),
				'param_name'  => 'price',
				'value'       => array(
					'Yes' => 'true',
				),
				'description' => esc_html__( 'If you want to show the price, enable this', 'north' ),
			),
			array(
				'type'        => 'thb_radio_image',
				'heading'     => esc_html__( 'Button Style', 'north' ),
				'param_name'  => 'btn_style',
				'options'     => $thb_button_style_array,
				'std'         => '',
				'description' => esc_html__( 'This changes the look of the button', 'north' ),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Button Color', 'north' ),
				'param_name'  => 'btn_color',
				'value'       => array(
					'Black' => '',
					'White' => 'white',
				),
				'description' => esc_html__( 'This changes the color of the button', 'north' ),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Alignment', 'north' ),
				'param_name' => 'align',
				'value'      => array(
					'Left'   => 'text-left',
					'Center' => 'text-center',
					'Right'  => 'text-right',
				),
			),
		),
		'description' => esc_html__( 'Single Add-to-Cart button', 'north' ),
	)
);

// Button shortcode
vc_map(
	array(
		'name'        => esc_html__( 'Button', 'north' ),
		'base'        => 'thb_button',
		'icon'        => 'thb_vc_ico_button',
		'class'       => 'thb_vc_sc_button',
		'category'    => esc_html__( 'by Fuel Themes', 'north' ),
		'params'      => array(
			array(
				'type'        => 'vc_link',
				'heading'     => esc_html__( 'Link', 'north' ),
				'param_name'  => 'link',
				'description' => esc_html__( 'Enter url for link', 'north' ),
				'admin_label' => true,
			),
			array(
				'type'        => 'thb_radio_image',
				'heading'     => esc_html__( 'Style', 'north' ),
				'param_name'  => 'style',
				'options'     => $thb_button_style_array,
				'std'         => '',
				'description' => esc_html__( 'This changes the look of the button', 'north' ),
			),
			array(
				'type'             => 'dropdown',
				'heading'          => esc_html__( 'Size', 'north' ),
				'param_name'       => 'size',
				'edit_field_class' => 'vc_col-sm-6',
				'std'              => 'medium',
				'value'            => array(
					'Small'  => 'small',
					'Medium' => 'medium',
					'Large'  => 'large',
				),
				'description'      => esc_html__( 'This changes the size of the button', 'north' ),
			),
			array(
				'type'             => 'dropdown',
				'heading'          => esc_html__( 'Color', 'north' ),
				'param_name'       => 'color',
				'edit_field_class' => 'vc_col-sm-6',
				'value'            => array(
					'Black' => '',
					'White' => 'white',
				),
				'description'      => esc_html__( 'This changes the color of the button', 'north' ),
			),
			$thb_animation_array,
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Full Width', 'north' ),
				'param_name'  => 'full_width',
				'value'       => array(
					'Yes' => 'true',
				),
				'description' => esc_html__( "If enabled, this will make the button fill it's container", 'north' ),
			),
			array(
				'type'       => 'textfield',
				'heading'    => esc_html__( 'Extra Class Name', 'north' ),
				'param_name' => 'extra_class',
			),
		),
		'description' => esc_html__( 'Add an animated button', 'north' ),
	)
);

vc_map(
	array(
		'name'        => esc_html__( 'Text Button', 'north' ),
		'base'        => 'thb_button_text',
		'icon'        => 'thb_vc_ico_button_text',
		'class'       => 'thb_vc_sc_button_text',
		'category'    => esc_html__( 'by Fuel Themes', 'north' ),
		'params'      => array(
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Style', 'north' ),
				'param_name'  => 'style',
				'value'       => array(
					'Style 1 (Line Left)'         => 'style1',
					'Style 2 (Line Bottom)'       => 'style2',
					'Style 3 (Arrow Left)'        => 'style3',
					'Style 4 (Arrow Right)'       => 'style4',
					'Style 5 (Arrow Right Small)' => 'style5',
				),
				'description' => esc_html__( 'This changes the look of the button', 'north' ),
			),
			array(
				'type'        => 'vc_link',
				'heading'     => esc_html__( 'Link', 'north' ),
				'param_name'  => 'link',
				'description' => esc_html__( 'Set your url & text for your button', 'north' ),
				'admin_label' => true,
			),
			$thb_animation_array,
			array(
				'type'       => 'textfield',
				'heading'    => esc_html__( 'Extra Class Name', 'north' ),
				'param_name' => 'extra_class',
			),
		),
		'description' => esc_html__( 'Add a text button', 'north' ),
	)
);

vc_map(
	array(
		'name'        => esc_html__( 'Block Button', 'north' ),
		'base'        => 'thb_button_block',
		'icon'        => 'thb_vc_ico_button_block',
		'class'       => 'thb_vc_sc_button_block',
		'category'    => esc_html__( 'by Fuel Themes', 'north' ),
		'params'      => array(
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Style', 'north' ),
				'param_name'  => 'thb_style',
				'admin_label' => true,
				'std'         => 'style1',
				'value'       => array(
					'Style 1' => 'style1',
					'Style 2' => 'style2',
				),
				'description' => esc_html__( 'This changes the style of the block buttons.', 'north' ),
			),
			array(
				'type'       => 'attach_image',
				'heading'    => esc_html__( 'Background Image', 'north' ),
				'param_name' => 'image',
			),
			array(
				'type'        => 'vc_link',
				'heading'     => esc_html__( 'Link', 'north' ),
				'param_name'  => 'link',
				'description' => esc_html__( 'Set your url & text for your button', 'north' ),
				'admin_label' => true,
			),
			$thb_animation_array,
			array(
				'type'       => 'textfield',
				'heading'    => esc_html__( 'Extra Class Name', 'north' ),
				'param_name' => 'extra_class',
			),
			array(
				'type'       => 'css_editor',
				'heading'    => esc_html__( 'Css', 'north' ),
				'param_name' => 'css',
				'group'      => esc_html__( 'Design Options', 'north' ),
			),
		),
		'description' => esc_html__( 'Add a block button with image', 'north' ),
	)
);

// Cascading Images
vc_map(
	array(
		'name'                    => esc_html__( 'Cascading Images', 'north' ),
		'base'                    => 'thb_cascading_parent',
		'icon'                    => 'thb_vc_ico_cascading',
		'class'                   => 'thb_vc_sc_cascading',
		'content_element'         => true,
		'show_settings_on_create' => false,
		'category'                => esc_html__( 'by Fuel Themes', 'north' ),
		'as_parent'               => array( 'only' => 'thb_cascading' ),
		'description'             => esc_html__( 'Insert a cascading Image', 'north' ),
		'js_view'                 => 'VcColumnView',
	)
);

vc_map(
	array(
		'name'     => esc_html__( 'Single Image', 'north' ),
		'base'     => 'thb_cascading',
		'icon'     => 'thb_vc_ico_cascading',
		'class'    => 'thb_vc_sc_cascading',
		'category' => esc_html__( 'by Fuel Themes', 'north' ),
		'as_child' => array( 'only' => 'thb_cascading_parent' ),
		'params'   => array(
			array(
				'type'        => 'attach_image',
				'heading'     => esc_html__( 'Select Image', 'north' ),
				'param_name'  => 'image',
				'description' => esc_html__( 'Select Image for the layer', 'north' ),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Offset X', 'north' ),
				'param_name' => 'image_x',
				'value'      => $thb_offset_array,
				'std'        => '0%',
			),
			array(
				'type'       => 'dropdown',
				'heading'    => __( 'Offset Y', 'north' ),
				'param_name' => 'image_y',
				'value'      => $thb_offset_array,
				'std'        => '0%',
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Retina Size?', 'north' ),
				'param_name'  => 'retina',
				'value'       => array(
					'Yes' => 'retina_size',
				),
				'description' => esc_html__( 'If selected, the image will be display half-size, so it looks crisps on retina screens. Full Width setting will override this.', 'north' ),
			),
			$thb_animation_array,
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Add Border Radius?', 'north' ),
				'param_name'  => 'radius',
				'group'       => 'Styling',
				'description' => esc_html__( 'You can add Border Radius in px value.', 'north' ),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Add Box Shadow?', 'north' ),
				'param_name'  => 'thb_box_shadow',
				'value'       => array(
					'Yes' => 'thb_box_shadow',
				),
				'group'       => 'Styling',
				'description' => esc_html__( 'You can add a Box Shadow to your image.', 'north' ),
			),
		),
	)
);

class WPBakeryShortCode_thb_cascading_parent extends WPBakeryShortCodesContainer {}
class WPBakeryShortCode_thb_cascading extends WPBakeryShortCode {}

// Clients Parent
vc_map(
	array(
		'name'            => esc_html__( 'Clients', 'north' ),
		'base'            => 'thb_clients_parent',
		'icon'            => 'thb_vc_ico_clients',
		'class'           => 'thb_vc_sc_clients',
		'content_element' => true,
		'category'        => esc_html__( 'by Fuel Themes', 'north' ),
		'as_parent'       => array( 'only' => 'thb_clients' ),
		'params'          => array(
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Style', 'north' ),
				'param_name'  => 'thb_style',
				'admin_label' => true,
				'value'       => array(
					'Style 1 (Grid)'                 => 'style1',
					'Style 2 (Carousel)'             => 'thb-carousel',
					'Style 3 (Grid with Titles)'     => 'style3',
					'Style 4 (Grid with Titles - 2)' => 'style4',
				),
				'description' => esc_html__( 'This changes the layout style of the client logos', 'north' ),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Columns', 'north' ),
				'param_name'  => 'thb_columns',
				'admin_label' => true,
				'value'       => array(
					'2 Columns' => '2',
					'3 Columns' => '3',
					'4 Columns' => '4',
					'5 Columns' => '5',
					'6 Columns' => '6',
				),
			),
			$thb_animation_array,
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Image Borders', 'north' ),
				'param_name'  => 'thb_image_borders',
				'value'       => array(
					'Yes' => 'true',
				),
				'description' => esc_html__( 'If you enable this, the logos will have border', 'north' ),
				'dependency'  => array(
					'element' => 'thb_style',
					'value'   => array( 'style1', 'thb-carousel', 'style4' ),
				),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Retina Size?', 'north' ),
				'param_name'  => 'retina',
				'value'       => array(
					'Yes' => 'retina_size',
				),
				'description' => esc_html__( 'If selected, the image will be display half-size, so it looks crisps on retina screens.', 'north' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_html__( 'Border Color', 'north' ),
				'param_name'  => 'thb_border_color',
				'admin_label' => true,
				'value'       => '#f0f0f0',
				'dependency'  => array(
					'element' => 'thb_image_borders',
					'value'   => array( 'true' ),
				),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Hover Effect', 'north' ),
				'param_name'  => 'thb_hover_effect',
				'admin_label' => true,
				'value'       => array(
					'None'                      => '',
					'Opacity'                   => 'thb-opacity',
					'Grayscale'                 => 'thb-grayscale',
					'Opacity with Accent hover' => 'thb-opacity with-accent',
				),
				'dependency'  => array(
					'element' => 'thb_style',
					'value'   => array( 'style1', 'thb-carousel' ),
				),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Auto Play', 'north' ),
				'param_name'  => 'autoplay',
				'value'       => array(
					'Yes' => 'true',
				),
				'description' => esc_html__( 'If enabled, the carousel will autoplay.', 'north' ),
				'dependency'  => array(
					'element' => 'thb_style',
					'value'   => array( 'thb-carousel' ),
				),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Speed of the AutoPlay', 'north' ),
				'param_name'  => 'autoplay_speed',
				'value'       => '4000',
				'description' => esc_html__( 'Speed of the autoplay, default 4000 (4 seconds)', 'north' ),
				'dependency'  => array(
					'element' => 'autoplay',
					'value'   => array( 'true' ),
				),
			),
		),
		'description'     => esc_html__( 'Partner/Client logos', 'north' ),
		'js_view'         => 'VcColumnView',
	)
);

vc_map(
	array(
		'name'        => esc_html__( 'Client', 'north' ),
		'base'        => 'thb_clients',
		'icon'        => 'thb_vc_ico_clients',
		'class'       => 'thb_vc_sc_clients',
		'category'    => esc_html__( 'by Fuel Themes', 'north' ),
		'as_child'    => array( 'only' => 'thb_clients_parent' ),
		'params'      => array(
			array(
				'type'        => 'attach_image',
				'heading'     => esc_html__( 'Image', 'north' ),
				'param_name'  => 'image',
				'value'       => '',
				'description' => esc_html__( 'Add logo image here.', 'north' ),
			),
			array(
				'type'        => 'vc_link',
				'heading'     => esc_html__( 'Link', 'north' ),
				'param_name'  => 'link',
				'admin_label' => true,
				'description' => esc_html__( 'Add a link to client website if desired.', 'north' ),
			),
		),
		'description' => esc_html__( 'Single Client', 'north' ),
	)
);
class WPBakeryShortCode_thb_clients_parent extends WPBakeryShortCodesContainer {}
class WPBakeryShortCode_thb_clients extends WPBakeryShortCode {}

// Google Map
vc_map(
	array(
		'name'            => esc_html__( 'Contact Map Parent', 'north' ),
		'base'            => 'thb_map_parent',
		'icon'            => 'thb_vc_ico_contactmap',
		'class'           => 'thb_vc_sc_contactmap',
		'content_element' => true,
		'category'        => esc_html__( 'by Fuel Themes', 'north' ),
		'as_parent'       => array( 'only' => 'thb_map' ),
		'params'          => array(
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Map Height', 'north' ),
				'param_name'  => 'height',
				'admin_label' => true,
				'value'       => 50,
				'description' => esc_html__( 'Enter height of the map in vh (0-100). For example, 50 will be 50% of viewport height and 100 will be full height. <small>Make sure you have filled in your Google Maps API inside Appearance > Theme Options.</small>', 'north' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Map Zoom', 'north' ),
				'param_name'  => 'zoom',
				'value'       => '0',
				'description' => esc_html__( 'Set map zoom level. Leave 0 to automatically fit to bounds.', 'north' ),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Map Controls', 'north' ),
				'param_name'  => 'map_controls',
				'std'         => 'panControl, zoomControl, mapTypeControl, scaleControl',
				'value'       => array(
					__( 'Pan Control', 'north' )         => 'panControl',
					__( 'Zoom Control', 'north' )        => 'zoomControl',
					__( 'Map Type Control', 'north' )    => 'mapTypeControl',
					__( 'Scale Control', 'north' )       => 'scaleControl',
					__( 'Street View Control', 'north' ) => 'streetViewControl',
				),
				'description' => esc_html__( 'Toggle map options.', 'north' ),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Map Type', 'north' ),
				'param_name'  => 'map_type',
				'std'         => 'roadmap',
				'value'       => array(
					__( 'Roadmap', 'north' )   => 'roadmap',
					__( 'Satellite', 'north' ) => 'satellite',
					__( 'Hybrid', 'north' )    => 'hybrid',
				),
				'description' => esc_html__( 'Choose map style.', 'north' ),
			),
			array(
				'type'        => 'textarea_raw_html',
				'heading'     => esc_html__( 'Map Style', 'north' ),
				'param_name'  => 'map_style',
				'description' => esc_html__( 'Paste the style code here. Browse map styles in <a href="https://snazzymaps.com/" target="_blank">SnazzyMaps</a>', 'north' ),
			),
		),
		'description'     => esc_html__( 'Insert your Contact Map', 'north' ),
		'js_view'         => 'VcColumnView',
	)
);

vc_map(
	array(
		'name'     => esc_html__( 'Contact Map Location', 'north' ),
		'base'     => 'thb_map',
		'icon'     => 'thb_vc_ico_contactmap',
		'class'    => 'thb_vc_sc_contactmap',
		'category' => esc_html__( 'by Fuel Themes', 'north' ),
		'as_child' => array( 'only' => 'thb_map_parent' ),
		'params'   => array(
			array(
				'type'        => 'attach_image',
				'heading'     => esc_html__( 'Marker Image', 'north' ),
				'param_name'  => 'marker_image',
				'description' => esc_html__( 'Add your Custom marker image or use default one.', 'north' ),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Retina Marker', 'north' ),
				'param_name'  => 'retina_marker',
				'value'       => array(
					__( 'Yes', 'north' ) => 'yes',
				),
				'description' => esc_html__( 'Enabling this option will reduce the size of marker for 50%, example if marker is 32x32 it will be 16x16.', 'north' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Latitude', 'north' ),
				'admin_label' => true,
				'param_name'  => 'latitude',
				'description' => esc_html__( 'Enter latitude coordinate. To select map coordinates <a href="http://www.latlong.net/convert-address-to-lat-long.html" target="_blank">click here</a>.', 'north' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Longitude', 'north' ),
				'admin_label' => true,
				'param_name'  => 'longitude',
				'description' => esc_html__( 'Enter longitude coordinate.', 'north' ),
			),
			array(
				'type'       => 'textfield',
				'heading'    => esc_html__( 'Marker Title', 'north' ),
				'param_name' => 'marker_title',
			),
			array(
				'type'       => 'textarea',
				'heading'    => esc_html__( 'Marker Description', 'north' ),
				'param_name' => 'marker_description',
			),
		),
	)
);

class WPBakeryShortCode_thb_map_parent extends WPBakeryShortCodesContainer {}
class WPBakeryShortCode_thb_map extends WPBakeryShortCode {}

// Iconbox shortcode
vc_map(
	array(
		'name'        => esc_html__( 'Iconbox', 'north' ),
		'base'        => 'thb_iconbox',
		'icon'        => 'thb_vc_ico_iconbox',
		'class'       => 'thb_vc_sc_iconbox',
		'category'    => esc_html__( 'by Fuel Themes', 'north' ),
		'params'      => array(
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Type', 'north' ),
				'param_name' => 'type',
				'value'      => array(
					'Top Icon - Style 1'   => 'top type1',
					'Top Icon - Style 2'   => 'top type2',
					'Top Icon - Style 3'   => 'top type3',
					'Left Icon - Style 1'  => 'left type1',
					'Right Icon - Style 1' => 'right type1',
				),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Alignment', 'north' ),
				'param_name' => 'alignment',
				'value'      => array(
					'Left'   => 'text-left',
					'Center' => 'text-center',
					'Right'  => 'text-right',
				),
				'std'        => 'text-center',
				'dependency' => array(
					'element' => 'type',
					'value'   => array( 'top type1' ),
				),

			),
			array(
				'type'       => 'attach_image',
				'heading'    => esc_html__( 'Add Background Image', 'north' ),
				'param_name' => 'bg_image',
				'dependency' => array(
					'element' => 'type',
					'value'   => array( 'top type3' ),
				),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Icon', 'north' ),
				'param_name' => 'icon',
				'value'      => thb_getIconArray(),
			),
			array(
				'type'       => 'colorpicker',
				'heading'    => esc_html__( 'SVG Icon Color', 'north' ),
				'param_name' => 'thb_icon_color',
			),
			array(
				'type'        => 'attach_image',
				'heading'     => esc_html__( 'Image As Icon', 'north' ),
				'param_name'  => 'icon_image',
				'description' => esc_html__( 'You can set an image instead of an icon.', 'north' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Image Width', 'north' ),
				'param_name'  => 'icon_image_width',
				'description' => esc_html__( 'If you are using an image, you can set custom width here. Default is 64 (pixels).', 'north' ),
			),
			array(
				'type'        => 'vc_link',
				'heading'     => esc_html__( 'Link', 'north' ),
				'param_name'  => 'link',
				'description' => esc_html__( 'Add a link to the iconbox if desired.', 'north' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Heading', 'north' ),
				'param_name'  => 'heading',
				'admin_label' => true,
			),
			array(
				'type'       => 'textarea_safe',
				'heading'    => esc_html__( 'Content', 'north' ),
				'param_name' => 'description',
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Animation', 'north' ),
				'param_name'  => 'animation',
				'value'       => array(
					'Yes' => 'true',
				),
				'weight'      => 1,
				'std'         => 'true',
				'description' => esc_html__( 'You can disable animation if you like.', 'north' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Animation Speed', 'north' ),
				'param_name'  => 'animation_speed',
				'value'       => '1.5',
				'description' => esc_html__( 'Speed of the animation in seconds', 'north' ),
				'dependency'  => array(
					'element' => 'animation',
					'value'   => array( 'true' ),
				),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_html__( 'Heading Color', 'north' ),
				'param_name'  => 'thb_heading_color',
				'group'       => 'Styling',
				'description' => esc_html__( 'Color of the heading', 'north' ),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_html__( 'Text Color', 'north' ),
				'param_name'  => 'thb_text_color',
				'group'       => 'Styling',
				'description' => esc_html__( 'Color of the text', 'north' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Heading Font Size', 'north' ),
				'param_name'  => 'heading_font_size',
				'group'       => 'Styling',
				'description' => esc_html__( 'Enter any valid font-size: 16px, 14pt, etc.', 'north' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Content Font Size', 'north' ),
				'param_name'  => 'description_font_size',
				'group'       => 'Styling',
				'description' => esc_html__( 'Enter any valid font-size: 16px, 14pt, etc.', 'north' ),
			),
		),
		'description' => esc_html__( 'Iconboxes with different animations', 'north' ),
	)
);

// Image shortcode
vc_map(
	array(
		'name'        => 'Image',
		'base'        => 'thb_image',
		'icon'        => 'thb_vc_ico_image',
		'class'       => 'thb_vc_sc_image',
		'category'    => esc_html__( 'by Fuel Themes', 'north' ),
		'params'      => array(
			array(
				'type'       => 'attach_image', // attach_images
				'heading'    => esc_html__( 'Select Image', 'north' ),
				'param_name' => 'image',
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Display Caption?', 'north' ),
				'param_name'  => 'caption',
				'value'       => array(
					'Yes' => 'true',
				),
				'description' => esc_html__( 'If selected, the image caption will be displayed.', 'north' ),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Caption Style', 'north' ),
				'param_name'  => 'caption_style',
				'value'       => array(
					'Style1' => 'style1',
					'Style2' => 'style2',
				),
				'description' => esc_html__( 'Select caption style.', 'north' ),
				'dependency'  => array(
					'element' => 'caption',
					'value'   => array( 'true' ),
				),
			),
			array(
				'type'       => 'textarea_html',
				'heading'    => esc_html__( 'Text Below Image', 'north' ),
				'param_name' => 'content',
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Retina Size?', 'north' ),
				'param_name'  => 'retina',
				'value'       => array(
					'Yes' => 'retina_size',
				),
				'description' => esc_html__( 'If selected, the image will be display half-size, so it looks crisps on retina screens. Full Width setting will override this.', 'north' ),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Full Width?', 'north' ),
				'param_name'  => 'full_width',
				'value'       => array(
					'Yes' => 'true',
				),
				'description' => esc_html__( 'If selected, the image will always fill its container', 'north' ),
			),
			$thb_animation_array,
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Image size', 'north' ),
				'param_name'  => 'img_size',
				'description' => esc_html__( "Enter image size. Example: thumbnail, medium, large, full or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use 'thumbnail' size.", 'north' ),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Image alignment', 'north' ),
				'param_name'  => 'alignment',
				'value'       => array(
					'Align left'   => 'alignleft',
					'Align right'  => 'alignright',
					'Align center' => 'aligncenter',
				),
				'description' => esc_html__( 'Select image alignment.', 'north' ),
			),
			array(
				'type'       => 'checkbox',
				'heading'    => esc_html__( 'Link to Full-Width Image?', 'north' ),
				'param_name' => 'lightbox',
				'value'      => array(
					'Yes' => 'true',
				),
			),
			array(
				'type'        => 'vc_link',
				'heading'     => esc_html__( 'Image link', 'north' ),
				'param_name'  => 'img_link',
				'description' => esc_html__( 'Enter url if you want this image to have link.', 'north' ),
				'dependency'  => array(
					'element'  => 'lightbox',
					'is_empty' => true,
				),
			),
		),
		'description' => esc_html__( 'Add an animated image', 'north' ),
	)
);

// Image Hotspots shortcode
vc_map(
	array(
		'name'             => esc_html__( 'Image Hot Spots', 'north' ),
		'base'             => 'thb_hotspots',
		'icon'             => 'thb_vc_ico_imagehotspots',
		'class'            => 'thb_vc_sc_hotspots',
		'front_enqueue_js' => array( Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/js/vendor/jquery.hotspot.js' ),
		'admin_enqueue_js' => array( Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/js/vendor/jquery.hotspot.js' ),
		'category'         => esc_html__( 'by Fuel Themes', 'north' ),
		'params'           => array(
			array(
				'type'        => 'attach_image', // attach_images
				'heading'     => esc_html__( 'Select Image', 'north' ),
				'param_name'  => 'image',
				'description' => esc_html__( 'After selecting your image, you can then click on the image in the preview area to add your hotspots on the desired locations.', 'north' ),
			),
			array(
				'type'             => 'thb_hotspot_param',
				'heading'          => esc_html__( 'Image Preview', 'north' ),
				'param_name'       => 'thb_hotspot_data',
				'edit_field_class' => 'vc_column vc_col-sm-12',
				'description'      => esc_html__( 'Click to add a hotspot - Drag to move it', 'north' ),
			),
			array(
				'type'       => 'dropdown',
				'param_name' => 'thb_tooltip_function',
				'heading'    => esc_html__( 'Functionality', 'north' ),
				'value'      => array(
					'Show on Hover' => 'hover',
					'Show on Click' => 'click',
				),
				'std'        => 'hover',
				'group'      => esc_html__( 'Styling', 'north' ),
			),
			array(
				'type'       => 'dropdown',
				'param_name' => 'thb_pin_color',
				'heading'    => esc_html__( 'Pin Color', 'north' ),
				'value'      => array(
					'Black' => 'pin-black',
					'White' => 'pin-white',
				),
				'std'        => 'pin-white',
				'group'      => esc_html__( 'Styling', 'north' ),
			),
			array(
				'type'       => 'dropdown',
				'heading'    => esc_html__( 'Pin Animation', 'north' ),
				'param_name' => 'animation',
				'value'      => array(
					'None'          => '',
					'Right to Left' => 'animation right-to-left',
					'Left to Right' => 'animation left-to-right',
					'Bottom to Top' => 'animation bottom-to-top',
					'Top to Bottom' => 'animation top-to-bottom',
					'Scale'         => 'animation scale',
					'Fade'          => 'animation fade-in',
				),
				'group'      => esc_html__( 'Styling', 'north' ),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Enable Pulsate Effect', 'north' ),
				'param_name'  => 'thb_pulsate',
				'value'       => array(
					esc_html__( 'Yes', 'north' ) => 'thb-pulsate',
				),
				'std'         => 'thb-pulsate',
				'description' => esc_html__( 'Shows a pulsate around the pin.', 'north' ),
				'group'       => esc_html__( 'Styling', 'north' ),
			),
		),
		'description'      => esc_html__( 'Add an image with hotspots', 'north' ),
	)
);

// Image Slider
vc_map(
	array(
		'name'        => esc_html__( 'Image Slider', 'north' ),
		'base'        => 'thb_slider',
		'icon'        => 'thb_vc_ico_slider',
		'class'       => 'thb_vc_sc_slider',
		'category'    => esc_html__( 'by Fuel Themes', 'north' ),
		'params'      => array(
			array(
				'type'        => 'attach_images', // attach_images
				'heading'     => esc_html__( 'Select Images', 'north' ),
				'param_name'  => 'images',
				'admin_label' => true,
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Navigation Arrows', 'north' ),
				'param_name'  => 'navigation',
				'value'       => array(
					'Yes' => 'true',
				),
				'description' => esc_html__( 'Check this if you want to show navigation arrows.', 'north' ),
			),
		),
		'description' => esc_html__( 'Add an image slider', 'north' ),
	)
);

// Instagram
vc_map(
	array(
		'name'        => esc_html__( 'Instagram', 'north' ),
		'base'        => 'thb_instagram',
		'icon'        => 'thb_vc_ico_instagram',
		'class'       => 'thb_vc_sc_instagram',
		'category'    => esc_html__( 'by Fuel Themes', 'north' ),
		'params'      => array(
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Instagram Username', 'north' ),
				'param_name'  => 'username',
				'admin_label' => true,
				'description' => esc_html__( 'Instagram username to retrieve photos from.', 'north' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Instagram Access Token', 'north' ),
				'param_name'  => 'access_token',
				'description' => esc_html__( 'Instagram Access Token.', 'north' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Number of Photos', 'north' ),
				'param_name'  => 'number',
				'description' => esc_html__( 'Number of Instagram Photos to retrieve.', 'north' ),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Columns', 'north' ),
				'param_name'  => 'columns',
				'admin_label' => true,
				'value'       => $thb_column_array,
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Column Padding', 'north' ),
				'param_name'  => 'column_padding',
				'value'       => array(
					'Normal'     => '',
					'Low'        => 'low-padding',
					'No-Padding' => 'no-padding',
				),
				'description' => esc_html__( 'You can have columns without spaces using this option', 'north' ),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Link Photos to Instagram?', 'north' ),
				'param_name'  => 'link',
				'value'       => array(
					esc_html__( 'Yes', 'north' ) => 'true',
				),
				'group'       => 'Other',
				'description' => esc_html__( 'Do you want to link the Instagram photos to instagram.com website?', 'north' ),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Display Username?', 'north' ),
				'param_name'  => 'display_username',
				'value'       => array(
					esc_html__( 'Yes', 'north' ) => 'true',
				),
				'group'       => 'Other',
				'description' => esc_html__( 'If you want to show the username above photos.', 'north' ),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Username Position', 'north' ),
				'param_name'  => 'display_username_alignment',
				'value'       => array(
					'Left'   => 'text-left',
					'Center' => 'text-center',
				),
				'std'         => 'text-left',
				'group'       => 'Other',
				'description' => esc_html__( 'Alignment of the username.', 'north' ),
				'dependency'  => array(
					'element' => 'display_username',
					'value'   => array( 'true' ),
				),
			),
		),
		'description' => esc_html__( 'Add Instagram Photos', 'north' ),
	)
);
// Instagram
vc_map(
	array(
		'name'        => esc_html__( 'Instagram Block', 'north' ),
		'base'        => 'thb_instagram_block',
		'icon'        => 'thb_vc_ico_instagram',
		'class'       => 'thb_vc_sc_instagram',
		'category'    => esc_html__( 'by Fuel Themes', 'north' ),
		'params'      => array(
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Instagram Username', 'north' ),
				'param_name'  => 'username',
				'admin_label' => true,
				'description' => esc_html__( 'Instagram username to retrieve photos from.', 'north' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Instagram Access Token', 'north' ),
				'param_name'  => 'access_token',
				'description' => esc_html__( 'Instagram Access Token.', 'north' ),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Link Photos to Instagram?', 'north' ),
				'param_name'  => 'link',
				'value'       => array(
					esc_html__( 'Yes', 'north' ) => 'true',
				),
				'group'       => 'Other',
				'description' => esc_html__( 'Do you want to link the Instagram photos to instagram.com website?', 'north' ),
			),
			array(
				'type'             => 'colorpicker',
				'heading'          => esc_html__( 'Color - 1', 'north' ),
				'param_name'       => 'thb_color',
				'edit_field_class' => 'vc_col-sm-4',
				'description'      => esc_html__( 'Select background color', 'north' ),
			),
			array(
				'type'             => 'colorpicker',
				'heading'          => esc_html__( 'Color - 2', 'north' ),
				'param_name'       => 'thb_color2',
				'edit_field_class' => 'vc_col-sm-4',
				'description'      => esc_html__( 'Select background color', 'north' ),
			),
			array(
				'type'             => 'colorpicker',
				'heading'          => esc_html__( 'Color - 3', 'north' ),
				'param_name'       => 'thb_color3',
				'edit_field_class' => 'vc_col-sm-4',
				'description'      => esc_html__( 'Select background color', 'north' ),
			),
		),
		'description' => esc_html__( 'Add Instagram Photos', 'north' ),
	)
);
// Image shortcode
vc_map(
	array(
		'name'        => 'Look Book',
		'base'        => 'thb_lookbook',
		'icon'        => 'thb_vc_ico_lookbook',
		'class'       => 'thb_vc_sc_lookbook',
		'category'    => esc_html__( 'by Fuel Themes', 'north' ),
		'params'      => array(
			array(
				'type'       => 'attach_image', // attach_images
				'heading'    => esc_html__( 'Select Image', 'north' ),
				'param_name' => 'image',
			),
			array(
				'type'        => 'loop',
				'heading'     => esc_html__( 'Select Products', 'north' ),
				'param_name'  => 'source',
				'description' => esc_html__( 'Set your products to show on hover', 'north' ),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Full Width?', 'north' ),
				'param_name'  => 'full_width',
				'value'       => array(
					'Yes' => 'true',
				),
				'description' => esc_html__( 'If selected, the image will always fill its container', 'north' ),
			),
			$thb_animation_array,
		),
		'description' => esc_html__( 'Add a Look Book element', 'north' ),
	)
);

// Label
vc_map(
	array(
		'name'        => esc_html__( 'Label', 'north' ),
		'base'        => 'thb_label',
		'icon'        => 'thb_vc_ico_label',
		'class'       => 'thb_vc_sc_label',
		'category'    => esc_html__( 'by Fuel Themes', 'north' ),
		'params'      => array(
			array(
				'type'       => 'textarea_html',
				'heading'    => esc_html__( 'Content', 'north' ),
				'param_name' => 'content',
				'group'      => 'Content',
			),
			$thb_animation_array,
			array(
				'type'       => 'textfield',
				'heading'    => esc_html__( 'Extra Class Name', 'north' ),
				'param_name' => 'extra_class',
			),
			array(
				'type'       => 'css_editor',
				'heading'    => esc_html__( 'Css', 'north' ),
				'param_name' => 'css',
				'group'      => esc_html__( 'Design options', 'north' ),
			),
		),
		'description' => esc_html__( 'Display a label box', 'north' ),
	)
);

// Play Button
vc_map(
	array(
		'name'                    => esc_html__( 'Play Button', 'north' ),
		'base'                    => 'thb_play',
		'icon'                    => 'thb_vc_ico_play',
		'class'                   => 'thb_vc_sc_play',
		'category'                => esc_html__( 'by Fuel Themes', 'north' ),
		'show_settings_on_create' => false,
		'description'             => esc_html__( 'For Row Video Backgrounds', 'north' ),
	)
);

// Products
vc_map(
	array(
		'name'        => esc_html__( 'Products', 'north' ),
		'base'        => 'thb_product',
		'icon'        => 'thb_vc_ico_product',
		'class'       => 'thb_vc_sc_product',
		'category'    => esc_html__( 'by Fuel Themes', 'north' ),
		'params'      => array(
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Product Sort', 'north' ),
				'param_name'  => 'product_sort',
				'value'       => array(
					'Best Sellers'      => 'best-sellers',
					'Latest Products'   => 'latest-products',
					'Top Rated'         => 'top-rated',
					'Featured Products' => 'featured-products',
					'Sale Products'     => 'sale-products',
					'By Category'       => 'by-category',
					'By Product ID'     => 'by-id',
				),
				'description' => esc_html__( "Select the order of the products you'd like to show.", 'north' ),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Product Category', 'north' ),
				'param_name'  => 'cat',
				'value'       => thb_productCategories(),
				'description' => esc_html__( "Select the order of the products you'd like to show.", 'north' ),
				'dependency'  => array(
					'element' => 'product_sort',
					'value'   => array( 'by-category' ),
				),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Product IDs', 'north' ),
				'param_name'  => 'product_ids',
				'description' => esc_html__( 'Enter the products IDs you would like to display seperated by comma', 'north' ),
				'dependency'  => array(
					'element' => 'product_sort',
					'value'   => array( 'by-id' ),
				),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Carousel', 'north' ),
				'param_name'  => 'carousel',
				'value'       => array(
					'Yes' => 'yes',
					'No'  => 'no',
				),
				'description' => esc_html__( 'Select yes to display the products in a carousel.', 'north' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Number of Items', 'north' ),
				'param_name'  => 'item_count',
				'value'       => '4',
				'description' => esc_html__( 'The number of products to show.', 'north' ),
				'dependency'  => array(
					'element' => 'product_sort',
					'value'   => array( 'by-category', 'sale-products', 'top-rated', 'latest-products', 'best-sellers', 'featured-products' ),
				),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Columns', 'north' ),
				'param_name'  => 'columns',
				'admin_label' => true,
				'value'       => array(
					'Six Columns'   => '6',
					'Five Columns'  => '5',
					'Four Columns'  => '4',
					'Three Columns' => '3',
					'Two Columns'   => '2',
				),
				'std'         => '4',
				'description' => esc_html__( 'Select the layout of the products.', 'north' ),
			),
		),
		'description' => esc_html__( 'Add WooCommerce products', 'north' ),
	)
);

// Product List
vc_map(
	array(
		'name'        => esc_html__( 'Product List', 'north' ),
		'base'        => 'thb_product_list',
		'icon'        => 'thb_vc_ico_product_list',
		'class'       => 'thb_vc_sc_product_list',
		'category'    => esc_html__( 'by Fuel Themes', 'north' ),
		'params'      => array(
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Title', 'north' ),
				'param_name'  => 'title',
				'admin_label' => true,
				'description' => esc_html__( 'Title of the widget', 'north' ),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Product Sort', 'north' ),
				'param_name'  => 'product_sort',
				'value'       => array(
					'Best Sellers'    => 'best-sellers',
					'Latest Products' => 'latest-products',
					'Top Rated'       => 'top-rated',
					'Sale Products'   => 'sale-products',
					'By Product ID'   => 'by-id',
				),
				'admin_label' => true,
				'description' => esc_html__( "Select the order of the products you'd like to show.", 'north' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Product IDs', 'north' ),
				'param_name'  => 'product_ids',
				'description' => esc_html__( 'Enter the products IDs you would like to display seperated by comma', 'north' ),
				'dependency'  => array(
					'element' => 'product_sort',
					'value'   => array( 'by-id' ),
				),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Number of Items', 'north' ),
				'param_name'  => 'item_count',
				'value'       => '4',
				'description' => esc_html__( 'The number of products to show.', 'north' ),
				'dependency'  => array(
					'element' => 'product_sort',
					'value'   => array( 'by-category', 'sale-products', 'top-rated', 'latest-products', 'best-sellers' ),
				),
			),
		),
		'description' => esc_html__( 'Add WooCommerce products in a list', 'north' ),
	)
);

// Product Slider
vc_map(
	array(
		'name'        => esc_html__( 'Product Slider', 'north' ),
		'base'        => 'thb_product_slider',
		'icon'        => 'thb_vc_ico_product_slider',
		'class'       => 'thb_vc_sc_product_slider',
		'category'    => esc_html__( 'by Fuel Themes', 'north' ),
		'params'      => array(
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Product Sort', 'north' ),
				'param_name'  => 'product_sort',
				'value'       => array(
					'Best Sellers'    => 'best-sellers',
					'Latest Products' => 'latest-products',
					'Top Rated'       => 'top-rated',
					'Sale Products'   => 'sale-products',
					'By Product ID'   => 'by-id',
				),
				'admin_label' => true,
				'description' => esc_html__( "Select the order of the products you'd like to show.", 'north' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Product IDs', 'north' ),
				'param_name'  => 'product_ids',
				'description' => esc_html__( 'Enter the products IDs you would like to display seperated by comma', 'north' ),
				'dependency'  => array(
					'element' => 'product_sort',
					'value'   => array( 'by-id' ),
				),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Number of Items', 'north' ),
				'param_name'  => 'item_count',
				'value'       => '4',
				'description' => esc_html__( 'The number of products to show.', 'north' ),
				'dependency'  => array(
					'element' => 'product_sort',
					'value'   => array( 'by-category', 'sale-products', 'top-rated', 'latest-products', 'best-sellers' ),
				),
			),
		),
		'description' => esc_html__( 'Add WooCommerce products in a slider', 'north' ),
	)
);

// Shop Grid
vc_map(
	array(
		'name'        => esc_html__( 'Product Category Grid', 'north' ),
		'base'        => 'thb_product_category_grid',
		'icon'        => 'thb_vc_ico_grid',
		'class'       => 'thb_vc_sc_grid',
		'category'    => esc_html__( 'by Fuel Themes', 'north' ),
		'params'      => array(
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Product Category', 'north' ),
				'param_name'  => 'cat',
				'value'       => thb_productCategories(),
				'description' => esc_html__( 'Select the categories you would like to display', 'north' ),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Category Ordering', 'north' ),
				'param_name'  => 'category_order',
				'admin_label' => true,
				'value'       => array(
					'By Name'          => 'name',
					'By Product Count' => 'count',
					'By ID'            => 'term_id',
					'By Menu Order'    => 'menu_order',
				),
				'std'         => 'name',
				'description' => esc_html__( 'This changes the ordering of categories', 'north' ),
			),
			array(
				'type'        => 'thb_radio_image',
				'heading'     => esc_html__( 'Style', 'north' ),
				'param_name'  => 'style',
				'admin_label' => true,
				'options'     => array(
					'style1' => Thb_Theme_Admin::$thb_theme_directory_uri . '/assets/img/admin/product_cat_grid/style1.png',
					'style2' => Thb_Theme_Admin::$thb_theme_directory_uri . '/assets/img/admin/product_cat_grid/style2.png',
					'style3' => Thb_Theme_Admin::$thb_theme_directory_uri . '/assets/img/admin/product_cat_grid/style3.png',
					'style4' => Thb_Theme_Admin::$thb_theme_directory_uri . '/assets/img/admin/product_cat_grid/style4.png',
					'style5' => Thb_Theme_Admin::$thb_theme_directory_uri . '/assets/img/admin/product_cat_grid/style5.png',
				),
				'std'         => 'style1',
				'description' => esc_html__( 'This applies different grid structures', 'north' ),
			),
		),
		'description' => esc_html__( 'Display Product Category Grid', 'north' ),
	)
);

// Product Categories
vc_map(
	array(
		'name'        => esc_html__( 'Product Categories', 'north' ),
		'base'        => 'thb_product_categories',
		'icon'        => 'thb_vc_ico_product_categories',
		'class'       => 'thb_vc_sc_product_categories',
		'category'    => esc_html__( 'by Fuel Themes', 'north' ),
		'params'      => array(
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Product Category', 'north' ),
				'param_name'  => 'cat',
				'value'       => thb_productCategories(),
				'description' => esc_html__( 'Select the categories you would like to display', 'north' ),
			),
			array(
				'type'        => 'thb_radio_image',
				'heading'     => esc_html__( 'Style', 'north' ),
				'param_name'  => 'style',
				'admin_label' => true,
				'options'     => array(
					'style1' => Thb_Theme_Admin::$thb_theme_directory_uri . '/assets/img/admin/product_cat/style1.png',
					'style2' => Thb_Theme_Admin::$thb_theme_directory_uri . '/assets/img/admin/product_cat/style2.png',
				),
				'std'         => 'style1',
				'description' => esc_html__( 'This applies different grid structures', 'north' ),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Columns', 'north' ),
				'param_name'  => 'columns',
				'admin_label' => true,
				'value'       => array(
					'Six Columns'   => '6',
					'Five Columns'  => '5',
					'Four Columns'  => '4',
					'Three Columns' => '3',
					'Two Columns'   => '2',
				),
				'std'         => '4',
				'description' => esc_html__( 'Select the layout of the products.', 'north' ),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Carousel?', 'north' ),
				'param_name'  => 'carousel',
				'value'       => array(
					'Yes' => 'true',
				),
				'std'         => 'true',
				'description' => esc_html__( 'If you want to use a carousel, enable this.', 'north' ),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Column Padding', 'north' ),
				'param_name'  => 'column_padding',
				'value'       => array(
					'Normal'       => '',
					'Mini Padding' => 'mini-padding',
					'Low Padding'  => 'low-padding',
					'No Padding'   => 'no-padding',
				),
				'std'         => '',
				'description' => esc_html__( 'You can have columns without spaces using this option', 'north' ),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Category Ordering', 'north' ),
				'param_name'  => 'category_order',
				'admin_label' => true,
				'value'       => array(
					'By Name'          => 'name',
					'By Product Count' => 'count',
					'By ID'            => 'term_id',
					'By Menu Order'    => 'menu_order',
				),
				'std'         => 'name',
				'description' => esc_html__( 'This changes the ordering of categories', 'north' ),
			),
		),
		'description' => esc_html__( 'Add WooCommerce product categories', 'north' ),
	)
);

// Posts
vc_map(
	array(
		'name'        => esc_html__( 'Posts', 'north' ),
		'base'        => 'thb_post',
		'icon'        => 'thb_vc_ico_post',
		'class'       => 'thb_vc_sc_post',
		'category'    => esc_html__( 'by Fuel Themes', 'north' ),
		'params'      => array(
			array(
				'type'        => 'loop',
				'heading'     => esc_html__( 'Post Source', 'north' ),
				'param_name'  => 'source',
				'description' => esc_html__( 'Set your post source here', 'north' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Offset', 'north' ),
				'param_name'  => 'offset',
				'description' => esc_html__( 'You can offset your post with the number of posts entered in this setting', 'north' ),
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Columns', 'north' ),
				'param_name'  => 'columns',
				'admin_label' => true,
				'value'       => array(
					'2 Columns' => '2',
					'3 Columns' => '3',
					'4 Columns' => '4',
				),
				'description' => esc_html__( 'Select the layout of the posts.', 'north' ),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Add Load More Button?', 'north' ),
				'param_name'  => 'loadmore',
				'value'       => array(
					'Yes' => 'true',
				),
				'description' => esc_html__( 'Add Load More button at the bottom', 'north' ),
			),
			array(
				'type'        => 'checkbox',
				'heading'     => esc_html__( 'Display Excerpt?', 'north' ),
				'param_name'  => 'thb_excerpt',
				'value'       => array(
					'Yes' => 'true',
				),
				'std'         => 'true',
				'group'       => 'Styling',
				'description' => esc_html__( 'You can hide the excerpt if you uncheck this.', 'north' ),
			),
		),
		'description' => esc_html__( 'Display Posts from your blog', 'north' ),
	)
);
// slidetype
vc_map(
	array(
		'base'        => 'thb_slidetype',
		'name'        => esc_html__( 'Slide Type', 'north' ),
		'description' => esc_html__( 'Animated text scrolling', 'north' ),
		'category'    => esc_html__( 'by Fuel Themes', 'north' ),
		'icon'        => 'thb_vc_ico_slidetype',
		'class'       => 'thb_vc_sc_slidetype',
		'params'      => array(
			array(
				'type'        => 'textarea_safe',
				'heading'     => esc_html__( 'Content', 'north' ),
				'param_name'  => 'slide_text',
				'value'       => '<h2>*North;Enter your Custom Content Here*</h2>',
				'description' => 'Enter the content to display with typing text. <br />
			Text within <b>*</b> will be animated, for example: <strong>*Sample text*</strong>. <br />
			Text separator is <b>;</b> for example: <strong>*revolution;Enter your Custom Content Here*</strong> which will create new lines at ;',
				'admin_label' => true,
			),
			array(
				'type'        => 'dropdown',
				'heading'     => esc_html__( 'Style', 'north' ),
				'param_name'  => 'style',
				'admin_label' => true,
				'value'       => array(
					'Lines'      => 'style1',
					'Words'      => 'style2',
					'Characters' => 'style3',
				),
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_html__( 'Animated Text Color', 'north' ),
				'param_name'  => 'thb_animated_color',
				'description' => esc_html__( 'Uses the accent color by default', 'north' ),
			),
			array(
				'type'       => 'textfield',
				'heading'    => esc_html__( 'Extra Class Name', 'north' ),
				'param_name' => 'extra_class',
			),
		),
	)
);
// stroke type
vc_map(
	array(
		'base'        => 'thb_stroketype',
		'name'        => esc_html__( 'Stroke Type', 'north' ),
		'description' => esc_html__( 'Text with Stroke style', 'north' ),
		'category'    => esc_html__( 'by Fuel Themes', 'north' ),
		'icon'        => 'thb_vc_ico_stroketype',
		'class'       => 'thb_vc_sc_stroketype',
		'params'      => array(
			array(
				'type'        => 'textarea_safe',
				'heading'     => esc_html__( 'Content', 'north' ),
				'param_name'  => 'slide_text',
				'value'       => '<h1>Revolution</h1>',
				'description' => 'Enter the content to display with stroke.',
				'admin_label' => true,
			),
			array(
				'type'        => 'colorpicker',
				'heading'     => esc_html__( 'Text Color', 'north' ),
				'param_name'  => 'thb_color',
				'description' => esc_html__( 'Select text color', 'north' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Stroke Width', 'north' ),
				'param_name'  => 'stroke_width',
				'std'         => '2px',
				'description' => esc_html__( 'Enter the value for the stroke width. ', 'north' ),
			),
			array(
				'type'       => 'textfield',
				'heading'    => esc_html__( 'Extra Class Name', 'north' ),
				'param_name' => 'extra_class',
			),
			$thb_animation_array,
		),
	)
);

// Subscription shortcode
vc_map(
	array(
		'name'                    => __( 'Subscription Form', 'north' ),
		'base'                    => 'thb_subscribe',
		'icon'                    => 'thb_vc_ico_subscribe',
		'class'                   => 'thb_vc_sc_subscribe',
		'category'                => 'by Fuel Themes',
		'show_settings_on_create' => false,
		'description'             => esc_html__( 'Add a subscription form', 'north' ),
	)
);

// Team Member shortcode
vc_map(
	array(
		'name'        => esc_html__( 'Team Member', 'north' ),
		'base'        => 'thb_teammember',
		'icon'        => 'thb_vc_ico_teammember',
		'class'       => 'thb_vc_sc_teammember',
		'category'    => esc_html__( 'by Fuel Themes', 'north' ),
		'params'      => array(
			array(
				'type'        => 'attach_image', // attach_images
				'heading'     => esc_html__( 'Select Team Member Image', 'north' ),
				'param_name'  => 'image',
				'description' => esc_html__( 'Minimum size is 270x270 pixels', 'north' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Name', 'north' ),
				'param_name'  => 'name',
				'admin_label' => true,
				'description' => esc_html__( 'Enter name of the team member', 'north' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Position', 'north' ),
				'param_name'  => 'position',
				'description' => esc_html__( 'Enter position/title of the team member', 'north' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Facebook', 'north' ),
				'param_name'  => 'facebook',
				'description' => esc_html__( 'Enter Facebook Link', 'north' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Twitter', 'north' ),
				'param_name'  => 'twitter',
				'description' => esc_html__( 'Enter Twitter Link', 'north' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Pinterest', 'north' ),
				'param_name'  => 'pinterest',
				'description' => esc_html__( 'Enter Pinterest Link', 'north' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Linkedin', 'north' ),
				'param_name'  => 'linkedin',
				'description' => esc_html__( 'Enter Linkedin Link', 'north' ),
			),
		),
		'description' => esc_html__( 'Display your team members in a stylish way', 'north' ),
	)
);

// Testimonial Parent
vc_map(
	array(
		'name'            => esc_html__( 'Testimonial Slider', 'north' ),
		'base'            => 'thb_testimonial_parent',
		'icon'            => 'thb_vc_ico_testimonial',
		'class'           => 'thb_vc_sc_testimonial',
		'content_element' => true,
		'category'        => esc_html__( 'by Fuel Themes', 'north' ),
		'as_parent'       => array( 'only' => 'thb_testimonial' ),
		'params'          => array(
			array(
				'type'        => 'thb_radio_image',
				'heading'     => esc_html__( 'Style', 'north' ),
				'param_name'  => 'thb_style',
				'admin_label' => true,
				'options'     => array(
					'testimonial-style1' => Thb_Theme_Admin::$thb_theme_directory_uri . '/assets/img/admin/testimonials-styles/style1.png',
					'testimonial-style2' => Thb_Theme_Admin::$thb_theme_directory_uri . '/assets/img/admin/testimonials-styles/style2.png',
					'testimonial-style3' => Thb_Theme_Admin::$thb_theme_directory_uri . '/assets/img/admin/testimonials-styles/style3.png',
				),
				'description' => esc_html__( 'This changes the layout style of the testimonials', 'north' ),
			),
		),
		'description'     => esc_html__( 'Testimonials Slider', 'north' ),
		'js_view'         => 'VcColumnView',
	)
);
vc_map(
	array(
		'name'        => esc_html__( 'Testimonial', 'north' ),
		'base'        => 'thb_testimonial',
		'icon'        => 'thb_vc_ico_testimonial',
		'class'       => 'thb_vc_sc_testimonial',
		'category'    => esc_html__( 'by Fuel Themes', 'north' ),
		'as_child'    => array( 'only' => 'thb_testimonial_parent' ),
		'params'      => array(
			array(
				'type'        => 'textarea',
				'heading'     => esc_html__( 'Quote', 'north' ),
				'param_name'  => 'quote',
				'description' => esc_html__( 'Quote you want to show', 'north' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Author', 'north' ),
				'param_name'  => 'author_name',
				'admin_label' => true,
				'description' => esc_html__( 'Name of the member.', 'north' ),
			),
			array(
				'type'        => 'textfield',
				'heading'     => esc_html__( 'Author Title', 'north' ),
				'param_name'  => 'author_title',
				'description' => esc_html__( 'Title that will appear below author name.', 'north' ),
			),
			array(
				'type'        => 'attach_image',
				'heading'     => esc_html__( 'Author Image', 'north' ),
				'param_name'  => 'author_image',
				'description' => esc_html__( 'Add Author image here. Could be used depending on style.', 'north' ),
			),
		),
		'description' => esc_html__( 'Single Testimonial', 'north' ),
	)
);
class WPBakeryShortCode_thb_testimonial_parent extends WPBakeryShortCodesContainer {}
class WPBakeryShortCode_thb_testimonial extends WPBakeryShortCode {}

/* Revolution Slider */
vc_add_param(
	'rev_slider_vc',
	array(
		'type'        => 'checkbox',
		'heading'     => esc_html__( 'Affect Header Colors', 'north' ),
		'param_name'  => 'thb_revslider_affect_headers',
		'weight'      => 1,
		'value'       => array(
			'Yes' => 'thb_revslider_affect_headers',
		),
		'description' => esc_html__( 'If enabled, this slider will affect header colors. Please make sure that every slide has an image background.', 'north' ),
	)
);
