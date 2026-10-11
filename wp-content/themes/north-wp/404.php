<?php get_header(); ?>
<div class="row align-center content404">
	<div class="small-12 medium-9 large-6 columns text-center">
		<?php get_template_part( 'assets/img/svg/404.svg' ); ?>
		<h2><?php esc_html_e( 'Page cannot be found.', 'north' ); ?></h2>
		<p><?php esc_html_e( "We are sorry, but the page you're looking for cannot be found.", 'north' ); ?></p>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn"><?php esc_html_e( 'BACK TO HOME', 'north' ); ?></a>
	</div>
</div>
<?php
get_footer();
