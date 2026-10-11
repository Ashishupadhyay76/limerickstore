<?php
/* Mega Menu */
add_filter( 'wp_setup_nav_menu_item', 'thb_add_custom_nav_fields' );
function thb_add_custom_nav_fields( $menu_item ) {

	$menu_item->menuicon  = get_post_meta( $menu_item->ID, '_menu_item_menuicon', true );
	$menu_item->megamenu  = get_post_meta( $menu_item->ID, '_menu_item_megamenu', true );
	$menu_item->menuimage = get_post_meta( $menu_item->ID, '_menu_item_menuimage', true );
	$menu_item->titleitem = get_post_meta( $menu_item->ID, '_menu_item_titleitem', true );
	return $menu_item;

}

add_action( 'wp_update_nav_menu_item', 'thb_update_custom_nav_fields', 1, 3 );
function thb_update_custom_nav_fields( $menu_id, $menu_item_db_id, $menu_item_data ) {
	if ( ! empty( $_REQUEST['edit-menu-item-menuicon'] ) && is_array( $_REQUEST['edit-menu-item-menuicon'] ) ) {
			$menu_icon_value = $_REQUEST['edit-menu-item-menuicon'][ $menu_item_db_id ];
			update_post_meta( $menu_item_db_id, '_menu_item_menuicon', $menu_icon_value );
	}

	if ( ! isset( $_REQUEST['edit-menu-item-menuimage'][ $menu_item_db_id ] ) ) {
			$_REQUEST['edit-menu-item-menuimage'][ $menu_item_db_id ] = '';
	}
	$menuimage_enabled_value = $_REQUEST['edit-menu-item-menuimage'][ $menu_item_db_id ];
	update_post_meta( $menu_item_db_id, '_menu_item_menuimage', $menuimage_enabled_value );

	if ( ! isset( $_REQUEST['edit-menu-item-titleitem'][ $menu_item_db_id ] ) ) {
			$_REQUEST['edit-menu-item-titleitem'][ $menu_item_db_id ] = '';
	}
	$titleitem_enabled_value = $_REQUEST['edit-menu-item-titleitem'][ $menu_item_db_id ];
	update_post_meta( $menu_item_db_id, '_menu_item_titleitem', $titleitem_enabled_value );

	if ( ! isset( $_REQUEST['edit-menu-item-megamenu'][ $menu_item_db_id ] ) ) {
			$_REQUEST['edit-menu-item-megamenu'][ $menu_item_db_id ] = '';

	}
	$menu_mega_enabled_value = $_REQUEST['edit-menu-item-megamenu'][ $menu_item_db_id ];
	update_post_meta( $menu_item_db_id, '_menu_item_megamenu', $menu_mega_enabled_value );

}

/**
 * Custom Walker - Mobile Menu
 *
 * @access      public
 * @since       1.0
 * @return      void
 */
class thb_mobileDropdown extends Walker_Nav_Menu {

	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$class_names = '';

		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = $class_names ? ' class=" ' . esc_attr( $class_names ) . '"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $class_names . '>';

		$atts           = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
		$atts['href']   = ! empty( $item->url ) ? $item->url : '';

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$item_output  = $args->before;
		$item_output .= '<a' . $attributes . '>' . ( in_array( 'menu-item-has-children', $item->classes ) ? '<span></span>' : '' );

		/** This filter is documented in wp-includes/post-template.php */
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}

/**
 * Custom Walker
 *
 * @access      public
 * @since       1.0
 * @return      void
 */
class thb_MegaMenu extends Walker_Nav_Menu {

	var $active_megamenu = 0;

	/**
	 * @see Walker::start_lvl()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth Depth of page. Used for padding.
	 */
	function start_lvl( &$output, $depth = 0, $args = array() ) {
			$menuimage_class = $args->menuimage != '' ? ' has_bg' : '';
			$menuimage_style = $args->menuimage != '' ? 'style="background-image:url(' . $args->menuimage . ');"' : '';

			$indent = str_repeat( "\t", $depth );

			$output .= "\n$indent<ul class=\"sub-menu" . esc_attr( $menuimage_class ) . '" ' . $menuimage_style . ">\n";
	}

	/**
	 * @see Walker::end_lvl()
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth Depth of page. Used for padding.
	 */
	function end_lvl( &$output, $depth = 0, $args = array() ) {
			$indent  = str_repeat( "\t", $depth );
			$output .= "$indent</ul>\n";
	}

