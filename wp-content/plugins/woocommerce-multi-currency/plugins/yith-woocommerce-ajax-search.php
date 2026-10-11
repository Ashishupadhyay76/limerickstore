<?php
/**
 * Class WOOMULTI_CURRENCY_Plugin_YITH_WooCommerce_Ajax_Search
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WOOMULTI_CURRENCY_Plugin_YITH_WooCommerce_Ajax_Search {

	public function __construct() {
		if ( ! class_exists( 'YITH_WCAS' ) ) {
			return;
		}

		$settings = WOOMULTI_CURRENCY_Data::get_ins();
		if ( ! $settings->get_enable() ) {
			return;
		}

		$current_currency = $settings->get_current_currency();
		$default_currency = $settings->get_default_currency();

		if ( $current_currency === $default_currency ) {
			return;
		}

		add_filter( 'ywcas_search_result_data', array( $this, 'convert_prices' ), 10, 4 );
		add_filter( 'ywcas_block_common_localize', array( $this, 'override_currency_data' ) );
	}

	/**
	 * Convert prices in YITH AJAX Search results to current currency.
	 * Uses get_variation_prices() / get_price() which trigger WooCommerce Multi Currency hooks
	 * to properly convert prices including fixed price overrides.
	 *
	 * @param array  $search_result_data Array of search result items from lookup table.
	 * @param string $query_string       Search query string.
	 * @param string $post_type          Post type being searched.
	 * @param string $lang               Current language.
	 *
	 * @return array
	 */
	public function convert_prices( $search_result_data, $query_string, $post_type, $lang ) {
		if ( empty( $search_result_data ) || ! is_array( $search_result_data ) ) {
			return $search_result_data;
		}

		foreach ( $search_result_data as &$item ) {
			if ( ! is_array( $item ) || ! isset( $item['post_id'] ) ) {
				continue;
			}

			$product = wc_get_product( $item['post_id'] );
			if ( ! $product ) {
				continue;
			}

			$product_type = isset( $item['product_type'] ) ? $item['product_type'] : $product->get_type();

			if ( 'variable' === $product_type ) {
				if ( isset( $item['min_price'] ) && $item['min_price'] !== '' ) {
					$converted = $this->get_converted_variation_min_price( $product );
					if ( $converted > 0 ) {
						$item['min_price'] = $converted;
					}
				}
				if ( isset( $item['max_price'] ) && $item['max_price'] !== '' ) {
					$converted = $this->get_converted_variation_max_price( $product );
					if ( $converted > 0 ) {
						$item['max_price'] = $converted;
					}
				}
			} else {
				$converted_price = $product->get_price();
				if ( $converted_price !== '' && (float) $converted_price > 0 ) {
					if ( isset( $item['min_price'] ) && $item['min_price'] !== '' ) {
						$item['min_price'] = $converted_price;
					}
					if ( isset( $item['max_price'] ) && $item['max_price'] !== '' ) {
						$item['max_price'] = $converted_price;
					}
				}
			}
		}

		return $search_result_data;
	}

	/**
	 * Override currency data passed to YITH AJAX Search frontend JS.
	 * This ensures the currency symbol, format and decimals match the user's selected currency.
	 *
	 * @param array $localize_data Data passed to wp_localize_script via ywcas_block_common_localize.
	 *
	 * @return array
	 */
	public function override_currency_data( $localize_data ) {
		$settings = WOOMULTI_CURRENCY_Data::get_ins();
		$currency_code = $settings->get_current_currency();
		$selected_currencies = $settings->get_list_currencies();

		if ( ! $currency_code || ! isset( $selected_currencies[ $currency_code ] ) ) {
			return $localize_data;
		}

		$currency_data = $selected_currencies[ $currency_code ];

		// Only override decimals if explicitly set in WMC config.
		// Empty string or 0 means use WooCommerce defaults.
		$decimals     = ( isset( $currency_data['decimals'] ) && $currency_data['decimals'] !== '' )
			? (int) $currency_data['decimals']
			: wc_get_price_decimals();
		$thousand_sep = ( isset( $currency_data['thousand_sep'] ) && $currency_data['thousand_sep'] !== '' )
			? $currency_data['thousand_sep']
			: wc_get_price_thousand_separator();

		$localize_data['wcData']['currency'] = array(
			'code'         => $currency_code,
			'decimals'     => $decimals,
			'symbol'       => html_entity_decode( get_woocommerce_currency_symbol( $currency_code ) ),
			'decimal_sep'  => '.',
			'thousand_sep' => $thousand_sep,
			'format'       => $this->get_currency_format( $currency_data['pos'] ?? '' ),
		);

		return $localize_data;
	}

	/**
	 * Map WooCommerce Multi Currency position key to price format string.
	 *
	 * @param string $pos Position: left, left_space, right, right_space.
	 *
	 * @return string
	 */
	private function get_currency_format( $pos ) {
		$pos = $pos ?: 'left';
		$formats = array(
			'left'        => '%1$s%2$s',
			'left_space'  => '%1$s %2$s',
			'right'       => '%2$s%1$s',
			'right_space' => '%2$s %1$s',
		);
		return html_entity_decode(
			str_replace(
				array( '%1$s', '%2$s' ),
				array( '%s', '%v' ),
				$formats[ $pos ] ?? '%1$s%2$s'
			)
		);
	}

	/**
	 * Get converted variation min price.
	 * Uses get_price('edit') to get raw base prices, then wmc_get_price() with none_hook=true
	 * to convert exactly once. This avoids double-conversion when WMC hooks return
	 * already-converted prices from cache, and also correctly handles the case where
	 * Fixed Price is enabled but per-currency prices are not set for variations.
	 *
	 * @param \WC_Product $product
	 *
	 * @return float|int
	 */
	private function get_converted_variation_min_price( $product ) {
		/** @var \WC_Product_Variable $product */
		$variation_ids = $product->get_visible_children();
		$price_min     = 0;
		$settings      = WOOMULTI_CURRENCY_Data::get_ins();
		$check_fixed   = $settings->check_fixed_price();
		$current_curr  = $settings->get_current_currency();

		foreach ( $variation_ids as $variation_id ) {
			$variation = wc_get_product( $variation_id );
			if ( ! $variation ) {
				continue;
			}

			$price = 0;

			if ( $check_fixed ) {
				$fixed = WOOMULTI_CURRENCY_Frontend_Price::get_fixed_price( $variation, $current_curr );
				if ( $fixed !== false ) {
					$price = $fixed;
				}
			}

			if ( ! $price ) {
				// Use get_price('edit') to get raw base price, then convert once.
				$raw_price = $variation->get_price( 'edit' );
				if ( $raw_price !== '' ) {
					$price = wmc_get_price( $raw_price, $current_curr, true );
				}
			}

			if ( $price > 0 && ( $price_min === 0 || $price < $price_min ) ) {
				$price_min = $price;
			}
		}

		return $price_min;
	}

	/**
	 * Get converted variation max price.
	 * Uses get_price('edit') to get raw base prices, then wmc_get_price() with none_hook=true
	 * to convert exactly once. This avoids double-conversion when WMC hooks return
	 * already-converted prices from cache, and also correctly handles the case where
	 * Fixed Price is enabled but per-currency prices are not set for variations.
	 *
	 * @param \WC_Product $product
	 *
	 * @return float|int
	 */
	private function get_converted_variation_max_price( $product ) {
		/** @var \WC_Product_Variable $product */
		$variation_ids = $product->get_visible_children();
		$price_max     = 0;
		$settings      = WOOMULTI_CURRENCY_Data::get_ins();
		$check_fixed   = $settings->check_fixed_price();
		$current_curr  = $settings->get_current_currency();

		foreach ( $variation_ids as $variation_id ) {
			$variation = wc_get_product( $variation_id );
			if ( ! $variation ) {
				continue;
			}

			$price = 0;

			if ( $check_fixed ) {
				$fixed = WOOMULTI_CURRENCY_Frontend_Price::get_fixed_price( $variation, $current_curr );
				if ( $fixed !== false ) {
					$price = $fixed;
				}
			}

			if ( ! $price ) {
				// Use get_price('edit') to get raw base price, then convert once.
				$raw_price = $variation->get_price( 'edit' );
				if ( $raw_price !== '' ) {
					$price = wmc_get_price( $raw_price, $current_curr, true );
				}
			}

			if ( $price > $price_max ) {
				$price_max = $price;
			}
		}

		return $price_max;
	}
}
