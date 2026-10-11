<?php function thb_button_block( $atts, $content = null ) {
	$atts = vc_map_get_attributes( 'thb_button_block', $atts );
	extract( $atts );

	$img_id = preg_replace( '/[^\d]/', '', $image );

	$link = ( $link == '||' ) ? '' : $link;
	$link = vc_build_link( $link );

	$link_to  = $link['url'];
	$a_title  = $link['title'];
	$a_target = $link['target'] ? $link['target'] : '_self';

	$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), 'thb_button_block', $atts );

	$class[] = 'btn-block';
	$class[] = $extra_class;
	$class[] = $animation;
	$class[] = $thb_style;
	$class[] = $css_class;
	$out     = '';

	ob_start();
	?>
	<a class="<?php echo esc_attr( implode( ' ', $class ) ); ?>" href="<?php echo esc_attr( $link_to ); ?>" target="<?php echo sanitize_text_field( $a_target ); ?>" role="button" title="<?php echo esc_attr( $a_title ); ?>">
		<div class="cover-bg"><?php echo wp_get_attachment_image( $img_id, 'full' ); ?></div>
	  <span><?php echo esc_attr( $a_title ); ?></span>
	</a>
	<?php
	$out = ob_get_clean();

	return $out;
}
thb_add_short( 'thb_button_block', 'thb_button_block' );
