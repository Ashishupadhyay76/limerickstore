<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class WOOMULTI_CURRENCY_Plugin_Fluent_Booking {
	protected $settings;
	public function __construct() {
		$this->settings = WOOMULTI_CURRENCY_Data::get_ins();
		if ( $this->settings->get_enable() && (defined('FLUENT_BOOKING_LANDING_SLUG') || isset($_GET['fluent-booking']))) {
			$args=[
				'curcy_hook_piority_init',
				'curcy_hook_piority_add_change_price_hooks',
				'curcy_hook_piority_prepare_params'
			];
			foreach ( $args as $hoook ) {
				add_filter( $hoook, array( $this, 'init_piority' ));
			}
			add_action( 'fluent_booking/author_landing_footer', array( $this, 'front_end_script' ) );
			add_action( 'fluent_booking/main_landing_footer', array( $this, 'front_end_script' ) );
		}
	}
	public function init_piority($piority) {
		$piority = 1;
		return $piority;
	}
	public function front_end_script() {
		if(wp_style_is('woocommerce-multi-currency')){
			return;
		}
		$design = new WOOMULTI_CURRENCY_Frontend_Design();
		$design->front_end_script();
		$design->switch_currency_by_js_script();
		$design->show_action();
		wp_print_styles();
		wp_print_scripts();
	}
}