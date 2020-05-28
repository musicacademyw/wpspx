<?php
/*
 * Config definitions
 */

 if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

 $options = get_option( 'wpspx_settings' );
 $cache = get_option( 'wpspx_cache_settings' );
 $validtime = 0;
 if ($cache['wpspx_expire_cache']) {
 	$validtime = $cache['wpspx_expire_cache'];
 } else {
	$validtime = 3600;
 }

 define( 'WPSPXREF', 'gB04eRXZpMB7D0SzL4MGpuXbtAjxN9.81794921' );
 define( 'WPSPXCACHEVALIDFOR', $validtime );

 define( 'WPSPX_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
 define( 'WPSPX_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
 define( 'WPSPX_PACEHOLDER', WP_CONTENT_URL . '/uploads/wpspx/wpspx-image-portrait.jpg' );

 define( 'SPEKTRIX_USER', esc_attr( $options['wpspx_account_name'] ) );
 define( 'SPEKTRIX_API_USERNAME',  esc_attr( $options['wpspx_api_username'] ) );
 define( 'SPEKTRIX_API',  esc_attr( $options['wpspx_api_key'] ) );
 define( 'SPEKTRIX_CUSTOM_URL',  esc_attr( $options['wpspx_custom_domain'] ) );

 define( 'SPEKTRIX_API_URL', 'https://'.SPEKTRIX_CUSTOM_URL.'/'.SPEKTRIX_USER.'/api/v3/');
 define( 'SPEKTRIX_SECURE_WEB_URL', 'https://'.SPEKTRIX_CUSTOM_URL.'/'.SPEKTRIX_USER.'/website/secure/');
 define( 'SPEKTRIX_NON_SECURE_WEB_URL', 'https://'.SPEKTRIX_CUSTOM_URL.'/'.SPEKTRIX_USER.'/website/');
 define( 'THEME_SLUG', wp_get_theme()->get( 'Name' ));
