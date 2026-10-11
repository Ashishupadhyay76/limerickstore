<?php function thb_testimonial( $atts, $content = null ) {
	global $thb_style;
	$atts = vc_map_get_attributes( 'thb_testimonial', $atts );
	extract( $atts );
	$img_size = 'testimonial-style3' === $thb_style ? 'full' : 'thumbnail';
	$image    = wpb_getImageBySize(
		array(
			'attach_id'  => $author_image,
			'thumb_size' => $img_size,
			'class'      => 'author_image retina_size hide',
		)
	);

	$el_class[] = 'thb-testimonial';
	$out        = '';
	ob_start();

	?>
	<div class="thb-testimonial">
		<blockquote><?php echo wpautop( $quote ); ?></blockquote>
		<?php
		if ( $author_image ) {
			echo $image['thumbnail']; }
		?>
		<?php if ( $author_name ) { ?>
			<div>
				<cite><?php echo esc_html( $author_name ); ?></cite>
				<span class="title"><?php echo esc_html( $author_title ); ?></span>
			</div>
		<?php } ?>
	</div>
	<?php
	$out = ob_get_clean();
	return $out;
}
thb_add_short( 'thb_testimonial', 'thb_testimonial' );
