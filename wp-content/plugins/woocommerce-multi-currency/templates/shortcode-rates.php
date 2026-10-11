<?php
/** @var  $country_code */
/** @var  $flag_size */
/** @var  $list_flag */
/** @var  $get_table */
/** @var  $currency_default */
/** @var  $symbol */
    if ( ! isset( $get_table ) || ! $get_table ) { ?>
	<div id="<?php echo esc_attr( $id ) ?>"
	     class="woocommerce-multi-currency wmc-shortcode wmc-list-currency-rates layout1"
	     data-flag_size="<?php echo esc_attr( $flag_size ) ?>">
		<div class="wmc-currency-wrapper">
            <div class="wmc-currency-base-wrap">
                <div class="wmc-currency-base-label"><?php esc_html_e('Convert 1 currency', 'woocommerce-multi-currency' ); ?></div>
                <select class="wmc-sc-rate-base-select" data-flag="<?php echo esc_attr( $flag_size ); ?>" aria-label="<?php esc_html_e('Select currency', 'woocommerce-multi-currency' ); ?>">
                    <?php
                    foreach ( $list_currencies as $c_name => $c_data ) {
                        echo '<option value="' . esc_attr( $c_name ) . '" ' . selected( $c_name, $current_currency ) . '>' . esc_attr( $c_name ) . '</option>';
                    }
                    ?>
                </select>
            </div>
			<div class="wmc-currency-rate-table">
                <div class="wmc-currency-rate-table-header">
                    <div class="wmc-rate-table-flag"><?php esc_html_e('Flag', 'woocommerce-multi-currency' ); ?></div>
                    <div class="wmc-rate-table-currency"><?php esc_html_e('Currency', 'woocommerce-multi-currency' ); ?></div>
                    <div class="wmc-rate-table-rate"><?php esc_html_e('Rate', 'woocommerce-multi-currency' ); ?></div>
                </div>
                <div class="wmc-currency-rate-table-content">
    <?php }
				foreach ( $list_currencies as $c_name => $c_data ) {
				    if ( empty( $c_data ) || ! is_array( $c_data ) || $c_name == $current_currency || ! isset( $c_data['rate'], $c_data['flag'] ) ) {
				        continue;
                    }
					$country_dt = $settings->get_country_data( $c_name );
//				    error_log(print_r($get_table,true));
//				    error_log(print_r($c_name,true));
//				    error_log(print_r($c_data,true));
				    if ( $c_data['flag'] ) {
				        $flag_html = "<img class='wmc-currency-custom-flag wmc-custom-flag-" . esc_html( strtolower( $country_dt['code'] ) ) . "' style='" .
				                     esc_attr( $flag_size ) . "' src='" . esc_url( $c_data['flag'] ) . "'>";
                    } else {
				        $flag_html = "<i style='" . esc_attr( $flag_size ) . "' class='wmc-current-flag vi-flag-64 flag-" . esc_attr( strtolower( $country_dt['code'] ) ) . "'></i>";
                    }

				    if ( $currency_default == $current_currency ) {
					    $rate_current = floatval( 1 * floatval( $c_data['rate'] ) );
				    } else {
					    $rate_current = floatval( floatval( $c_data['rate'] ) / floatval( $list_currencies[$current_currency]['rate'] ) );
				    }
				    $rate_current = number_format( $rate_current, $settings->get_rate_decimals(), ',', '.');
                    echo '<div class="wmc-rate-table-flag">' . $flag_html . '</div>';
                    echo '<div class="wmc-rate-table-currency">' . esc_html( $c_name ) . '</div>';
                    echo '<div class="wmc-rate-table-rate">' . esc_html( $rate_current ) . '</div>';
				}
    if ( ! isset( $get_table ) || ! $get_table ) { ?>
                </div>
			</div>
		</div>
	</div>
<?php }