<?php
/*
Template Name: Snap Scroll
*/
?>
<?php
	$disable_scroll = get_post_meta( get_the_ID(), 'disable_scroll', true );
?>
<?php get_header(); ?>
<?php
if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		?>
		<div id="thb_fullscreen_rows" data-disable-scroll="<?php echo esc_attr( $disable_scroll ); ?>">
			<?php the_content(); ?>
		</div>
		<?php
	endwhile;
endif;
?>
<?php
get_footer();
