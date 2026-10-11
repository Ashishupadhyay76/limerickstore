<?php

/**
 * Class WOOMULTI_CURRENCY_Plugin_Woodmart
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOOMULTI_CURRENCY_Plugin_Woodmart {
	protected $settings;

	public function __construct() {
		$this->settings = WOOMULTI_CURRENCY_Data::get_ins();
		if ( $this->settings->get_enable() ) {
			add_action( 'init', array( $this, 'woodmart_curcy_init' ) );
//			add_filter( 'woodmart_shipping_progress_bar_amount', array( $this, 'woodmart_shipping_progress_bar_amount' ) );

			//fbt bundle
			add_filter( 'woodmart_fbt_set_product_cart_price', [ $this, 'woodmart_curcy_convert_product_bundle_in_cart' ], 10, 2 );
			add_filter( 'woodmart_pricing_before_calculate_discounts', [ $this, 'woodmart_curcy_convert_product_bundle_in_cart' ], 10, 2 );

			add_filter( 'woodmart_pricing_amount_discounts_value', [ $this, 'woodmart_curcy_convert_price' ], 10, 1 );
			add_filter( 'woodmart_product_pricing_amount_discounts_value', [ $this, 'woodmart_curcy_convert_price' ], 10, 1 );
		}
	}

	public function woodmart_curcy_convert_product_bundle_in_cart( $price, $cart_item ) {

		return wmc_revert_price( $price );
	}

	public function woodmart_curcy_convert_price( $price ) {

		return wmc_get_price( $price );
	}

	public function woodmart_shipping_progress_bar_amount( $limit ) {
		return wmc_get_price( $limit );
	}

	public function woodmart_curcy_init() {
		if ( defined( 'WOODMART_VERSION' ) && version_compare( WOODMART_VERSION, '8.3', '<'  ) ) {
			add_filter( 'woodmart_shipping_progress_bar_amount', array( $this, 'woodmart_shipping_progress_bar_amount' ) );
		}
	}
}