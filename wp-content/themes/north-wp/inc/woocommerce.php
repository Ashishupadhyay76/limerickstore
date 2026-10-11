<?php
if ( ! thb_wc_supported() ) {
	return;
}
/* Add WooCommerce assets to Edit Post Screen */
function set_wc_screen_ids( $screen ) {
	$screen[] = 'post';
	$screen[] = 'page';
	return $screen;
}
add_filter( 'woocommerce_screen_ids', 'set_wc_screen_ids' );

/* Product Masonry Sizes */
function thb_get_product_size( $style = 'style2', $i = 0 ) {
	$size = '';
	if ( $style === 'style2' ) {
		$i = in_array( $i, array( '3', '4', '5', '6', '10', '11', '12', '13', '17', '18', '19', '20', '24', '25', '26', '27', '31', '32', '33', '34' ) ) ? 1 : 2;
		switch ( $i ) {
			case 1:
				$size = 'large-3';
				break;
			case 2:
				$size = 'large-4';
				break;
		}
	} elseif ( $style === 'style3' ) {
		$i = in_array( $i, array( '4', '5', '6', '7', '8', '13', '14', '15', '16', '17', '22', '23', '24', '25', '26', '31', '32', '33', '34', '35' ) ) ? 1 : 2;
		switch ( $i ) {
			case 1:
				$size = 'thb-5';
				break;
			case 2:
				$size = 'large-3';
				break;
		}
	} elseif ( $style === 'style4' ) {
		$i = in_array( $i, array( '5', '6', '7', '8', '9', '10', '16', '17', '18', '19', '20', '21', '27', '28', '29', '30', '31', '32' ) ) ? 1 : 2;
		switch ( $i ) {
			case 1:
				$size = 'large-2';
				break;
			case 2:
				$size = 'thb-5';
				break;
		}
	} elseif ( $style === 'style5' ) {
		$i = in_array( $i, array( '0', '1', '2', '3', '7', '8', '9', '10', '14', '15', '16', '17', '21', '22', '23', '24', '28', '29', '30', '31' ) ) ? 1 : 2;
		switch ( $i ) {
			case 2:
				$size = 'large-4';
				break;
			case 1:
				$size = 'large-3';
				break;
		}
	} elseif ( $style === 'style6' ) {
		$i = in_array( $i, array( '5', '6', '7', '8', '14', '15', '16', '17', '23', '24', '25', '26', '32', '33', '34', '35' ) ) ? 1 : 2;
		switch ( $i ) {
			case 2:
				$size = 'thb-5';
				break;
			case 1:
				$size = 'large-3';
				break;
		}
	} elseif ( $style === 'style7' ) {
		$i = in_array( $i, array( '6', '7', '8', '9', '10', '17', '18', '19', '20', '21', '28', '29', '30', '31', '32', '39', '40', '41', '42', '43' ) ) ? 1 : 2;
		switch ( $i ) {
			case 2:
				$size = 'large-2';
				break;
			case 1:
				$size = 'thb-5';
				break;
		}
	} elseif ( $style === 'style8' ) {
		$i = in_array( $i, array( '8', '9', '10', '19', '20', '21', '30', '31', '32' ) ) ? 1 : 2;
		switch ( $i ) {
			case 1:
				$size = 'large-4';
				break;
			case 2:
				$size = 'large-3';
				break;
		}
	}
	return $size;
}

/* Breadcrumbs */
function thb_change_breadcrumb_delimiter( $defaults ) {
	// Change the breadcrumb delimeter from '/' to '>'
	$defaults['delimiter'] = ' <i>/</i> ';
	return $defaults;
}
add_filter( 'woocommerce_breadcrumb_defaults', 'thb_change_breadcrumb_delimiter' );

