<?php function thb_product_categories( $atts, $content = null ) {
	if ( ! thb_wc_supported() ) {
		return;
	}
	$atts = vc_map_get_attributes( 'thb_product_categories', $atts );
	extract( $atts );

	$args = $product_categories = $category_ids = array();
	$cats = explode( ',', $cat );

	foreach ( $cats as $cat ) {
		$c = get_term_by( 'slug', $cat, 'product_cat' );

		if ( $c ) {
			array_push( $category_ids, $c->term_id );
		}
	}

	$args = array(
		'orderby'    => $category_order,
		'order'      => 'ASC',
		'hide_empty' => '0',
		'include'    => $category_ids,
	);
	if ( $category_order === 'count' ) {
		$args['order'] = 'DESC';
	}
	$product_categories = get_terms( 'product_cat', $args );

	ob_start();

	$classes[] = 'row products';
	$classes[] = $carousel === 'true' ? 'carousel slick' : false;
	$classes[] = $style;
	$classes[] = $column_padding;
	?>

	<ul class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>" data-navigation="true">
		<?php
		if ( $product_categories ) {

			foreach ( $product_categories as $category ) {
				$params = array(
					'category' => $category,
				);
				if ( ! $carousel ) {
					$col             = thb_translate_columns( $columns );
					$params['class'] = $col;
				}

				$file = 'style1' === $style ? 'content-product_cat.php' : 'content-product_cat-style3.php';
				wc_get_template( $file, $params );
			}
		}
			woocommerce_reset_loop();
		?>
	</ul>

	<?php

	$out = ob_get_clean();

	return $out;
}
thb_add_short( 'thb_product_categories', 'thb_product_categories' );
