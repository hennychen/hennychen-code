<?php
/*
Plugin Name: List Stores
Plugin URI: http://example.com/
Description: Add rewrite rules to list stores as children of the Stores page
Version: 1.0
Author: Ozh
Author URI: http://wrox.com
*/

// Add the rewrite rule and flush
register_activation_hook( __FILE__, 'boj_rrs_activate' );
function boj_rrs_activate() {
    boj_rrs_add_rules();
    flush_rewrite_rules();
}

// Flush when deactivated
register_deactivation_hook( __FILE__, 'boj_rrs_deactivate' );
function boj_rrs_deactivate() {
    flush_rewrite_rules();
}

// Add the rewrite rule
add_action( 'init', 'boj_rrs_add_rules' );
function boj_rrs_add_rules() {
    add_rewrite_rule( 'stores/?([^/]*)',
        'index.php?pagename=stores&store_id=$matches[1]', 'top' );
}

// Add the store_id var so that WP recognizes it
add_filter( 'query_vars', 'boj_rrs_add_query_var' );
function boj_rrs_add_query_var( $vars ) {
    $vars[] = 'store_id';
    $vars[] = 'storename';
    $vars[] = 'parame';
    return $vars;
}
