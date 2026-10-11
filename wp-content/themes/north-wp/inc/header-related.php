<?php

// Subheader Sections
function thb_subheader_sections( $sections ) {

	if ( ! is_array( $sections ) || count( $sections ) < 1 ) {
		return;
	}
	foreach ( $sections as $section ) {
		$section_type = $section['section_type'];

		switch ( $section_type ) {
			case 'menu':
				$subheader_menu = $section['menu'];
				if ( $subheader_menu ) {
					wp_nav_menu(
						array(
							'menu'       => $subheader_menu,
							'container'  => false,
							'theme_location' => '',
							'depth'      => 2,
							'menu_class' => 'thb-full-menu',
						)
					);
				}
				break;
			case 'text':
				$subheader_text = $section['text'];
				echo '<div class="subheader-text">' . do_shortcode( $subheader_text ) . '</div>';
				break;
			case 'ls':
				do_action( 'thb_language_switcher' );
				break;
			case 'cs':
				do_action( 'thb_currency_switcher' );
				break;
			case 'social':
				thb_get_social_list();
				break;
		}
	}
}
add_action( 'thb_subheader_sections', 'thb_subheader_sections', 10, 2 );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function thb_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'thb_pingback_header' );
