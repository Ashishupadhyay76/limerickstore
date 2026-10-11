<?php

/*
 * Here we have all the custom functions for the theme
 * Please be extremely cautious editing this file.
 * You have been warned!
 *
 */

// Option-Tree Theme Mode.
require get_theme_file_path( '/inc/admin/option-tree/init.php' );

// Theme Admin.
require get_theme_file_path( '/inc/admin/welcome/class-thb-theme-admin.php' );

// TGM Plugin Activation Class.
require get_theme_file_path( '/inc/admin/plugins/plugins.php' );

// Imports.
require get_theme_file_path( '/inc/admin/imports/import.php' );

// Ajax.
require get_theme_file_path( '/inc/ajax.php' );

// Add Menu Support.
require get_theme_file_path( '/inc/wp3menu.php' );

// Enable Sidebars.
require get_theme_file_path( '/inc/sidebar.php' );

// Misc.
require get_theme_file_path( '/inc/misc.php' );

// Header Related.
require get_theme_file_path( '/inc/header-related.php' );

// Script Calls.
require get_theme_file_path( '/inc/script-calls.php' );

// Twitter oAuth.
require get_theme_file_path( '/inc/framework/thb-twitter-helper.php' );

// WPML Support.
require get_theme_file_path( '/inc/wpml.php' );

// WooCommerce Settings specific for theme.
require get_theme_file_path( '/inc/woocommerce.php' );
require get_theme_file_path( '/inc/woocommerce-category-settings.php' );
require get_theme_file_path( '/inc/woocommerce-attribute-settings.php' );
require get_theme_file_path( '/inc/woocommerce-product-data-tabs.php' );
require get_theme_file_path( '/inc/woocommerce-quickview.php' );

// CSS Output of Theme Options.
require get_theme_file_path( '/inc/selection.php' );

// Visual Composer Integration.
require get_theme_file_path( '/inc/framework/visualcomposer/visualcomposer.php' );
