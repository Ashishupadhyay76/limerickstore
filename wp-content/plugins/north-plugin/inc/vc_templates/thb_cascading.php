<?php function thb_cascading( $atts, $content = null ) {
	$atts = vc_map_get_attributes( 'thb_cascading', $atts );
	extract( $atts );

	if ( $image ) {
		$placeholder_url = thb_wc_supported() ? wc_placeholder_img_src( 'full' ) : 'https://via.placeholder.com/600';
		$placeholder     = array(
			$placeholder_url,
			600,
			600,
		);
		$image_src       = wp_get_attachment_image_src( $image, 'full' ) ? wp_get_attachment_image_src( $image, 'full' ) : $placeholder;
		$image_url       = $image_src ? $image_src[0] : $placeholder_url;
		$image_alt       = get_post_meta( $image, '_wp_attachment_image_alt', true );
	}

	$transform = 'translateX(' . $image_x . ') translateY(' . $image_y . ')';

	$image_classes[] = $thb_box_shadow;
	$image_classes[] = $retina;
	ob_start(); ?>

	<figure class="cascading_image">
		<div class="cascading_inner <?php echo esc_attr( $animation ); ?>">
			<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" style="
								 <?php
									if ( $transform ) {
										?>
				 transform: <?php echo esc_attr( $transform ); ?>;<?php } ?> <?php
					if ( $radius ) {
						?>
				border-radius: <?php echo esc_attr( $radius ); ?>px;<?php } ?>" width="<?php echo esc_attr( $image_src[1] ); ?>" class="<?php echo esc_attr( implode( ' ', $image_classes ) ); ?>"/>
		</div>
	</figure>
	<?php
	$out = ob_get_clean();
	return $out;
}
thb_add_short( 'thb_cascading', 'thb_cascading' );