/* Pagination */
function thb_woocommerce_pagination_args( $defaults ) {
	$defaults['prev_text'] = '&larr; ' . esc_html__( 'Prev', 'north' );
	$defaults['next_text'] = esc_html__( 'Next', 'north' ) . ' &rarr;';

	return $defaults;
}
add_filter( 'woocommerce_pagination_args', 'thb_woocommerce_pagination_args' );

/* Shop Filters */
function thb_shop_filters() {
	if ( is_shop() || is_product_category() || is_product_tag() || is_product_taxonomy() ) {
		?>
		<div id="side-filters" class="side-panel">
			<header>
				<h6><?php esc_html_e( 'Filter', 'north' ); ?></h6>
				<a href="#" class="thb-close" title="<?php esc_attr_e( 'Close', 'north' ); ?>"><?php get_template_part( 'assets/img/svg/close.svg' ); ?></a>
			</header>
			<div class="side-panel-content custom_scroll">
				<?php
				if ( is_active_sidebar( 'thb-shop-filters' ) ) {
					dynamic_sidebar( 'thb-shop-filters' ); }
				?>
			</div>
		</div>
		<?php
	}
}
add_action( 'thb_shop_filters', 'thb_shop_filters' );

/* Side Cart */
function thb_side_cart() {
	if ( ! is_cart() && ! is_checkout() ) {
		?>
		<nav id="side-cart" class="side-panel">
			<header>
				<h6><?php esc_html_e( 'Shopping Bag', 'north' ); ?></h6>
				<a href="#" class="thb-close" title="<?php esc_attr_e( 'Close', 'north' ); ?>"><?php get_template_part( 'assets/img/svg/close.svg' ); ?></a>
			</header>
			<div class="side-panel-content">
				<?php
				if ( class_exists( 'WC_Widget_Cart' ) ) {
					the_widget(
						'WC_Widget_Cart',
						array(
							'title' => false,
						)
					);
				}
				?>
			</div>
		</nav>
		<?php
	}
}
add_action( 'thb_side_cart', 'thb_side_cart', 3 );

/* Quick Shop */
function thb_quick_shop() {

	if ( 'on' === ot_get_option( 'quick_shop', 'on' ) ) {
		$quick_shop_categories = ot_get_option( 'quick_shop_categories', 'on' );
		$quick_shop_count      = ot_get_option( 'quick_shop_count', 8 );

		$args = array(
			'post_type'      => 'product',
			'posts_per_page' => $quick_shop_count,
			'orderby'        => 'date',
			'order'          => 'desc',
			'paged'          => 1,
		);

		$quick_products = new WP_Query( $args );

		?>
		<a href="#" class="quick-shop"><?php esc_html_e( 'Quick Shop', 'north' ); ?></a>
		<nav id="quick-shop" class="side-panel">
			<header>
				<h6><?php esc_html_e( 'Quick Shop', 'north' ); ?></h6>
				<a href="#" class="thb-close" title="<?php esc_attr_e( 'Close', 'north' ); ?>"><?php get_template_part( 'assets/img/svg/close.svg' ); ?></a>
			</header>
			<div class="side-panel-content">
				<?php if ( 'on' === $quick_shop_categories ) { ?>
					<select id="thb-quick-shop-categories" data-security="<?php echo esc_attr( wp_create_nonce( 'thb_quick_shop' ) ); ?>">
						<option value=""><?php echo esc_html_e( 'Categories', 'north' ); ?></option>
						<?php
							$categories = thb_productCategories();
						foreach ( $categories as $category => $slug ) {
							?>
								<option value="<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $category ); ?></option>
								<?php
						}
						?>
					</select>
				<?php } ?>
				<div class="product_container custom_scroll">
					<ul class="products row">
					<?php
						set_query_var( 'thb_columns', 'medium-6' );
					if ( $quick_products->have_posts() ) :
						while ( $quick_products->have_posts() ) :
							$quick_products->the_post();
							wc_get_template_part( 'content', 'product' );
						endwhile;
endif;
						set_query_var( 'thb_columns', false );
					?>
					</ul>
				</div>
			</div>
		</nav>
		<?php
	}
}
add_action( 'thb_quick_shop', 'thb_quick_shop', 10 );

