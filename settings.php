<?php
/*
 * File includes & settings
 */

if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

// Include framework bootstrap file
require plugin_dir_path( __FILE__ ) . '/framework/bootstrap.php';

// Inlude custom post types items
// require plugin_dir_path( __FILE__ )  . '/lib/custom-post-types/cpts.php';

// Plugin helpers
require plugin_dir_path( __FILE__ )  . '/lib/helpers/misc.php';
require plugin_dir_path( __FILE__ )  . '/lib/helpers/number_to_words.php';
require plugin_dir_path( __FILE__ )  . '/lib/helpers/options-page.php';

// Load post / show association
require plugin_dir_path( __FILE__ )  . '/lib/helpers/post-assoc.php';

function wpspx_admin_scripts()
 {
	 wp_register_style('wpspx_admin_css', WPSPX_PLUGIN_URL . 'lib/assets/css/wpspx-admin.css', false, '1.0');
	 wp_register_script('wpspx_js',  WPSPX_PLUGIN_URL . 'lib/assets/js/wpspx-min.js', array( 'jquery' ), '1.0', true);

	 wp_enqueue_style( 'wpspx_admin_css' );
	 wp_enqueue_script( 'wpspx_js' );
 }

 function wpspx_frontend_scripts()
 {
	 wp_register_style('wpspx_css', WPSPX_PLUGIN_URL . 'lib/assets/css/wpspx-front.css', false, '1.0');
	 wp_register_script('wpspx-integrate', '//'.SPEKTRIX_CUSTOM_URL.'/'.SPEKTRIX_USER.'/website/scripts/integrate.js', '', '', false);
	 wp_register_script('wpspx-viewfromseats','//'.SPEKTRIX_CUSTOM_URL.'/'.SPEKTRIX_USER.'/website/scripts/viewfromseats.js', '', '', false);

	 wp_enqueue_style( 'wpspx_css' );

	 if(!wp_script_is('jquery')) {
		 wp_enqueue_script( 'jquery' );
	 }
	 wp_enqueue_script( 'wpspx-integrate' );
	 wp_enqueue_script( 'wpspx-viewfromseats' );
 }

/*----------  load custom templates for post types  ----------*/

add_filter('single_template', 'wpspx_templates');
function wpspx_templates()
{
	global $wp_query, $post;

	// Check for single template by post type
	if ($post->post_type == "shows")
	{
		if(file_exists(plugin_dir_path( __FILE__ )  . '/lib/templates/single-shows.php'))
		{
			return plugin_dir_path( __FILE__ )  . '/lib/templates/single-shows.php';
		}
	}
}

add_filter( 'page_template', 'wpspx_page_template' );
function wpspx_page_template( $page_template )
{
	if ( is_page( 'basket' ) )
	{
		$page_template = dirname( __FILE__ ) . '/lib/templates/wpspx-basket.php';
	}
    if ( is_page( 'book-online' ) )
    {
        $page_template = dirname( __FILE__ ) . '/lib/templates/wpspx-book-online.php';
    }
    if ( is_page( 'checkout' ) )
    {
        $page_template = dirname( __FILE__ ) . '/lib/templates/wpspx-checkout.php';
    }
    if ( is_page( 'my-account' ) )
    {
        $page_template = dirname( __FILE__ ) . '/lib/templates/wpspx-my-account.php';
    }
	if ( is_page( 'upcoming' ) )
	{
		$page_template = dirname( __FILE__ ) . '/lib/templates/wpspx-upcoming.php';
	}
    return $page_template;
}
