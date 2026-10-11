<?php
$article_author_name = ot_get_option( 'article_author_name', 'on' );
$article_date        = ot_get_option( 'article_date', 'on' );
$article_cat         = ot_get_option( 'article_cat', 'on' );
?>
<article itemscope itemtype="http://schema.org/Article" <?php post_class( 'post post-detail style1-detail' ); ?>>
	<figure class="post-gallery parallax">
		<?php
		if ( has_post_thumbnail() ) {
			$image_id  = get_post_thumbnail_id();
			$image_url = wp_get_attachment_image_src( $image_id, 'full' ); }
		?>
		<div class="parallax_bg"
					data-top-bottom="transform: translate3d(0px, 40%, 0px);"
					data-top="transform: translate3d(0px, 0%, 0px);"
					style="
					<?php
					if ( has_post_thumbnail() ) {
						?>
						background-image: url(<?php echo esc_html( $image_url[0] ); ?>); <?php } ?>"></div>
	</figure>
	<header class="post-title entry-header">
		<div class="row align-center">
			<div class="small-12 medium-10 large-7 columns">
				<?php if ( 'on' === $article_cat ) { ?>
					<?php get_template_part( 'inc/templates/postbit/post-categories' ); ?>
				<?php } ?>
				<?php the_title( '<h1 class="entry-title" itemprop="name headline">', '</h1>' ); ?>
				<?php if ( 'on' === $article_author_name ) { ?>
				<aside class="post-author">
					<?php esc_html_e( 'By', 'north' ); ?> <?php the_author_posts_link(); ?>
				</aside>
			<?php } ?>
			</div>
		</div>
	</header>
	<div class="row align-center">
		<div class="small-12 medium-10 large-7 columns">
			<div class="post-content">
				<?php
				// The following determines what the post format is and shows the correct file accordingly
				$format = get_post_format();
				if ( in_array( $format, array( 'gallery', 'video' ), true ) ) {
					get_template_part( 'inc/templates/postformats/' . $format );
				}
				?>
				<?php the_content(); ?>
				<?php wp_link_pages(); ?>
			</div>
			<?php get_template_part( 'inc/templates/postbit/post-tags' ); ?>
		</div>
	</div>
	<?php do_action( 'thb_PostMeta' ); ?>
</article>