// Thb Header Profile.
function thb_quick_profile() {
	?>
	<a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>" id="quick_profile"><?php get_template_part( 'assets/img/svg/header-profile.svg' ); ?></a>
	<?php
}
add_action( 'thb_quick_profile', 'thb_quick_profile' );

// Header Cart.
function thb_quick_cart( $header_secondary_style ) {
	if ( 'on' === ot_get_option( 'header_cart', 'on' ) ) {
		?>
		<a id="quick_cart" data-target="open-cart" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'Cart', 'north' ); ?>">
			<?php
			if ( 'style1' === $header_secondary_style ) {
				esc_html_e( 'Cart', 'north' );
			} else {
				get_template_part( 'assets/img/svg/header-cart.svg' );
			}
			?>
			<span class="float_count"><?php echo is_object( WC()->cart ) ? esc_html( WC()->cart->get_cart_contents_count() ) : '0'; ?></span>
		</a>
		<?php
	}
}
add_action( 'thb_quick_cart', 'thb_quick_cart', 3, 1 );

/* Out of Stock Check */
function thb_out_of_stock() {
	global $post;
	$id     = $post->ID;
	$status = get_post_meta( $id, '_stock_status', true );

	if ( 'outofstock' === $status ) {
		return true;
	} else {
		return false;
	}
}

/* Product Badges */
function thb_product_badge() {
	global $post, $product;
	if ( thb_out_of_stock() ) {
		echo '<span class="badge out-of-stock">' . esc_html__( 'Out of Stock', 'north' ) . '</span>';
	} elseif ( $product->is_on_sale() ) {
		if ( ot_get_option( 'shop_sale_badge', 'text' ) == 'discount' ) {
			if ( $product->get_type() == 'variable' ) {
				$available_variations = $product->get_available_variations();
				$maximumper           = 0;
				for ( $i = 0; $i < count( $available_variations ); ++$i ) {
					$variation_id      = $available_variations[ $i ]['variation_id'];
					$variable_product1 = new WC_Product_Variation( $variation_id );
					$regular_price     = $variable_product1->get_regular_price();
					$sales_price       = $variable_product1->get_sale_price();
					$percentage        = $sales_price ? round( ( ( $regular_price - $sales_price ) / $regular_price ) * 100 ) : 0;
					if ( $percentage > $maximumper ) {
						$maximumper = $percentage;
					}
				}
				echo apply_filters( 'woocommerce_sale_flash', '<span class="badge onsale perc">&darr; ' . $maximumper . '%</span>', $post, $product );
			} elseif ( $product->get_type() == 'simple' ) {
				$percentage = round( ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100 );
				echo apply_filters( 'woocommerce_sale_flash', '<span class="badge onsale perc">&darr; ' . $percentage . '%</span>', $post, $product );
			} elseif ( $product->get_type() == 'external' ) {
				$percentage = round( ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100 );
				echo apply_filters( 'woocommerce_sale_flash', '<span class="badge onsale perc">&darr; ' . $percentage . '%</span>', $post, $product );
			}
		} else {
			echo apply_filters( 'woocommerce_sale_flash', '<span class="badge onsale">' . esc_html__( 'Sale', 'north' ) . '</span>', $post, $product );
		}
	} else {
		$postdate      = get_the_time( 'Y-m-d' );          // Post date
		$postdatestamp = strtotime( $postdate );           // Timestamped post date
		$newness       = ot_get_option( 'shop_newness', 7 );
		if ( ( time() - ( 60 * 60 * 24 * $newness ) ) < $postdatestamp ) { // If the product was published within the newness time frame display the new badge
			echo '<span class="badge new">' . esc_html__( 'Just Arrived', 'north' ) . '</span>';
		}
	}
}
add_action( 'thb_product_badge', 'thb_product_badge', 3 );

