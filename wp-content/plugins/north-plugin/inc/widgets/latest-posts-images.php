<?php
// thb latest Posts w/ Images
class widget_latestimages extends WP_Widget {
	function __construct() {
		$widget_ops = array(
			'classname'   => 'widget_latestimages',
			'description' => esc_html__( 'Display latest posts with images', 'north' ),
		);

		parent::__construct(
			'thb_latestimages_widget',
			esc_html__( 'Fuel Themes - Latest Posts with Images', 'north' ),
			$widget_ops
		);

		$this->defaults = array(
			'title'          => '',
			'layout'         => 'thumbnail',
			'posts_per_page' => 5,
			'orderby'        => 'date',
			'order'          => 'desc',
			'time_frame'     => '',
			'category'       => false,
		);
	}

	function widget( $args, $instance ) {
		$params = array_merge( $this->defaults, $instance );

		$query_args = array(
			'posts_per_page'      => $params['posts_per_page'],
			'order'               => $params['order'],
			'no_found_rows'       => true,
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
		);

		// Category
		if ( $params['category'] ) {
			$query_args['cat'] = $params['category'];
		}

		// Post order.
		if ( 'comments' === $params['orderby'] ) {
			$query_args['orderby'] = 'comment_count';
		} elseif ( 'rand' === $params['orderby'] ) {
			$query_args['orderby'] = 'rand';
		}

		// Time Frame
		if ( $params['time_frame'] ) {
			$query_args['date_query'] = array(
				array(
					'column' => 'post_date_gmt',
					'after'  => $params['time_frame'] . ' ago',
				),
			);
		}
		// Before Widget.
		echo $args['before_widget'];

		// Title.
		if ( $params['title'] ) {
			echo wp_kses_post( $args['before_title'] . apply_filters( 'widget_title', $params['title'], $instance, $this->id_base ) . $args['after_title'] );
		}

		$widget_posts = new WP_Query( $query_args );

		if ( $params['layout'] == 'slider' ) {
			?>
			<div class="thb-carousel equal-height-carousel" data-columns="1" data-pagination="true" data-infinite="false">
			<?php
			while ( $widget_posts->have_posts() ) :
				$widget_posts->the_post();
				// get_template_part( 'inc/templates/post-styles/misc/widget-slider');
			endwhile;
			?>
			</div>
			<?php
		} elseif ( $params['layout'] == 'thumbnail' ) {
			?>
			<ul>
				<?php
				$i = 1;
				while ( $widget_posts->have_posts() ) :
					$widget_posts->the_post();
					?>
					<li <?php post_class( 'post listing' ); ?>>
						<a href="<?php the_permalink(); ?>" class="post-gallery">
							<?php the_post_thumbnail(); ?>
						</a>
						<div class="listing_content">
							<div class="post-title">
								<?php the_title( '<h6 class="entry-title" itemprop="name headline"><a href="' . get_permalink() . '" title="' . the_title_attribute( 'echo=0' ) . '">', '</a></h6>' ); ?>
							</div>
							<aside class="post-meta">
								<?php echo get_the_date(); ?>
							</aside>
						</div>
					</li>
								<?php
								$i++;
endwhile;
				?>
			</ul>
			<?php
		} elseif ( $params['layout'] == 'large' ) {
			while ( $widget_posts->have_posts() ) :
				$widget_posts->the_post();
				// get_template_part( 'inc/templates/post-styles/misc/widget-large');
			endwhile;
		}

		wp_reset_postdata();
		echo $args['after_widget'];
	}
	function update( $new_instance, $old_instance ) {
		$instance = $new_instance;

		return $instance;
	}
	function form( $instance ) {
		$params = array_merge( $this->defaults, $instance );
		?>
			<!-- Title -->
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'north' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $params['title'] ); ?>" /></p>

			<!-- Number of Posts -->
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'posts_per_page' ) ); ?>"><?php esc_html_e( 'Number of Posts', 'north' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'posts_per_page' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'posts_per_page' ) ); ?>" type="number" value="<?php echo esc_attr( $params['posts_per_page'] ); ?>" /></p>

			<!-- Order by -->
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>"><?php esc_html_e( 'Order by', 'north' ); ?></label>
				<select name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>" class="widefat">
					<option value="date" <?php selected( $params['orderby'], 'date' ); ?>><?php esc_html_e( 'Date', 'north' ); ?></option>
					<option value="comment_count" <?php selected( $params['orderby'], 'comment_count' ); ?>><?php esc_html_e( 'Comments', 'north' ); ?></option>
					<option value="rand" <?php selected( $params['orderby'], 'rand' ); ?>><?php esc_html_e( 'Random', 'north' ); ?></option>
				</select>
			</p>

			<!-- Order -->
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>"><?php esc_html_e( 'Order', 'north' ); ?></label>
				<select name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>" class="widefat">
					<option value="desc" <?php selected( $params['order'], 'desc' ); ?>><?php esc_html_e( 'Descending', 'north' ); ?></option>
					<option value="asc" <?php selected( $params['order'], 'asc' ); ?>><?php esc_html_e( 'Ascending', 'north' ); ?></option>
				</select>
			</p>

			<!-- Time Frame -->
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'time_frame' ) ); ?>"><?php esc_html_e( 'Time Frame', 'north' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'time_frame' ) ); ?>" placeholder="<?php esc_html_e( '3 months', 'north' ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'time_frame' ) ); ?>" type="text" value="<?php echo esc_attr( $params['time_frame'] ); ?>" /></p>

			<!-- Category -->
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>"><?php esc_html_e( 'Category', 'north' ); ?></label>
				<select name="<?php echo esc_attr( $this->get_field_name( 'category' ) ); ?>[]" id="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>" class="widefat" style="height: auto !important;" multiple="multiple" size="8">
					<?php
						$cat_args = array(
							'hide_empty'   => 0,
							'hierarchical' => 1,
							'selected'     => (array) $params['category'],
							'walker'       => new THB_Posts_Categories_Tree_Walker(),
						);

						$allowed_html = array(
							'option' => array(
								'class'    => true,
								'value'    => true,
								'selected' => true,
							),
						);

						echo wp_kses( walk_category_dropdown_tree( get_categories( $cat_args ), 0, $cat_args ), $allowed_html );
						?>
				</select>
			</p>
		<?php
	}
}
