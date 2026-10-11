<div class="small-12 medium-10 large-9 columns">
	<article itemscope itemtype="http://schema.org/Article" <?php post_class( 'post style2' ); ?>>
		<?php if ( has_post_thumbnail() ) { ?>
		<figure class="post-gallery">
			<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
				<?php the_post_thumbnail( 'north-blog-vertical-2x' ); ?>
			</a>
		</figure>
		<?php } ?>
		<?php get_template_part( 'inc/templates/postbit/post-categories' ); ?>
		<header class="post-title entry-header">
			<?php the_title( '<h3 class="entry-title" itemprop="name headline"><a href="' . get_permalink() . '" title="' . the_title_attribute( 'echo=0' ) . '">', '</a></h3>' ); ?>
		</header>
		<div class="post-content">
			<?php the_excerpt(); ?>
		</div>
		<?php do_action( 'thb_PostMeta' ); ?>
	</article>
</div>
