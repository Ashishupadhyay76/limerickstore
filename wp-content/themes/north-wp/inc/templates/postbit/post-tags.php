<?php
if ( 'on' !== ot_get_option( 'article_tags', 'on' ) ) {
	return;
}
?>
<footer class="article-tags entry-footer">
	<?php
	$posttags = get_the_tags();
	if ( $posttags ) {
		$return = '';
		foreach ( $posttags as $thb_tag ) {
			$return .= '<a href="' . get_tag_link( $thb_tag->term_id ) . '" title="' . get_tag_link( $thb_tag->name ) . '" class="tag-link">' . $thb_tag->name . '</a> ';
		}
		echo substr( $return, 0, -1 );
	}
	?>
</footer>