/* WOOCOMMERCE CART LINK */
function thb_woocomerce_ajax_cart_update( $fragments ) {
	ob_start();
	?>
		<span class="float_count"><?php echo is_object( WC()->cart ) ? esc_html( WC()->cart->get_cart_contents_count() ) : '0'; ?></span>
	<?php
	$fragments['.float_count'] = ob_get_clean();
	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'thb_woocomerce_ajax_cart_update', 8 );

/* WishList Button*/
function thb_wishlist_button( $singular = false ) {
	if ( class_exists( 'YITH_WCWL' ) ) {
		global $product;
		$url               = YITH_WCWL()->get_wishlist_url();
		$product_type      = $product->get_type();
		$default_wishlists = is_user_logged_in() ? YITH_WCWL_Wishlist_Factory::get_wishlists( array( 'is_default' => true ) ) : false;

		if ( ! empty( $default_wishlists ) ) {
			$default_wishlist = $default_wishlists[0]['ID'];
		} else {
			$default_wishlist = false;
		}

		$exists = YITH_WCWL_Wishlists()->is_product_in_wishlist( $product->get_id(), $default_wishlist );

		$classes = get_option( 'yith_wcwl_use_button' ) === 'yes' ? 'add_to_wishlist single_add_to_wishlist button alt thb-icon-container' : 'add_to_wishlist thb-icon-container';
		?>
		<div class="
		<?php
		if ( ! $singular ) {
			?>
			thb-product-icon<?php } ?> yith-wcwl-add-to-wishlist add-to-wishlist-<?php echo esc_attr( $product->get_id() ); ?> <?php echo esc_attr( $exists ? 'exists' : '' ); ?>">
		<div class="yith-wcwl-add-button" style="display: <?php echo esc_attr( $exists ? 'none' : 'block' ); ?>">
				<a href="<?php echo esc_url( add_query_arg( 'add_to_wishlist', $product->get_id() ) ); ?>"
					data-product-id="<?php echo esc_attr( $product->get_id() ); ?>"
					data-product-type="<?php echo esc_attr( $product_type ); ?>"
					class="<?php echo esc_attr( $classes ); ?>">
					<span class="thb-icon-text"><?php echo esc_html__( 'Add To Wishlist', 'north' ); ?></span><?php get_template_part( 'assets/img/svg/wishlist.svg' ); ?>
				</a>
			</div>
			<div class="yith-wcwl-wishlistexistsbrowse">
				<a href="<?php echo esc_url( $url ); ?>" class="thb-icon-container">
					<span class="thb-icon-text"><?php echo esc_html__( 'View Wishlist', 'north' ); ?></span><?php get_template_part( 'assets/img/svg/wishlist.svg' ); ?>
				</a>
			</div>
		</div>
		<?php
	}

}

/* Sharing */
function thb_product_sharing() {
	thb_wishlist_button( true );
	thb_share();
}
add_action( 'woocommerce_single_product_summary', 'thb_product_sharing', 38 );

/* Sizing Guide */
function thb_sizing_guide() {
	$thb_id               = get_the_ID();
	$sizing_guide         = get_post_meta( $thb_id, 'sizing_guide', true );
	$sizing_guide_content = get_post_meta( $thb_id, 'sizing_guide_content', true );

	if ( 'on' === $sizing_guide ) {
		?>
		<a href="#sizing-popup" rel="inline" class="sizing_guide" data-class="upsell-popup">
			<?php get_template_part( 'assets/img/svg/sizing_guide.svg' ); ?>
			<?php esc_html_e( 'Sizing Guide', 'north' ); ?>
		</a>
		<aside id="sizing-popup" class="mfp-hide theme-popup text-left">
			<div class="theme-popup-content">
				<?php echo do_shortcode( $sizing_guide_content ); ?>
			</div>
		</aside>
		<?php
	}
}
add_action( 'woocommerce_single_product_summary', 'thb_sizing_guide', 37 );

