<?php
function thb_register_sidebars() {
	register_sidebar(
		array(
			'name'          => 'Shop Sidebar',
			'id'            => 'thb-shop-filters',
			'description'   => 'Sidebar used for filters on the Shop page',
			'before_widget' => '<div id="%1$s" class="widget cf %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h6>',
			'after_title'   => '</h6>',
		)
	);

	register_sidebar(
		array(
			'name'          => 'Product Sidebar',
			'id'            => 'thb-product-sidebar',
			'description'   => 'Sidebar used inside Product Pages if enabled from Theme Options',
			'before_widget' => '<div id="%1$s" class="widget cf %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h6>',
			'after_title'   => '</h6>',
		)
	);

	register_sidebar(
		array(
			'name'          => 'Footer Column 1',
			'id'            => 'footer1',
			'description'   => 'Footer - First column',
			'before_widget' => '<div id="%1$s" class="widget cf %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h6>',
			'after_title'   => '</h6>',
		)
	);

	register_sidebar(
		array(
			'name'          => 'Footer Column 2',
			'id'            => 'footer2',
			'description'   => 'Footer - Second column',
			'before_widget' => '<div id="%1$s" class="widget cf %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h6>',
			'after_title'   => '</h6>',
		)
	);

	register_sidebar(
		array(
			'name'          => 'Footer Column 3',
			'id'            => 'footer3',
			'description'   => 'Footer - Third column',
			'before_widget' => '<div id="%1$s" class="widget cf %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h6>',
			'after_title'   => '</h6>',
		)
	);

	register_sidebar(
		array(
			'name'          => 'Footer Column 4',
			'id'            => 'footer4',
			'description'   => 'Footer - Forth column',
			'before_widget' => '<div id="%1$s" class="widget cf %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h6>',
			'after_title'   => '</h6>',
		)
	);

	register_sidebar(
		array(
			'name'          => 'Footer Column 5',
			'id'            => 'footer5',
			'description'   => 'Footer - Fifth column',
			'before_widget' => '<div id="%1$s" class="widget cf %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h6>',
			'after_title'   => '</h6>',
		)
	);

	register_sidebar(
		array(
			'name'          => 'Footer Column 6',
			'id'            => 'footer6',
			'description'   => 'Footer - Sixth column',
			'before_widget' => '<div id="%1$s" class="widget cf %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h6>',
			'after_title'   => '</h6>',
		)
	);
}
add_action( 'widgets_init', 'thb_register_sidebars' );
