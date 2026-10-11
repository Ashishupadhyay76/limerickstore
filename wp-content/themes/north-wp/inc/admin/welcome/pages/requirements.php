<table class="widefat" cellspacing="0">
	<thead>
		<thead>
			<tr>
				<td><?php esc_html_e( 'Requirements', 'north' ); ?></td>
				<td></td>
			</tr>
		</thead>
		<?php if ( function_exists( 'ini_get' ) ) : ?>
			<tr>
				<td>
					<?php esc_html_e( 'Server Memory Limit', 'north' ); ?>:
				</td>
				<td>
					<?php
						$mem  = ini_get( 'memory_limit' );
						$mark = intval( substr( $mem, 0, -1 ) ) >= 256 ? 'yes' : 'error';
					?>
					<?php if ( 'yes' === $mark ) { ?>
						<mark class="<?php echo esc_attr( $mark ); ?>">
								<span class="dashicons dashicons-yes"></span><?php echo esc_html( $mem ); ?>
						</mark>
					<?php } else { ?>
						<mark class="<?php echo esc_attr( $mark ); ?>">
								<span class="dashicons dashicons-warning"></span>  <span><?php echo esc_html( $mem ); ?></span>.
								<?php esc_html_e( 'The recommended value is 256M.', 'north' ); ?>
						</mark>
					<?php } ?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'PHP Version:', 'north' ); ?></td>
				<td>
					<?php
					if ( function_exists( 'phpversion' ) ) {
						$phpversion = phpversion();
						$mark       = version_compare( $phpversion, '7.0', '>' ) ? 'yes' : 'error';
						?>
						<?php if ( 'yes' === $mark ) { ?>
							<mark class="<?php echo esc_attr( $mark ); ?>">
									<span class="dashicons dashicons-yes"></span><?php echo esc_html( $phpversion ); ?>
							</mark>
						<?php } else { ?>
							<mark class="<?php echo esc_attr( $mark ); ?>">
									<span class="dashicons dashicons-warning"></span>  <span><?php echo esc_html( $phpversion ); ?></span>.
									<?php wp_kses_post( 'Required PHP Version is at least 5.6. WordPress <a href="https://wordpress.org/about/requirements/" target="_blank">recommends using at least PHP 7.0.</a> ', 'north' ); ?>
							</mark>
						<?php } ?>
					<?php	} ?>
				</td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'PHP Time Limit', 'north' ); ?>:</td>
				<td>
					<?php
						$exec = intval( ini_get( 'max_execution_time' ) );
						$mark = $exec >= 180 ? 'yes' : 'error';
					?>
					<?php if ( 'yes' === $mark ) { ?>
						<mark class="<?php echo esc_attr( $mark ); ?>">
								<span class="dashicons dashicons-yes"></span><?php echo esc_html( $exec ); ?>
						</mark>
					<?php } else { ?>
						<mark class="<?php echo esc_attr( $mark ); ?>">
								<span class="dashicons dashicons-warning"></span>  <span><?php echo esc_html( $exec ); ?></span>.
								<?php esc_html_e( 'The recommended value is 180.', 'north' ); ?>
						</mark>
					<?php } ?>
				</td>
			</tr>
		<?php endif; ?>
		<tr>
			<td><?php esc_html_e( 'Max Upload Size', 'north' ); ?>:</td>
			<td>
				<?php
					$upl  = size_format( wp_max_upload_size() );
					$mark = intval( substr( $upl, 0, -1 ) ) >= 12 ? 'yes' : 'error';
				?>
				<?php if ( 'yes' === $mark ) { ?>
					<mark class="<?php echo esc_attr( $mark ); ?>">
							<span class="dashicons dashicons-yes"></span><?php echo esc_html( $upl ); ?>
					</mark>
				<?php } else { ?>
					<mark class="<?php echo esc_attr( $mark ); ?>">
							<span class="dashicons dashicons-warning"></span>  <span><?php echo esc_html( $upl ); ?></span>.
							<?php esc_html_e( 'The recommended value is 12M.', 'north' ); ?>
					</mark>
				<?php } ?>
			</td>
		</tr>
		<?php
		$posting = array();

		// fsockopen/cURL
		$posting['fsockopen_curl']['name'] = 'fsockopen/cURL';
		$posting['fsockopen_curl']['help'] = '<a href="#" class="help_tip" data-tip="' . esc_attr__( 'Payment gateways can use cURL to communicate with remote servers to authorize payments, other plugins may also use it when communicating with remote services.', 'north' ) . '">[?]</a>';

		if ( ( function_exists( 'fsockopen' ) || function_exists( 'curl_init' ) ) ) {
			$posting['fsockopen_curl']['success'] = true;
		} else {
			$posting['fsockopen_curl']['success'] = false;
			$posting['fsockopen_curl']['note']    = 'Disabled';
		}

		// GZIP
		$posting['gzip']['name'] = 'GZip';

		if ( is_callable( 'gzopen' ) ) {
			$posting['gzip']['success'] = true;
		} else {
			$posting['gzip']['success'] = false;
			$posting['gzip']['note']    = 'Disabled.';
		}

		// WP Remote Get Check
		$posting['wp_remote_get']['name'] = esc_html__( 'Remote Get', 'north' );

		$response = wp_safe_remote_get( 'http://www.woothemes.com/wc-api/product-key-api?request=ping&network=' . ( is_multisite() ? '1' : '0' ) );

		if ( ! is_wp_error( $response ) && $response['response']['code'] >= 200 && $response['response']['code'] < 300 ) {
			$posting['wp_remote_get']['success'] = true;
		} else {
			$posting['wp_remote_get']['note']    = 'Disabled.';
			$posting['wp_remote_get']['success'] = false;
		}

		$posting = apply_filters( 'woocommerce_debug_posting', $posting );

		foreach ( $posting as $thb_post ) {
			$mark = ! empty( $thb_post['success'] ) ? 'yes' : 'error';
			?>
			<tr>
				<td><?php echo esc_html( $thb_post['name'] ); ?>:</td>
				<td>
					<mark class="<?php echo esc_attr( $mark ); ?>">
						<?php echo ! empty( $thb_post['success'] ) ? '<span class="dashicons dashicons-yes"></span>' : '<span class="dashicons dashicons-warning"></span>'; ?>
						<span><?php echo ! empty( $thb_post['note'] ) ? esc_html( $thb_post['note'] ) : ''; ?></span>
					</mark>
				</td>
			</tr>
			<?php
		}
		?>
		<tr>
			<td><?php esc_html_e( 'XML Reader', 'north' ); ?>:</td>
			<td>
				<?php
					$get_loaded_extensions = get_loaded_extensions();
					$mark                  = in_array( 'xmlreader', $get_loaded_extensions, true ) ? 'yes' : 'error';
				?>
				<?php if ( 'yes' === $mark ) { ?>
					<mark class="<?php echo esc_attr( $mark ); ?>">
							<span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Installed', 'north' ); ?>
					</mark>
				<?php } else { ?>
					<mark class="<?php echo esc_attr( $mark ); ?>">
							<span class="dashicons dashicons-warning"></span>
							<?php esc_html_e( 'Please install XMLReader extension for your PHP.', 'north' ); ?>
					</mark>
				<?php } ?>
			</td>
		</tr>
	</tbody>
</table>
