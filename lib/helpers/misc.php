<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

/*==========================================
=            QUERY VAR - CUSTOM            =
==========================================*/

add_filter('query_vars', 'wpspx_dd_my_var');
function wpspx_dd_my_var($public_query_vars)
{
	$public_query_vars[] = 'performance';
	$public_query_vars[] = 'lid';
	return $public_query_vars;
}

/*==================================
=            DO REWRITE            =
==================================*/

add_action('init', 'wpspx_do_rewrite');
function wpspx_do_rewrite()
{
	add_rewrite_rule('book-online/([^/]+)/?$', 'index.php?pagename=book-online&performance=$matches[1]','top');
}

/*===========================================
=            GET FIRST PARAGRAPH            =
===========================================*/

function wpspx_get_first_paragraph($post_content)
{
	$str = wpautop($post_content);
	$str = substr( $str, 0, strpos( $str, '</p>' ) + 4 );
	$str = strip_tags($str, '<a><strong><em>');
	return '<p>' . $str . '</p>';
}

/*=======================================
=            CLEAR THE CACHE            =
=======================================*/
function wpspx_bust_cache()
{
	$cached_files = WPSPX_PLUGIN_DIR . '/wpspx-cache/*.txt';
	try
	{
		array_map('unlink', glob($cached_files)); ?>
		<div class="notice notice-success is-dismissible">
			<p><?php _e( 'WPSPX cache has been cleared!', 'wpspx' ); ?></p>
		</div>
		<?php
	} catch (Exception $e)
	{
		?>
		<div class="notice notice-success is-dismissible">
			<p><?php echo  $e->getMessage() . '\n'; ?></p>
		</div>
		<?php
	}
}

/*=============================================
=            CONVERT HOURS TO MINS            =
=============================================*/

function wpspx_convert_to_hours_minutes($minutes)
{
	$return_string = '';

	$hours = floor($minutes / 60);
	if($hours)
	{
		$return_string .= $hours . ' hours';
	}

	$minutes = $minutes % 60;
	if($minutes) {
		$return_string .= ' ' . $minutes . ' minutes';
	}

	return $return_string;
}

/*============================================
=            CONVERT MINS TO SECS            =
============================================*/
function wpspx_convert_to_seconds($minutes)
{
	$seconds = $minutes * 60;
	return $seconds;
}

/*=============================================
=            CREATE REQUIRED PAGES            =
=============================================*/
