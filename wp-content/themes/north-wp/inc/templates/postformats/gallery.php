<?php
	$thb_id = get_the_ID();

	$post_gallery_photos = get_post_meta( $thb_id, 'pp_gallery_slider', true );
if ( $post_gallery_photos ) {
	$post_gallery_photos_arr = explode( ',', $post_gallery_photos );
} else {
	return;
}
?>
<div class="slick row post-format-gallery-container" data-columns="1" data-pagination="true">
	<?php foreach ( $post_gallery_photos_arr as $photo ) { ?>
		<div class="columns">
			<?php echo wp_get_attachment_image( $photo, 'north-blog-vertical' ); ?>
		</div>
	<?php } ?>
</div>
