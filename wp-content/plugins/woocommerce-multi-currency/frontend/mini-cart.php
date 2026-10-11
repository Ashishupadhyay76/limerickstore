<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WOOMULTI_CURRENCY_Frontend_Mini_Cart
 */
class WOOMULTI_CURRENCY_Frontend_Mini_Cart {
	protected $settings;

	public function __construct() {
		$this->settings = WOOMULTI_CURRENCY_Data::get_ins();
		if ( $this->settings->get_enable() ) {
			if ( is_plugin_active( 'woocommerce-memberships/woocommerce-memberships.php' ) || is_plugin_active( 'polylang/polylang.php' ) || is_plugin_active( 'woocommerce-side-cart-premium/xoo-wsc-main.php' ) || is_plugin_active( 'woocommerce-myparcel/woocommerce-myparcel.php' ) ) {
				add_action( 'woocommerce_before_mini_cart', array( $this, 'woocommerce_before_mini_cart' ), 99 );
				if ( is_plugin_active( 'woocommerce-side-cart-premium/xoo-wsc-main.php' ) ) {
					add_action( 'woocommerce_before_mini_cart', array( $this, 'fix_tiered_line_totals_mini' ), 10 );
				}
				if ( is_plugin_active( 'elementor-pro/elementor-pro.php' ) ) {
					add_action( 'woocommerce_before_mini_cart_contents', array(
						$this,
						'woocommerce_before_mini_cart'
					), 99 );
				}
			} else {
				add_action( 'woocommerce_cart_loaded_from_session', array(
					$this,
					'woocommerce_before_mini_cart'
				), 99 );
			}

			add_action( 'wp_enqueue_scripts', array( $this, 'remove_session' ) );
		}
	}

	public function fix_tiered_line_totals_mini() {
		if ( ! function_exists( 'wmc_get_price' ) || ! function_exists( 'wmc_revert_price' ) ) {
			return;
		}

		if ( ! class_exists( 'TierPricingTable\PriceManager' ) ) {
			return;
		}

		$current_currency = $this->settings->get_current_currency();
		$default_currency = $this->settings->get_default_currency();
		if ( $current_currency === $default_currency ) {
			return;
		}

		$cart = WC()->cart;
		if ( ! $cart || $cart->is_empty() ) {
			return;
		}

		try {
			foreach ( $cart->get_cart() as $cart_key => $cart_item ) {
				$product = $cart_item['data'];
				if ( ! $product || ! is_object( $product ) ) {
					continue;
				}

				$product_id = $product->get_id();
				$quantity   = $cart_item['quantity'];

				$pricing_rule = \TierPricingTable\PriceManager::getPricingRule( $product_id );
				if ( ! $pricing_rule || ! $pricing_rule->getRules() ) {
					continue;
				}

				$tiered_price = \TierPricingTable\PriceManager::getPriceByRules(
					$quantity,
					$product_id,
					'edit',
					'cart',
					false
				);

				if ( $tiered_price === false || $tiered_price <= 0 ) {
					continue;
				}

				$line_total = $tiered_price * $quantity;

				if ( $product->is_taxable() && $cart->display_prices_including_tax() ) {
					$line_total = wc_get_price_including_tax( $product, array( 'qty'   => $quantity,
					                                                           'price' => $tiered_price
					) );
				}

				$cart->cart_contents[ $cart_key ]['line_subtotal'] = $line_total;
				$cart->cart_contents[ $cart_key ]['line_total']    = $line_total;
			}
		} catch ( Exception $e ) {
			return;
		}
	}

	public function remove_session() {
		$selected_currencies = $this->settings->get_currencies();
		if ( isset( $_REQUEST['_woo_multi_currency_nonce'] ) && ! wp_verify_nonce( wc_clean( wp_unslash( $_REQUEST['_woo_multi_currency_nonce'] ) ), 'wmc_currency_nonce' ) ) {
			return;
		}
		$get_currency = isset( $_GET['wmc-currency'] ) ? sanitize_text_field( wp_unslash( $_GET['wmc-currency'] ) ) : '';
		if ( $get_currency && in_array( $get_currency, $selected_currencies, true ) ) {
			$src_min = WP_DEBUG ? '' : '.min';
			wp_enqueue_script( 'woocommerce-multi-currency-cart', WOOMULTI_CURRENCY_JS . 'woocommerce-multi-currency-cart' . $src_min . '.js', array( 'jquery' ), WOOMULTI_CURRENCY_VERSION, false );
		}
	}

	/**
	 * Recalculator for mini cart
	 */
	public function woocommerce_before_mini_cart() {
		if ( isset( $_REQUEST['_woo_multi_currency_nonce'] ) && ! wp_verify_nonce( wc_clean( wp_unslash( $_REQUEST['_woo_multi_currency_nonce'] ) ), 'wmc_currency_nonce' ) ) {
			return;
		}
		if ( is_plugin_active( 'yith-woocommerce-eu-vat-premium/init.php' ) && is_plugin_active( 'woocommerce-paypal-payments/woocommerce-paypal-payments.php' )
		     && ( isset( $_REQUEST['wc-ajax'] ) && wc_clean( wp_unslash( $_REQUEST['wc-ajax'] ) ) == 'ppc-create-order' ) ) {
		} else {
			if ( is_plugin_active( 'woo-discount-rules/woo-discount-rules.php' ) && defined( 'WDR_VERSION' ) &&
			     version_compare( WDR_VERSION, '2.6.2', '>=' ) ) {
				return;
			}
			if ( is_plugin_active( 'cart-for-woocommerce/plugin.php' ) ) {
				return;
			}
			do_action( 'wmc_before_force_recalculate_totals' );
			@WC()->cart->calculate_totals();
			do_action( 'wmc_after_force_recalculate_totals' );
		}
	}
}