<?php
if( !defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN') )
	exit;

global $wpdb;

function wpspx_uninstall() {

	delete_site_option( 'wpspx_settings' );

	$postmeta_table = $wpdb->postmeta;
	$posts_table = $wpdb->posts;

	$postmeta_table = str_replace($wpdb->base_prefix, $wpdb->prefix, $postmeta_table);
	$postmeta_table = str_replace($wpdb->base_prefix, $wpdb->prefix, $postmeta_table);

	$wpdb->query("DELETE FROM " . $postmeta_table . " WHERE meta_key = 'wpspx_account'");
	$wpdb->query("DELETE FROM " . $postmeta_table . " WHERE meta_key = 'wpspx_api'");
	$wpdb->query("DELETE FROM " . $postmeta_table . " WHERE meta_key = 'wpspx_crt'");
	$wpdb->query("DELETE FROM " . $postmeta_table . " WHERE meta_key = 'wpspx_key'");
	$wpdb->query("DELETE FROM " . $postmeta_table . " WHERE meta_key = 'wpspx_custom_url'");
	$wpdb->query("DELETE FROM " . $posts_table . " WHERE post_type = 'shows'");

	$cached_files = WP_CONTENT_DIR . '/wpspx-cache/*.json';
	array_map('unlink', glob($cached_files));

	unregister_post_type( 'shows' );
    flush_rewrite_rules();
}
register_uninstall_hook( __FILE__, 'wpspx_uninstall' );
