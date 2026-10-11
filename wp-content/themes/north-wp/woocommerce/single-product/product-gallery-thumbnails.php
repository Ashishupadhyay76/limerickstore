<?php
/**
 * Single Product Thumbnails
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-thumbnails.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     3.5.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post, $product;

$shop_product_thumbnail_layout = ot_get_option( 'shop_product_thumbnail_layout', 'style1' );
$attachment_ids                = $product->get_gallery_image_ids();
$columns                       = 'style2' === $shop_product_thumbnail_layout ? '5' : '4';

if ( $attachment_ids && $product->get_image_id() ) {
	?>
	<div id="product-thumbnails" class="product-thumbnails carousel slick" data-navigation="false" data-autoplay="false" data-columns="<?php echo esc_attr( $columns ); ?>" data-asnavfor="#product-images" data-infinite="false" data-product-thumbnail-layout="<?php echo esc_attr( $shop_product_thumbnail_layout ); ?>">
		<?php
		if ( $product->get_image_id() ) :
			?>
			<?php
				echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', wc_get_gallery_image_html( $product->get_image_id() ), $product->get_image_id() );
			?>
			<?php
		endif;
		foreach ( $attachment_ids as $attachment_id ) {
			echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', wc_get_gallery_image_html( $attachment_id ), $attachment_id );
		}
		?>
	</div>
	<?php
}
