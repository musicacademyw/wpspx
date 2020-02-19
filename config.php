<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

/*
 * Config definitions
 */
define( 'WPSPX_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPSPX_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

define( 'SPEKTRIX_USER', esc_attr( get_option('wpspx_account') ) );
define( 'SPEKTRIX_CERT', esc_attr( get_option('wpspx_crt') ) );
define( 'SPEKTRIX_KEY',  esc_attr( get_option('wpspx_key') ) );
define( 'SPEKTRIX_API',  esc_attr( get_option('wpspx_api') ) );
define( 'SPEKTRIX_CUSTOM_URL',  esc_attr( get_option('wpspx_custom_url') ) );

define( 'SPEKTRIX_API_URL', 'https://api.system.spektrix.com/'.SPEKTRIX_USER.'/api/v2/');
define( 'SPEKTRIX_SECURE_WEB_URL', 'https://system.spektrix.com/'.SPEKTRIX_USER.'/website/secure/');
define( 'SPEKTRIX_NON_SECURE_WEB_URL', 'https://system.spektrix.com/'.SPEKTRIX_USER.'/website/');
define( 'THEME_SLUG', wp_get_theme()->get( 'Name' ));
