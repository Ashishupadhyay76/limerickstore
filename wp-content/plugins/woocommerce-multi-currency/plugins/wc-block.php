<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class WOOMULTI_CURRENCY_Plugin_Wc_Block {
	protected static $settings, $cache=[];
	public function __construct() {
		self::$settings = WOOMULTI_CURRENCY_Data::get_ins();
		if ( !self::$settings->get_enable()) {
			return;
		}
		add_action( 'woocommerce_blocks_checkout_enqueue_data', array( $this, 'maybe_use_default_currency_on_checkout' ), 1 );
		add_action( 'woocommerce_blocks_checkout_enqueue_data', array( $this, 'wmc_frontend_extra_params' ) );
		add_action( 'woocommerce_blocks_cart_enqueue_data', array( $this, 'maybe_use_checkout_currency_on_cart' ), 1 );
		add_action( 'woocommerce_blocks_cart_enqueue_data', array( $this, 'wmc_frontend_extra_params' ) );
		add_filter( 'rest_pre_dispatch', array( $this, 'maybe_use_default_currency_on_store_api' ), 1, 3 );
		add_filter( 'option_woocommerce_currency_pos', [$this,'reset_currency_pos'], 10,1);
	}
	/**
	 * Checkout block hydrates totals after init. Switch to an allowed checkout currency.
	 */
	public function maybe_use_default_currency_on_checkout() {
		if ( self::$settings->get_enable_multi_payment() ) {
			$this->switch_to_allowed_checkout_currency();
			return;
		}

		$this->switch_to_default_checkout_currency();
	}

	/**
	 * Cart block should follow checkout currency when that option is enabled.
	 * Mini-cart also fires this hook, so only switch on the actual cart page.
	 */
	public function maybe_use_checkout_currency_on_cart() {
		if ( ! self::$settings->enable_cart_page() ) {
			return;
		}

		if ( ! $this->is_full_cart_page() ) {
			return;
		}

		$this->switch_to_allowed_checkout_currency();
	}

	/**
	 * Store API cart/checkout requests do not run classic checkout AJAX, so force checkout currency there too.
	 *
	 * @param mixed           $result  Response to replace the requested version with.
	 * @param WP_REST_Server  $server  Server instance.
	 * @param WP_REST_Request $request Request used to generate the response.
	 *
	 * @return mixed
	 */
	public function maybe_use_default_currency_on_store_api( $result, $server, $request ) {
		if ( ! $request instanceof WP_REST_Request ) {
			return $result;
		}

		$route = $request->get_route();
		if ( false === strpos( $route, '/wc/store' ) ) {
			return $result;
		}

		$is_checkout_route = false !== strpos( $route, '/checkout' );
		if ( ! $is_checkout_route && false !== strpos( $route, '/batch' ) ) {
			$requests = $request->get_param( 'requests' );
			if ( is_array( $requests ) ) {
				foreach ( $requests as $sub_request ) {
					$path = is_array( $sub_request ) && ! empty( $sub_request['path'] ) ? $sub_request['path'] : '';
					if ( $path && false !== strpos( $path, '/checkout' ) ) {
						$is_checkout_route = true;
						break;
					}
				}
			}
		}

		$is_checkout_context = $is_checkout_route || $this->referer_is_checkout_page();
		$is_cart_context     = $this->referer_is_cart_page();

		if ( $is_checkout_context ) {
			if ( self::$settings->get_enable_multi_payment() ) {
				$this->switch_to_allowed_checkout_currency();
			} else {
				$this->switch_to_default_checkout_currency();
			}

			return $result;
		}

		if ( $is_cart_context && self::$settings->enable_cart_page() ) {
			$this->switch_to_allowed_checkout_currency();
		}

		return $result;
	}

	/**
	 * @return void
	 */
	protected function switch_to_default_checkout_currency() {
		if ( self::$settings->get_enable_multi_payment() ) {
			return;
		}

		$default_currency = self::$settings->get_default_currency();
		if ( $default_currency && self::$settings->get_current_currency() !== $default_currency ) {
			self::$settings->set_current_currency( $default_currency, false );
			unset( self::$cache['current_currency_pos'] );
		}
	}

	/**
	 * Switch to the checkout currency when the current currency is not allowed at checkout/cart.
	 *
	 * @return void
	 */
	protected function switch_to_allowed_checkout_currency() {
		$current_currency       = self::$settings->get_current_currency();
		$checkout_currency      = self::$settings->get_checkout_currency();
		$checkout_currency_args = self::$settings->get_checkout_currency_args();

		if ( is_array( $checkout_currency_args ) && in_array( $current_currency, $checkout_currency_args, true ) ) {
			return;
		}

		if ( ! $checkout_currency ) {
			$checkout_currency = self::$settings->get_default_currency();
		}

		if ( $checkout_currency && $current_currency !== $checkout_currency ) {
			self::$settings->set_current_currency( $checkout_currency, false );
			unset( self::$cache['current_currency_pos'] );
		}
	}

	/**
	 * @return bool
	 */
	protected function referer_is_checkout_page() {
		$referer = wp_get_referer();
		if ( ! $referer ) {
			$referer = wp_get_raw_referer();
		}
		if ( ! $referer ) {
			return false;
		}

		if ( function_exists( 'wc_get_checkout_url' ) ) {
			$checkout_path = wp_parse_url( wc_get_checkout_url(), PHP_URL_PATH );
			$referer_path  = wp_parse_url( $referer, PHP_URL_PATH );
			if ( $checkout_path && $referer_path && untrailingslashit( $referer_path ) === untrailingslashit( $checkout_path ) ) {
				return true;
			}
		}

		$post_id = url_to_postid( $referer );
		if ( ! $post_id ) {
			return false;
		}

		$post = get_post( $post_id );

		return is_a( $post, 'WP_Post' ) && ( has_block( 'woocommerce/checkout', $post ) || has_shortcode( $post->post_content, 'woocommerce_checkout' ) );
	}

	/**
	 * @return bool
	 */
	protected function referer_is_cart_page() {
		$referer = wp_get_referer();
		if ( ! $referer ) {
			$referer = wp_get_raw_referer();
		}
		if ( ! $referer ) {
			return false;
		}

		if ( function_exists( 'wc_get_cart_url' ) ) {
			$cart_path    = wp_parse_url( wc_get_cart_url(), PHP_URL_PATH );
			$referer_path = wp_parse_url( $referer, PHP_URL_PATH );
			if ( $cart_path && $referer_path && untrailingslashit( $referer_path ) === untrailingslashit( $cart_path ) ) {
				return true;
			}
		}

		$post_id = url_to_postid( $referer );
		if ( ! $post_id ) {
			return false;
		}

		$post = get_post( $post_id );

		return is_a( $post, 'WP_Post' ) && ( has_block( 'woocommerce/cart', $post ) || has_shortcode( $post->post_content, 'woocommerce_cart' ) );
	}

	/**
	 * True on the WooCommerce cart page / cart block page, not mini-cart on other pages.
	 *
	 * @return bool
	 */
	protected function is_full_cart_page() {
		if ( function_exists( 'is_cart' ) && is_cart() ) {
			return true;
		}

		global $post;
		if ( ! is_a( $post, 'WP_Post' ) ) {
			return false;
		}

		return has_block( 'woocommerce/cart', $post ) || has_shortcode( $post->post_content, 'woocommerce_cart' );
	}

	public function reset_currency_pos($value) {
		if (!isset(self::$cache['current_currency_pos'])) {
			$currencies = self::$settings->get_list_currencies();
			$current_currency = self::$settings->get_current_currency();
			$currency = $currencies[$current_currency]??'';
			self::$cache['current_currency_pos'] = $currency['pos'] ??'';
		}
		$value = self::$cache['current_currency_pos'];
		return $value;
	}
	public function wmc_frontend_extra_params() {
		if (wp_script_is('wmc-checkout-block')){
			return;
		}
		$suffix = WP_DEBUG ? '' : '.min';
		wp_enqueue_script( 'wmc-checkout-block', WOOMULTI_CURRENCY_JS . 'frontend-wc-block'.$suffix.'.js', array( 'jquery' ), WOOMULTI_CURRENCY_VERSION, false );
		wp_localize_script( 'wmc-checkout-block', 'wmc_checkout_block_params', apply_filters( 'wmc_checkout_block_params',array(
			'currencies'               => self::$settings->get_list_currencies(),
		) )
		);
	}
}