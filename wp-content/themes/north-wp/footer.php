<?php
	$footer_style   = ot_get_option( 'footer_style', 'style1' );
	$disable_footer = get_post_meta( get_the_ID(), 'disable_footer', true );
	$onepage        = ( ot_get_option( 'footer', 'on' ) !== 'off' && $disable_footer !== 'on' ) && is_page_template( 'template-snap.php' );
?>
		</div><!-- End role["main"] -->

		<!-- Start Quick Shop -->
		<?php do_action( 'thb_quick_shop' ); ?>
		<!-- End Quick Shop -->

		<?php if ( $onepage ) { ?>
			<div class="footer-container wpb_row fp-auto-height" id="fp-footer">
		<?php } ?>
		<?php
		if ( 'on' === ot_get_option( 'footer', 'on' ) && 'on' !== $disable_footer ) {
			get_template_part( 'inc/templates/footer/footer-' . $footer_style );
		}
		?>
		<?php if ( $onepage ) { ?>
			</div>
		<?php } ?>
	<?php do_action( 'thb_wrapper_end' ); ?>
</div> <!-- End #wrapper -->
<?php

	/*
	 * Always have wp_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */
	wp_footer();
?>
</body>
</html>
