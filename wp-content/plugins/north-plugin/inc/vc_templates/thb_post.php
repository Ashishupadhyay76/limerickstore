<?php function thb_post( $atts, $content = null ) {
	$atts = vc_map_get_attributes( 'thb_post', $atts );
	extract( $atts );
	$rand = wp_rand( 0, 1000 );
	if ( $offset ) {
		$source .= '|offset:' . $offset;
	}
	$source_data   = VcLoopSettings::parseData( $source );
	$query_builder = new ThbLoopQueryBuilder( $source_data );
	$thb_posts     = $query_builder->build();
	$posts         = $thb_posts[1];

	$classes[] = 'row posts-shortcode align-stretch';
	$classes[] = 'true' === $loadmore ? 'posts-pagination-style2' : '';
	ob_start();
	?>
	<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" data-loadmore="#loadmore-<?php echo esc_attr( $rand ); ?>" data-security="<?php echo esc_attr( wp_create_nonce( 'thb_posts_ajax' ) ); ?>">
		<?php
		if ( $posts->have_posts() ) :
			while ( $posts->have_posts() ) :
				$posts->the_post();
				set_query_var( 'columns', $columns );
				set_query_var( 'thb_excerpt', $thb_excerpt );
				get_template_part( 'inc/templates/blogbit/style4' );
			endwhile;
		endif;
		?>
	</div>
	<?php if ( 'true' === $loadmore ) { ?>
		<?php
		wp_localize_script(
			'thb-app',
			esc_attr( 'thb_postsajax_' . $rand ),
			array(
				'count'    => $source_data['size'],
				'loop'     => $source,
				'columns'  => $columns,
				'excerpts' => $thb_excerpt,
			)
		);
		?>
	<div class="row">
		<div class="small-12 columns text-center">
			<a href="#" class="thb_load_more masonry_btn btn" title="<?php esc_html_e( 'Load More', 'north' ); ?>" data-posts-id="<?php echo esc_attr( $rand ); ?>" id="loadmore-<?php echo esc_attr( $rand ); ?>"><?php esc_html_e( 'Load More', 'north' ); ?></a>
		</div>
	</div>
	<?php } ?>
	<?php

	$out = ob_get_clean();

	wp_reset_query();
	wp_reset_postdata();

	return $out;
}
thb_add_short( 'thb_post', 'thb_post' );
