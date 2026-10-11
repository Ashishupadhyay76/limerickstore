<?php get_header(); ?>
<div class="page-padding">
	<h1 class="thb-shop-title"><?php single_post_title(); ?></h1>
	<?php
		$blog_style = ( isset( $_GET['blog_style'] ) ? wp_unslash( $_GET['blog_style'] ) : ot_get_option( 'blog_style', 'style1' ) );
		set_query_var( 'thb_excerpt', true );
		get_template_part( 'inc/templates/blog/' . $blog_style );
	?>
</div>
<?php
get_footer();
