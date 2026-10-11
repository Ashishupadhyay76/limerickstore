<?php
if (!defined('ABSPATH')) exit;
if (!class_exists('BVWP2FAEmailOTPSender')) :
class BVWP2FAEmailOTPSender {
	public static function send($user, $code, $lifetime) {
		$template = new BVWP2FAEmailOTPTemplate($code, $lifetime);

		return wp_mail($user->user_email, $template->subject(), $template->body(), $template->headers());
	}
}
endif;
