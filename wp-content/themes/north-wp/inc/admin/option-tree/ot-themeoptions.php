<?php
/**
 * Initialize the options before anything else.
 */
add_action( 'admin_init', 'thb_custom_theme_options', 1 );

/**
 * Theme Mode demo code of all the available option types.
 *
 * @return    void
 *
 * @access    private
 * @since     2.0
 */
function thb_custom_theme_options() {

	/**
	 * Get a copy of the saved settings array.
	 */
	$saved_settings = get_option( 'option_tree_settings', array() );

	/**
	 * Create a custom settings array that we pass to
	 * the OptionTree Settings API Class.
	 */
	$custom_settings = array(
		'sections' => array(
			array(
				'title' => esc_html__( 'General', 'north' ),
				'id'    => 'general',
			),
			array(
				'title' => esc_html__( 'Sub-Header', 'north' ),
				'id'    => 'subheader',
			),
			array(
				'title' => esc_html__( 'Header', 'north' ),
				'id'    => 'header',
			),
			array(
				'title' => esc_html__( 'Shop', 'north' ),
				'id'    => 'shop',
			),
			array(
				'title' => esc_html__( 'Blog', 'north' ),
				'id'    => 'blog',
			),
			array(
				'title' => esc_html__( 'Footer', 'north' ),
				'id'    => 'footer',
			),
			array(
				'title' => esc_html__( 'Typography', 'north' ),
				'id'    => 'typography',
			),
			array(
				'title' => esc_html__( 'Customization', 'north' ),
				'id'    => 'customization',
			),
			array(
				'title' => esc_html__( 'Misc', 'north' ),
				'id'    => 'misc',
			),
			array(
				'title' => esc_html__( 'GDPR', 'north' ),
				'id'    => 'gdpr',
			),
		),
		'settings' => array(
			array(
				'id'      => 'general_tab0',
				'label'   => esc_html__( 'General', 'north' ),
				'type'    => 'tab',
				'section' => 'general',
			),
			array(
				'label'   => esc_html__( 'Custom Revolution Slider Arrows?', 'north' ),
				'id'      => 'revslider_arrows',
				'type'    => 'on_off',
				'desc'    => esc_html__( 'This will override the Revolution slider arrows to use the North color changing ones.', 'north' ),
				'std'     => 'on',
				'section' => 'general',
			),
			array(
				'label'   => esc_html__( 'Global Notification', 'north' ),
				'id'      => 'global_notification',
				'type'    => 'on_off',
				'desc'    => esc_html__( 'This adds a global notification at the top.', 'north' ),
				'std'     => 'off',
				'section' => 'general',
			),
			array(
				'label'   => esc_html__( 'Global Notification Text Color', 'north' ),
				'id'      => 'global_notification_color',
				'type'    => 'radio-image',
				'desc'    => esc_html__( 'Changes the color of the contents of the global notificaion', 'north' ),
				'std'     => 'light',
				'section' => 'general',
			),
			array(
				'label'     => esc_html__( 'Global Notification Content', 'north' ),
				'id'        => 'global_notification_content',
				'type'      => 'textarea',
				'desc'      => esc_html__( 'Content of the notification.', 'north' ),
				'rows'      => '4',
				'section'   => 'general',
				'condition' => 'global_notification:is(on)',
			),
			array(
				'id'      => 'general_tab1',
				'label'   => esc_html__( 'Newsletter', 'north' ),
				'type'    => 'tab',
				'section' => 'general',
			),
			array(
				'label'   => esc_html__( 'Display Newsletter Popup?', 'north' ),
				'id'      => 'newsletter',
				'type'    => 'on_off',
				'desc'    => __( 'Would you like to display the Newsletter Popup? You can download the subscribed emails through the theme subscription form here: <br><br> <a href="?thb_download_emails=true" class="button button-primary">Download Emails</a>', 'north' ),
				'std'     => 'on',
				'section' => 'general',
			),
			array(
				'label'     => esc_html__( 'Newsletter refresh interval', 'north' ),
				'id'        => 'newsletter-interval',
				'type'      => 'radio',
				'desc'      => esc_html__( 'When the user closes the popup, the newsletter will not be visible on the next page. After the below period, its going to be visible again unless he closes it again', 'north' ),
				'choices'   => array(
					array(
						'label' => esc_html__( 'Never - the popup will be shown every page', 'north' ),
						'value' => '0',
					),
					array(
						'label' => esc_html__( '1 Day', 'north' ),
						'value' => '1',
					),
					array(
						'label' => esc_html__( '2 Days', 'north' ),
						'value' => '2',
					),
					array(
						'label' => esc_html__( '3 Days', 'north' ),
						'value' => '3',
					),
					array(
						'label' => esc_html__( '1 Week', 'north' ),
						'value' => '7',
					),
					array(
						'label' => esc_html__( '2 Weeks', 'north' ),
						'value' => '14',
					),
					array(
						'label' => esc_html__( '3 Weeks', 'north' ),
						'value' => '21',
					),
					array(
						'label' => esc_html__( '1 Month', 'north' ),
						'value' => '30',
					),

				),
				'std'       => '1',
				'section'   => 'general',
				'condition' => 'newsletter:is(on)',
			),
			array(
				'label'        => esc_html__( 'Newsletter Delay', 'north' ),
				'id'           => 'newsletter_delay',
				'std'          => '0',
				'type'         => 'numeric-slider',
				'min_max_step' => '0,120,1',
				'desc'         => esc_html__( 'You can delay the newsletter popup reveal by certain seconds.', 'north' ),
				'section'      => 'general',
				'condition'    => 'newsletter:is(on)',
			),
			array(
				'label'     => esc_html__( 'Newsletter Content', 'north' ),
				'id'        => 'newsletter_content',
				'type'      => 'textarea',
				'desc'      => esc_html__( 'This content appears just above the email form.', 'north' ),
				'rows'      => '4',
				'section'   => 'general',
				'condition' => 'newsletter:is(on)',
			),
			array(
				'label'     => esc_html__( 'Newsletter Image', 'north' ),
				'id'        => 'newsletter_image',
				'type'      => 'upload',
				'class'     => 'ot-upload-attachment-id',
				'desc'      => esc_html__( 'You can add an image to your newsletter if you want. This is optional.', 'north' ),
				'section'   => 'general',
				'condition' => 'newsletter:is(on)',
			),
			array(
				'label'     => esc_html__( 'Newsletter Background', 'north' ),
				'id'        => 'newsletter_bg',
				'type'      => 'background',
				'desc'      => esc_html__( 'You can change the background of the newsletter from here.', 'north' ),
				'section'   => 'general',
				'condition' => 'newsletter:is(on)',
			),
			array(
				'label'   => esc_html__( 'Newsletter Form', 'north' ),
				'id'      => 'newsletter_form',
				'type'    => 'radio',
				'desc'    => esc_html__( 'You can choose to use theme form, or a shortcode.', 'north' ),
				'choices' => array(
					array(
						'label' => esc_html__( 'Theme Form', 'north' ),
						'value' => 'theme-form',
					),
					array(
						'label' => esc_html__( 'Shortcode', 'north' ),
						'value' => 'shortcode',
					),
				),
				'std'     => 'theme-form',
				'section' => 'general',
			),
			array(
				'label'     => esc_html__( 'Newsletter Shortcode', 'north' ),
				'id'        => 'newsletter_form_shortcode',
				'type'      => 'text',
				'section'   => 'general',
				'desc'      => esc_html__( 'Shortcode you want to use on the newsletter', 'north' ),
				'operator'  => 'and',
				'condition' => 'newsletter:is(on),newsletter_form:is(shortcode)',
			),
			array(
				'id'      => 'general_tab2',
				'label'   => esc_html__( 'Social Sharing', 'north' ),
				'type'    => 'tab',
				'section' => 'general',
			),
			array(
				'label'   => esc_html__( 'Sharing buttons', 'north' ),
				'id'      => 'sharing_buttons',
				'type'    => 'checkbox',
				'desc'    => esc_html__( 'You can choose which social networks to display and get counts from.', 'north' ),
				'choices' => array(
					array(
						'label' => esc_html__( 'Facebook', 'north' ),
						'value' => 'facebook',
					),
					array(
						'label' => esc_html__( 'Twitter', 'north' ),
						'value' => 'twitter',
					),
					array(
						'label' => esc_html__( 'Pinterest', 'north' ),
						'value' => 'pinterest',
					),
					array(
						'label' => esc_html__( 'Linkedin', 'north' ),
						'value' => 'linkedin',
					),
					array(
						'label' => esc_html__( 'WhatsApp', 'north' ),
						'value' => 'whatsapp',
					),
				),
				'section' => 'general',
			),
			array(
				'id'      => 'subheader_tab0',
				'label'   => esc_html__( 'Sub-Header Settings', 'north' ),
				'type'    => 'tab',
				'section' => 'subheader',
			),
			array(
				'label'   => esc_html__( 'Display Sub-Header', 'north' ),
				'id'      => 'subheader',
				'type'    => 'on_off',
				'desc'    => esc_html__( 'Would you like to display the Sub-Header?', 'north' ),
				'std'     => 'off',
				'section' => 'subheader',
			),
			array(
				'label'   => esc_html__( 'Sub-Header Full Width', 'north' ),
				'id'      => 'thb_subheader_full_width',
				'type'    => 'on_off',
				'desc'    => esc_html__( 'By default, the subheader on Peak Shops is limited to the grid, you can make it full width here.', 'north' ),
				'std'     => 'off',
				'section' => 'subheader',
			),
			array(
				'label'   => esc_html__( 'Sub-Header Color', 'north' ),
				'id'      => 'subheader_color',
				'type'    => 'radio-image',
				'std'     => 'light',
				'section' => 'subheader',
			),
			array(
				'id'      => 'subheader_tab1',
				'label'   => esc_html__( 'Sub-Header - Left Content', 'north' ),
				'type'    => 'tab',
				'section' => 'subheader',
			),
			array(
				'label'    => esc_html__( 'Sections', 'north' ),
				'id'       => 'subheader_left_sections',
				'type'     => 'list-item',
				'desc'     => esc_html__( 'Add your desired sections here.', 'north' ),
				'settings' => array(
					array(
						'label'   => esc_html__( 'Section Type', 'north' ),
						'id'      => 'section_type',
						'type'    => 'select',
						'choices' => array(
							array(
								'label' => esc_html__( 'Menu', 'north' ),
								'value' => 'menu',
							),
							array(
								'label' => esc_html__( 'Text', 'north' ),
								'value' => 'text',
							),
							array(
								'label' => esc_html__( 'Language Switcher (WPML/Polylang)', 'north' ),
								'value' => 'ls',
							),
							array(
								'label' => esc_html__( 'Currency Switcher (WPML)', 'north' ),
								'value' => 'cs',
							),
						),
					),
					array(
						'label'     => esc_html__( 'Sub-Header Text', 'north' ),
						'id'        => 'text',
						'type'      => 'textarea',
						'desc'      => esc_html__( 'This content appears on the sub-header', 'north' ),
						'rows'      => '4',
						'condition' => 'section_type:is(text)',
					),
					array(
						'label'     => esc_html__( 'Sub-Header Menu', 'north' ),
						'id'        => 'menu',
						'type'      => 'menu_select',
						'desc'      => esc_html__( 'Menu to be displayed on the sub-header', 'north' ),
						'condition' => 'section_type:is(menu)',
					),
				),
				'section'  => 'subheader',
			),
			array(
				'id'      => 'subheader_tab2',
				'label'   => esc_html__( 'Sub-Header - Right Content', 'north' ),
				'type'    => 'tab',
				'section' => 'subheader',
			),
			array(
				'label'    => esc_html__( 'Sections', 'north' ),
				'id'       => 'subheader_right_sections',
				'type'     => 'list-item',
				'desc'     => esc_html__( 'Add your desired sections here.', 'north' ),
				'settings' => array(
					array(
						'label'   => esc_html__( 'Section Type', 'north' ),
						'id'      => 'section_type',
						'type'    => 'select',
						'choices' => array(
							array(
								'label' => esc_html__( 'Menu', 'north' ),
								'value' => 'menu',
							),
							array(
								'label' => esc_html__( 'Text', 'north' ),
								'value' => 'text',
							),
							array(
								'label' => esc_html__( 'Language Switcher (WPML/Polylang)', 'north' ),
								'value' => 'ls',
							),
							array(
								'label' => esc_html__( 'Currency Switcher (WPML)', 'north' ),
								'value' => 'cs',
							),
						),
					),
					array(
						'label'     => esc_html__( 'Sub-Header Text', 'north' ),
						'id'        => 'text',
						'type'      => 'textarea',
						'desc'      => esc_html__( 'This content appears on the sub-header', 'north' ),
						'rows'      => '4',
						'condition' => 'section_type:is(text)',
					),
					array(
						'label'     => esc_html__( 'Sub-Header Menu', 'north' ),
						'id'        => 'menu',
						'type'      => 'menu_select',
						'desc'      => esc_html__( 'Menu to be displayed on the sub-header', 'north' ),
						'condition' => 'section_type:is(menu)',
					),
				),
				'section'  => 'subheader',
			),
			array(
				'id'      => 'header_tab1',
				'label'   => esc_html__( 'Header Settings', 'north' ),
				'type'    => 'tab',
				'section' => 'header',
			),
			array(
				'label'   => esc_html__( 'Header Style', 'north' ),
				'id'      => 'header_style',
				'type'    => 'radio-image',
				'std'     => 'style1',
				'section' => 'header',
			),
			array(
				'label'   => esc_html__( 'Header Full Width', 'north' ),
				'id'      => 'header_fullwidth',
				'type'    => 'on_off',
				'desc'    => esc_html__( 'This will make sure that the header is always full width in large screens.', 'north' ),
				'section' => 'header',
				'std'     => 'off',
			),
			array(
				'label'   => esc_html__( 'Fixed Header Shadow', 'north' ),
				'id'      => 'fixed_header_shadow',
				'type'    => 'select',
				'desc'    => esc_html__( 'You can set your fixed header shadow here.', 'north' ),
				'choices' => array(
					array(
						'label' => esc_html__( 'None', 'north' ),
						'value' => 'thb-shadow-none',
					),
					array(
						'label' => esc_html__( 'Small', 'north' ),
						'value' => 'thb-fixed-shadow-style1',
					),
					array(
						'label' => esc_html__( 'Medium', 'north' ),
						'value' => 'thb-fixed-shadow-style2',
					),
					array(
						'label' => esc_html__( 'Large', 'north' ),
						'value' => 'thb-fixed-shadow-style3',
					),
				),
				'std'     => 'thb-shadow-none',
				'section' => 'header',
			),
			array(
				'id'      => 'header_tab2',
				'label'   => esc_html__( 'Secondary Area Settings', 'north' ),
				'type'    => 'tab',
				'section' => 'header',
			),
			array(
				'label'   => esc_html__( 'Header Language Switcher', 'north' ),
				'id'      => 'thb_ls',
				'type'    => 'on_off',
				'desc'    => esc_html__( 'You can toggle the language switcher here. Requires that you have WPML or PolyLang installed.', 'north' ),
				'std'     => 'on',
				'section' => 'header',
			),
			array(
				'label'   => esc_html__( 'Secondary Area Style', 'north' ),
				'id'      => 'header_secondary_style',
				'type'    => 'radio',
				'desc'    => esc_html__( 'Would you like to display the search icon in the header?', 'north' ),
				'choices' => array(
					array(
						'label' => esc_html__( 'Text', 'north' ),
						'value' => 'style1',
					),
					array(
						'label' => esc_html__( 'Icons', 'north' ),
						'value' => 'style2',
					),
				),
				'std'     => 'style1',
				'section' => 'header',
			),
			array(
				'label'     => esc_html__( 'Icon Color', 'north' ),
				'id'        => 'header_secondary_icon_color',
				'type'      => 'colorpicker',
				'desc'      => esc_html__( 'You can modify the icon colors here.', 'north' ),
				'section'   => 'header',
				'condition' => 'header_secondary_style:is(style2)',
			),
			array(
				'label'     => esc_html__( 'Header Subscribe Button', 'north' ),
				'id'        => 'header_subscribe',
				'type'      => 'on_off',
				'desc'      => esc_html__( 'Would you like to display a subscribe button inside header?', 'north' ),
				'section'   => 'header',
				'std'       => 'on',
				'condition' => 'header_style:is(style5)',
			),
			array(
				'label'   => esc_html__( 'Header Search', 'north' ),
				'id'      => 'header_search',
				'type'    => 'on_off',
				'desc'    => esc_html__( 'Would you like to display the search icon in the header?', 'north' ),
				'section' => 'header',
				'std'     => 'on',
			),
			array(
				'label'     => esc_html__( 'Header My Account Icon', 'north' ),
				'id'        => 'header_myaccount',
				'type'      => 'on_off',
				'desc'      => esc_html__( 'Would you like to display the myaccount icon in the header?', 'north' ),
				'section'   => 'header',
				'std'       => 'on',
				'condition' => 'header_secondary_style:is(style2)',
			),
			array(
				'label'   => esc_html__( 'Header Shopping Cart', 'north' ),
				'id'      => 'header_cart',
				'type'    => 'on_off',
				'desc'    => esc_html__( 'Would you like to display the shopping cart icon in the header', 'north' ),
				'section' => 'header',
				'std'     => 'on',
			),
			array(
				'label'     => esc_html__( 'Quick Cart', 'north' ),
				'id'        => 'header_quick_cart',
				'type'      => 'on_off',
				'desc'      => esc_html__( 'When cart icon is clicked, the cart is revealed from the side. You can disable this behaviour and redirect the user directly to the cart page.', 'north' ),
				'section'   => 'header',
				'std'       => 'on',
				'condition' => 'header_cart:is(on)',
			),
			array(
				'id'      => 'header_tab3',
				'label'   => esc_html__( 'Mobile Menu Settings', 'north' ),
				'type'    => 'tab',
				'section' => 'header',
			),
			array(
				'label'   => esc_html__( 'Mobile Menu Icon Style', 'north' ),
				'id'      => 'mobile_menu_icon_style',
				'type'    => 'radio-image',
				'std'     => 'style1',
				'desc'    => esc_html__( 'You can select a different icon for your mobile menu here.', 'north' ),
				'section' => 'header',
			),
			array(
				'label'   => esc_html__( 'Mobile Menu Icon "MENU" label', 'north' ),
				'id'      => 'mobile_menu_text',
				'type'    => 'on_off',
				'desc'    => esc_html__( 'Would you like to display "Menu" Text next to mobile menu icon?', 'north' ),
				'std'     => 'off',
				'section' => 'header',
			),
			array(
				'label'   => esc_html__( 'Display Search in Mobile Menu?', 'north' ),
				'id'      => 'mobile_menu_search',
				'type'    => 'on_off',
				'desc'    => esc_html__( 'Would you like to display the search field inside the mobile menu?', 'north' ),
				'std'     => 'on',
				'section' => 'header',
			),
			array(
				'id'      => 'header_tab5',
				'label'   => esc_html__( 'Logo Settings', 'north' ),
				'type'    => 'tab',
				'section' => 'header',
			),
			array(
				'label'   => esc_html__( 'Logo Height', 'north' ),
				'id'      => 'logo_height',
				'type'    => 'measurement',
				'desc'    => esc_html__( 'You can modify the logo height from here. This is maximum height, so your logo may get smaller depending on spacing inside header', 'north' ),
				'section' => 'header',
			),
			array(
				'label'   => esc_html__( 'Mobile Logo Height', 'north' ),
				'id'      => 'logo_height_mobile',
				'type'    => 'measurement',
				'desc'    => esc_html__( 'You can modify the logo height for the mobile screens.', 'north' ),
				'section' => 'header',
			),
			array(
				'label'   => esc_html__( 'Logo Upload for Light Backgrounds', 'north' ),
				'id'      => 'logo',
				'type'    => 'upload',
				'desc'    => esc_html__( 'You can upload your own logo here. Since this theme is retina-ready, <strong>please upload a double size image.</strong> The image should be maximum 100 pixels in height.', 'north' ),
				'section' => 'header',
			),
			array(
				'label'   => esc_html__( 'Logo Upload for Dark Backgrounds', 'north' ),
				'id'      => 'logo_dark',
				'type'    => 'upload',
				'desc'    => esc_html__( 'You can upload your own logo here. Since this theme is retina-ready, <strong>please upload a double size image.</strong> The image should be maximum 100 pixels in height.', 'north' ),
				'section' => 'header',
			),
			array(
				'id'      => 'header_tab6',
				'label'   => esc_html__( 'Measurements', 'north' ),
				'type'    => 'tab',
				'section' => 'header',
			),
			array(
				'label'   => esc_html__( 'Header Padding', 'north' ),
				'id'      => 'header_padding',
				'type'    => 'spacing',
				'desc'    => esc_html__( 'This affects header on large screens. The values are in px.', 'north' ),
				'section' => 'header',
			),
			array(
				'label'   => esc_html__( 'Fixed Header Padding', 'north' ),
				'id'      => 'header_padding_fixed',
				'type'    => 'spacing',
				'desc'    => esc_html__( 'This affects the fixed header on large screens. The values are in px.', 'north' ),
				'section' => 'header',
			),
			array(
				'label'   => esc_html__( 'Mobile Header Padding', 'north' ),
				'id'      => 'header_padding_mobile',
				'type'    => 'spacing',
				'desc'    => esc_html__( 'This affects header on mobile screens for both regular and fixed versions.', 'north' ),
				'section' => 'header',
			),
			array(
				'id'      => 'shop_tab0',
				'label'   => esc_html__( 'General', 'north' ),
				'type'    => 'tab',
				'section' => 'shop',
			),
			array(
				'label'   => esc_html__( 'Catalog Mode', 'north' ),
				'id'      => 'shop_catalog_mode',
				'type'    => 'on_off',
				'desc'    => esc_html__( 'If enabled, this will hide add to cart buttons and prices along the site.', 'north' ),
				'section' => 'shop',
				'std'     => 'off',
			),
			array(
				'label'   => esc_html__( 'Product Listing Style', 'north' ),
				'id'      => 'shop_product_listing',
				'type'    => 'radio-image',
				'std'     => 'style1',
				'section' => 'shop',
			),
			array(
				'label'   => esc_html__( 'Product Listing Button Style', 'north' ),
				'id'      => 'shop_product_listing_button',
				'type'    => 'radio-image',
				'std'     => 'style4',
				'section' => 'shop',
			),
			array(
				'label'     => esc_html__( 'Add to Cart Button Color', 'north' ),
				'id'        => 'shop_product_listing_button_color',
				'type'      => 'radio',
				'desc'      => esc_html__( 'You can change add to cart button color here.', 'north' ),
				'choices'   => array(
					array(
						'label' => esc_html__( 'Black', 'north' ),
						'value' => 'black',
					),
					array(
						'label' => esc_html__( 'White', 'north' ),
						'value' => 'white',
					),
				),
				'std'       => 'black',
				'section'   => 'shop',
				'operator'  => 'or',
				'condition' => 'shop_product_listing_button:is(style1),shop_product_listing_button:is(style2)',
			),
			array(
				'label'   => esc_html__( 'Product Quick View', 'north' ),
				'id'      => 'shop_product_quickview',
				'type'    => 'on_off',
				'desc'    => esc_html__( 'When enabled, this shows the quick view button on products.', 'north' ),
				'section' => 'shop',
				'std'     => 'on',
			),
			array(
				'label'   => esc_html__( 'Display Product Category?', 'north' ),
				'id'      => 'shop_product_listing_category',
				'type'    => 'on_off',
				'desc'    => esc_html__( 'If enabled, this will display product category on listing pages.', 'north' ),
				'section' => 'shop',
				'std'     => 'off',
			),
			array(
				'label'   => esc_html__( 'Display Product Rating?', 'north' ),
				'id'      => 'shop_product_listing_rating',
				'type'    => 'on_off',
				'desc'    => esc_html__( 'If enabled, this will display product rating on listing pages.', 'north' ),
				'section' => 'shop',
				'std'     => 'off',
			),
			array(
				'label'   => esc_html__( 'Display Attribute/Swatch?', 'north' ),
				'id'      => 'shop_product_listing_attribute_display',
				'type'    => 'on_off',
				'desc'    => esc_html__( 'If enabled, this will show the selected attribute on listing pages.', 'north' ),
				'section' => 'shop',
				'std'     => 'off',
			),
			array(
				'label'     => esc_html__( 'Select Attribute to Display', 'north' ),
				'id'        => 'shop_product_listing_variation',
				'type'      => 'attribute_select',
				'desc'      => esc_html__( 'Select Attribute to display. How it is displayed depends on the Attribute Type on Edit Attribute page. The swatches html is cached for 1 hour for performance reasons.', 'north' ),
				'section'   => 'shop',
				'condition' => 'shop_product_listing_attribute_display:is(on)',
			),
			array(
				'label'   => esc_html__( 'Product Listing Layout', 'north' ),
				'id'      => 'shop_product_listing_layout',
				'type'    => 'radio',
				'desc'    => esc_html__( 'Which layout would you like to use on listing pages?', 'north' ),
				'choices' => array(
					array(
						'label' => esc_html__( 'Grid', 'north' ),
						'value' => 'style1',
					),
					array(
						'label' => esc_html__( 'Alternating - 3 / 4 columns', 'north' ),
						'value' => 'style2',
					),
					array(
						'label' => esc_html__( 'Alternating - 4 / 5 columns', 'north' ),
						'value' => 'style3',
					),
					array(
						'label' => esc_html__( 'Alternating - 5 / 6 columns', 'north' ),
						'value' => 'style4',
					),
					array(
						'label' => esc_html__( 'Alternating - 4 / 3 columns', 'north' ),
						'value' => 'style5',
					),
					array(
						'label' => esc_html__( 'Alternating - 5 / 4 columns', 'north' ),
						'value' => 'style6',
					),
					array(
						'label' => esc_html__( 'Alternating - 6 / 5 columns', 'north' ),
						'value' => 'style7',
					),
					array(
						'label' => esc_html__( 'Alternating - 4 / 4 / 3 columns', 'north' ),
						'value' => 'style8',
					),
				),
				'std'     => 'style1',
				'section' => 'shop',
			),
			array(
				'label'     => esc_html__( 'Products Per Row', 'north' ),
				'id'        => 'products_per_row',
				'std'       => 'thb-5',
				'type'      => 'radio',
				'choices'   => array(
					array(
						'label' => esc_html__( '2 Columns', 'north' ),
						'value' => 'large-6',
					),
					array(
						'label' => esc_html__( '3 Columns', 'north' ),
						'value' => 'large-4',
					),
					array(
						'label' => esc_html__( '4 Columns', 'north' ),
						'value' => 'large-3',
					),
					array(
						'label' => esc_html__( '5 Columns', 'north' ),
						'value' => 'thb-5',
					),
					array(
						'label' => esc_html__( '6 Columns', 'north' ),
						'value' => 'large-2',
					),
				),
				'section'   => 'shop',
				'condition' => 'shop_product_listing_layout:is(style1)',
			),
			array(
				'label'   => esc_html__( 'Product Pagination Style', 'north' ),
				'id'      => 'shop_product_listing_pagination',
				'type'    => 'radio',
				'desc'    => esc_html__( 'Which pagination syle would you like to use on shop page?', 'north' ),
				'choices' => array(
					array(
						'label' => esc_html__( 'Regular Pagination', 'north' ),
						'value' => 'style1',
					),
					array(
						'label' => esc_html__( 'Load More Button', 'north' ),
						'value' => 'style2',
					),
					array(
						'label' => esc_html__( 'Infinite Load', 'north' ),
						'value' => 'style3',
					),
				),
				'std'     => 'style1',
				'section' => 'shop',
			),
			array(
				'label'   => esc_html__( 'Products Per Page', 'north' ),
				'id'      => 'products_per_page',
				'type'    => 'text',
				'section' => 'shop',
				'std'     => '12',
			),
			array(
				'label'   => esc_html__( 'Product Hover Image', 'north' ),
				'id'      => 'shop_product_hover',
				'type'    => 'on_off',
				'desc'    => esc_html__( 'When enabled, this shows a secondary product image on hover.', 'north' ),
				'section' => 'shop',
				'std'     => 'on',
			),
			array(
				'id'      => 'shop_tab2',
				'label'   => esc_html__( 'Product Page', 'north' ),
				'type'    => 'tab',
				'section' => 'shop',
			),
			array(
				'label'   => esc_html__( 'Product Page Style', 'north' ),
				'id'      => 'shop_product_style',
				'type'    => 'radio-image',
				'std'     => 'style1',
				'section' => 'shop',
			),
			array(
				'label'     => esc_html__( 'Thumbnail Layout', 'north' ),
				'id'        => 'shop_product_thumbnail_layout',
				'type'      => 'radio-image',
				'std'       => 'style1',
				'section'   => 'shop',
				'operator'  => 'OR',
				'condition' => 'shop_product_style:is(style1),shop_product_style:is(style3)',
			),
			array(
				'label'   => esc_html__( 'Tabs Style', 'north' ),
				'id'      => 'shop_product_tabs_style',
				'type'    => 'radio-image',
				'std'     => 'style1',
				'section' => 'shop',
			),
			array(
				'label'     => esc_html__( 'Enable Sidebar?', 'north' ),
				'id'        => 'shop_product_sidebar',
				'type'      => 'on_off',
				'desc'      => esc_html__( 'Would you like to display a sidebar on product pages?', 'north' ),
				'std'       => 'off',
				'section'   => 'shop',
				'operator'  => 'OR',
				'condition' => 'shop_product_style:is(style1),shop_product_style:is(style2),shop_product_style:is(style3),shop_product_style:is(style4)',
			),
			array(
				'label'   => esc_html__( 'Breadcrumbs', 'north' ),
				'id'      => 'shop_product_breadcrumbs',
				'type'    => 'on_off',
				'desc'    => esc_html__( 'Would you like to display breadcrumbs on Product Pages?', 'north' ),
				'std'     => 'on',
				'section' => 'shop',
			),
			array(
				'label'   => esc_html__( 'Use Ajax Add To Cart?', 'north' ),
				'id'      => 'shop_product_ajax_addtocart',
				'type'    => 'on_off',
				'desc'    => esc_html__( 'Would you like to use Ajax Add to Cart on product pages?', 'north' ),
				'std'     => 'on',
				'section' => 'shop',
			),
			array(
				'label'   => esc_html__( 'Use Lightbox or Zoom?', 'north' ),
				'id'      => 'shop_product_lightbox',
				'type'    => 'radio',
				'desc'    => esc_html__( 'Would you like to use a lightbox or a mouse hover zoom?', 'north' ),
				'choices' => array(
					array(
						'label' => esc_html__( 'Lightbox', 'north' ),
						'value' => 'lightbox',
					),
					array(
						'label' => esc_html__( 'Zoom', 'north' ),
						'value' => 'zoom',
					),
					array(
						'label' => esc_html__( 'None', 'north' ),
						'value' => 'none',
					),
				),
				'std'     => 'lightbox',
				'section' => 'shop',
			),
			array(
				'id'      => 'shop_tab3',
				'label'   => esc_html__( 'Quick Shop', 'north' ),
				'type'    => 'tab',
				'section' => 'shop',
			),
			array(
				'label'   => esc_html__( 'Display Quick Shop', 'north' ),
				'id'      => 'quick_shop',
				'type'    => 'on_off',
				'desc'    => esc_html__( 'Would you like to display the Quick Shop link?', 'north' ),
				'std'     => 'on',
				'section' => 'shop',
			),
			array(
				'label'     => esc_html__( 'Display Category Filter', 'north' ),
				'id'        => 'quick_shop_categories',
				'type'      => 'on_off',
				'desc'      => esc_html__( 'Would you like to display the category filter?', 'north' ),
				'std'       => 'on',
				'section'   => 'shop',
				'condition' => 'quick_shop:is(on)',
			),
			array(
				'label'     => esc_html__( 'Number of Products', 'north' ),
				'id'        => 'quick_shop_count',
				'type'      => 'text',
				'section'   => 'shop',
				'std'       => '8',
				'condition' => 'quick_shop:is(on)',
			),
			array(
				'id'      => 'shop_tab4',
				'label'   => esc_html__( 'Shop Header', 'north' ),
				'type'    => 'tab',
				'section' => 'shop',
			),
			array(
				'label'   => esc_html__( 'Shop Page Header Style', 'north' ),
				'id'      => 'shop_header_style',
				'type'    => 'radio',
				'desc'    => esc_html__( 'This changes the look of the header on main shop page.', 'north' ),
				'choices' => array(
					array(
						'label' => esc_html__( 'Just Text', 'north' ),
						'value' => 'style1',
					),
					array(
						'label' => esc_html__( 'With Image', 'north' ),
						'value' => 'style2',
					),

				),
				'std'     => 'style1',
				'section' => 'shop',
			),
			array(
				'label'     => esc_html__( 'Shop Header Background', 'north' ),
				'id'        => 'shop_header_bg',
				'type'      => 'background',
				'desc'      => esc_html__( 'Background settings for the shop header', 'north' ),
				'section'   => 'shop',
				'condition' => 'shop_header_style:is(style2)',
			),
			array(
				'label'     => esc_html__( 'Shop Header Color', 'north' ),
				'id'        => 'shop_menu_color',
				'type'      => 'radio-image',
				'desc'      => esc_html__( 'What color would you like to display for the header? <small>You can change category headers on individual Edit Category pages</small>', 'north' ),
				'std'       => 'light-title',
				'section'   => 'shop',
				'condition' => 'shop_header_style:is(style2)',
			),
			array(
				'id'      => 'shop_tab5',
				'label'   => esc_html__( 'Misc', 'north' ),
				'type'    => 'tab',
				'section' => 'shop',
			),
			array(
				'label'   => esc_html__( 'Product "Just Arrived" Badge time', 'north' ),
				'id'      => 'shop_newness',
				'type'    => 'radio',
				'desc'    => esc_html__( 'Products that are added before the below time will display the new product page', 'north' ),
				'choices' => array(
					array(
						'label' => esc_html__( 'Never - "Just Arrived" Badge will never be shown', 'north' ),
						'value' => '0',
					),
					array(
						'label' => esc_html__( '1 Day', 'north' ),
						'value' => '1',
					),
					array(
						'label' => esc_html__( '2 Days', 'north' ),
						'value' => '2',
					),
					array(
						'label' => esc_html__( '3 Days', 'north' ),
						'value' => '3',
					),
					array(
						'label' => esc_html__( '1 Week', 'north' ),
						'value' => '7',
					),
					array(
						'label' => esc_html__( '2 Weeks', 'north' ),
						'value' => '14',
					),
					array(
						'label' => esc_html__( '3 Weeks', 'north' ),
						'value' => '21',
					),
					array(
						'label' => esc_html__( '1 Month', 'north' ),
						'value' => '30',
					),
				),
				'std'     => '7',
				'section' => 'shop',
			),
			array(
				'id'      => 'blog_tab1',
				'label'   => esc_html__( 'General Blog Settings', 'north' ),
				'type'    => 'tab',
				'section' => 'blog',
			),
			array(
				'label'   => esc_html__( 'Blog Style', 'north' ),
				'id'      => 'blog_style',
				'type'    => 'radio',
				'desc'    => esc_html__( 'You can choose different blog styles here', 'north' ),
				'choices' => array(
					array(
						'label' => esc_html__( 'Style 1 - Masonry', 'north' ),
						'value' => 'style1',
					),
					array(
						'label' => esc_html__( 'Style 2 - Vertical', 'north' ),
						'value' => 'style2',
					),
					array(
						'label' => esc_html__( 'Style 3 - List', 'north' ),
						'value' => 'style3',
					),
					array(
						'label' => esc_html__( 'Style 4 - Grid', 'north' ),
						'value' => 'style4',
					),
				),
				'std'     => 'style1',
				'section' => 'blog',
			),
			array(
				'label'   => esc_html__( 'Blog Pagination Style', 'north' ),
				'id'      => 'blog_pagination_style',
				'type'    => 'radio',
				'desc'    => esc_html__( 'You can choose different blog pagination styles here. The regular pagination will be used for archive pages.', 'north' ),
				'choices' => array(
					array(
						'label' => esc_html__( 'Regular Pagination', 'north' ),
						'value' => 'style1',
					),
					array(
						'label' => esc_html__( 'Load More Button', 'north' ),
						'value' => 'style2',
					),
					array(
						'label' => esc_html__( 'Infinite Scroll', 'north' ),
						'value' => 'style3',
					),
				),
				'std'     => 'style1',
				'section' => 'blog',
			),
			array(
				'id'      => 'blog_tab2',
				'label'   => esc_html__( 'Article Settings', 'north' ),
				'type'    => 'tab',
				'section' => 'blog',
			),
			array(
				'label'   => esc_html__( 'Display Category?', 'north' ),
				'id'      => 'article_cat',
				'type'    => 'on_off',
				'desc'    => esc_html__( 'Displays article category over article title', 'north' ),
				'std'     => 'on',
				'section' => 'blog',
			),
			array(
				'label'   => esc_html__( 'Display Author Name?', 'north' ),
				'id'      => 'article_author_name',
				'type'    => 'on_off',
				'desc'    => esc_html__( 'This hides the Author Name below the article title.', 'north' ),
				'std'     => 'on',
				'section' => 'blog',
			),
			array(
				'label'   => esc_html__( 'Display Article Date?', 'north' ),
				'id'      => 'article_date',
				'type'    => 'on_off',
				'desc'    => esc_html__( 'This hides the Author Date below the article title.', 'north' ),
				'std'     => 'on',
				'section' => 'blog',
			),
			array(
				'label'   => esc_html__( 'Display Tags?', 'north' ),
				'id'      => 'article_tags',
				'type'    => 'on_off',
				'desc'    => esc_html__( 'Displays article tags at the bottom', 'north' ),
				'std'     => 'on',
				'section' => 'blog',
			),
			array(
				'label'   => esc_html__( 'Display Article Navigation?', 'north' ),
				'id'      => 'blog_nav',
				'type'    => 'on_off',
				'desc'    => esc_html__( 'Displays article navigation at the bottom', 'north' ),
				'std'     => 'on',
				'section' => 'blog',
			),
			array(
				'id'      => 'misc_tab1',
				'label'   => esc_html__( 'General', 'north' ),
				'type'    => 'tab',
				'section' => 'misc',
			),
			array(
				'label'   => esc_html__( 'Use Combined JavaScript Library?', 'north' ),
				'id'      => 'thb_combined_libraries',
				'type'    => 'on_off',
				'desc'    => esc_html__( 'When disabled, each javascript library will be loaded on its own. It will allow for greater control using plugins, but may hinder site speed as multiple files will be loaded instead of one.', 'north' ),
				'std'     => 'on',
				'section' => 'misc',
			),
			array(
				'label'   => esc_html__( 'Google Maps API Key', 'north' ),
				'id'      => 'map_api_key',
				'type'    => 'text',
				'desc'    => esc_html__( 'Please enter the Google Maps Api Key. <small>You need to create a browser API key. For more information, please visit: <a href="https://developers.google.com/maps/documentation/javascript/get-api-key">https://developers.google.com/maps/documentation/javascript/get-api-key</a></small>', 'north' ),
				'section' => 'misc',
			),
			array(
				'label'   => esc_html__( 'Extra CSS', 'north' ),
				'id'      => 'extra_css',
				'type'    => 'css',
				'desc'    => esc_html__( 'Any CSS that you would like to add to the theme.', 'north' ),
				'section' => 'misc',
			),
			array(
				'id'      => 'misc_tab3',
				'label'   => esc_html__( 'Create Additional Sidebars', 'north' ),
				'type'    => 'tab',
				'section' => 'misc',
			),
			array(
				'id'      => 'sidebars_text',
				'label'   => esc_html__( 'About the sidebars', 'north' ),
				'desc'    => esc_html__( 'All sidebars that you create here will appear both in the Widgets Page(Appearance > Widgets), from where you will have to configure them, and in the pages, where you will be able to choose a sidebar for each page', 'north' ),
				'type'    => 'textblock',
				'section' => 'misc',
			),
			array(
				'label'    => esc_html__( 'Create Sidebars', 'north' ),
				'id'       => 'sidebars',
				'type'     => 'list-item',
				'desc'     => esc_html__( 'Please choose a unique title for each sidebar!', 'north' ),
				'section'  => 'misc',
				'settings' => array(
					array(
						'label' => esc_html__( 'ID', 'north' ),
						'id'    => 'id',
						'type'  => 'text',
						'desc'  => esc_html__( 'Please write a lowercase id, with <strong>no spaces</strong>', 'north' ),
					),
				),
			),
			array(
				'id'      => 'typography_tab0',
				'label'   => esc_html__( 'Font Families', 'north' ),
				'type'    => 'tab',
				'section' => 'typography',
			),
			array(
				'id'      => 'font_cache_text',
				'label'   => esc_html__( 'Clear Font Cache', 'north' ),
				'desc'    => __( 'If you cant find Google Webfonts inside the boxes below, you can clear your transient for the font cache here: <br><br> <a href="themes.php?page=ot-theme-options&thb_clear_font_cache=true" class="button button-primary">Clear Font Cache</a>', 'north' ),
				'type'    => 'textblock',
				'section' => 'typography',
			),
			array(
				'label'   => esc_html__( 'Primary Font', 'north' ),
				'id'      => 'title_type',
				'type'    => 'typography',
				'desc'    => esc_html__( 'Font Family for the titles & Headings', 'north' ),
				'section' => 'typography',
			),
			array(
				'label'   => esc_html__( 'Secondary Font', 'north' ),
				'id'      => 'body_type',
				'type'    => 'typography',
				'desc'    => esc_html__( 'Font Family for the other text.', 'north' ),
				'section' => 'typography',
			),
			array(
				'label'   => esc_html__( 'Menu Font', 'north' ),
				'id'      => 'menu_font',
				'type'    => 'typography',
				'desc'    => esc_html__( 'Font Family for the full menu.', 'north' ),
				'section' => 'typography',
			),
			array(
				'label'   => esc_html__( 'Button Font', 'north' ),
				'id'      => 'button_font',
				'type'    => 'typography',
				'desc'    => esc_html__( 'Font Family for the buttons.', 'north' ),
				'section' => 'typography',
			),
			array(
				'label'   => esc_html__( '<EM> Font', 'north' ),
				'id'      => 'em_font',
				'type'    => 'typography',
				'desc'    => esc_html__( 'This adds a separate font for styling of EM tags so you can add stylish typographic elements.', 'north' ),
				'section' => 'typography',
			),
			array(
				'label'   => esc_html__( '<label> Font', 'north' ),
				'id'      => 'label_font',
				'type'    => 'typography',
				'desc'    => esc_html__( 'Font Family for the <label> elements', 'north' ),
				'section' => 'typography',
			),
			array(
				'label'   => esc_html__( 'Widget Title Font', 'north' ),
				'id'      => 'widget_title_font',
				'type'    => 'typography',
				'desc'    => esc_html__( 'Font Family for the widget titles.', 'north' ),
				'section' => 'typography',
			),
			array(
				'id'      => 'typography_tab1',
				'label'   => esc_html__( 'Typography', 'north' ),
				'type'    => 'tab',
				'section' => 'typography',
			),
			array(
				'label'   => esc_html__( 'Main Menu Typography', 'north' ),
				'id'      => 'menu_left_type',
				'type'    => 'typography',
				'desc'    => esc_html__( 'Font Settings for the main menu', 'north' ),
				'section' => 'typography',
			),
			array(
				'label'   => esc_html__( 'Main Menu Submenu Typography', 'north' ),
				'id'      => 'menu_left_submenu_type',
				'type'    => 'typography',
				'desc'    => esc_html__( 'Font Settings for the submenu elements of the main menu', 'north' ),
				'section' => 'typography',
			),
			array(
				'label'   => esc_html__( 'Secondary Menu Typography', 'north' ),
				'id'      => 'menu_right_type',
				'type'    => 'typography',
				'desc'    => esc_html__( 'Font Settings for the secondary menu on the right', 'north' ),
				'section' => 'typography',
			),
			array(
				'label'   => esc_html__( 'Mobile Menu Typography', 'north' ),
				'id'      => 'menu_mobile_type',
				'type'    => 'typography',
				'desc'    => esc_html__( 'Font Settings for the mobile menu', 'north' ),
				'section' => 'typography',
			),
			array(
				'label'   => esc_html__( 'Mobile Menu Submenu Typography', 'north' ),
				'id'      => 'menu_mobile_submenu_type',
				'type'    => 'typography',
				'desc'    => esc_html__( 'Font Settings for the submenu elements of the mobile menu', 'north' ),
				'section' => 'typography',
			),
			array(
				'label'   => esc_html__( 'Secondary Mobile Menu Typography', 'north' ),
				'id'      => 'menu_mobile_secondary_type',
				'type'    => 'typography',
				'desc'    => esc_html__( 'Font Settings for the secondary mobile menu', 'north' ),
				'section' => 'typography',
			),
			array(
				'label'   => esc_html__( 'Global Notification Typography', 'north' ),
				'id'      => 'global_notification_type',
				'type'    => 'typography',
				'desc'    => esc_html__( 'Font Settings for the global notification. Uses the Body Font by default', 'north' ),
				'section' => 'typography',
			),
			array(
				'label'   => esc_html__( 'Ajax Notification Typography', 'north' ),
				'id'      => 'ajax_notification_type',
				'type'    => 'typography',
				'desc'    => esc_html__( 'Font Settings for the ajax notification that slides into view.', 'north' ),
				'section' => 'typography',
			),
			array(
				'label'   => esc_html__( 'Widget Title Typography', 'north' ),
				'id'      => 'widget_title_type',
				'type'    => 'typography',
				'desc'    => esc_html__( 'Font Settings for the widget titles.', 'north' ),
				'section' => 'typography',
			),
			array(
				'label'   => esc_html__( 'Footer Social Icon Typography', 'north' ),
				'id'      => 'footer_social_type',
				'type'    => 'typography',
				'desc'    => esc_html__( 'Font Settings for the social icons in footer.', 'north' ),
				'section' => 'typography',
			),
			array(
				'label'   => esc_html__( 'Footer Menu Typography', 'north' ),
				'id'      => 'footer_menu_type',
				'type'    => 'typography',
				'desc'    => esc_html__( 'Font Settings for the menu in footer.', 'north' ),
				'section' => 'typography',
			),
			array(
				'label'   => esc_html__( 'Footer Copyright Typography', 'north' ),
				'id'      => 'footer_copyright_type',
				'type'    => 'typography',
				'desc'    => esc_html__( 'Font Settings for the copyright text in footer.', 'north' ),
				'section' => 'typography',
			),
			array(
				'id'      => 'typography_tab2',
				'label'   => esc_html__( 'Heading Typography', 'north' ),
				'type'    => 'tab',
				'section' => 'typography',
			),
			array(
				'id'      => 'heading_text',
				'label'   => esc_html__( 'About Heading Typography', 'north' ),
				'desc'    => esc_html__( 'These affect all h* tags inside the theme, so use wisely. Some particular headings may need additional css to target.', 'north' ),
				'type'    => 'textblock',
				'section' => 'typography',
			),
			array(
				'label'   => esc_html__( 'Heading 1', 'north' ),
				'id'      => 'h1_type',
				'type'    => 'typography',
				'desc'    => esc_html__( 'Font Settings for the H1 tag', 'north' ),
				'section' => 'typography',
			),
			array(
				'label'   => esc_html__( 'Heading 2', 'north' ),
				'id'      => 'h2_type',
				'type'    => 'typography',
				'desc'    => esc_html__( 'Font Settings for the H2 tag', 'north' ),
				'section' => 'typography',
			),
			array(
				'label'   => esc_html__( 'Heading 3', 'north' ),
				'id'      => 'h3_type',
				'type'    => 'typography',
				'desc'    => esc_html__( 'Font Settings for the H3 tag', 'north' ),
				'section' => 'typography',
			),
			array(
				'label'   => esc_html__( 'Heading 4', 'north' ),
				'id'      => 'h4_type',
				'type'    => 'typography',
				'desc'    => esc_html__( 'Font Settings for the H4 tag', 'north' ),
				'section' => 'typography',
			),
			array(
				'label'   => esc_html__( 'Heading 5', 'north' ),
				'id'      => 'h5_type',
				'type'    => 'typography',
				'desc'    => esc_html__( 'Font Settings for the H5 tag', 'north' ),
				'section' => 'typography',
			),
			array(
				'label'   => esc_html__( 'Heading 6', 'north' ),
				'id'      => 'h6_type',
				'type'    => 'typography',
				'desc'    => esc_html__( 'Font Settings for the H6 tag', 'north' ),
				'section' => 'typography',
			),
			array(
				'id'      => 'typography_tab3',
				'label'   => esc_html__( 'Shop Page Typography', 'north' ),
				'type'    => 'tab',
				'section' => 'typography',
			),
			array(
				'label'   => esc_html__( 'Shop Title', 'north' ),
				'id'      => 'shop_title',
				'type'    => 'typography',
				'desc'    => esc_html__( 'Typography Setting for the shop title on Shop, Cart, Checkout, etc pages.', 'north' ),
				'section' => 'typography',
			),
			array(
				'label'   => esc_html__( 'Category Title', 'north' ),
				'id'      => 'shop_product_category_title',
				'type'    => 'typography',
				'desc'    => esc_html__( 'Typography Setting for the category titles for products', 'north' ),
				'section' => 'typography',
			),
			array(
				'label'   => esc_html__( 'Product Title', 'north' ),
				'id'      => 'shop_product_title',
				'type'    => 'typography',
				'desc'    => esc_html__( 'Typography Setting for the product titles on shop pages.', 'north' ),
				'section' => 'typography',
			),
			array(
				'label'   => esc_html__( 'Product Price', 'north' ),
				'id'      => 'shop_product_price',
				'type'    => 'typography',
				'desc'    => esc_html__( 'Typography Setting for the product prices.', 'north' ),
				'section' => 'typography',
			),
			array(
				'id'      => 'typography_tab4',
				'label'   => esc_html__( 'Product Page Typography', 'north' ),
				'type'    => 'tab',
				'section' => 'typography',
			),
			array(
				'label'   => esc_html__( 'Product Title', 'north' ),
				'id'      => 'shop_product_detail_title',
				'type'    => 'typography',
				'desc'    => esc_html__( 'Typography Setting for the product titles on product pages.', 'north' ),
				'section' => 'typography',
			),
			array(
				'label'   => esc_html__( 'Product Price', 'north' ),
				'id'      => 'shop_product_detail_price',
				'type'    => 'typography',
				'desc'    => esc_html__( 'Typography Setting for the product price on product pages.', 'north' ),
				'section' => 'typography',
			),
			array(
				'label'   => esc_html__( 'Product Excerpt Text', 'north' ),
				'id'      => 'shop_product_detail_excerpt',
				'type'    => 'typography',
				'desc'    => esc_html__( 'Typography Setting for the product excerpt on product pages.', 'north' ),
				'section' => 'typography',
			),
			array(
				'id'      => 'typography_tab6',
				'label'   => esc_html__( 'Font Support', 'north' ),
				'type'    => 'tab',
				'section' => 'typography',
			),
			array(
				'label'   => esc_html__( 'Google WebFont Subsets', 'north' ),
				'id'      => 'font_subsets',
				'type'    => 'radio',
				'desc'    => esc_html__( 'You can add additional character subset specific to your language.', 'north' ),
				'choices' => array(
					array(
						'label' => esc_html__( 'No Subset', 'north' ),
						'value' => 'no-subset',
					),
					array(
						'label' => esc_html__( 'Latin Extended', 'north' ),
						'value' => 'latin-ext',
					),
					array(
						'label' => esc_html__( 'Greek', 'north' ),
						'value' => 'greek',
					),
					array(
						'label' => esc_html__( 'Cyrillic', 'north' ),
						'value' => 'cyrillic',
					),
					array(
						'label' => esc_html__( 'Vietnamese', 'north' ),
						'value' => 'vietnamese',
					),
				),
				'std'     => 'no-subset',
				'section' => 'typography',
			),
			array(
				'id'      => 'typekit_text',
				'label'   => esc_html__( 'About Typekit Support', 'north' ),
				'desc'    => esc_html__( 'Please make sure that you enter your Typekit ID or the fonts wont work. After adding Typekit Font Names, these names will appear on the font selection dropdown on the Typography tab.', 'north' ),
				'std'     => '',
				'type'    => 'textblock',
				'section' => 'typography',
			),
			array(
				'label'   => esc_html__( 'Typekit Kit ID', 'north' ),
				'id'      => 'typekit_id',
				'type'    => 'text',
				'desc'    => esc_html__( 'Paste the provided Typekit Kit ID. <small>Usually 6-7 random letters</small>', 'north' ),
				'section' => 'typography',
			),
			array(
				'label'   => esc_html__( 'Typekit Font Names', 'north' ),
				'id'      => 'typekit_fonts',
				'type'    => 'text',
				'desc'    => esc_html__( 'Enter your Typekit Font Name, seperated by comma. For example: futura-pt,aktiv-grotesk <strong>Do not leave spaces between commas</strong>', 'north' ),
				'section' => 'typography',
			),
			array(
				'label'    => esc_html__( 'Self Hosted Fonts', 'north' ),
				'id'       => 'self_hosted_fonts',
				'type'     => 'list-item',
				'settings' => array(
					array(
						'label'   => esc_html__( 'Font Stylesheet URL', 'north' ),
						'id'      => 'font_url',
						'type'    => 'text',
						'desc'    => esc_html__( 'URL of the font stylesheet (.css file) you want to use.', 'north' ),
						'section' => 'typography',
					),
					array(
						'label'   => esc_html__( 'Font Family Names', 'north' ),
						'id'      => 'font_name',
						'type'    => 'text',
						'desc'    => esc_html__( 'Enter your Font Family Name, use the name that will be used in css. For example: futura-pt, aktiv-grotesk. After saving, you will be able to use this name in the typography settings.', 'north' ),
						'section' => 'typography',
					),
				),
				'section'  => 'typography',
			),
			array(
				'id'      => 'customization_tab1',
				'label'   => esc_html__( 'Colors', 'north' ),
				'type'    => 'tab',
				'section' => 'customization',
			),
			array(
				'label'   => esc_html__( 'Sub-Header Text Color', 'north' ),
				'id'      => 'subheader_text_color',
				'type'    => 'colorpicker',
				'desc'    => esc_html__( 'You can change the sub-header text color here.', 'north' ),
				'section' => 'customization',
			),
			array(
				'label'   => esc_html__( 'Widget Title Color', 'north' ),
				'id'      => 'widget_title_color',
				'type'    => 'colorpicker',
				'desc'    => esc_html__( 'You can change the color of the widget titles here.', 'north' ),
				'section' => 'customization',
			),
			array(
				'label'   => esc_html__( 'Footer Text Color', 'north' ),
				'id'      => 'footer_text_color',
				'type'    => 'colorpicker',
				'desc'    => esc_html__( 'You can modify the footer text color here', 'north' ),
				'class'   => 'ot-colorpicker-opacity',
				'section' => 'customization',
			),
			array(
				'id'      => 'customization_tab2',
				'label'   => esc_html__( 'Link Colors', 'north' ),
				'type'    => 'tab',
				'section' => 'customization',
			),
			array(
				'label'   => esc_html__( 'Sub-Header Link Color', 'north' ),
				'id'      => 'subheader_link_color',
				'type'    => 'link_color',
				'class'   => 'ot-colorpicker-opacity',
				'desc'    => esc_html__( 'You can modify the link colors inside Sub-Header', 'north' ),
				'section' => 'customization',
			),
			array(
				'label'   => esc_html__( 'Full Menu Link Color', 'north' ),
				'id'      => 'fullmenu_link_color_dark',
				'type'    => 'link_color',
				'class'   => 'ot-colorpicker-opacity',
				'desc'    => esc_html__( 'You can modify the link color of the full menu.', 'north' ),
				'section' => 'customization',
			),
			array(
				'label'   => esc_html__( 'Full Menu Light Link Color', 'north' ),
				'id'      => 'fullmenu_link_color_light',
				'type'    => 'link_color',
				'class'   => 'ot-colorpicker-opacity',
				'desc'    => esc_html__( 'This will change the colors only when the light header is used (default links are white)', 'north' ),
				'section' => 'customization',
			),
			array(
				'label'   => esc_html__( 'Sub-Menu Link Color', 'north' ),
				'id'      => 'submenu_link_color',
				'type'    => 'link_color',
				'class'   => 'ot-colorpicker-opacity',
				'desc'    => esc_html__( 'You can modify the link colors inside the sub-menus of the full menu.', 'north' ),
				'section' => 'customization',
			),
			array(
				'label'   => esc_html__( 'Mobile Menu Link Color', 'north' ),
				'id'      => 'mobilemenu_link_color',
				'type'    => 'link_color',
				'class'   => 'ot-colorpicker-opacity',
				'desc'    => esc_html__( 'You can modify the link color of the mobile menu.', 'north' ),
				'section' => 'customization',
			),
			array(
				'label'   => esc_html__( 'Mobile Menu Sub-Menu Link Color', 'north' ),
				'id'      => 'mobilemenu_submenu_link_color',
				'type'    => 'link_color',
				'class'   => 'ot-colorpicker-opacity',
				'desc'    => esc_html__( 'You can modify the link color of the submenus of the mobile menu.', 'north' ),
				'section' => 'customization',
			),
			array(
				'label'   => esc_html__( 'Footer Link Color', 'north' ),
				'id'      => 'footer_link_color',
				'type'    => 'link_color',
				'class'   => 'ot-colorpicker-opacity',
				'desc'    => esc_html__( 'You can modify the footer link color here.', 'north' ),
				'section' => 'customization',
			),
			array(
				'label'   => esc_html__( 'Footer Social Icon Link Color', 'north' ),
				'id'      => 'footer_social_link_color',
				'type'    => 'link_color',
				'class'   => 'ot-colorpicker-opacity',
				'desc'    => esc_html__( 'You can modify the footer link color for the social icons here.', 'north' ),
				'section' => 'customization',
			),
			array(
				'id'      => 'customization_tab4',
				'label'   => esc_html__( 'Shop Colors', 'north' ),
				'type'    => 'tab',
				'section' => 'customization',
			),
			array(
				'label'   => esc_html__( 'Rating Star Color', 'north' ),
				'id'      => 'rating_star_color',
				'type'    => 'colorpicker',
				'desc'    => esc_html__( 'You can modify the rating start color here.', 'north' ),
				'section' => 'customization',
			),
			array(
				'label'   => esc_html__( 'Price Color', 'north' ),
				'id'      => 'shop_price_color',
				'type'    => 'colorpicker',
				'desc'    => esc_html__( 'You can modify the color of the prices here.', 'north' ),
				'section' => 'customization',
			),
			array(
				'label'   => esc_html__( 'Product Page Product Title Color', 'north' ),
				'id'      => 'product_detail_title_color',
				'type'    => 'colorpicker',
				'desc'    => esc_html__( 'You can change the color of the product title on product detail pages.', 'north' ),
				'section' => 'customization',
			),
			array(
				'id'      => 'customization_tab5',
				'label'   => esc_html__( 'Badge Colors', 'north' ),
				'type'    => 'tab',
				'section' => 'customization',
			),
			array(
				'label'   => esc_html__( 'Just Arrived Badge Color', 'north' ),
				'id'      => 'badge_justarrived',
				'type'    => 'colorpicker',
				'desc'    => esc_html__( 'You can change the just arrived badge color from here', 'north' ),
				'section' => 'customization',
			),
			array(
				'label'   => esc_html__( 'On Sale Badge Color', 'north' ),
				'id'      => 'badge_sale',
				'type'    => 'colorpicker',
				'desc'    => esc_html__( 'You can change the on sale badge color from here', 'north' ),
				'section' => 'customization',
			),
			array(
				'label'   => esc_html__( 'Out of Stock Badge Color', 'north' ),
				'id'      => 'badge_outofstock',
				'type'    => 'colorpicker',
				'desc'    => esc_html__( 'You can change the out of stock badge color from here', 'north' ),
				'section' => 'customization',
			),
			array(
				'id'      => 'customization_tab6',
				'label'   => esc_html__( 'Menu Customization', 'north' ),
				'type'    => 'tab',
				'section' => 'customization',
			),
			array(
				'id'      => 'menu_margin',
				'label'   => esc_html__( 'Top Level Menu Item Margin', 'north' ),
				'desc'    => esc_html__( 'If you want to fit more menu items to the given space, you can decrease the margin between them here. The default margin is 40px', 'north' ),
				'type'    => 'measurement',
				'section' => 'customization',
			),
			array(
				'id'      => 'customization_tab3',
				'label'   => esc_html__( 'Backgrounds', 'north' ),
				'type'    => 'tab',
				'section' => 'customization',
			),
			array(
				'label'   => esc_html__( 'Sub-Header Background', 'north' ),
				'id'      => 'subheader_bg',
				'type'    => 'background',
				'class'   => 'ot-colorpicker-opacity',
				'section' => 'customization',
			),
			array(
				'label'   => esc_html__( 'Header Background', 'north' ),
				'id'      => 'header_bg',
				'type'    => 'background',
				'class'   => 'ot-colorpicker-opacity',
				'section' => 'customization',
			),
			array(
				'label'   => esc_html__( 'Mobile Menu Background', 'north' ),
				'id'      => 'mobilemenu_bg',
				'type'    => 'background',
				'class'   => 'ot-colorpicker-opacity',
				'section' => 'customization',
			),
			array(
				'label'   => esc_html__( 'Side Cart Background', 'north' ),
				'id'      => 'sidecart_bg',
				'type'    => 'background',
				'class'   => 'ot-colorpicker-opacity',
				'section' => 'customization',
			),
			array(
				'label'   => esc_html__( 'Quick Shop Background', 'north' ),
				'id'      => 'quickshop_bg',
				'type'    => 'background',
				'class'   => 'ot-colorpicker-opacity',
				'section' => 'customization',
			),
			array(
				'label'   => esc_html__( 'Footer Background', 'north' ),
				'id'      => 'footer_bg',
				'type'    => 'background',
				'class'   => 'ot-colorpicker-opacity',
				'section' => 'customization',
			),
			array(
				'label'   => esc_html__( 'Search Background', 'north' ),
				'id'      => 'search_bg',
				'type'    => 'background',
				'class'   => 'ot-colorpicker-opacity',
				'section' => 'customization',
			),
			array(
				'label'   => esc_html__( 'Global Notification Background', 'north' ),
				'id'      => 'global_notification_bg',
				'type'    => 'background',
				'class'   => 'ot-colorpicker-opacity',
				'section' => 'customization',
			),
			array(
				'label'   => esc_html__( 'Ajax Notification - Message', 'north' ),
				'id'      => 'ajax_notification_message_bg',
				'type'    => 'background',
				'desc'    => esc_html__( 'Background settings for the ajax notification - message.', 'north' ),
				'section' => 'customization',
			),
			array(
				'label'   => esc_html__( 'Ajax Notification - Info', 'north' ),
				'id'      => 'ajax_notification_info_bg',
				'type'    => 'background',
				'desc'    => esc_html__( 'Background settings for the ajax notification - info.', 'north' ),
				'section' => 'customization',
			),
			array(
				'label'   => esc_html__( 'Ajax Notification - Error', 'north' ),
				'id'      => 'ajax_notification_error_bg',
				'type'    => 'background',
				'desc'    => esc_html__( 'Background settings for the ajax notification - error.', 'north' ),
				'section' => 'customization',
			),
			array(
				'id'      => 'footer_tab1',
				'label'   => esc_html__( 'General', 'north' ),
				'type'    => 'tab',
				'section' => 'footer',
			),
			array(
				'label'   => esc_html__( 'Display Footer', 'north' ),
				'id'      => 'footer',
				'type'    => 'on_off',
				'desc'    => esc_html__( 'Would you like to display the Footer?', 'north' ),
				'std'     => 'on',
				'section' => 'footer',
			),
			array(
				'label'   => esc_html__( 'Footer Style', 'north' ),
				'id'      => 'footer_style',
				'type'    => 'radio-image',
				'std'     => 'style1',
				'section' => 'footer',
			),
			array(
				'label'   => esc_html__( 'Footer Color', 'north' ),
				'id'      => 'footer_color',
				'type'    => 'radio-image',
				'desc'    => esc_html__( 'You can choose your footer color here. You can also change your footer background from "Customization"', 'north' ),
				'std'     => 'light',
				'section' => 'footer',
			),
			array(
				'label'   => esc_html__( 'Footer Full Width', 'north' ),
				'id'      => 'footer_fullwidth',
				'type'    => 'on_off',
				'desc'    => esc_html__( 'This will make sure that the footer is always full width in large screens.', 'north' ),
				'section' => 'footer',
				'std'     => 'off',
			),
			array(
				'label'   => esc_html__( 'Footer Top Content', 'north' ),
				'id'      => 'footer_top_content',
				'type'    => 'page-select',
				'desc'    => esc_html__( 'This allows you to add contents of a page inside the footer.', 'north' ),
				'section' => 'footer',
			),
			array(
				'id'      => 'footer_tab2',
				'label'   => esc_html__( 'Footer Content Settings', 'north' ),
				'type'    => 'tab',
				'section' => 'footer',
			),
			array(
				'id'      => 'simple_footer_text',
				'label'   => esc_html__( 'About the footer content', 'north' ),
				'desc'    => esc_html__( 'These settings are used when Footer Style is set to Style 1 or Style 3', 'north' ),
				'type'    => 'textblock',
				'section' => 'footer',
			),
			array(
				'label'   => esc_html__( 'Copyright Text', 'north' ),
				'id'      => 'copyright',
				'type'    => 'textarea',
				'rows'    => '4',
				'desc'    => esc_html__( 'Copyright Text at the bottom left', 'north' ),
				'section' => 'footer',
			),
			array(
				'label'   => esc_html__( 'Social Links to display', 'north' ),
				'id'      => 'footer_social_icons',
				'type'    => 'social-links',
				'desc'    => esc_html__( 'Add your desired Social Links for the footer here', 'north' ),
				'section' => 'footer',
			),
			array(
				'label'    => esc_html__( 'Payment Icons to display', 'north' ),
				'id'       => 'footer_payment_icons',
				'type'     => 'list-item',
				'desc'     => esc_html__( 'Add your desired Payment Icons for the footer here', 'north' ),
				'settings' => array(
					array(
						'label'   => esc_html__( 'Payment Type', 'north' ),
						'id'      => 'payment_type',
						'type'    => 'select',
						'choices' => array(
							array(
								'label' => 'Visa',
								'value' => 'payment_visa',
							),
							array(
								'label' => 'MasterCard',
								'value' => 'payment_mc',
							),
							array(
								'label' => 'PayPal',
								'value' => 'payment_pp',
							),
							array(
								'label' => 'Discover',
								'value' => 'payment_discover',
							),
							array(
								'label' => 'Amazon Payments',
								'value' => 'payment_amazon',
							),
							array(
								'label' => 'Stripe',
								'value' => 'payment_stripe',
							),
							array(
								'label' => 'American Express',
								'value' => 'payment_amex',
							),
							array(
								'label' => 'Diners Club',
								'value' => 'payment_diners',
							),
							array(
								'label' => 'Google Pay',
								'value' => 'payment_googlepay',
							),
							array(
								'label' => 'Apple Pay',
								'value' => 'payment_applepay',
							),
							array(
								'label' => 'JCB',
								'value' => 'payment_jcb',
							),
							array(
								'label' => 'Wire Card',
								'value' => 'payment_wirecard',
							),
							array(
								'label' => 'Payfast',
								'value' => 'payment_payfast',
							),
						),
					),
				),
				'section'  => 'footer',
			),
			array(
				'label'    => esc_html__( 'Custom Payment Icon', 'north' ),
				'id'       => 'footer_payment_icons_custom',
				'type'     => 'list-item',
				'settings' => array(
					array(
						'label' => esc_html__( 'Icon Image / SVG', 'north' ),
						'id'    => 'icon_image',
						'type'  => 'upload',
						'desc'  => esc_html__( 'Select your payment image / svg.', 'north' ),
					),
				),
				'section'  => 'footer',
			),
			array(
				'id'      => 'footer_tab3',
				'label'   => esc_html__( 'Footer Widget Settings', 'north' ),
				'type'    => 'tab',
				'section' => 'footer',
			),
			array(
				'id'      => 'widgetized_footer_text',
				'label'   => esc_html__( 'About the footer content', 'north' ),
				'desc'    => esc_html__( 'These settings are used when Footer Style is set to Style 2', 'north' ),
				'type'    => 'textblock',
				'section' => 'footer',
			),
			array(
				'label'   => esc_html__( 'Footer Columns', 'north' ),
				'id'      => 'footer_columns',
				'type'    => 'radio-image',
				'desc'    => esc_html__( 'You can change the layout of footer columns here', 'north' ),
				'std'     => 'fourcolumns',
				'section' => 'footer',
			),
			array(
				'id'      => 'footer_tab4',
				'label'   => esc_html__( 'Measurements', 'north' ),
				'type'    => 'tab',
				'section' => 'footer',
			),
			array(
				'label'   => esc_html__( 'Footer Padding', 'north' ),
				'id'      => 'footer_padding',
				'type'    => 'spacing',
				'desc'    => esc_html__( 'You can modify the footer padding here', 'north' ),
				'section' => 'footer',
			),
			array(
				'id'      => 'gdpr_tab0',
				'label'   => esc_html__( 'Newsletter', 'north' ),
				'type'    => 'tab',
				'section' => 'gdpr',
			),
			array(
				'label'   => esc_html__( 'Newsletter Privacy Checkbox', 'north' ),
				'id'      => 'newsletter_privacy_checkbox',
				'type'    => 'on_off',
				'desc'    => esc_html__( 'You can toggle displaying a checkbox underneath the subscribe box.', 'north' ),
				'std'     => 'on',
				'section' => 'gdpr',
			),
			array(
				'label'   => esc_html__( 'Newsletter Privacy Checkbox - Checked by Default?', 'north' ),
				'id'      => 'newsletter_privacy_checkbox_checked',
				'type'    => 'on_off',
				'desc'    => esc_html__( 'You can toggle the default state of the checkbox.', 'north' ),
				'std'     => 'on',
				'section' => 'gdpr',
			),
			array(
				'id'      => 'gdpr_tab1',
				'label'   => esc_html__( 'Cookie Bar', 'north' ),
				'type'    => 'tab',
				'section' => 'gdpr',
			),
			array(
				'label'   => esc_html__( 'Cookie Bar', 'north' ),
				'id'      => 'thb_cookie_bar',
				'type'    => 'on_off',
				'desc'    => esc_html__( 'Would you like to show the cookie bar?', 'north' ),
				'std'     => 'off',
				'section' => 'gdpr',
			),
			array(
				'label'     => esc_html__( 'Cookie Bar Content', 'north' ),
				'id'        => 'thb_cookie_bar_content',
				'type'      => 'textarea',
				'desc'      => esc_html__( 'This content appears inside the cookie bar.', 'north' ),
				'rows'      => '4',
				'section'   => 'gdpr',
				'condition' => 'thb_cookie_bar:is(on)',
			),
		),
	);

	/* settings are not the same update the DB */
	if ( $saved_settings !== $custom_settings ) {
		update_option( 'option_tree_settings', $custom_settings );
	}
}

