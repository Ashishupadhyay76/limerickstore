<?php
	add_filter( 'excerpt_length', 'thb_short_excerpt_length' );
?>
<article itemscope itemtype="http://schema.org/Article" <?php post_class( 'post style3' ); ?>>
	<div class="row">
		<div class="small-12 large-6 columns">
			<?php if ( has_post_thumbnail() ) { ?>
			<figure class="post-gallery">
				<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
					<?php the_post_thumbnail( 'north-blog-list-2x' ); ?>
				</a>
			</figure>
			<?php } ?>
		</div>
		<div class="small-12 large-6 columns">
			<?php get_template_part( 'inc/templates/postbit/post-categories' ); ?>
			<header class="post-title entry-header">
				<?php the_title( '<h3 class="entry-title" itemprop="name headline"><a href="' . get_permalink() . '" title="' . the_title_attribute( 'echo=0' ) . '">', '</a></h3>' ); ?>
			</header>
			<div class="post-content">
				<?php the_excerpt(); ?>
			</div>
		</div>
	</div>
	<?php do_action( 'thb_PostMeta' ); ?>
</article>
