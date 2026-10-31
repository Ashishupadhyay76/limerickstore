<?php
function thb_csv_export() {
	$download = !empty($_GET['thb_download_emails']) ? sanitize_text_field( $_GET['thb_download_emails'] ) : '';

	if ($download && current_user_can( 'manage_options' ) ) {
		check_admin_referer( 'thb_download_emails' );

		$filename = 'thb_subcribed_emails_' . time(). '.csv';
		// emails
		$stack = get_option( 'subscribed_emails', array() );

		$fh = fopen( 'php://output', 'w' );

		if ( false === $fh ) {
			wp_die( esc_html__( 'Could not create the CSV export.', 'north' ) );
		}

		header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
		header( 'Content-Description: File Transfer' );
		header( 'Content-type: text/csv' );
		header( "Content-Disposition: attachment; filename=\"{$filename}\"" );
		header( 'Expires: 0' );
		header( 'Pragma: public' );

		foreach ( is_array( $stack ) ? $stack : array() as $line ) {
			if ( ! is_string( $line ) ) {
				continue;
			}

			$val = explode( ",",$line );
			foreach ( $val as $key => $cell ) {
				$trimmed = ltrim( $cell, ' ' );
				if ( '' !== $trimmed && in_array( $trimmed[0], array( '=', '+', '-', '@', "\t", "\r" ), true ) ) {
					$val[ $key ] = "'" . $cell;
				}
			}
			fputcsv( $fh, $val );
		}

		fclose( $fh );
		die();
	}

}
add_action( 'admin_init', 'thb_csv_export' );

/* Remove Type Attribute */
function thb_clean_type_tag($input) {
  $input = str_replace( "type='text/javascript' ", '', $input);
  $input = str_replace( "type='text/css' ", '', $input);
  return $input;
}
add_filter( 'script_loader_tag', 'thb_clean_type_tag');
add_filter( 'style_loader_tag', 'thb_clean_type_tag');