/* Menu Icon Modification */
function thb_new_menu_items( $items ) {
		unset( $items['dashboard'] );
	return $items;
}

add_filter( 'woocommerce_account_menu_items', 'thb_new_menu_items' );

/* Shop Headers */
add_action(
	'woocommerce_before_main_content',
	function() {
		if ( is_shop() || is_archive() ) {
			return get_template_part( 'inc/templates/header/header-shop-filters' );
		}
	},
	5
);

/* Shop Page - Remove orderby & breadcrumb */
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
add_action( 'thb_before_shop_loop_result_count', 'woocommerce_result_count', 20 );
add_action( 'thb_before_shop_loop_catalog_ordering', 'woocommerce_catalog_ordering', 30 );

/* Shop Page - Remove Breadcrumb */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
add_action( 'thb_breadcrumbs', 'woocommerce_breadcrumb', 20 );

/* Category Text */
function thb_before_subcategory_title() {
	echo '<div>';
}
add_action( 'woocommerce_before_subcategory_title', 'thb_before_subcategory_title', 15 );
function thb_subcategory_title() {
	echo '<span>' . esc_html__( 'Explore Now', 'north' ) . '</span>';
}
function thb_subcategory_title_style2( $category ) {
	echo '<div class="thb-category-excerpt">' . esc_html( $category->description ) . '</div>';
}
add_action( 'woocommerce_shop_loop_subcategory_title', 'thb_subcategory_title', 15 );

function thb_subcategory_title_style4( $category ) {
	echo '<a href="' . esc_url( get_term_link( $category->term_id, 'product_cat' ) ) . '" class="button small-radius white">' . esc_html( $category->name ) . '</a>';
}

function thb_after_subcategory_title() {
	echo '</div>';
}
function thb_after_subcategory_title_style2( $category ) {
	echo '</div>';
	echo '<a href="' . esc_url( get_term_link( $category->term_id, 'product_cat' ) ) . '" class="btn small black">' . esc_html__( 'Shop The Collection', 'north' ) . '</a>';
}
add_action( 'woocommerce_after_subcategory_title', 'thb_after_subcategory_title', 15 );

function thb_subcategory_count_html( $markup, $category ) {
	return '<mark class="count">' . $category->count . '</mark>';
}
add_filter( 'woocommerce_subcategory_count_html', 'thb_subcategory_count_html', 10, 2 );

/* Change Category Thumbnail Size */
function thb_template_loop_category_link_open( $category ) {
	$thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
	if ( $thumbnail_id ) {
		$image = wp_get_attachment_image_src( $thumbnail_id, 'full' );
		$image = $image[0];
	} else {
		$image = wc_placeholder_img_src();
	}
	echo '<a href="' . esc_url( get_term_link( $category, 'product_cat' ) ) . '" style="background-image:url(' . esc_url( $image ) . ' )" class="thb-category-link">';
}
remove_action( 'woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail', 10 );
remove_action( 'woocommerce_before_subcategory', 'woocommerce_template_loop_category_link_open', 10 );
add_action( 'woocommerce_before_subcategory', 'thb_template_loop_category_link_open', 10 );

/* Cart Page - Move Crosssells */
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display' );

/* Checkout */
function thb_checkout_before_customer_details() {
	echo '<div class="row"><div class="small-12 large-8 xlarge-9 columns">';
}
add_action(
	'woocommerce_checkout_before_customer_details',
	'thb_checkout_before_customer_details',
	5
);

function thb_checkout_after_customer_details() {
	echo '</div><div class="small-12 large-4 xlarge-3 columns">';
}
add_action(
	'woocommerce_checkout_after_customer_details',
	'thb_checkout_after_customer_details',
	30
);

function thb_checkout_after_order_review() {
	echo '</div></div>';
}
add_action(
	'woocommerce_checkout_after_order_review',
	'thb_checkout_after_order_review',
	30
);

