<?php
// thb Subscribe Widget
class thb_subscribe_widget extends WP_Widget {

	function __construct() {

		$widget_ops = array(
			'classname'   => 'widget_subscribe_widget',
			'description' => esc_html__( 'A widget that gathers email addresses.', 'north' ),
		);

		parent::__construct(
			'thb_subscribe_widget',
			esc_html__( 'Fuel Themes - Subscribe Widget', 'north' ),
			$widget_ops
		);

		$this->defaults = array(
			'title' => '',
			'desc'  => '',
		);

	}

	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters( 'widget_title', $instance['title'] );
		$desc  = $instance['desc'];

		echo $before_widget;
		echo ( $title ? $before_title . $title . $after_title : '' );

		?>

	<p><?php echo esc_html( $desc ); ?></p>

	<div class="thb_subscribe thb_subscribe_shortcode">
		<form class="newsletter-form" action="#" method="post" data-security="<?php echo esc_attr( wp_create_nonce( 'thb_subscription' ) ); ?>">
			<input placeholder="<?php esc_attr_e( 'Your E-Mail', 'north' ); ?>" type="text" name="widget_subscribe" class="widget_subscribe large">
			<?php $icon = is_rtl() ? '&larr;' : '&rarr;'; ?>
			<input type="submit" name="submit" class="widget_subscribe_btn" value="<?php echo esc_attr( $icon ); ?>" />
		</form>
			<?php do_action( 'thb_after_newsletter_form' ); ?>
	</div>

		<?php

		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] = wp_strip_all_tags( $new_instance['title'] );
		$instance['desc']  = stripslashes( $new_instance['desc'] );

		return $instance;
	}

	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = $this->defaults;
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Widget Title:', 'north' ); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'desc' ) ); ?>"><?php esc_html_e( 'Short Description:', 'north' ); ?></label>
			<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'desc' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'desc' ) ); ?>" value="<?php echo stripslashes( htmlspecialchars( ( $instance['desc'] ), ENT_QUOTES ) ); ?>" />
		</p>

		<?php if ( current_user_can( 'manage_options' ) ) { ?>
			<p>
				<a href="<?php echo esc_url( wp_nonce_url( '?thb_download_emails=true', 'thb_download_emails' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Download Emails', 'north' ); ?></a>
			</p>
		<?php } ?>
		<?php
	}
}
