<?php function thb_add_to_cart( $atts, $content = null ) {
	if ( ! thb_wc_supported() ) {
		return;
	}
	$atts = vc_map_get_attributes( 'thb_add_to_cart', $atts );
	extract( $atts );

	$out = '';
	if ( ! empty( $id ) ) {
		$product_data = get_post( $id );
	}
	$product = wc_setup_product_data( $product_data );

	if ( ! $product ) {
		return;
	}
	ob_start();

	?>
	<div class="thb_product_add_to_cart_shortcode <?php echo esc_attr( $align ); ?>">
	<?php if ( $title == 'true' ) { ?>
		<h5><a href="<?php echo esc_url( $product->get_permalink() ); ?>" title="<?php echo esc_attr( $product->get_title() ); ?>"><?php echo esc_attr( $product->get_title() ); ?></a></h5>
	<?php } ?>
	<?php if ( $excerpt == 'true' ) { ?>
		<div class="woocommerce-product-details__short-description">
			<?php echo apply_filters( 'woocommerce_short_description', $product_data->post_excerpt ); ?>
		</div>
	<?php } ?>
	<?php if ( $price == 'true' ) { ?>
		<div class="price"><?php echo $product->get_price_html(); ?></div>
	<?php } ?>
	<?php
		$args = array(
			'class' => implode(
				' ',
				array_filter(
					array(
						$btn_style,
						$btn_color,
						'button',
						'product_type_' . $product->get_type(),
						$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
						$product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
					)
				)
			),
		);
		$args = apply_filters( 'woocommerce_loop_add_to_cart_args', $args, $product );
		wc_get_template( 'loop/add-to-cart.php', $args );
		?>
	</div>
	<?php
	$out = ob_get_clean();
	return $out;
}
thb_add_short( 'thb_add_to_cart', 'thb_add_to_cart' );
