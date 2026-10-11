<?php function thb_hotspots( $atts, $content = null ) {
	$atts = vc_map_get_attributes( 'thb_hotspots', $atts );
	extract( $atts );

	$img_id = preg_replace( '/[^\d]/', '', $image );

	$img = wpb_getImageBySize(
		array(
			'attach_id'  => $img_id,
			'thumb_size' => 'full',
		)
	);
	if ( $img == null ) {
		$img['thumbnail'] = '<img src="http://placekitten.com/g/400/300" />'; }

	$spots = json_decode( urldecode( $thb_hotspot_data ) );

	ob_start();
	?>
	<div class="thb-hotspot-wrapper">
		<div class="row no-padding">
			<div class="small-12 medium-6 columns">
			<div class="thb-hotspot-container <?php echo esc_attr( $thb_tooltip_function ); ?>">
				<?php echo $img['thumbnail']; ?>
				<?php
				foreach ( $spots as $key => $spot ) {
					unset( $hotspot_classes );
					$hotspot_classes[] = 'thb-hotspot';
					$hotspot_classes[] = $thb_pin_color;
					$hotspot_classes[] = $animation;
					?>
				<div class="<?php echo esc_attr( implode( ' ', $hotspot_classes ) ); ?>" style="left:<?php echo esc_attr( $spot->x ); ?>%; top:<?php echo esc_attr( $spot->y ); ?>%">
					<div class="thb-hotspot-content <?php echo esc_attr( $thb_pulsate ); ?>"><?php echo esc_attr( $key + 1 ); ?></div>
					<?php $products[] = $spot->Product[0][0]; ?>
				</div>
				<?php } ?>
			</div>
			</div>
			<div class="small-12 medium-6 columns thb-hotspots-products">
				<ul class="products row slick" data-columns="1" data-autoplay="false" data-pagination="true">
					<?php foreach ( $products as $product_id ) { ?>
						<?php if ( thb_wc_supported() ) { ?>
							<?php
							$args         = array(
								'post_type'           => 'product',
								'post_status'         => 'publish',
								'ignore_sticky_posts' => 1,
								'p'                   => $product_id,
							);
							$product_loop = new WP_Query( $args );
							$product      = wc_get_product( $product_id );
							?>
							<?php
							while ( $product_loop->have_posts() ) :
								$product_loop->the_post();
								?>
								<?php
									set_query_var( 'thb_columns', 'small-12 medium-12' );
									wc_get_template_part( 'content', 'product' );
								?>
								<?php
							endwhile;
							wp_reset_postdata();
							?>
						<?php } ?>
					<?php } ?>
				</ul>
			</div>
		</div>
	</div>

	<?php
	$out = ob_get_clean();

	return $out;
}
thb_add_short( 'thb_hotspots', 'thb_hotspots' );
