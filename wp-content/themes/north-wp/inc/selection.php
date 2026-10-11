<?php function thb_selection() {
	$id = get_queried_object_id();
	ob_start();
	?>
/* Options set in the admin page */
	<?php if ( $menu_margin = ot_get_option( 'menu_margin' ) ) { ?>
.thb-full-menu>li+li {
	margin-left: <?php thb_measurementoutput( $menu_margin ); ?>
}
<?php } ?>

/* Logo Height */
	<?php if ( $logo_height = ot_get_option( 'logo_height' ) ) { ?>
.header .logolink .logoimg {
	max-height: <?php thb_measurementoutput( $logo_height ); ?>;
}
.header .logolink .logoimg[src$=".svg"] {
	max-height: 100%;
	height: <?php thb_measurementoutput( $logo_height ); ?>;
}
<?php } ?>
	<?php if ( $logo_height_mobile = ot_get_option( 'logo_height_mobile' ) ) { ?>
@media screen and (max-width: 40.0625em) {
	.header .logolink .logoimg {
		max-height: <?php thb_measurementoutput( $logo_height_mobile ); ?>;
	}
	.header .logolink .logoimg[src$=".svg"] {
		max-height: 100%;
		height: <?php thb_measurementoutput( $logo_height_mobile ); ?>;
	}
}
<?php } ?>
/* Measurements */
	<?php if ( $header_padding = ot_get_option( 'header_padding' ) ) { ?>
@media only screen and (min-width: 64.063em) {
	.header {
		<?php thb_paddingoutput( $header_padding ); ?>;
	}
}
<?php } ?>
	<?php if ( $header_padding_fixed = ot_get_option( 'header_padding_fixed' ) ) { ?>
@media only screen and (min-width: 64.063em) {
	.header.fixed {
		<?php thb_paddingoutput( $header_padding_fixed ); ?>;
	}
}
<?php } ?>
	<?php if ( $header_padding_mobile = ot_get_option( 'header_padding_mobile' ) ) { ?>
@media only screen and (max-width: 64.063em) {
	.header,
	.header.fixed {
		<?php thb_paddingoutput( $header_padding_mobile ); ?>;
	}
}
<?php } ?>
	<?php if ( $footer_padding = ot_get_option( 'footer_padding' ) ) { ?>
@media only screen and (min-width: 40.063em) {
	.footer.style1,
	.footer.style2,
	.footer.style3 {
		<?php thb_paddingoutput( $footer_padding ); ?>;
	}
}
<?php } ?>
	<?php if ( thb_wc_supported() ) { ?>
		<?php if ( is_shop() || is_product_tag() ) { ?>
			<?php if ( $shop_header_bg = ot_get_option( 'shop_header_bg' ) ) { ?>
			.post-type-archive-product .shop-header-style2,
			.tax-product_tag .shop-header-style2 {
				<?php thb_bgoutput( $shop_header_bg ); ?>
			}
		<?php } ?>
			<?php
		} elseif ( is_product_category() ) {
			$cat       = get_queried_object();
			$cat_id    = $cat->term_id;
			$header_id = get_term_meta( $cat_id, 'header_id', true );

			$image = wp_get_attachment_url( $header_id, 'full' );
			?>
		.tax-product_cat.term-<?php echo esc_attr( $cat_id ); ?> .shop-header-style2 {
			background-image: url(<?php echo esc_url( $image ); ?>);
		}
		<?php } ?>
	<?php } ?>

/* Typography */
	<?php if ( $title_type = ot_get_option( 'title_type' ) ) { ?>
h1,h2,h3,h4,h5,h6 {
		<?php thb_typeoutput( $title_type, false ); ?>
}
	<?php } ?>
	<?php if ( $body_type = ot_get_option( 'body_type' ) ) { ?>
body,
p {
		<?php thb_typeoutput( $body_type, false ); ?>
}
	<?php } ?>
	<?php if ( $menu_font = ot_get_option( 'menu_font' ) ) { ?>
.thb-full-menu,
.account-holder {
		<?php thb_typeoutput( $menu_font ); ?>
}
	<?php } ?>
	<?php if ( $button_font = ot_get_option( 'button_font' ) ) { ?>
.btn,
.button,
input[type=submit] {
		<?php thb_typeoutput( $button_font ); ?>
}
	<?php } ?>
	<?php if ( $em_font = ot_get_option( 'em_font' ) ) { ?>
	em {
			<?php thb_typeoutput( $em_font ); ?>
	}
	<?php } ?>
	<?php if ( $label_font = ot_get_option( 'label_font' ) ) { ?>
label {
		<?php thb_typeoutput( $label_font ); ?>
}
	<?php } ?>
	<?php if ( $widget_title_font = ot_get_option( 'widget_title_font' ) ) { ?>
.widget>h6,
.footer .widget>h6 {
		<?php thb_typeoutput( $widget_title_font ); ?>
}
<?php } ?>
	<?php if ( $footer_text_color = ot_get_option( 'footer_text_color' ) ) { ?>
.footer,
.footer p{
	color: <?php echo esc_attr( $footer_text_color ); ?>;
}
	<?php } ?>
/* Typography */
	<?php if ( $menu_left_type = ot_get_option( 'menu_left_type' ) ) { ?>
.header .thb-full-menu {
		<?php thb_typeoutput( $menu_left_type ); ?>
}
	<?php } ?>
	<?php if ( $menu_left_submenu_type = ot_get_option( 'menu_left_submenu_type' ) ) { ?>
.header .thb-full-menu .sub-menu {
		<?php thb_typeoutput( $menu_left_submenu_type ); ?>
}
	<?php } ?>
	<?php if ( $menu_right_type = ot_get_option( 'menu_right_type' ) ) { ?>
.account-holder {
		<?php thb_typeoutput( $menu_right_type ); ?>
}
	<?php } ?>
	<?php if ( $menu_mobile_type = ot_get_option( 'menu_mobile_type' ) ) { ?>
.mobile-menu {
		<?php thb_typeoutput( $menu_mobile_type ); ?>
}
	<?php } ?>
	<?php if ( $menu_mobile_submenu_type = ot_get_option( 'menu_mobile_submenu_type' ) ) { ?>
.mobile-menu .sub-menu {
		<?php thb_typeoutput( $menu_mobile_submenu_type ); ?>
}
	<?php } ?>
	<?php if ( $menu_mobile_secondary_type = ot_get_option( 'menu_mobile_secondary_type' ) ) { ?>
.mobile-secondary-menu {
		<?php thb_typeoutput( $menu_mobile_secondary_type ); ?>
}
	<?php } ?>
	<?php if ( $widget_title_type = ot_get_option( 'widget_title_type' ) ) { ?>
.widget>h6,
.footer .widget>h6 {
		<?php thb_typeoutput( $widget_title_type ); ?>
}
	<?php } ?>
	<?php if ( $footer_social_type = ot_get_option( 'footer_social_type' ) ) { ?>
.footer .footer-social-icons .social {
		<?php thb_typeoutput( $footer_social_type ); ?>
}
	<?php } ?>
	<?php if ( $footer_menu_type = ot_get_option( 'footer_menu_type' ) ) { ?>
.footer .thb-footer-menu {
		<?php thb_typeoutput( $footer_menu_type ); ?>
}
	<?php } ?>
	<?php if ( $footer_copyright_type = ot_get_option( 'footer_copyright_type' ) ) { ?>
.footer .thb-footer-copyright {
		<?php thb_typeoutput( $footer_copyright_type ); ?>
}
	<?php } ?>
	<?php if ( $button_type = ot_get_option( 'button_type' ) ) { ?>
.btn, .button, input[type=submit], button {
		<?php thb_typeoutput( $button_type ); ?>
}
	<?php } ?>
	<?php if ( $global_notification_type = ot_get_option( 'global_notification_type' ) ) { ?>
.thb-global-notification {
		<?php thb_typeoutput( $global_notification_type ); ?>
}
	<?php } ?>
	<?php if ( $ajax_notification_type = ot_get_option( 'ajax_notification_type' ) ) { ?>
.woocommerce-message,
.woocommerce-error,
.woocommerce-info {
		<?php thb_typeoutput( $ajax_notification_type ); ?>
}
	<?php } ?>
	<?php if ( $shop_title = ot_get_option( 'shop_title' ) ) { ?>
h1.thb-shop-title {
		<?php thb_typeoutput( $shop_title ); ?>
}
	<?php } ?>
	<?php if ( $shop_product_title = ot_get_option( 'shop_product_title' ) ) { ?>
.products .product.thb-listing-style2 h3,
.products .product.thb-listing-style1 h3 {
		<?php thb_typeoutput( $shop_product_title ); ?>
}
	<?php } ?>
	<?php if ( $shop_product_price = ot_get_option( 'shop_product_price' ) ) { ?>
.products .product .amount {
		<?php thb_typeoutput( $shop_product_price ); ?>
}
	<?php } ?>
	<?php if ( $shop_product_category_title = ot_get_option( 'shop_product_category_title' ) ) { ?>
.products .product .product-category {
		<?php thb_typeoutput( $shop_product_category_title ); ?>
}
	<?php } ?>
	<?php if ( $shop_product_detail_title = ot_get_option( 'shop_product_detail_title' ) ) { ?>
.thb-product-detail .product-information h1.product_title {
		<?php thb_typeoutput( $shop_product_detail_title ); ?>
}
	<?php } ?>
	<?php if ( $shop_product_detail_price = ot_get_option( 'shop_product_detail_price' ) ) { ?>
.thb-product-detail .product-information .price .amount {
		<?php thb_typeoutput( $shop_product_detail_price ); ?>
}
<?php } ?>
	<?php if ( $shop_product_detail_excerpt = ot_get_option( 'shop_product_detail_excerpt' ) ) { ?>
.thb-product-detail .product-information .woocommerce-product-details__short-description,
.thb-product-detail .product-information .woocommerce-product-details__short-description p {
		<?php thb_typeoutput( $shop_product_detail_excerpt ); ?>
}
	<?php } ?>
/* Heading Typography */
	<?php if ( $h1_type = ot_get_option( 'h1_type' ) ) { ?>
@media screen and (min-width: 1024px) {
	h1,
	.h1 {
		<?php thb_typeoutput( $h1_type ); ?>
	}
}
	<?php } ?>
	<?php if ( $h2_type = ot_get_option( 'h2_type' ) ) { ?>
@media screen and (min-width: 1024px) {
	h2 {
		<?php thb_typeoutput( $h2_type ); ?>
	}
}
	<?php } ?>
	<?php if ( $h3_type = ot_get_option( 'h3_type' ) ) { ?>
@media screen and (min-width: 1024px) {
	h3 {
		<?php thb_typeoutput( $h3_type ); ?>
	}
}
	<?php } ?>
	<?php if ( $h4_type = ot_get_option( 'h4_type' ) ) { ?>
@media screen and (min-width: 1024px) {
	h4 {
		<?php thb_typeoutput( $h4_type ); ?>
	}
}
	<?php } ?>
	<?php if ( $h5_type = ot_get_option( 'h5_type' ) ) { ?>
@media screen and (min-width: 1024px) {
	h5 {
		<?php thb_typeoutput( $h5_type ); ?>
	}
}
	<?php } ?>
	<?php if ( $h6_type = ot_get_option( 'h6_type' ) ) { ?>
h6 {
		<?php thb_typeoutput( $h6_type ); ?>
}
	<?php } ?>
/* Colors */
	<?php if ( $subheader_text_color = ot_get_option( 'subheader_text_color' ) ) { ?>
.subheader { color: <?php echo esc_attr( $subheader_text_color ); ?>; }
	<?php } ?>
	<?php if ( $badge_justarrived = ot_get_option( 'badge_justarrived' ) ) { ?>
	.badge.new { background-color: <?php echo esc_attr( $badge_justarrived ); ?>; }
	<?php } ?>
	<?php if ( $badge_sale = ot_get_option( 'badge_sale' ) ) { ?>
	.badge.onsale { background-color: <?php echo esc_attr( $badge_sale ); ?>; }
	<?php } ?>
	<?php if ( $badge_outofstock = ot_get_option( 'badge_outofstock' ) ) { ?>
	.badge.out-of-stock { background-color: <?php echo esc_attr( $badge_outofstock ); ?>; }
	<?php } ?>
	<?php if ( $widget_title_color = ot_get_option( 'widget_title_color' ) ) { ?>
	.widget>h6,
	.footer .widget>h6 { color: <?php echo esc_attr( $widget_title_color ); ?>; }
	<?php } ?>
	<?php
	$header_secondary_icon_color = ot_get_option( 'header_secondary_icon_color' );

	if ( $header_secondary_icon_color ) {
		?>
		.header #quick_search svg,
		.header #quick_profile svg {
			fill:  <?php echo esc_attr( $header_secondary_icon_color ); ?>;
		}
		.header #quick_cart svg {
			stroke: <?php echo esc_attr( $header_secondary_icon_color ); ?>;
		}
		<?php
	}
	?>
