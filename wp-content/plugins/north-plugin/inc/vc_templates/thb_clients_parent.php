<?php function thb_clients_parent( $atts, $content = null ) {
	global $thb_columns, $thb_border_color, $thb_style, $thb_clients_animation, $retina;
	$atts = vc_map_get_attributes( 'thb_clients_parent', $atts );
	extract( $atts );

	$thb_clients_animation = $animation;
	$element_id            = uniqid( 'thb-client-logos-' );

	$el_class[] = 'thb-client-row';
	if ( $thb_style !== 'style3' ) {
		$el_class[] = $thb_hover_effect;
	}
	if ( $thb_style === 'thb-carousel' ) {
		$thb_style = 'slick';
	}
	if ( $thb_image_borders == 'true' ) {
		$el_class[] = 'has-border';
	}

	$out = '';
	ob_start();

	?>
	<div id="<?php echo esc_attr( $element_id ); ?>" class="<?php echo esc_attr( implode( ' ', $el_class ) ); ?>">
		<div class="row 
		<?php
		if ( 'style3' !== $thb_style ) {
			?>
			no-padding<?php } ?> <?php echo esc_attr( $thb_style ); ?>" data-columns="<?php echo esc_attr( $thb_columns ); ?>" data-autoplay="<?php echo esc_attr( $autoplay ); ?>" data-autoplay-speed="<?php echo esc_attr( $autoplay_speed ); ?>" data-pagination="true">
			<?php echo wpb_js_remove_wpautop( $content, false ); ?>
		</div>
	</div>
	<?php
	$out = ob_get_clean();
	return $out;
}
thb_add_short( 'thb_clients_parent', 'thb_clients_parent' );
