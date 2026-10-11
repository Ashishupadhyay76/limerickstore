<?php
class Thb_Theme_Admin {
	/**
	 * Main instance
	 */
	private static $_instance;

	/**
	 * Theme Name
	 */
	public static $thb_theme_name;

	/**
	 * Theme Version
	 */
	public static $thb_theme_version;

	/**
	 * Theme Slug
	 */
	public static $thb_theme_slug;

	/**
	 * Theme Directory
	 */
	public static $thb_theme_directory;

	/**
	 * Theme Directory URL
	 */
	public static $thb_theme_directory_uri;

	/**
	 * Product Key
	 */
	public static $thb_product_key;

	/**
	 * Product Key Expiration
	 */
	public static $thb_product_key_expired;

	/**
	 * Theme Constructor executed only once per request
	 */
	public function __construct() {
		if ( self::$_instance ) {
			_doing_it_wrong( __FUNCTION__, 'Cheatin&#8217; huh?', '2.0' );
		}
	}

	/**
	 * You cannot clone this class
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, 'Cheatin&#8217; huh?', '2.0' );
	}

	/**
	 * You cannot unserialize instances of this class
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, 'Cheatin&#8217; huh?', '2.0' );
	}

	public static function instance() {
		global $thb_theme_admin;
		if ( ! self::$_instance ) {
			self::$_instance = new self();
			$thb_theme_admin = self::$_instance;

			// Theme Variables.
			$theme                         = wp_get_theme();
			self::$thb_theme_name          = $theme->get( 'Name' );
			self::$thb_theme_version       = $theme->parent() ? $theme->parent()->get( 'Version' ) : $theme->get( 'Version' );
			self::$thb_theme_slug          = $theme->template;
			self::$thb_theme_directory     = get_template_directory() . '/';
			self::$thb_theme_directory_uri = get_template_directory_uri() . '/';
			self::$thb_product_key         = get_option( 'thb_' . self::$thb_theme_slug . '_key' );
			self::$thb_product_key_expired = get_option( 'thb_' . self::$thb_theme_slug . '_key_expired' );

			// After Setup Theme.
			add_action( 'after_setup_theme', array( self::$_instance, 'thb_after_setup_theme' ) );

			// Setup Admin Menus.
			if ( is_admin() ) {
				self::$_instance->thb_init_admin_pages();
			}
		}

		return self::$_instance;
	}
	/**
	 * After Theme Setup
	 */
	public function thb_after_setup_theme() {
		/* Text Domain */
		load_theme_textdomain( 'north', get_stylesheet_directory() . '/inc/languages' );

		/* Custom Background Support */
		add_theme_support( 'custom-background', array( 'default-color' => 'ffffff' ) );

		/* Title Support */
		add_theme_support( 'title-tag' );

		/* HTML5 Galleries */
		add_theme_support( 'html5', array( 'gallery', 'caption' ) );

		/* Editor Styling */
		add_editor_style();

		/* WooCommerce Support */
		add_theme_support( 'woocommerce' );
		if ( in_array( ot_get_option( 'shop_product_lightbox', 'lightbox' ), array( 'lightbox', 'zoom' ) ) ) {
			add_theme_support( 'wc-product-gallery-lightbox' );
		}
		if ( ot_get_option( 'shop_product_lightbox', 'lightbox' ) === 'zoom' ) {
			add_theme_support( 'wc-product-gallery-zoom' );
		}
		/* WooCommerce Products per Page */
		add_filter( 'loop_shop_per_page', 'thb_shops_per_page', 20, 1 );

		function thb_shops_per_page( $products_per_page ) {
			$products_per_page_get = filter_input( INPUT_GET, 'products_per_page', FILTER_VALIDATE_INT );
			$products_per_page     = isset( $products_per_page_get ) ? $products_per_page_get : ot_get_option( 'products_per_page' );
			return $products_per_page;
		}

		/* Gutenberg */
		add_theme_support( 'align-wide' );
		add_theme_support( 'align-full' );

		/* Required Settings */
		if ( ! isset( $content_width ) ) {
			$content_width = 1170; }
		add_theme_support( 'automatic-feed-links' );

		/* Image Settings */
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 150, 150, true );
		add_image_size( 'north-blog-grid', 430, 265, true );
		add_image_size( 'north-blog-grid-2x', 860, 530, true );
		add_image_size( 'north-blog-masonry', 430, 9999, false );
		add_image_size( 'north-blog-masonry-2x', 860, 9999, false );
		add_image_size( 'north-blog-vertical', 1000, 660, true );
		add_image_size( 'north-blog-vertical-2x', 2000, 1330, true );
		add_image_size( 'north-blog-list', 400, 280, true );
		add_image_size( 'north-blog-list-2x', 800, 560, true );

