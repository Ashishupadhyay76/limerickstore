<?php function thb_subscribe( $atts, $content = null ) {
	$style = 'style1';
	$atts  = vc_map_get_attributes( 'thb_subscribe', $atts );
	extract( $atts );
	ob_start();

	?>
	<div class="thb_subscribe thb_subscribe_shortcode">
		<form class="newsletter-form" action="#" method="post" data-security="<?php echo esc_attr( wp_create_nonce( 'thb_subscription' ) ); ?>">
			<input placeholder="<?php esc_attr_e( 'Your E-Mail', 'north' ); ?>" type="text" name="widget_subscribe" class="widget_subscribe large">
			<?php $icon = is_rtl() ? '&larr;' : '&rarr;'; ?>
			<input type="submit" name="submit" class="widget_subscribe_btn" value="<?php echo esc_attr( $icon ); ?>" />
		</form>
		<?php do_action( 'thb_after_newsletter_form' ); ?>
	</div>
	<?php
	$out = ob_get_clean();
	return $out;
}
thb_add_short( 'thb_subscribe', 'thb_subscribe' );
