<?php
	$vc                 = class_exists( 'WPBakeryVisualComposerAbstract' );
	$enable_pagepadding = get_post_meta( get_the_ID(), 'page_padding', true );

	$classes[] = 'on' === $enable_pagepadding ? 'page-padding' : false;
	$classes[] = 'post';
?>
<?php get_header(); ?>
<?php
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		?>
			<?php
			if ( post_password_required() ) {
				get_template_part( 'inc/templates/password-protected' );
			} elseif ( $vc && ! thb_is_woocommerce() ) {
				?>
		<div <?php post_class( $classes ); ?>>
				<?php the_content(); ?>
		</div>
		<?php } elseif ( thb_is_woocommerce() ) { ?>
		<div <?php post_class( 'page-padding' ); ?>>
			<div class="row">
				<div class="small-12 columns">
					<div class="post-content no-vc">
						<?php the_content(); ?>
					</div>
				</div>
			</div>
		</div>
	<?php } else { ?>
		<div <?php post_class( 'page-padding post' ); ?>>
			<div class="row">
				<div class="small-12 columns">
					<header class="post-title page-title">
						<?php the_title( '<h1 class="entry-title" itemprop="name headline">', '</h1>' ); ?>
					</header>
					<div class="post-content no-vc">
						<?php the_content(); ?>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
		<?php if ( comments_open() || get_comments_number() ) : ?>
	<!-- Start #comments -->
			<?php comments_template( '', true ); ?>
	<!-- End #comments -->
	<?php endif; ?>
		<?php
	endwhile;
endif;
?>
<?php
get_footer();
