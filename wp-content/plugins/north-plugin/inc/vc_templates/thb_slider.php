<?php function thb_slider( $atts, $content = null ) {
	$atts = vc_map_get_attributes( 'thb_slider', $atts );
	extract( $atts );
	$images = explode( ',', $images );
	ob_start();

	$nav = ( $navigation == 'true' ? 'true' : 'false' );
	?>
	<div class="carousel slick image-slider" data-columns="1" data-navigation="<?php echo esc_attr( $nav ); ?>">
		<?php
		foreach ( $images as $image ) {
			?>
				<figure>
				<?php echo wp_get_attachment_image( $image, 'full' ); ?>
				</figure>
				<?php
		}
		?>
	</div>
	<?php
	$out = ob_get_clean();
	return $out;
}
thb_add_short( 'thb_slider', 'thb_slider' );