/**
 * Menu Select option type.
 *
 * See @ot_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     2.0
 */
if ( ! function_exists( 'ot_type_menu_select' ) ) {

	function ot_type_menu_select( $args = array() ) {

		extract( $args );

		$has_desc = $field_desc ? true : false;

		echo '<div class="format-setting type-category-select ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

		if ( $has_desc ) {
			echo '<div class="description">' . wp_kses_post( wp_specialchars_decode( $field_desc ) ) . '</div>'; }

			echo '<div class="format-setting-inner">';

				echo '<select name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" class="option-tree-ui-select ' . $field_class . '">';

				$menus = get_terms( 'nav_menu' );

		if ( ! empty( $menus ) ) {
			echo '<option value="">-- ' . esc_html__( 'Choose One', 'north' ) . ' --</option>';
			foreach ( $menus as $menu ) {
				echo '<option value="' . esc_attr( $menu->slug ) . '"' . selected( $field_value, $menu->slug, false ) . '>' . esc_attr( $menu->name ) . '</option>';
			}
		} else {
			echo '<option value="">' . esc_html__( 'No Menus Found', 'north' ) . '</option>';
		}

				echo '</select>';

			echo '</div>';

		echo '</div>';

	}
}
/**
 * Variation Select option type.
 *
 * See @ot_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     2.0
 */
