<?php get_header(); ?>
<div class="blog-container">
	<?php
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			?>
			<?php
			$article_style = ot_get_option( 'article_style', 'style1' );
			get_template_part( 'inc/templates/postbit/' . $article_style );
			?>
			<?php do_action( 'thb_post_nav' ); ?>
			<?php if ( comments_open() || get_comments_number() ) : ?>
		<!-- Start #comments -->
				<?php comments_template( '', true ); ?>
		<!-- End #comments -->
		<?php endif; ?>

			<?php
	endwhile;
endif;
	?>
</div>
<?php
get_footer();
