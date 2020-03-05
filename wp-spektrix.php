<?php
/*
 * Plugin Name: WPSPX
 * Tags: spektrix, tickets, api, booking, theatre
 * Plugin URI: https://wearebeard.com/wpwpx
 * Description: A WordPress plugin that intergrates WordPress with Spektrix API
 * Version: 2.0.0
 * Author: Martin Greenwood
 * Author URI: https://martingreenwood.com
 * Text Domain: wpspx
 * Domain Path: /languages
 * License: GPL v2 or later

 Copyright © 2016 Martin Greenwood

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 */

if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

class WPSPX {

	private static $instance = null;
	private $wpspx;

	public static function get_instance()
	{
		if ( is_null( self::$instance ) )
		{
			self::$instance = new self;
		}
		return self::$instance;
	}

	private function __construct()
	{
		add_action( 'admin_enqueue_scripts', 'wpspx_admin_scripts' );
		add_action( 'wp_enqueue_scripts', 'wpspx_frontend_scripts', 99 );
		register_activation_hook( __FILE__, 'wpspx_activate' );
	}
}

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'wpspx_plugin_settings_link' );
function wpspx_plugin_settings_link( $links )
{
	$url = admin_url() . 'options-general.php?page=wpspx';
	$_link = '<a href="'.$url.'">' . __( 'Settings', 'wpspx' ) . '</a>';
	$links[] = $_link;
	return $links;
}


include plugin_dir_path( __FILE__ )  . '/config.php';
include plugin_dir_path( __FILE__ )  . '/settings.php';
include plugin_dir_path( __FILE__ )  . '/activate.php';
// include plugin_dir_path( __FILE__ )  . '/deactivate.php';
// include plugin_dir_path( __FILE__ )  . '/uninstall.php';

WPSPX::get_instance();