		/* Post Formats */
		add_theme_support( 'post-formats', array( 'standard', 'gallery', 'video' ) );

		/* Catalog Mode */
		if ( ot_get_option( 'shop_catalog_mode', 'off' ) === 'on' ) {
			remove_action( 'woocommerce_after_shop_loop_item_title_loop_price', 'woocommerce_template_loop_price', 10 );
			remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
		}

		if ( class_exists( 'WCML_WC_MultiCurrency' ) ) {
			global $WCML_WC_MultiCurrency;
			remove_action( 'woocommerce_product_meta_start', array( $WCML_WC_MultiCurrency, 'currency_switcher' ) );
		}

		/* Register Menus */
		add_theme_support( 'nav-menus' );
		register_nav_menus(
			array(
				'nav-menu'     => esc_html__( 'Navigation Menu', 'north' ),
				'acc-menu-out' => esc_html__( 'Secondary Menu (Not Logged-in)', 'north' ),
				'acc-menu-in'  => esc_html__( 'Secondary Menu (Logged-in)', 'north' ),
				'mobile-menu'  => esc_html__( 'Mobile Menu', 'north' ),
				'footer-menu'  => esc_html__( 'Footer Menu', 'north' ),
			)
		);

		$sidebars = ot_get_option( 'sidebars' );
		if ( ! empty( $sidebars ) ) {
			foreach ( $sidebars as $sidebar ) {
				register_sidebar(
					array(
						'name'          => $sidebar['title'],
						'id'            => $sidebar['id'],
						'before_widget' => '<div id="%1$s" class="widget cf %2$s">',
						'after_widget'  => '</div>',
						'before_title'  => '<h6>',
						'after_title'   => '</h6>',
					)
				);
			}
		}
	}
	public function thbDemos() {
		return array(
			array(
				'import_file_name'         => 'Default',
				'import_file_url'          => self::$thb_theme_directory_uri . 'demo-import/default/demo-content.xml',
				'import_widget_file_url'   => self::$thb_theme_directory_uri . 'demo-import/default/widget_data.json',
				'import_theme_options_url' => self::$thb_theme_directory_uri . 'demo-import/default/theme-options.txt',
				'import_image'             => self::$thb_theme_directory_uri . 'assets/img/admin/demos/homepages/home1.jpg',
				'import_demo_url'          => 'https://newnorth.fuelthemes.net/',
			),
			array(
				'import_file_name'         => 'Lookbook',
				'import_file_url'          => self::$thb_theme_directory_uri . 'demo-import/lookbook/demo-content.xml',
				'import_widget_file_url'   => self::$thb_theme_directory_uri . 'demo-import/lookbook/widget_data.json',
				'import_theme_options_url' => self::$thb_theme_directory_uri . 'demo-import/lookbook/theme-options.txt',
				'import_image'             => self::$thb_theme_directory_uri . 'assets/img/admin/demos/homepages/home_lookbook.jpg',
				'import_demo_url'          => 'https://newnorth.wpthemestudios.com/north-lookbook/',
			),
			array(
				'import_file_name'         => 'Product Landing',
				'import_file_url'          => self::$thb_theme_directory_uri . 'demo-import/landing/demo-content.xml',
				'import_widget_file_url'   => self::$thb_theme_directory_uri . 'demo-import/landing/widget_data.json',
				'import_theme_options_url' => self::$thb_theme_directory_uri . 'demo-import/landing/theme-options.txt',
				'import_image'             => self::$thb_theme_directory_uri . 'assets/img/admin/demos/homepages/home_landing.jpg',
				'import_demo_url'          => 'https://newnorth.wpthemestudios.com/north-landing/',
			),
			array(
				'import_file_name'         => 'Snap to Scroll',
				'import_file_url'          => self::$thb_theme_directory_uri . 'demo-import/snap/demo-content.xml',
				'import_widget_file_url'   => self::$thb_theme_directory_uri . 'demo-import/snap/widget_data.json',
				'import_theme_options_url' => self::$thb_theme_directory_uri . 'demo-import/snap/theme-options.txt',
				'import_image'             => self::$thb_theme_directory_uri . 'assets/img/admin/demos/homepages/home_snap.jpg',
				'import_demo_url'          => 'https://newnorth.wpthemestudios.com/north-snap/',
			),
			array(
				'import_file_name'         => 'Grid Categories',
				'import_file_url'          => self::$thb_theme_directory_uri . 'demo-import/grid/demo-content.xml',
				'import_widget_file_url'   => self::$thb_theme_directory_uri . 'demo-import/grid/widget_data.json',
				'import_theme_options_url' => self::$thb_theme_directory_uri . 'demo-import/grid/theme-options.txt',
				'import_image'             => self::$thb_theme_directory_uri . 'assets/img/admin/demos/homepages/home_grid.jpg',
				'import_demo_url'          => 'https://newnorth.wpthemestudios.com/north-grid-categories',
			),
			array(
				'import_file_name'         => 'Simple',
				'import_file_url'          => self::$thb_theme_directory_uri . 'demo-import/simple/demo-content.xml',
				'import_widget_file_url'   => self::$thb_theme_directory_uri . 'demo-import/simple/widget_data.json',
				'import_theme_options_url' => self::$thb_theme_directory_uri . 'demo-import/simple/theme-options.txt',
				'import_image'             => self::$thb_theme_directory_uri . 'assets/img/admin/demos/homepages/home_simple.jpg',
				'import_demo_url'          => 'https://newnorth.wpthemestudios.com/north-simple',
			),
			array(
				'import_file_name'         => 'Jewellery',
				'import_file_url'          => self::$thb_theme_directory_uri . 'demo-import/jewellery/demo-content.xml',
				'import_widget_file_url'   => self::$thb_theme_directory_uri . 'demo-import/jewellery/widget_data.json',
				'import_theme_options_url' => self::$thb_theme_directory_uri . 'demo-import/jewellery/theme-options.txt',
				'import_image'             => self::$thb_theme_directory_uri . 'assets/img/admin/demos/homepages/home_jewellery.jpg',
				'import_demo_url'          => 'https://newnorth.wpthemestudios.com/north-jewellery',
			),
			array(
				'import_file_name'         => 'Furniture',
				'import_file_url'          => self::$thb_theme_directory_uri . 'demo-import/furniture/demo-content.xml',
				'import_widget_file_url'   => self::$thb_theme_directory_uri . 'demo-import/furniture/widget_data.json',
				'import_theme_options_url' => self::$thb_theme_directory_uri . 'demo-import/furniture/theme-options.txt',
				'import_image'             => self::$thb_theme_directory_uri . 'assets/img/admin/demos/homepages/home_furniture.jpg',
				'import_demo_url'          => 'https://newnorth.wpthemestudios.com/north-furniture',
			),
			array(
				'import_file_name'         => 'Beauty',
				'import_file_url'          => self::$thb_theme_directory_uri . 'demo-import/beauty/demo-content.xml',
				'import_widget_file_url'   => self::$thb_theme_directory_uri . 'demo-import/beauty/widget_data.json',
				'import_theme_options_url' => self::$thb_theme_directory_uri . 'demo-import/beauty/theme-options.txt',
				'import_image'             => self::$thb_theme_directory_uri . 'assets/img/admin/demos/homepages/home_beauty.jpg',
				'import_demo_url'          => 'https://newnorth.wpthemestudios.com/north-beauty',
			),
			array(
				'import_file_name'         => 'Winter',
				'import_file_url'          => self::$thb_theme_directory_uri . 'demo-import/winter/demo-content.xml',
				'import_widget_file_url'   => self::$thb_theme_directory_uri . 'demo-import/winter/widget_data.json',
				'import_theme_options_url' => self::$thb_theme_directory_uri . 'demo-import/winter/theme-options.txt',
				'import_image'             => self::$thb_theme_directory_uri . 'assets/img/admin/demos/homepages/home_winter.jpg',
				'import_demo_url'          => 'https://newnorth.wpthemestudios.com/north-winter',
			),
		);
	}
	/**
	 *  Inintialize Admin Pages
	 */
	public function thb_init_admin_pages() {
		global $pagenow;

		// Script and styles
		add_action( 'admin_enqueue_scripts', array( & $this, 'thb_admin_page_enqueue' ) );

		// Menu Pages
		add_action( 'admin_menu', array( & $this, 'thb_admin_setup_menu' ), 1 );

		// Theme Options Redirect
		if ( isset( $_GET['page'] ) ) {
			if ( 'admin.php' === $pagenow && 'thb-theme-options' === $_GET['page'] ) {
				if ( ! ( defined( 'WP_CLI' ) && WP_CLI ) ) {
					wp_redirect( admin_url( 'themes.php?page=ot-theme-options' ) );
					die();
				}
			}
		}
		// Redirect to Main Page
		add_action( 'after_switch_theme', array( & $this, 'thb_activation_redirect' ) );

		// Ajax Option Update
		add_action( 'wp_ajax_thb_update_options', array( & $this, 'thb_update_options' ) );
		add_action( 'wp_ajax_nopriv_thb_update_options', array( & $this, 'thb_update_options' ) );

		// Admin Notices
		add_action( 'admin_notices', array( & $this, 'thb_admin_notices' ) );

		// Theme Updates
		add_action( 'admin_init', array( & $this, 'thb_theme_update' ) );

		// Plugin Update Nonce
		add_action( 'register_sidebar', array( & $this, 'thb_theme_admin_init' ) );

	}
	function thb_admin_notices() {
		$remote_ver = get_option( 'thb_' . self::$thb_theme_slug . '_remote_ver' ) ? get_option( 'thb_' . self::$thb_theme_slug . '_remote_ver' ) : self::$thb_theme_version;
		$local_ver  = self::$thb_theme_version;

		if ( version_compare( $local_ver, $remote_ver, '<' ) ) {
			if (
				( ! self::$thb_product_key && ( self::$thb_product_key_expired == 0 ) ) ||
				( self::$thb_product_key && ( self::$thb_product_key_expired == 1 ) )
			) {
				echo '<div class="notice is-dismissible error thb_admin_notices">
				<p>There is an update available for the <strong>' . self::$thb_theme_name . '</strong> theme. Go to <a href="' . admin_url( 'admin.php?page=thb-product-registration' ) . '">Product Registration</a> to enable theme updates.</p>
				</div>';
			}

			if ( ( self::$thb_product_key && ( self::$thb_product_key_expired == 0 ) ) ) {
				echo '<div class="notice is-dismissible error thb_admin_notices">
				<p>There is an update available for the <strong>' . self::$thb_theme_name . '</strong> theme. <a href="' . admin_url() . 'update-core.php">Update now</a>.</p>
				</div>';
			}
		}
	}
	public function thb_update_options() {
		check_ajax_referer( 'thb_register_ajax', 'security' );
		$key     = !empty($_POST['key']) ? sanitize_text_field( $_POST['key'] ) : '';
		$expired = filter_input( INPUT_POST, 'expired', FILTER_VALIDATE_BOOLEAN );
		update_option( 'thb_' . self::$thb_theme_slug . '_key', $key );
		update_option( 'thb_' . self::$thb_theme_slug . '_key_expired', $expired );
		wp_die();
	}
	public function thb_theme_update() {
		add_filter( 'pre_set_site_transient_update_themes', array( & $this, 'thb_check_for_update_theme' ) );
		add_filter( 'upgrader_pre_download', array( $this, 'thb_upgrade_filter' ), 10, 4 );
	}
	public function thb_check_for_update_plugins() {
		$name = 'thb_' . self::$thb_theme_slug . '_plugin_transient';
		$data = get_transient( $name );
		if ( ! $data ) {
			$args = array(
				'timeout' => 30,
				'body'    => array(
					'item_ids'    => '242431,2751380,8514038,7119279,5951088,3796656',
					'product_key' => self::$thb_product_key,
				),
			);

			$request = wp_remote_get( self::$_instance->thb_dashboard_url( 'plugin/version' ), $args );

			$data = '';
			if ( ! is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) === 200 ) {
				$data = json_decode( wp_remote_retrieve_body( $request ) );
			}
			set_transient( $name, $data, HOUR_IN_SECONDS );
		}
		return $data;
	}
	public function thb_check_for_update_theme( $transient ) {
		global $wp_filesystem;
		$args = array(
			'timeout' => 30,
			'body'    => array(
				'theme_name'  => self::$thb_theme_name,
				'product_key' => self::$thb_product_key,
			),
		);

		$request = wp_remote_get( self::$_instance->thb_dashboard_url( 'version' ), $args );

		if ( ! is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) === 200 ) {
			$data = json_decode( wp_remote_retrieve_body( $request ) );
			update_option( 'thb_' . self::$thb_theme_slug . '_key_expired', 0 );

			if ( isset( $data->success ) && $data->success == false ) {
				self::$thb_product_key_expired = 1;
				update_option( 'thb_' . self::$thb_theme_slug . '_key_expired', 1 );
			} else {
				if ( version_compare( self::$thb_theme_version, $data->version, '<' ) ) {
					$transient->response[ self::$thb_theme_slug ] = array(
						'new_version' => $data->version,
						'package'     => $data->download_url,
						'url'         => 'https://fuelthemes.net',
					);

					update_option( 'thb_' . self::$thb_theme_slug . '_remote_ver', $data->version );
				}
			}
		}
		return $transient;
	}
	public function thb_upgrade_filter( $reply, $package, $updater ) {
		global $wp_filesystem;
		$cond = ( ! self::$thb_product_key || ( self::$thb_product_key_expired == 1 ) );

		if ( isset( $updater->skin->theme_info ) && $updater->skin->theme_info['Name'] == self::$thb_theme_name ) {
			if ( $cond ) {
				return new WP_Error( 'no_credentials', sprintf( __( 'To receive automatic updates, registration is required. Please visit <a href="%1$s" target="_blank">Product Registration</a> to activate your theme.', 'north' ), admin_url( 'admin.php?page=thb-product-registration' ) ) );
			}
		}

		// VisualComposer
		if ( ( isset( $updater->skin->plugin ) ) && ( $updater->skin->plugin == 'js_composer/js_composer.php' ) ) {
			if ( $cond ) {
				return new WP_Error( 'no_credentials', sprintf( __( 'To receive automatic updates, registration is required. Please visit <a href="%1$s" target="_blank">Product Registration</a> to activate your theme.', 'north' ), admin_url( 'admin.php?page=thb-product-registration' ) ) );
			}
		}
		return $reply;
	}
	public function thb_plugins_install( $item ) {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$installed_plugins = get_plugins();

		$item['sanitized_plugin'] = $item['name'];

		// WordPress Repository
		if ( ! $item['version'] ) {
			$item['version'] = TGM_Plugin_Activation::$instance->does_plugin_have_update( $item['slug'] );
		}

		// Install Link
		if ( ! isset( $installed_plugins[ $item['file_path'] ] ) ) {
			$actions = array(
				'install' => sprintf(
					'<a href="%1$s" class="button" title="Install %2$s">Install Now</a>',
					esc_url(
						wp_nonce_url(
							add_query_arg(
								array(
									'page'          => rawurlencode( TGM_Plugin_Activation::$instance->menu ),
									'plugin'        => rawurlencode( $item['slug'] ),
									'plugin_name'   => rawurlencode( $item['sanitized_plugin'] ),
									'tgmpa-install' => 'install-plugin',
									'return_url'    => network_admin_url( 'admin.php?page=thb-plugins' ),
								),
								TGM_Plugin_Activation::$instance->get_tgmpa_url()
							),
							'tgmpa-install',
							'tgmpa-nonce'
						)
					),
					$item['sanitized_plugin']
				),
			);
		}
		// Activate Link
		elseif ( is_plugin_inactive( $item['file_path'] ) ) {
			$actions = array(
				'activate' => sprintf(
					'<a href="%1$s" class="button button-primary" title="Activate %2$s">Activate</a>',
					esc_url(
						add_query_arg(
							array(
								'plugin'             => rawurlencode( $item['slug'] ),
								'plugin_name'        => rawurlencode( $item['sanitized_plugin'] ),
								'thb-activate'       => 'activate-plugin',
								'thb-activate-nonce' => wp_create_nonce( 'thb-activate' ),
								'return_url'         => network_admin_url( 'admin.php?page=thb-plugins' ),
							),
							admin_url( 'admin.php?page=thb-plugins' )
						)
					),
					$item['sanitized_plugin']
				),
			);
		}
		// Update Link
		elseif ( version_compare( $installed_plugins[ $item['file_path'] ]['Version'], $item['version'], '<' ) ) {
			$actions = array(
				'update' => sprintf(
					'<a href="%1$s" class="button button-update" title="Install %2$s"><span class="dashicons dashicons-update"></span> Update</a>',
					wp_nonce_url(
						add_query_arg(
							array(
								'page'         => rawurlencode( TGM_Plugin_Activation::$instance->menu ),
								'plugin'       => rawurlencode( $item['slug'] ),
								'tgmpa-update' => 'update-plugin',
								'version'      => rawurlencode( $item['version'] ),
								'return_url'   => network_admin_url( 'admin.php?page=thb-plugins' ),
							),
							TGM_Plugin_Activation::$instance->get_tgmpa_url()
						),
						'tgmpa-update',
						'tgmpa-nonce'
					),
					$item['sanitized_plugin']
				),
			);
		} elseif ( self::$_instance->thb_ispluginactive( $item['file_path'] ) ) {
			$actions = array(
				'deactivate' => sprintf(
					'<a href="%1$s" class="button" title="Deactivate %2$s">Deactivate</a>',
					esc_url(
						add_query_arg(
							array(
								'plugin'               => rawurlencode( $item['slug'] ),
								'plugin_name'          => rawurlencode( $item['sanitized_plugin'] ),
								'thb-deactivate'       => 'deactivate-plugin',
								'thb-deactivate-nonce' => wp_create_nonce( 'thb-deactivate' ),
							),
							admin_url( 'admin.php?page=thb-plugins' )
						)
					),
					$item['sanitized_plugin']
				),
			);
		}

		return $actions;
	}
	public function thb_theme_admin_init() {
		$get_name = !empty($_GET['plugin_name']) ? sanitize_text_field( $_GET['plugin_name'] ) : '';

		if ( isset( $_GET['thb-deactivate'] ) && $_GET['thb-deactivate'] == 'deactivate-plugin' ) {

			check_admin_referer( 'thb-deactivate', 'thb-deactivate-nonce' );

			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			$plugins = get_plugins();

			foreach ( $plugins as $plugin_name => $plugin ) {
				if ( $plugin['Name'] == $get_name ) {
						deactivate_plugins( $plugin_name );
				}
			}
		}

		if ( isset( $_GET['thb-activate'] ) && $_GET['thb-activate'] == 'activate-plugin' ) {

			check_admin_referer( 'thb-activate', 'thb-activate-nonce' );

			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			$plugins = get_plugins();

			foreach ( $plugins as $plugin_name => $plugin ) {
				if ( $plugin['Name'] == $get_name ) {
					activate_plugin( $plugin_name );
				}
			}
		}

	}
	public function thb_activation_redirect() {
		if ( ! ( defined( 'WP_CLI' ) && WP_CLI ) ) {
			$north_installed = 'north_installed';

			if ( false == get_option( $north_installed, false ) ) {
				update_option( $north_installed, true );
				wp_redirect( admin_url( 'admin.php?page=thb-product-registration' ) );
				die();
			}

			delete_option( $north_installed );
		}
	}
	public function thb_admin_page_enqueue( $hook_suffix ) {

		wp_enqueue_script( 'thb-admin-meta', self::$thb_theme_directory_uri . 'assets/js/admin-meta.min.js', array( 'jquery' ), esc_attr( self::$thb_theme_version ) );

		wp_localize_script(
			'thb-admin-meta',
			'thb_admin',
			array(
				'i18n'           => array(
					'mediaTitle'  => esc_html__( 'Choose an image', 'north' ),
					'mediaButton' => esc_html__( 'Use image', 'north' ),
				),
				'wc_placeholder' => thb_wc_supported() ? wc_placeholder_img_src() : '',
			)
		);

		wp_enqueue_style( 'thb-admin-css', self::$thb_theme_directory_uri . 'assets/css/admin.css', null, esc_attr( self::$thb_theme_version ) );
		wp_enqueue_style( 'thb-admin-vs-css', self::$thb_theme_directory_uri . 'assets/css/admin_vc.css', null, esc_attr( self::$thb_theme_version ) );

		if ( class_exists( 'WPBakeryVisualComposerAbstract' ) ) {

			wp_enqueue_style( 'vc_extra_css', self::$thb_theme_directory_uri . 'assets/css/vc_extra.css' );
			wp_enqueue_script( 'thb-admin-vc', self::$thb_theme_directory_uri . 'assets/js/admin-vc.min.js', array( 'jquery' ), esc_attr( self::$thb_theme_version ) );
		}
	}
	public function thb_admin_setup_menu() {

		// Product Registration.
		add_menu_page( self::$thb_theme_name, self::$thb_theme_name, 'edit_theme_options', 'thb-product-registration', array( & $this, 'thb_Product_Registration' ), self::$thb_theme_directory_uri . 'assets/img/admin/fuelthemes-icon.svg', 3 );

		// Product Registration.
		add_submenu_page( 'thb-product-registration', 'Registration', 'Registration', 'edit_theme_options', 'thb-product-registration', array( & $this, 'thb_Product_Registration' ) );

		// Main Menu Item.
		add_submenu_page( 'thb-product-registration', 'Plugins', 'Plugins', 'edit_theme_options', 'thb-plugins', array( & $this, 'thb_plugins' ) );

		// Demo Import.
		add_submenu_page( 'thb-product-registration', 'Demo Import', 'Demo Import', 'edit_theme_options', 'thb-demo-import', array( & $this, 'thb_demo_import' ) );

		// Theme Options.
		add_submenu_page( 'thb-product-registration', 'Theme Options', 'Theme Options', 'edit_theme_options', 'thb-theme-options', '__return_false' );

	}
	public function thb_plugins() {
		get_template_part( 'inc/admin/welcome/pages/plugins' );
	}
	public function thb_product_registration() {
		get_template_part( 'inc/admin/welcome/pages/registration' );
	}
	public function thb_demo_import() {
		get_template_part( 'inc/admin/welcome/pages/demo-import' );
	}
	public function thb_ispluginactive( $value ) {
		$func = 'is_plugin' . '_active';
		return $func( $value );
	}
	/**
	 *  Inintialize API
	 */
	public function thb_dashboard_url( $type = null ) {
		$url = 'https://my.wpthemestudios.com';
		switch ( $type ) {
			case 'verify':
				$url .= '/api/verify';
				break;
			case 'verify-by-purchase':
				$url .= '/api/verify-by-purchase';
				break;
			case 'version':
				$url .= '/api/version';
				break;
			case 'plugin/version':
				$url .= '/api/plugin/version';
				break;
			case 'demo':
				$url .= '/api/demo';
				break;
		}
		return $url;
	}
}
// Main instance shortcut
function thb_Theme_Admin() {
	global $thb_theme_admin;
	return $thb_theme_admin;
}
Thb_Theme_Admin::instance();