/* Link Colors */
	<?php if ( $subheader_link_color = ot_get_option( 'subheader_link_color' ) ) { ?>
		<?php thb_linkcoloroutput( $subheader_link_color, '.subheader .thb-full-menu>.menu-item>' ); ?>
		<?php thb_linkcoloroutput( $subheader_link_color, '.subheader.dark .thb-full-menu>.menu-item>' ); ?>
	<?php } ?>
	<?php if ( $fullmenu_link_color_dark = ot_get_option( 'fullmenu_link_color_dark' ) ) { ?>
		<?php thb_linkcoloroutput( $fullmenu_link_color_dark, '.header .thb-full-menu>li>' ); ?>
		<?php thb_linkcoloroutput( $fullmenu_link_color_dark, '.thb-header-menu>li>' ); ?>
		<?php thb_linkcoloroutput( $fullmenu_link_color_dark, '.header .account-holder' ); ?>
	<?php } ?>
	<?php if ( $fullmenu_link_color_light = ot_get_option( 'fullmenu_link_color_light' ) ) { ?>
		<?php thb_linkcoloroutput( $fullmenu_link_color_light, '.header:not(.fixed):not(:hover) .thb-full-menu>li>', true ); ?>
		<?php thb_linkcoloroutput( $fullmenu_link_color_light, '.header:not(.fixed):not(:hover) .account-holder', true ); ?>
	<?php } ?>
	<?php if ( $submenu_link_color = ot_get_option( 'submenu_link_color' ) ) { ?>
		<?php thb_linkcoloroutput( $submenu_link_color, '.thb-full-menu .sub-menu li' ); ?>
	<?php } ?>
	<?php if ( $footer_link_color = ot_get_option( 'footer_link_color' ) ) { ?>
		<?php thb_linkcoloroutput( $footer_link_color, '.footer .widget' ); ?>
		<?php thb_linkcoloroutput( $footer_link_color, '.footer .thb-footer-menu' ); ?>
	<?php } ?>
	<?php if ( $footer_social_link_color = ot_get_option( 'footer_social_link_color' ) ) { ?>
		<?php thb_linkcoloroutput( $footer_social_link_color, '.footer .footer-social-icons' ); ?>
	<?php } ?>
	<?php if ( $mobilemenu_link_color = ot_get_option( 'mobilemenu_link_color' ) ) { ?>
		<?php thb_linkcoloroutput( $mobilemenu_link_color, '#mobile-menu .mobile-menu>li>' ); ?>
	<?php } ?>
	<?php if ( $mobilemenu_submenu_link_color = ot_get_option( 'mobilemenu_submenu_link_color' ) ) { ?>
		<?php thb_linkcoloroutput( $mobilemenu_submenu_link_color, '#mobile-menu .mobile-menu .sub-menu' ); ?>
	<?php } ?>
