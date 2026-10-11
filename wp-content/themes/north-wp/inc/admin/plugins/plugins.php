<?php
require get_template_directory() . '/inc/admin/plugins/class-tgm-plugin-activation.php';

function thb_register_required_plugins() {
	$data = thb_Theme_Admin()->thb_check_for_update_plugins();

	if ( isset( $data->plugins ) ) {
		foreach ( $data->plugins as $plugin ) {
			switch ( $plugin->plugin_name ) {
				case 'WPBakery Visual Composer':
				case 'WPBakery Page Builder':
					$slug = 'js_composer';
					break;
				case 'Slider Revolution':
					$slug = 'revslider';
					break;
				case 'WooCommerce Product Filter':
					$slug = 'prdctfltr';
					break;
				case 'WooCommerce PDF Invoice':
					$slug = 'woocommerce-pdf-invoice';
					break;
				case 'WooCommerce Table Rate Shipping':
					$slug = 'woocommerce-table-rate-shipping';
					break;
				case 'WooCommerce Dynamic Pricing & Discounts':
					$slug = 'wc-dynamic-pricing-and-discounts';
					break;
			}
			$plugins[] = array(
				'name'               => $plugin->plugin_name,
				'slug'               => $slug,
				'source'             => $plugin->download_url,
				'force_activation'   => false,
				'force_deactivation' => false,
				'version'            => $plugin->version,
				'required'           => true,
				'image_url'          => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/plugins/' . esc_attr( $slug ) . '.png',
			);
		}
	} else {
		$plugins[] = array(
			'name'               => 'WPBakery Visual Composer',
			'slug'               => 'js_composer',
			'source'             => get_template_directory() . '/inc/admin/plugins/plugins/codecanyon-aaankceN-wpbakery-page-builder-for-wordpress-wordpress-plugin.zip',
			'version'            => '8.3',
			'force_activation'   => false,
			'force_deactivation' => false,
			'required'           => true,
			'image_url'          => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/plugins/js_composer.png',
		);
	}
	$plugins[] = array(
		'name'               => esc_html__( 'WooCommerce', 'north' ),
		'slug'               => 'woocommerce',
		'required'           => true,
		'force_activation'   => false,
		'force_deactivation' => false,
		'image_url'          => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/plugins/woo.png',
	);
	$plugins[] = array(
		'name'               => esc_html__( 'North - Required Plugin', 'north' ),
		'slug'               => 'north-plugin',
		'source'             => get_template_directory() . '/inc/plugins/north-plugin.zip',
		'version'            => '1.4.2',
		'required'           => true,
		'force_activation'   => false,
		'force_deactivation' => false,
		'image_url'          => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/plugins/north.png',
	);
	$plugins[] = array(
		'name'               => esc_html__( 'Classic Editor', 'north' ),
		'slug'               => 'classic-editor',
		'required'           => true,
		'force_activation'   => false,
		'force_deactivation' => false,
		'image_url'          => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/plugins/classic-editor.png',
	);
	$plugins[] = array(
		'name'               => esc_html__( 'Classic Widgets', 'north' ),
		'slug'               => 'classic-widgets',
		'required'           => true,
		'force_activation'   => false,
		'force_deactivation' => false,
		'image_url'          => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/plugins/classic-widgets.png',
	);
	$plugins[] = array(
		'name'               => esc_html__( 'YITH WooCommerce Wishlist', 'north' ),
		'slug'               => 'yith-woocommerce-wishlist',
		'required'           => true,
		'force_activation'   => false,
		'force_deactivation' => false,
		'image_url'          => Thb_Theme_Admin::$thb_theme_directory_uri . 'assets/img/admin/plugins/wishlist.png',
	);
	$plugins[] = array(
		'name'               => esc_html__( 'Contact Form 7', 'north' ),
		'slug'               => 'contact-form-7',
		'required'           => false,
		'force_activation'   => false,
		'force_deactivation' => false,
	);

	$config = array(
		'id'           => 'thb',
		'domain'       => 'north',
		'default_path' => '',
		'parent_slug'  => 'themes.php',
		'menu'         => 'install-required-plugins',
		'has_notices'  => true,
		'is_automatic' => false,
		'message'      => '',
		'strings'      => array(
			'return' => esc_html__( 'Return to Theme Plugins', 'north' ),
		),
	);
	tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'thb_register_required_plugins' );