/* Product Page */
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );

// woocommerce_before_shop_loop_item
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );

// woocommerce_after_shop_loop_item
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

// woocommerce_after_shop_loop_item_title.
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );

// woocommerce_after_shop_loop_item_title.
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

add_action( 'woocommerce_after_shop_loop_item_title_loop_price', 'woocommerce_template_loop_price', 10 );
add_action( 'woocommerce_after_shop_loop_item_title_loop_rating', 'woocommerce_template_loop_rating', 5 );

// Add Breadcrumb
function thb_product_page_breadcrumbs() {
	$shop_product_breadcrumbs = ot_get_option( 'shop_product_breadcrumbs', 'on' );

	if ( 'on' === $shop_product_breadcrumbs ) {
		woocommerce_breadcrumb();
	}
}
add_action( 'woocommerce_single_product_summary', 'thb_product_page_breadcrumbs', 0 );

// Remove Sidebar
add_action(
	'template_redirect',
	function() {
		if ( is_product() ) {
			remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar' );
		}
	},
	0
);

// Product Gallery.
add_action(
	'woocommerce_before_single_product_summary',
	function() {
		$shop_product_style   = !empty($_GET['shop_product_style']) ? sanitize_text_field( $_GET['shop_product_style'] ) : '';
		$shop_product_style   = $shop_product_style ? $shop_product_style : ot_get_option( 'shop_product_style', 'style1' );
		$shop_product_sidebar = ot_get_option( 'shop_product_sidebar', 'off' );
		$class                = 'style4' === $shop_product_style ? 'large-7' : 'large-6';

		?>
		<?php
		if ( 'on' === $shop_product_sidebar && 'style5' !== $shop_product_style ) {
			$class = 'large-7';
			?>
	<div class="row align-center">
		<div class="small-12 medium-8 large-9 columns">
		<?php } ?>
	<div class="row align-center">
		<div class="small-12 <?php echo esc_attr( $class ); ?> columns">
		<?php
	},
	0
);

// Product Summary.
add_action(
	'woocommerce_before_single_product_summary',
	function() {
		$shop_product_style   = !empty($_GET['shop_product_style']) ? sanitize_text_field( $_GET['shop_product_style'] ) : '';
		$shop_product_style   = $shop_product_style ? $shop_product_style : ot_get_option( 'shop_product_style', 'style1' );
		$shop_product_sidebar = ot_get_option( 'shop_product_sidebar', 'off' );
		switch ( $shop_product_style ) {
			case 'style1':
				$class = 'on' === $shop_product_sidebar ? 'large-5' : 'large-4';
				break;
			case 'style4':
			case 'style3':
				$class = 'large-5';
				break;
			case 'style5':
				$class = 'large-6';
				break;
			default:
				$class = 'on' === $shop_product_sidebar ? 'large-5' : 'large-6';
		}
		?>
		</div>
		<div class="small-12 <?php echo esc_attr( $class ); ?> columns product-information">
		<?php
	},
	100
);
add_action(
	'woocommerce_after_single_product_summary',
	function() {
		$shop_product_sidebar = ot_get_option( 'shop_product_sidebar', 'off' );
		$shop_product_style   = !empty($_GET['shop_product_style']) ? sanitize_text_field( $_GET['shop_product_style'] ) : '';
		$shop_product_style   = $shop_product_style ? $shop_product_style : ot_get_option( 'shop_product_style', 'style1' );
		?>
			</div>
		</div>
		<?php if ( 'on' === $shop_product_sidebar && 'style5' !== $shop_product_style ) { ?>
		</div>
		<div class="small-12 medium-4 large-3 columns sidebar">
			<?php
			if ( is_active_sidebar( 'thb-product-sidebar' ) ) {
				dynamic_sidebar( 'thb-product-sidebar' ); }
			?>
		</div>
	</div>
	<?php } ?>
		<?php
	},
	0
);

