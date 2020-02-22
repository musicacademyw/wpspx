<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );
	exit;

global $wpdb;

function wpspx_deactivate() {

	// do things on deactivation

}
register_deactivation_hook( __FILE__, 'wpspx_deactivate' );
