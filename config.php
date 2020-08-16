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

 define( 'WPSPXCACHEVALIDFOR', $validtime );

 define( 'WPSPX_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
 define( 'WPSPX_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
 define( 'WPSPX_PACEHOLDER', WP_CONTENT_URL . '/uploads/wpspx/wpspx-image-portrait.jpg' );

 define( 'WPSPX_SPEKTRIX_USER', esc_attr( $options['wpspx_account_name'] ) );
 define( 'WPSPX_SPEKTRIX_CUSTOM_URL',  esc_attr( $options['wpspx_custom_domain'] ) );

 define( 'WPSPX_SPEKTRIX_API_URL', 'https://'.WPSPX_SPEKTRIX_CUSTOM_URL.'/'.WPSPX_SPEKTRIX_USER.'/api/v3/');
 define( 'WPSPX_SPEKTRIX_SECURE_WEB_URL', 'https://'.WPSPX_SPEKTRIX_CUSTOM_URL.'/'.WPSPX_SPEKTRIX_USER.'/website/secure/');
 define( 'WPSPX_SPEKTRIX_NON_SECURE_WEB_URL', 'https://'.WPSPX_SPEKTRIX_CUSTOM_URL.'/'.WPSPX_SPEKTRIX_USER.'/website/');