if ( ! function_exists( 'ot_type_attribute_select' ) ) {

	function ot_type_attribute_select( $args = array() ) {

		extract( $args );

		$has_desc = $field_desc ? true : false;

		echo '<div class="format-setting type-attribute-select ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

		if ( $has_desc ) {
			echo '<div class="description">' . wp_kses_post( wp_specialchars_decode( $field_desc ) ) . '</div>'; }

			echo '<div class="format-setting-inner">';

			echo '<select name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" class="option-tree-ui-select ' . esc_attr( $field_class ) . '">';

			$attributes = array();

		if ( function_exists( 'wc_get_attribute_taxonomies' ) ) {
			foreach ( wc_get_attribute_taxonomies() as $attribute ) {
				$attributes[ 'pa_' . $attribute->attribute_name ] = array(
					'name'  => $attribute->attribute_label,
					'value' => 'pa_' . $attribute->attribute_name,
				);
			}
		}

		if ( ! empty( $attributes ) ) {
			echo '<option value="">-- ' . esc_html__( 'Choose One', 'north' ) . ' --</option>';
			foreach ( $attributes as $attribute ) {
				echo '<option value="' . esc_attr( $attribute['value'] ) . '"' . selected( $field_value, $attribute['value'], false ) . '>' . esc_attr( $attribute['name'] ) . '</option>';
			}
		} else {
			echo '<option value="">' . esc_html__( 'No Attributes Found', 'north' ) . '</option>';
		}

				echo '</select>';

			echo '</div>';

		echo '</div>';

	}
}
/**
 * Product Category Checkbox option type.
 *
 * See @ot_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     2.0
 */
