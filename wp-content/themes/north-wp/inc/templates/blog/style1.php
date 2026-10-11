<?php $blog_pagination_style = is_home() ? ot_get_option( 'blog_pagination_style', 'style1' ) : 'style1'; ?>
<div class="row">
	<div class="small-12 columns">
		<div class="row <?php echo esc_attr( 'pagination-' . $blog_pagination_style ); ?> masonry" data-security="<?php echo esc_attr( wp_create_nonce( 'thb_blog_ajax' ) ); ?>">
			<?php
			if ( have_posts() ) :
				while ( have_posts() ) :
					the_post();
					?>
					<?php get_template_part( 'inc/templates/blogbit/style1' ); ?>
			<?php endwhile; else : ?>
				<?php get_template_part( 'inc/templates/not-found' ); ?>
			<?php endif; ?>
		</div>
		<?php do_action( 'thb_blog_pagination' ); ?>
	</div>
</div>