/* My Account Content */
add_action(
	'woocommerce_account_content',
	function() {
		?>
	<div class="row align-center">
		<div class="small-12 large-8 columns">
		<?php
	},
	0
);
add_action(
	'woocommerce_account_content',
	function() {
		?>
		</div>
	</div>
		<?php
	},
	100
);

/* Wishlist */
add_action(
	'yith_wcwl_before_wishlist_form',
	function() {
		?>
	<div class="page-padding">
		<div class="row">
			<div class="small-12 columns">
				<div class="post-content no-vc">
		<?php
	},
	0
);
add_action(
	'yith_wcwl_after_wishlist_form',
	function() {
		?>
				</div>
			</div>
		</div>
	</div>
		<?php
	},
	0
);

/* Plugin Page Ajax Add to Cart */
function thb_woocommerce_single_product() {
	if ( 'off' === ot_get_option( 'shop_product_ajax_addtocart', 'on' ) ) {
		return;
	}

	function thb_ajax_add_to_cart_redirect_template() {
		$thb_ajax = filter_input( INPUT_GET, 'thb-ajax-add-to-cart', FILTER_VALIDATE_BOOLEAN );

		if ( $thb_ajax ) {
			wc_get_template( 'ajax/add-to-cart-fragments.php' );
			exit;
		}
	}
	add_action( 'wp', 'thb_ajax_add_to_cart_redirect_template', 1000 );
	function thb_woocommerce_after_add_to_cart_button() {
		global $product;
		?>
			<input type="hidden" name="action" value="wc_prod_ajax_to_cart" />
		<?php
		// Make sure we have the add-to-cart avaiable as button name doesn't submit via ajax.
		if ( $product->is_type( 'simple' ) ) {
			?>
			<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>"/>
			<?php
		}
	}
	add_action( 'woocommerce_after_add_to_cart_button', 'thb_woocommerce_after_add_to_cart_button' );
	function thb_woocommerce_display_site_notice() {
		?>
		<div class="thb_prod_ajax_to_cart_notices"></div>
		<?php
	}
	add_action( 'woocommerce_before_main_content', 'thb_woocommerce_display_site_notice', 10 );
}
add_action( 'before_woocommerce_init', 'thb_woocommerce_single_product' );

function thb_swatches_list() {
	if ( 'off' === ot_get_option( 'shop_product_listing_attribute_display', 'off' ) ) {
		return;
	}
	global $product;

	$thb_id = $product->get_id();

	if ( empty( $thb_id ) || ! $product->is_type( 'variable' ) ) {
		return false;
	}
	$attribute_name = ot_get_option( 'shop_product_listing_variation' );
	if ( empty( $attribute_name ) ) {
		return false;
	}
	$transient_name = 'thb_listing_swatches_' . esc_attr( sanitize_title( $attribute_name ) ) . '_' . $thb_id;
	$return_html    = get_transient( $transient_name );
	if ( false === $return_html ) {
		$available_variations = $product->get_available_variations();
		if ( empty( $available_variations ) ) {
			return false;
		}
		$attribute_id = wc_attribute_taxonomy_id_by_name( sanitize_title( $attribute_name ) );
		$attribute    = wc_get_attribute( $attribute_id );
		$terms        = wc_get_product_terms( $thb_id, $attribute->slug, array( 'fields' => 'all' ) );

		$swatches = new THB_Product_Attributes();
		$html     = '';
		foreach ( $terms as $term ) {
			$html .= $swatches->thb_render_swatch( $attribute->type, $term, '', $attribute->slug, $available_variations );
		}
		$return_html = '<div class="thb-swatches ' . esc_attr( $attribute->type ) . '-swatch" data-attribute_name="attribute_' . esc_attr( $attribute->slug ) . '">' . $html . '</div>';

		set_transient( $transient_name, $return_html, HOUR_IN_SECONDS );
	}
	echo apply_filters( 'thb_listing_swatches_html', $return_html );
}
add_action( 'woocommerce_after_shop_loop_item', 'thb_swatches_list', 5 );


