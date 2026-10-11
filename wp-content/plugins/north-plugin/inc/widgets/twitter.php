<?php
// thb twitter
class thb_twitter extends WP_Widget {
	function __construct() {
		$widget_ops = array(
			'classname'   => 'thb_widget_twitter',
			'description' => esc_html__( 'Display Your Twitter Feed', 'north' ),
		);

		parent::__construct(
			'thb_twitter_widget',
			esc_html__( 'Fuel Themes - Twitter', 'north' ),
			$widget_ops
		);

		$this->defaults = array(
			'title'    => '',
			'layout'   => 'list',
			'username' => 'ripple',
			'show'     => 5,
		);
	}

	function widget( $args, $instance ) {
		$params = array_merge( $this->defaults, $instance );

		// Before Widget.
		echo $args['before_widget'];

		// Title.
		if ( $params['title'] ) {
			echo wp_kses_post( $args['before_title'] . apply_filters( 'widget_title', $params['title'], $instance, $this->id_base ) . $args['after_title'] );
		}
		$tweets = thb_gettweets( $params['show'], $params['username'] );
		if ( array_key_exists( 'errors', $tweets ) ) {
			echo esc_html( $tweets['errors'][0]['message'] );
			return;
		}
		$twitter_data = thb_twitter_data( $tweets );

		if ( $params['layout'] == 'slider' ) { ?>
		<div class="thb-twitter-carousel-wrapper">
			<div class="thb-twitter-carousel-header">
				<?php get_template_part( 'assets/img/svg/twitter.svg' ); ?>
			</div>
			<div class="thb-carousel slick" data-columns="1" data-fade="true" data-autoplay="1">
				<?php foreach ( $twitter_data['tweets'] as $tweet ) { ?>
					<div class="thb-tweet">
						<div class="thb-tweet-text"><?php echo wp_kses_post( $tweet['text'] ); ?></div>
						<div class="thb-tweet-time"><a href="https://twitter.com/<?php echo esc_html( $twitter_data['screen_name'] ); ?>/status/<?php echo esc_attr( $tweet['id'] ); ?>" target="_blank"><?php echo esc_html( $tweet['time'] ); ?></a></div>
						<div class="thb-tweet-actions">
							<a href="https://twitter.com/intent/tweet?in_reply_to=<?php echo esc_attr( $tweet['id'] ); ?>" class="social"><?php get_template_part( 'assets/img/svg/comment.svg' ); ?></a>
							<a href="https://twitter.com/intent/retweet?tweet_id=<?php echo esc_attr( $tweet['id'] ); ?>" class="social"><?php get_template_part( 'assets/img/svg/rt.svg' ); ?>
																							<?php
																							if ( $tweet['retweet_count'] ) {
																								?>
								<span class="tweet-count"><?php echo thb_numberAbbreviation( $tweet['retweet_count'] ); ?></span><?php } ?></a>
							<a href="https://twitter.com/intent/favorite?tweet_id=<?php echo esc_attr( $tweet['id'] ); ?>" class="social"><?php get_template_part( 'assets/img/svg/like.svg' ); ?>
																							 <?php
																								if ( $tweet['fav_count'] ) {
																									?>
								<span class="tweet-count"><?php echo thb_numberAbbreviation( $tweet['fav_count'] ); ?></span><?php } ?></a>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
		<?php } else { ?>
		<div class="thb-twitter-header">
			<a href="https://twitter.com/<?php echo esc_attr( $twitter_data['screen_name'] ); ?>" target="_blank">
				<img src="<?php echo esc_url( $twitter_data['background'] ); ?>" alt="<?php echo esc_attr( $twitter_data['name'] ); ?>" class="thb_twitter_header_bg"/>
				<img src="<?php echo esc_url( $twitter_data['avatar'] ); ?>" alt="<?php echo esc_attr( $twitter_data['name'] ); ?>" class="thb_twitter_avatar"/>
			</a>
		</div>
		<div class="thb-twitter-content">
			<div class="thb-twitter-user">
				<span class="thb-twitter-username"><?php echo esc_html( $twitter_data['name'] ); ?></span><span class="thb-twitter-screenname">@<?php echo esc_html( $twitter_data['screen_name'] ); ?></span>
				<div class="thb-twitter-usermeta">
					<span><?php echo thb_numberAbbreviation( $twitter_data['following'] ); ?> <?php esc_html_e( 'Following', 'north' ); ?></span>
					<span><?php echo thb_numberAbbreviation( $twitter_data['followers'] ); ?> <?php esc_html_e( 'Followers', 'north' ); ?></span>
				</div>
			</div>
			<div class="thb-twitter-tweets">
				<?php foreach ( $twitter_data['tweets'] as $tweet ) { ?>
					<div class="thb-tweet">
						<?php get_template_part( 'assets/img/svg/twitter.svg' ); ?>
						<div class="thb-tweet-text"><?php echo wp_kses_post( $tweet['text'] ); ?></div>
						<div class="thb-tweet-time"><a href="https://twitter.com/<?php echo esc_html( $twitter_data['screen_name'] ); ?>/status/<?php echo esc_attr( $tweet['id'] ); ?>" target="_blank"><?php echo esc_html( $tweet['time'] ); ?></a></div>
						<div class="thb-tweet-actions">
							<a href="https://twitter.com/intent/tweet?in_reply_to=<?php echo esc_attr( $tweet['id'] ); ?>" class="social"><?php get_template_part( 'assets/img/svg/comment.svg' ); ?></a>
							<a href="https://twitter.com/intent/retweet?tweet_id=<?php echo esc_attr( $tweet['id'] ); ?>" class="social"><?php get_template_part( 'assets/img/svg/rt.svg' ); ?>
																							<?php
																							if ( $tweet['retweet_count'] ) {
																								?>
								<span class="tweet-count"><?php echo thb_numberAbbreviation( $tweet['retweet_count'] ); ?></span><?php } ?></a>
							<a href="https://twitter.com/intent/favorite?tweet_id=<?php echo esc_attr( $tweet['id'] ); ?>" class="social"><?php get_template_part( 'assets/img/svg/like.svg' ); ?>
																							 <?php
																								if ( $tweet['fav_count'] ) {
																									?>
								<span class="tweet-count"><?php echo thb_numberAbbreviation( $tweet['fav_count'] ); ?></span><?php } ?></a>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
			<?php
		}
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

			<!-- Layout -->
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>"><?php esc_html_e( 'Layout', 'north' ); ?></label>
				<select name="<?php echo esc_attr( $this->get_field_name( 'layout' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>" class="widefat">
					<option value="list" <?php selected( $params['layout'], 'list' ); ?>><?php esc_html_e( 'List', 'north' ); ?></option>
					<option value="slider" <?php selected( $params['layout'], 'slider' ); ?>><?php esc_html_e( 'Slider', 'north' ); ?></option>
				</select>
			</p>

			<!-- Username -->
			<p><label for="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>"><?php esc_html_e( 'Username:', 'north' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'username' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'username' ) ); ?>" type="text" value="<?php echo esc_attr( $params['username'] ); ?>" /></p>

	  <p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'show' ) ); ?>"><?php esc_html_e( 'Number of Tweets:', 'north' ); ?></label>
				<input id="<?php echo esc_attr( $this->get_field_id( 'show' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show' ) ); ?>" value="<?php echo esc_attr( $params['show'] ); ?>" class="widefat" />
			</p>
		<?php
	}
}
