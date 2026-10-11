<?php if ( 'on' === ot_get_option( 'thb_cookie_bar', 'off' ) ) { ?>
<aside class="thb-cookie-bar">
	<div class="thb-cookie-text">
	<?php echo do_shortcode( ot_get_option( 'thb_cookie_bar_content', '<p>Our site uses cookies. Learn more about our use of cookies: <a href="#">cookie policy</a></p>' ) ); ?>
	</div>
	<a class="button-accept"><?php esc_html_e( 'I accept', 'north' ); ?></a>
</aside>
	<?php
}
