<?php
/**B2BKing Pro
 * Author: WebWizards
 * Class WOOMULTI_CURRENCY_Plugin_B2bking_Pro
 */


class WOOMULTI_CURRENCY_Plugin_B2bking_Pro {
	protected $settings;

	public function __construct() {
		if ( is_plugin_active( 'b2bking-pro/b2bking.php' ) ) {

			$this->settings = WOOMULTI_CURRENCY_Data::get_ins();

			add_filter( 'b2bking_currency_converted_price', array( $this, 'b2bking_currency_converted_price' ), 10 );
		}
	}

	public function b2bking_currency_converted_price( $price ) {

		if ( ! $this->is_default_currency() ) {
			return wmc_get_price( $price );
		}

		return $price;
	}

	private function is_default_currency() {
		$current_currency = $this->settings->get_current_currency();
		$default_currency = $this->settings->get_default_currency();

		return $current_currency === $default_currency;
	}

	private function get_current_currency_rate() {
		$wmc_currencies   = $this->settings->get_currencies();
		$current_currency = $this->settings->get_current_currency();

		return floatval( $wmc_currencies[ $current_currency ]['rate'] );
	}
}