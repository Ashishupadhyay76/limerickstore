<?php function thb_button( $atts, $content = null ) {
	$animation = '';
	$atts      = vc_map_get_attributes( 'thb_button', $atts );
	extract( $atts );

	$full_width = $full_width === 'true' ? 'full' : '';

	$link = ( $link == '||' ) ? '' : $link;
	$link = vc_build_link( $link );

	$link_to  = $link['url'];
	$a_title  = $link['title'];
	$a_target = $link['target'] ? $link['target'] : '_self';

	$class[] = 'btn';
	$class[] = $style;
	$class[] = $full_width;
	$class[] = $color;
	$class[] = $animation;
	$class[] = $size;
	$class[] = $extra_class;

	$out = '';

	ob_start();
	?>
	<a class="<?php echo esc_attr( implode( ' ', $class ) ); ?>" href="<?php echo esc_attr( $link_to ); ?>" target="<?php echo sanitize_text_field( $a_target ); ?>" role="button" title="<?php echo esc_attr( $a_title ); ?>"><span><?php echo esc_attr( $a_title ); ?></span></a>

	<?php
	$out = ob_get_clean();

	return $out;
}
thb_add_short( 'thb_button', 'thb_button' );