/* Shop Colors */
	<?php if ( $rating_star_color = ot_get_option( 'rating_star_color' ) ) { ?>
.star-rating>span:before,
.comment-form-rating p.stars>span:before {
	color: <?php echo esc_attr( $rating_star_color ); ?>;
}
<?php } ?>
	<?php if ( $shop_price_color = ot_get_option( 'shop_price_color' ) ) { ?>
.price ins, .price .amount,
.thb-cart-amount .amount,
.product_list_widget .amount {
color: <?php echo esc_attr( $shop_price_color ); ?>;
}
	<?php } ?>
	<?php if ( $product_detail_title_color = ot_get_option( 'product_detail_title_color' ) ) { ?>
.thb-product-detail .product-information h1 {
	color: <?php echo esc_attr( $product_detail_title_color ); ?>;
}
	<?php } ?>
/* Backgrounds */
	<?php if ( $subheader_bg = ot_get_option( 'subheader_bg' ) ) { ?>
.subheader {
		<?php thb_bgoutput( $subheader_bg ); ?>
}
	<?php } ?>
	<?php if ( $header_bg = ot_get_option( 'header_bg' ) ) { ?>
.header.fixed,
.header:hover {
		<?php thb_bgoutput( $header_bg ); ?>
}
	<?php } ?>
	<?php if ( $footer_bg = ot_get_option( 'footer_bg' ) ) { ?>
.footer {
		<?php thb_bgoutput( $footer_bg ); ?>
}
	<?php } ?>
	<?php if ( $mobilemenu_bg = ot_get_option( 'mobilemenu_bg' ) ) { ?>
#mobile-menu {
		<?php thb_bgoutput( $mobilemenu_bg ); ?>
}
	<?php } ?>
	<?php if ( $sidecart_bg = ot_get_option( 'sidecart_bg' ) ) { ?>
#side-cart {
		<?php thb_bgoutput( $sidecart_bg ); ?>
}
	<?php } ?>
	<?php if ( $quickshop_bg = ot_get_option( 'quickshop_bg' ) ) { ?>
#quick-shop {
		<?php thb_bgoutput( $quickshop_bg ); ?>
}
	<?php } ?>
	<?php if ( $search_bg = ot_get_option( 'search_bg' ) ) { ?>
.mfp-bg.quick-search {
		<?php thb_bgoutput( $search_bg ); ?>
}
	<?php } ?>
	<?php if ( $global_notification_bg = ot_get_option( 'global_notification_bg' ) ) { ?>
.thb-global-notification {
		<?php thb_bgoutput( $global_notification_bg ); ?>
}
	<?php } ?>
	<?php if ( $ajax_notification_message_bg = ot_get_option( 'ajax_notification_message_bg' ) ) { ?>
.woocommerce-message {
		<?php thb_bgoutput( $ajax_notification_message_bg ); ?>
}
	<?php } ?>
	<?php if ( $ajax_notification_info_bg = ot_get_option( 'ajax_notification_info_bg' ) ) { ?>
.woocommerce-info:not(.cart-empty) {
		<?php thb_bgoutput( $ajax_notification_info_bg ); ?>
}
	<?php } ?>
	<?php if ( $ajax_notification_error_bg = ot_get_option( 'ajax_notification_error_bg' ) ) { ?>
.woocommerce-error {
		<?php thb_bgoutput( $ajax_notification_error_bg ); ?>
}
	<?php } ?>
	<?php if ( $newsletter_bg = ot_get_option( 'newsletter_bg' ) ) { ?>
	#newsletter-popup .newsletter-content {
		<?php thb_bgoutput( $newsletter_bg ); ?>
	}
	<?php } ?>
/* Extra CSS */
	<?php echo ot_get_option( 'extra_css' ); ?>
	<?php
	$out = ob_get_clean();
	// Remove comments
	$out = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $out );
	// Remove space after colons
	$out = str_replace( ': ', ':', $out );
	// Remove whitespace
	$out = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $out );

	return $out;
}
