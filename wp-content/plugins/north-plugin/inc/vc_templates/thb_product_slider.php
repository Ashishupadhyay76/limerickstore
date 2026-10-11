<?php function thb_product_slider( $atts, $content = null ) {
	if ( ! thb_wc_supported() ) {
		return;
	}
	$atts = vc_map_get_attributes( 'thb_product_slider', $atts );
	extract( $atts );

	$args = array();

	if ( $product_sort == 'latest-products' ) {
		$args = array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'posts_per_page'      => $item_count,
		);
	} elseif ( $product_sort == 'featured-products' ) {
		$args = array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'tax_query'           => array(
				array(
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => 'featured',
					'operator' => 'IN',
				),
			),
			'posts_per_page'      => $item_count,
		);
	} elseif ( $product_sort == 'top-rated' ) {
		$ordering_args = WC()->query->get_catalog_ordering_args( 'rating', 'asc' );

		$args = array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'posts_per_page'      => $item_count,
			'meta_key'            => $ordering_args['meta_key'],
			'orderby'             => $ordering_args['orderby'],
			'order'               => $ordering_args['order'],
		);

	} elseif ( $product_sort == 'sale-products' ) {
		$args = array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'posts_per_page'      => $item_count,
			'meta_query'          => array(
				'relation' => 'OR',
				array( // Simple products type
					'key'     => '_sale_price',
					'value'   => 0,
					'compare' => '>',
					'type'    => 'numeric',
				),
				array( // Variable products type
					'key'     => '_min_variation_sale_price',
					'value'   => 0,
					'compare' => '>',
					'type'    => 'numeric',
				),
			),
		);
	} elseif ( $product_sort == 'by-category' ) {
		$args = array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'product_cat'         => $cat,
			'posts_per_page'      => $item_count,
		);
	} elseif ( $product_sort == 'by-id' ) {
		$product_id_array = explode( ',', $product_ids );
		$args             = array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'post__in'            => $product_id_array,
		);
	} else {
		$args = array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => 1,
			'order'               => 'desc',
			'posts_per_page'      => $item_count,
			'meta_key'            => 'total_sales',
			'orderby'             => 'meta_value_num',
		);
	}
	$args['meta_query'] = WC()->query->get_meta_query();
	ob_start();
	$products = new WP_Query( $args );

	if ( $products->have_posts() ) { ?>
		<div class="thb-product-slider carousel slick" data-columns="1" data-pagination="true" data-autoplay="false" data-fade="true">
			<?php
			while ( $products->have_posts() ) :
				$products->the_post();
				?>
		<div class="row no-padding">
			<div class="small-12 medium-6 columns image-side">
				<?php
				$product        = wc_get_product( get_the_id() );
				$featured       = wp_get_attachment_url( get_post_thumbnail_id(), 'shop_catalog' );
				$attachment_ids = $product->get_gallery_image_ids();
				if ( $attachment_ids ) {
					$loop = 0;
					foreach ( $attachment_ids as $attachment_id ) {
						$image_link = wp_get_attachment_url( $attachment_id );
						if ( ! $image_link ) {
							continue; }
						$loop++;
						$thumbnail_second = $attachment_id;
						if ( $image_link !== $featured ) {
							if ( $loop == 1 ) {
								break; }
						}
					}
				}
				?>
				<?php
				if ( $thumbnail_second ) {
					echo wp_get_attachment_image( $thumbnail_second, 'full' ); }
				?>
			</div>
			<ul class="small-12 medium-6 columns products">
				<?php
				set_query_var( 'thb_columns', 'small-12 medium-6' );
				wc_get_template( 'content-product.php' );
				?>
			</ul>
		</div>
			<?php endwhile; // end of the loop. ?>
		</div>
		<?php
	}

	$out = ob_get_clean();
	set_query_var( 'thb_columns', false );
	wp_reset_query();
	wp_reset_postdata();

	return $out;
}
thb_add_short( 'thb_product_slider', 'thb_product_slider' );