// Video Icon.
function thb_product_video() {
	$thb_product_video = get_post_meta( get_the_ID(), 'thb_product_video', true );

	if ( ! $thb_product_video || '' === $thb_product_video ) {
		return;
	}
	?>
	<a class="thb-product-icon thb-product-video mfp-video" href="<?php echo esc_url( $thb_product_video ); ?>">
		<div class="thb-icon-container">
			<span class="thb-icon-text on-right"><?php echo esc_html__( 'View Video', 'north' ); ?></span>
			<?php get_template_part( 'assets/img/svg/video.svg' ); ?>
		</div>
	</a>
	<?php
}
add_action( 'thb_product_images', 'thb_product_video', 30 );

// Quick View.
function thb_quickview_button() {
	if ( 'on' !== ot_get_option( 'shop_product_quickview', 'on' ) ) {
		return;
	}
	global $product;
	?>
	<div class="thb-product-icon thb-quick-view" data-id="<?php echo esc_attr( $product->get_id() ); ?>">
		<div class="thb-icon-container">
			<span class="thb-icon-text"><?php echo esc_html__( 'Quick View', 'north' ); ?></span>
			<?php get_template_part( 'assets/img/svg/quick-view.svg' ); ?>
		</div>
	</div>
	<?php
}
add_action( 'woocommerce_before_shop_loop_item_title', 'thb_quickview_button', 10 );

// Add to Cart Styles
add_action( 'before_woocommerce_init', 'thb_different_add_to_cart', 15 );

function thb_different_add_to_cart() {
	$shop_product_listing_button = ot_get_option( 'shop_product_listing_button', 'style4' );

	// Regular Button
	if ( 'style1' === $shop_product_listing_button ) {
		add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
	}
	// Over Image - style2
	if ( 'style2' === $shop_product_listing_button ) {
		add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
		add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_add_to_cart', 10 );
	}
	// Transform Price
	if ( 'style4' === $shop_product_listing_button ) {
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

		add_action( 'thb_template_loop_price', 'woocommerce_template_loop_price' );
		add_action( 'thb_template_loop_add_to_cart', 'woocommerce_template_loop_add_to_cart' );
		add_action(
			'woocommerce_after_shop_loop_item',
			function() {
				?>
			<div class="thb_transform_price">
				<div class="thb_transform_loop_price">
					<?php do_action( 'thb_template_loop_price' ); ?>
				</div>
				<div class="thb_transform_loop_buttons">
					<?php do_action( 'thb_template_loop_add_to_cart' ); ?>
				</div>
			</div>
				<?php
			},
			15
		);
	}
	// No Button
	if ( 'style0' === $shop_product_listing_button ) {
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
	}
};

add_filter( 'woocommerce_loop_add_to_cart_args', 'thb_filter_woocommerce_loop_add_to_cart_args', 10, 2 );
function thb_filter_woocommerce_loop_add_to_cart_args( $args, $product ) {
	$shop_product_listing_button_color = ot_get_option( 'shop_product_listing_button_color', 'black' );
	$shop_product_listing_button       = ot_get_option( 'shop_product_listing_button', 'style4' );

	if ( 'style2' === $shop_product_listing_button || 'style1' === $shop_product_listing_button ) {
		$args['class'] .= ' ' . $shop_product_listing_button_color;
	}
	return $args;
}

// dokan
function thb_dokan_dashboard_start() {
	echo '<div class="page-padding">';
}
add_action( 'dokan_dashboard_wrap_start', 'thb_dokan_dashboard_start', 0 );

function thb_dokan_dashboard_end() {
	echo '</div>';
}
add_action( 'dokan_dashboard_wrap_end', 'thb_dokan_dashboard_end', 100 );
