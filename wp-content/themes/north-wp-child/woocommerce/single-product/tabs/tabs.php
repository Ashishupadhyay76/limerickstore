<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$product_tabs            = apply_filters( 'woocommerce_product_tabs', array() );
$shop_product_tabs_style = ot_get_option( 'shop_product_tabs_style', 'style1' );
$column_classes          = 'style1' === $shop_product_tabs_style ? 'medium-10 large-7' : 'large-8';
if ( ! empty( $product_tabs ) ) : ?>
<div class="row align-center">
	<div class="small-12 <?php echo esc_attr( $column_classes ); ?> columns">
		<div class="woocommerce-tabs wc-tabs-wrapper <?php echo esc_attr( $shop_product_tabs_style ); ?>">
			<ul class="tabs wc-tabs" role="tablist">
				<?php foreach ( $product_tabs as $key => $tab ) : ?>
					<li class="<?php echo esc_attr( $key ); ?>_tab" id="tab-title-<?php echo esc_attr( $key ); ?>" role="tab" aria-controls="tab-<?php echo esc_attr( $key ); ?>">
						<a href="#tab-<?php echo esc_attr( $key ); ?>"><?php echo wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', esc_html( $tab['title'] ), $key ) ); ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
			<div class="woocommerce-Tabs-panel-wrapper">
				<?php foreach ( $product_tabs as $key => $tab ) : ?>
					<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr( $key ); ?> panel entry-content wc-tab" id="tab-<?php echo esc_attr( $key ); ?>" role="tabpanel" aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>">
						<?php
							if ( isset( $tab['callback'] ) ) {
								call_user_func( $tab['callback'], $key, $tab );
							}
						?>
					</div>
				<?php endforeach; ?>
				<?php do_action( 'woocommerce_product_after_tabs' ); ?>
			</div>
		</div>
	</div>
</div>
<?php endif; ?>