if ( ! function_exists( 'ot_type_product_category_checkbox' ) ) {

	function ot_type_product_category_checkbox( $args = array() ) {

		extract( $args );

		$has_desc = $field_desc ? true : false;

		echo '<div class="format-setting type-category-checkbox type-checkbox ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

		if ( $has_desc ) {
			echo '<div class="description">' . wp_kses_post( wp_specialchars_decode( $field_desc ) ) . '</div>'; }

		echo '<div class="format-setting-inner">';

		$args = array(
			'orderby'    => 'name',
			'order'      => 'ASC',
			'hide_empty' => '0',
		);

		$categories = get_terms( apply_filters( 'ot_type_category_checkbox_query', 'product_cat', $args, $field_id ) );

		if ( ! empty( $categories ) ) {
			foreach ( $categories as $category ) {
				echo '<p>';
					echo '<input type="checkbox" name="' . esc_attr( $field_name ) . '[' . esc_attr( $category->term_id ) . ']" id="' . esc_attr( $field_id ) . '-' . esc_attr( $category->term_id ) . '" value="' . esc_attr( $category->term_id ) . '" ' . ( isset( $field_value[ $category->term_id ] ) ? checked( $field_value[ $category->term_id ], $category->term_id, false ) : '' ) . ' class="option-tree-ui-checkbox ' . esc_attr( $field_class ) . '" />';
					echo '<label for="' . esc_attr( $field_id ) . '-' . esc_attr( $category->term_id ) . '">' . esc_attr( $category->name ) . '</label>';
				echo '</p>';
			}
		} else {
			echo '<p>' . esc_html__( 'No Product Categories Found', 'north' ) . '</p>';
		}

			echo '</div>';

		echo '</div>';

	}
}
