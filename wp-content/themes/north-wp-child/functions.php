<?php
/**
 * North child theme functions.
 *
 * @package north-wp-child
 */

/**
 * Rebuild the "Download Emails" link on the Theme Options page with a nonce.
 *
 * The parent theme hard-codes a plain link in the 'newsletter' option description, but
 * thb_csv_export() (north-plugin) now runs check_admin_referer( 'thb_download_emails' ).
 *
 * @param array|mixed $settings Theme Options settings read from the option_tree_settings option.
 * @return array|mixed
 */
function north_theme_options_download_emails_link( $settings ) {
	if ( ! is_array( $settings ) ) {
		return $settings;
	}

	foreach ( $settings as $key => $setting ) {
		if ( is_array( $setting ) && isset( $setting['id'] ) && 'newsletter' === $setting['id'] ) {
			$settings[ $key ]['desc'] = esc_html__( 'Would you like to display the Newsletter Popup? You can download the subscribed emails through the theme subscription form here:', 'north' )
				. ' <br><br> <a href="' . esc_url( wp_nonce_url( '?thb_download_emails=true', 'thb_download_emails' ) ) . '" class="button button-primary">'
				. esc_html__( 'Download Emails', 'north' ) . '</a>';
			break;
		}
	}

	return $settings;
}
add_filter( 'ot_theme_options_settings', 'north_theme_options_download_emails_link' );
