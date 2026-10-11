<?php $blog_header = ot_get_option( 'blog_header' ); ?>
<?php if ( $blog_header && is_home() ) { ?>
	<div class="row expanded">
		<div class="small-12 columns">
			<?php echo do_shortcode( $blog_header ); ?>
		</div>
	</div>
	<?php
}
