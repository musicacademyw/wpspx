<?php if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );


/*================================
=            Includes            =
================================*/

// Include framework bootstrap file
require plugin_dir_path( __FILE__ ) . '/framework/bootstrap.php';

// Inlude custom post types items
require plugin_dir_path( __FILE__ )  . '/lib/custom-post-types/cpts.php';

// load helpers
require plugin_dir_path( __FILE__ )  . '/lib/helpers/misc.php';
require plugin_dir_path( __FILE__ )  . '/lib/helpers/array_colums.php';
require plugin_dir_path( __FILE__ )  . '/lib/helpers/number_to_words.php';
require plugin_dir_path( __FILE__ )  . '/lib/helpers/options-page.php';

// load post / show association function
require plugin_dir_path( __FILE__ )  . '/lib/helpers/post-assoc.php';

// load shortcodes
require plugin_dir_path( __FILE__ )  . '/lib/shortcodes/shortcodes.php';


/*=================================
=            Functions            =
=================================*/

/*----------  enqueue admin scripts  ----------*/

add_action( 'admin_enqueue_scripts', 'wpspx_admin_scripts' );
function wpspx_admin_scripts() {
	wp_register_script(
		'wpspx_select',  WPSPX_PLUGIN_URL . 'lib/assets/js/wpspx.js', array( 'jquery' ), '1.0', true
	);
	wp_register_style(
		'wpspx_admin_css', WPSPX_PLUGIN_URL . 'lib/assets/css/wpspx.css', false, '1.0'
	);
	wp_enqueue_style( 'wpspx_admin_css' );
	wp_enqueue_script( 'wpspx_select' );
}


/*----------  enqueue front end scripts  ----------*/

add_action( 'wp_enqueue_scripts', 'wpspx_scripts', 99 );
function wpspx_scripts()
{
    wp_register_script(
        'wpspx-integrate', '//'.SPEKTRIX_CUSTOM_URL.'/'.SPEKTRIX_USER.'/website/scripts/integrate.js', '', '', false
    );
    wp_register_script(
        'wpspx-viewfromseats','//'.SPEKTRIX_CUSTOM_URL.'/'.SPEKTRIX_USER.'/website/scripts/viewfromseats.js', '', '', false
    );

    // Included jQuery check incase it has not already been added.
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
    if ( is_page( 'basket' ) ) {
        $page_template = dirname( __FILE__ ) . '/lib/templates/wpspx-basket.php';
    }
    if ( is_page( 'book-online' ) ) {
        $page_template = dirname( __FILE__ ) . '/lib/templates/wpspx-book-online.php';
    }
    if ( is_page( 'checkout' ) ) {
        $page_template = dirname( __FILE__ ) . '/lib/templates/wpspx-checkout.php';
    }
    if ( is_page( 'my-account' ) ) {
        $page_template = dirname( __FILE__ ) . '/lib/templates/wpspx-my-account.php';
    }
	if ( is_page( 'upcoming' ) ) {
		$page_template = dirname( __FILE__ ) . '/lib/templates/wpspx-upcoming.php';
	}
    return $page_template;
}


/*----------  Sanitize image  ----------*/

function wpx_sanitize_image( $input ){

	$output = '';
	$filetype = wp_check_filetype( $input );
	$mime_type = $filetype['type'];
	if ( strpos( $mime_type, 'image' ) !== false ){
		$output = $input;
	}
	return $output;
}
