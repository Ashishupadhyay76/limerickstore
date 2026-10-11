<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product, $woocommerce_loop;

// Check if the product is a valid WooCommerce product and ensure its visibility before proceeding.
if ( ! is_a( $product, WC_Product::class ) || ! $product->is_visible() ) {
	return;
}

$shop_product_listing_layout = isset( $_GET['shop_product_listing_layout'] ) ? $_GET['shop_product_listing_layout'] : ot_get_option( 'shop_product_listing_layout', 'style1' );
$shop_product_listing        = isset( $_GET['shop_product_listing'] ) ? $_GET['shop_product_listing'] : ot_get_option( 'shop_product_listing', 'style1' );
$shop_product_hover          = ot_get_option( 'shop_product_hover', 'on' );
$columns                     = isset( $_GET['products_per_row'] ) ? $_GET['products_per_row'] : ot_get_option( 'products_per_row', 'large-3' );
$shop_product_listing_button = ot_get_option( 'shop_product_listing_button', 'style4' );

if ( in_array( $shop_product_listing_layout, array( 'style2', 'style3', 'style4', 'style5', 'style6', 'style7', 'style8' ), true ) && ( is_shop() || is_product_category() || is_product_tag() ) ) {
	$columns = thb_get_product_size( $shop_product_listing_layout, $woocommerce_loop['loop'] );
}

$columns = get_query_var( 'thb_columns' ) ? get_query_var( 'thb_columns' ) : $columns;

$classes[] = 'small-6';
$classes[] = $columns;
$classes[] = 'columns';
$classes[] = 'thb-listing-' . $shop_product_listing;
$classes[] = 'thb-listing-button-' . $shop_product_listing_button;

$product_url = apply_filters( 'woocommerce_loop_product_link', get_the_permalink(), $product );

$thumbnail_second = '';

$featured       = wp_get_attachment_url( get_post_thumbnail_id(), 'shop_catalog' );
$attachment_ids = $product->get_gallery_image_ids();
if ( $attachment_ids ) {
	$loop = 0;
	foreach ( $attachment_ids as $attachment_id ) {
		$image_link = wp_get_attachment_url( $attachment_id );
		if ( ! $image_link ) {
			continue;
		}
		$loop++;
		$thumbnail_second = $attachment_id;
		if ( $image_link !== $featured ) {
			if ( $loop == 1 ) {
				break;
			}
		}
	}
}
$style = $class = $thumbnail_class = '';
if ( $thumbnail_second ) {
	$thumbnail_class = 'thb_hover';
}

if ( $shop_product_listing === 'style1' ) {
	add_action( 'woocommerce_before_shop_loop_item_title', 'thb_wishlist_button', 5 );
}
?>
<li <?php wc_product_class( $classes, $product ); ?>>
	<?php
		/**
		 * woocommerce_before_shop_loop_item hook.
		 *
		 * @hooked woocommerce_template_loop_product_link_open - 10
		 */
		do_action( 'woocommerce_before_shop_loop_item' );
	?>
	<figure class="product_thumbnail <?php echo esc_attr( $thumbnail_class ); ?>">
		<?php do_action( 'thb_product_badge' ); ?>
		<a href="<?php echo esc_url( $product_url ); ?>" title="<?php the_title_attribute(); ?>">
			<?php if ( $shop_product_hover === 'on' && $thumbnail_second ) { ?>
			<span class="product_thumbnail_hover"><?php echo wp_get_attachment_image( $thumbnail_second, 'shop_catalog' ); ?></span>
			<?php } ?>
			<?php echo woocommerce_get_product_thumbnail(); ?>
		</a>
		<?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
	</figure>
	<?php if ( 'on' === ot_get_option( 'shop_product_listing_category', 'off' ) ) { ?>
		<div class="product-category">
			<?php
				$product_cats = function_exists( 'wc_get_product_category_list' ) ? wc_get_product_category_list( get_the_ID(), '\n', '', '' ) : $product->get_categories( '\n', '', '' );
				$product_cats = wp_strip_all_tags( $product_cats );

			if ( $product_cats ) {
				list( $first_part ) = explode( '\n', $product_cats );
				echo esc_html( $first_part );
			}
			?>
		</div>
	<?php } ?>
	<h3>
		<a href="<?php echo esc_url( $product_url ); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
		<?php
		if ( $shop_product_listing === 'style2' ) {
			thb_wishlist_button(); }
		?>
	</h3>
	<?php do_action( 'woocommerce_after_shop_loop_item_title' ); ?>
	<?php if ( 'on' === ot_get_option( 'shop_product_listing_rating', 'off' ) ) { ?>
		<div class="product-listing-rating"><?php wc_get_template( 'loop/rating.php' ); ?></div>
	<?php } ?>
	<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
	<?php do_action( 'thb_content_product_end' ); ?>
</li>
