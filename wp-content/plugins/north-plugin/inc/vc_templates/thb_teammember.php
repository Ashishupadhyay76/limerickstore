<?php function thb_teammember( $atts, $content = null ) {
	$atts = vc_map_get_attributes( 'thb_teammember', $atts );
	extract( $atts );

	if ( ! $image ) {
		return;
	}
	$img_id = preg_replace( '/[^\d]/', '', $image );
	$image  = wpb_getImageBySize(
		array(
			'attach_id'  => $image,
			'thumb_size' => 'full',
		)
	);

	$out = '';
	ob_start();

	$el_class[] = 'thb-team-member';

	?>
<div class="<?php echo esc_attr( implode( ' ', $el_class ) ); ?>">
	<?php echo $image['thumbnail']; ?>
		<div class="overlay">
			<div class="thb-member-information">
				<?php if ( $name ) { ?>
					<h5><?php echo esc_html( $name ); ?></h5>
				<?php } ?>
				<?php if ( $position ) { ?>
					<p><?php echo esc_html( $position ); ?></p>
				<?php } ?>
			</div>
			<div class="thb-member-social">
				<?php if ( $facebook ) { ?>
					<a href="<?php esc_url( $facebook ); ?>" class="facebook" target="_blank"><i class="fa fa-facebook"></i></a>
				<?php } ?>
				<?php if ( $twitter ) { ?>
					<a href="<?php esc_url( $twitter ); ?>" class="twitter" target="_blank"><i class="fa fa-twitter"></i></a>
				<?php } ?>
				<?php if ( $pinterest ) { ?>
					<a href="<?php esc_url( $pinterest ); ?>" class="pinterest" target="_blank"><i class="fa fa-pinterest"></i></a>
				<?php } ?>
				<?php if ( $linkedin ) { ?>
					<a href="<?php esc_url( $linkedin ); ?>" class="linkedin" target="_blank"><i class="fa fa-linkedin"></i></a>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php
	$out = ob_get_clean();
	return $out;
}
thb_add_short( 'thb_teammember', 'thb_teammember' );
