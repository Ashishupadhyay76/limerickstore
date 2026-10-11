<?php

function thb_register_widgets() {
	$thb_widgets = array(
		'latest-posts-images' => 'widget_latestimages',
		'subscribe'           => 'thb_subscribe_widget',
		'twitter'             => 'thb_twitter',
	);
	foreach ( $thb_widgets as $key => $value ) {
		require_once north_plugin()->get_plugin_path() . 'inc/widgets/' . sanitize_key( $key ) . '.php';
		register_widget( $value );
	}
}

add_action( 'widgets_init', 'thb_register_widgets' );

// thb Tag Cloud Size
function tag_cloud_filter( $args = array() ) {
	$args['smallest'] = 10;
	$args['largest']  = 10;
	$args['unit']     = 'px';
	return $args;
}

add_filter( 'widget_tag_cloud_args', 'tag_cloud_filter', 90 );
add_filter( 'woocommerce_product_tag_cloud_widget_args', 'tag_cloud_filter', 90 );

/**
 * Create HTML dropdown list of Categories.
 */
if ( ! class_exists( 'THB_Posts_Categories_Tree_Walker' ) ) {
	class THB_Posts_Categories_Tree_Walker extends Walker_CategoryDropdown {

		/**
		 * Starts the element output.
		 *
		 * @since 2.1.0
		 * @access public
		 *
		 * @see Walker::start_el()
		 *
		 * @param string $output   Passed by reference. Used to append additional content.
		 * @param object $category Category data object.
		 * @param int    $depth    Depth of category. Used for padding.
		 * @param array  $args     Uses 'selected', 'show_count', and 'value_field' keys, if they exist.
		 *                         See wp_dropdown_categories().
		 * @param int    $id       Optional. ID of the current category. Default 0 (unused).
		 */
		public function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
			$space  = str_repeat( '&nbsp;', $depth * 3 );
			$space .= $depth > 0 ? '- ' : '';

			$term_id = isset( $category->term_id ) ? intval( $category->term_id ) : false;
			if ( $term_id ) {
				$cat_name = apply_filters( 'list_cats', $category->name, $category );
				$output  .= '<option class="level-' . $depth . '" value="' . $term_id . '" ' . selected( true, in_array( $term_id, $args['selected'], true ) ) . '>';
				$output  .= $space . $cat_name;
				$output  .= '</option>';
			}
		}
	}
}
