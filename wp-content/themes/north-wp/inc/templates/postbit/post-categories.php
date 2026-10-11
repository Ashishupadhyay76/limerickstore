<aside class="post-meta">
	<time class="time" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_time( get_option( 'date_format' ) ); ?></a></time> - <?php the_category( ', ' ); ?>
</aside>
