<?php function thb_lookbook( $atts, $content = null ) {
	if ( ! thb_wc_supported() ) {
		return;
	}
	$atts = vc_map_get_attributes( 'thb_lookbook', $atts );
	extract( $atts );

	$img_id = preg_replace( '/[^\d]/', '', $image );

	$img = wpb_getImageBySize(
		array(
			'attach_id'  => $img_id,
			'thumb_size' => 'full',
		)
	);
	if ( $img == null ) {
		$img['thumbnail'] = '<img src="http://placekitten.com/g/400/300" />';
	}

	$posts      = vc_build_loop_query( $source );
	$look_posts = $posts[1];
	$full       = $full_width === 'true' ? 'full' : '';

	$classes[] = 'thb_lookbook';
	$classes[] = $full;
	$classes[] = $animation;

	ob_start();
	?>

	<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
		<?php echo $img['thumbnail']; ?>
		<aside class="shop_this_look">
			<div class="lookbook_products">
			<?php
			if ( $look_posts->have_posts() ) :
				while ( $look_posts->have_posts() ) :
					$look_posts->the_post();
					?>
				<a class="lookbook_product_link" href="<?php the_permalink(); ?>">
					<div>
										<?php the_post_thumbnail(); ?>
						<span><?php the_title(); ?></span>
					</div>
									<?php wc_get_template( 'loop/price.php' ); ?>
				</a>
							<?php
			endwhile;
endif;
			?>
			</div>
			<h6><?php esc_html_e( 'Shop This Look', 'north' ); ?></h6>
		</aside>
	</div>

	<?php
	$out = ob_get_clean();

	wp_reset_query();
	wp_reset_postdata();

	return $out;
}
thb_add_short( 'thb_lookbook', 'thb_lookbook' );
