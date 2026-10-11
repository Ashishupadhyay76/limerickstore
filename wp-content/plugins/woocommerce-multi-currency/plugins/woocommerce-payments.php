<?php

/**
 * Class WOOMULTI_CURRENCY_Plugin_WooCommerce_Payments
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOOMULTI_CURRENCY_Plugin_WooCommerce_Payments {
	protected $settings;

	public function __construct() {
		$this->settings = WOOMULTI_CURRENCY_Data::get_ins();
		if ( $this->settings->get_enable() ) {
			add_action( 'init', array( $this, 'init_wcpay_hooks' ), 999 );
//			$list_currencies = $this->settings->get_list_currencies();
//			if ( count( $list_currencies ) ) {
//				foreach ( $list_currencies as $currency => $currency_info ) {
//					add_filter( 'wcpay_' . strtolower( $currency ) . '_format', array(
//						$this,
//						'wcpay_currency_format'
//					), PHP_INT_MAX );
//				}
//			}
			add_filter( 'wcpay_multi_currency_should_convert_product_price', '__return_false' );
		}
	}

	public function init_wcpay_hooks() {
		if ( ! is_plugin_active( 'woocommerce-payments/woocommerce-payments.php' ) ) {
			return;
		}
		$list_currencies = $this->settings->get_list_currencies();
		if ( count( $list_currencies ) ) {
			foreach ( $list_currencies as $currency => $currency_info ) {
				add_filter( 'wcpay_' . strtolower( $currency ) . '_format', array(
					$this,
					'wcpay_currency_format'
				), PHP_INT_MAX );
			}
		}

//		add_filter( 'wcpay_multi_currency_should_convert_product_price', '__return_false' );

		add_filter( 'wcpay_payment_element_styles', array( $this, 'wcpay_inject_currency_symbol_style' ), 10, 2 );
	}

	public function wcpay_inject_currency_symbol_style( $styles, $args = array() ) {
		$current_currency = $this->settings->get_current_currency();
		$list_currencies = $this->settings->get_list_currencies();

		if ( isset( $list_currencies[ $current_currency ] ) ) {
			$currency_data = $list_currencies[ $current_currency ];
			$custom_symbol = ! empty( $currency_data['custom'] ) ? $currency_data['custom'] : '';
			$pos          = ! empty( $currency_data['pos'] ) ? $currency_data['pos'] : 'left';

			if ( $custom_symbol ) {
				$styles .= sprintf(
					'--wmc-currency-symbol: "%s"; --wmc-currency-pos: "%s";',
					esc_attr( $custom_symbol ),
					esc_attr( $pos )
				);
			}
		}

		return $styles;
	}

	/**
	 * Override currency format
	 *
	 * @param $format
	 *
	 * @return mixed
	 */
	public function wcpay_currency_format( $format ) {
		$list_currencies  = $this->settings->get_list_currencies();
		$current_currency = $this->settings->get_current_currency();
		if ( isset( $list_currencies[ $current_currency ] ) ) {
			if ( $list_currencies[ $current_currency ]['pos'] ) {
				$format['currency_pos'] = $list_currencies[ $current_currency ]['pos'];
			}
			$format['thousand_sep'] = get_option( 'woocommerce_price_thousand_sep' );
			$format['decimal_sep']  = get_option( 'woocommerce_price_decimal_sep' );
			$format['num_decimals'] = $list_currencies[ $current_currency ]['decimals'];
		}

		return $format;
	}
}