	function start_el( &$output, $item, $depth = 0, $args = array(), $current_object_id = 0 ) {
			$item_output = $column_class = '';

			$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

			$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
			$classes[] = 'menu-item-' . $item->ID;

		if ( $depth === 1 && $this->active_megamenu ) {
			$classes[] = 'mega-menu-title';
		}
			/**
			 * Filter the CSS class(es) applied to a menu item's list item element.
			 *
			 * @since 3.0.0
			 * @since 4.1.0 The `$depth` parameter was added.
			 *
			 * @param array  $classes The CSS classes that are applied to the menu item's `<li>` element.
			 * @param object $item    The current menu item.
			 * @param array  $args    An array of {@see wp_nav_menu()} arguments.
			 * @param int    $depth   Depth of menu item. Used for padding.
			 */
			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );

		if ( $depth === 0 ) {
				$this->active_megamenu = get_post_meta( $item->ID, '_menu_item_megamenu', true );
			if ( $this->active_megamenu ) {
				$class_names .= ' menu-item-mega-parent '; }
		} else {
				$class_names .= get_post_meta( $item->ID, '_menu_item_titleitem', true ) === 'enabled' ? ' title-item' : '';
		}
		if ( $depth === 2 ) {
			if ( $this->active_megamenu ) {
				$class_names .= ' menu-item-mega-link '; }
		}

			$atts           = array();
			$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
			$atts['target'] = ! empty( $item->target ) ? $item->target : '';
			$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
			$atts['href']   = ! empty( $item->url ) ? $item->url : '';
			$menu_icon_tag  = ! empty( $item->menuicon ) ? '<i class="fa ' . esc_attr( $item->menuicon ) . '"></i>' : '';

			$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

			$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

			$item_output  = $args->before;
			$item_output .= '<a' . $attributes . '>';
			/** This filter is documented in wp-includes/post-template.php */
			$item_output .= $menu_icon_tag;
			$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
			$item_output .= '</a>';
			$item_output .= $args->after;

			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

			$output .= $indent . '<li id="menu-item-' . $item->ID . '"' . $class_names . '>';
			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );

			apply_filters( 'walker_nav_menu_start_lvl', $item_output, $depth, $args->menuimage = $item->menuimage );
	}
}



function thb_custom_nav_fields_action( $item_id, $item, $depth, $args, $id ) {
	?>
	<div class="thb_menu_options">
		<p class="thb-field-link-mega description description-thin">
			<label for="edit-menu-item-megamenu-<?php echo esc_attr( $item_id ); ?>">
				<?php esc_html_e( 'Mega Menu', 'north' ); ?><br />
				<?php $value = get_post_meta( $item_id, '_menu_item_megamenu', true ); ?>
				<input type="checkbox" value="enabled" id="edit-menu-item-megamenu-<?php echo esc_attr( $item_id ); ?>" name="edit-menu-item-megamenu[<?php echo esc_attr( $item_id ); ?>]" <?php checked( $value, 'enabled' ); ?> />
				<?php esc_html_e( 'Enable', 'north' ); ?>
			</label>
		</p>
		<p class="thb-field-link-image description description-thin">
			<label for="edit-menu-item-menuimage-<?php echo esc_attr( $item_id ); ?>">
				<?php esc_html_e( 'Mega Menu Background', 'north' ); ?><br />
				<?php $savedimage = get_post_meta( $item_id, '_menu_item_menuimage', true ); ?>
				<input type="text" class="widefat code edit-menu-item-custom" id="edit-menu-item-menuimage-<?php echo esc_attr( $item_id ); ?>" name="edit-menu-item-menuimage[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $savedimage ); ?>"/>
			</label>
		</p>
		<p class="thb-field-link-title description description-thin">
			<label for="edit-menu-item-titleitem-<?php echo esc_attr( $item_id ); ?>">
				<?php esc_html_e( 'Title', 'north' ); ?><br />
				<?php $value = get_post_meta( $item_id, '_menu_item_titleitem', true ); ?>
				<input type="checkbox" value="enabled" id="edit-menu-item-titleitem-<?php echo esc_attr( $item_id ); ?>" name="edit-menu-item-titleitem[<?php echo esc_attr( $item_id ); ?>]" <?php checked( $value, 'enabled' ); ?> />
				<?php esc_html_e( 'Enable', 'north' ); ?>
			</label>
		</p>
	</div>
	<?php
}
add_action( 'wp_nav_menu_item_custom_fields', 'thb_custom_nav_fields_action', 10, 5 );
