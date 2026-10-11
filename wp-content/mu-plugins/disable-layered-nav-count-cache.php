<?php
/**
 * Plugin Name: Disable WC layered-nav count transient
 * Description: Prevents WooCommerce from rewriting the wc_layered_nav_counts_* transients
 *              on every new filter combination (caused DB binlog bloat / disk-full outages).
 */
add_filter( 'woocommerce_layered_nav_count_maybe_cache', '__return_false' );
