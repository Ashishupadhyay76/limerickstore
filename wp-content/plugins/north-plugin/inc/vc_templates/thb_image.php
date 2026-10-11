<?php function thb_image( $atts, $content = null ) {
	$atts = vc_map_get_attributes( 'thb_image', $atts );
	extract( $atts );

	$img_id = preg_replace( '/[^\d]/', '', $image );

	$full     = $full_width === 'true' ? 'full' : '';
	$img_size = ( $img_size === '' ? 'full' : $img_size );
	$img      = wpb_getImageBySize(
		array(
			'attach_id'  => $img_id,
			'thumb_size' => $img_size,
			'class'      => 'thb_image ' . $retina,
		)
	);
	if ( $img == null ) {
		$img['thumbnail'] = '<img src="http://placekitten.com/g/400/300" />';
	}

	$link_to       = $c_lightbox = $a_title = $a_target = '';
	$image_post    = get_post( $img_id );
	$image_title   = isset( $image_post->post_title ) ? $image_post->post_title : '';
	$image_caption = isset( $image_post->post_excerpt ) ? $image_post->post_excerpt : '';

	if ( $lightbox == true ) {
		$link       = wp_get_attachment_image_src( $img_id, 'full' );
		$link_to    = $link[0];
		$c_lightbox = ' rel="magnific"';
		$a_title    = $image_title;
	} else {
		$img_link = ( $img_link == '||' ) ? '' : $img_link;
		$link     = vc_build_link( $img_link );
		$link_to  = $link['url'];
		$a_title  = $link['title'];
		$a_target = $link['target'] ? $link['target'] : '_self';
	}

	$classes[] = 'caption-' . $caption_style;
	$classes[] = $animation;
	$classes[] = $alignment;
	$classes[] = $full;
	$classes[] = 'thb_image_link wp-caption';

	$out = '<div class="' . implode( ' ', $classes ) . '">';
	if ( ! empty( $link_to ) ) {
		$out .= '<a ' . $c_lightbox . ' href="' . esc_url( $link_to ) . '"' . ' target="' . sanitize_text_field( $a_target ) . '" title="' . $a_title . '">';
	}
	$out .= $img['thumbnail'];
	if ( ! empty( $link_to ) ) {
		$out .= '</a>';
	}
	if ( $image_caption && $caption === 'true' ) {
		$out .= '<div class="wp-caption-text">' . esc_html( $image_caption ) . '</div>';
	}
	if ( $content ) {
		$out .= '<div class="thb-image-content">';
		$out .= $content;
		$out .= '</div>';
	}
	$out .= '</div>';

	return $out;
}
thb_add_short( 'thb_image', 'thb_image' );
