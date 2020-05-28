<?php
/*
 * File includes & settings
 */

if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

// Include framework bootstrap file
require plugin_dir_path( __FILE__ ) . '/framework/bootstrap.php';

// Plugin helpers
require plugin_dir_path( __FILE__ )  . '/lib/helpers/misc.php';
require plugin_dir_path( __FILE__ )  . '/lib/helpers/number_to_words.php';
require plugin_dir_path( __FILE__ )  . '/lib/helpers/options-page.php';

// Load post / show association
require plugin_dir_path( __FILE__ )  . '/lib/helpers/post-assoc.php';

// shortcodes
require plugin_dir_path( __FILE__ )  . '/lib/shortcodes/wpspx-shows.php';
require plugin_dir_path( __FILE__ )  . '/lib/shortcodes/wpspx-giftcard.php';
require plugin_dir_path( __FILE__ )  . '/lib/shortcodes/wpspx-donate.php';
require plugin_dir_path( __FILE__ )  . '/lib/shortcodes/wpspx-membership.php';
require plugin_dir_path( __FILE__ )  . '/lib/shortcodes/wpspx-memberships.php';

function wpspx_admin_scripts()
 {
	 wp_register_style('wpspx_admin_css', WPSPX_PLUGIN_URL . 'lib/assets/css/wpspx-admin.css', false, '1.0');
	 wp_register_script('wpspx_js',  WPSPX_PLUGIN_URL . 'lib/assets/js/wpspx-min.js', array( 'jquery' ), '1.0', true);

	 wp_enqueue_style( 'wpspx_admin_css' );
	 wp_enqueue_script( 'wpspx_js' );
 }

 function wpspx_frontend_scripts()
 {
	 wp_register_style('wpspx_styles', WPSPX_PLUGIN_URL . 'lib/assets/css/wpspx-styles.css', false, '1.0');

	 wp_register_script('wpspx_front_js',  WPSPX_PLUGIN_URL . 'lib/assets/js/wpspx-front-min.js', array( 'jquery' ), '1.0', true);

	 wp_register_script('wpspx-fontawesome', 'https://kit.fontawesome.com/07652d90a4.js', '', '', false);
	 wp_register_script('wpspx-integrate', '//'.SPEKTRIX_CUSTOM_URL.'/'.SPEKTRIX_USER.'/website/scripts/integrate.js', '', '', false);
	 wp_register_script('wpspx-viewfromseats','//'.SPEKTRIX_CUSTOM_URL.'/'.SPEKTRIX_USER.'/website/scripts/viewfromseats.js', '', '', false);

	 $options = get_option( 'wpspx_support_settings' );

	 if (!isset($options['wpspx_disable_styles'] )) {
		 wp_enqueue_style( 'wpspx_styles' );
	 }
	 if(!wp_script_is('jquery')) {
		 wp_enqueue_script( 'jquery' );
	 }

	 if (!isset($options['wpspx_disable_fontawesome'] )) {
		 wp_enqueue_script( 'wpspx-fontawesome' );
	 }
	 wp_enqueue_script( 'wpspx_front_js' );
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
		if(file_exists(get_template_directory()  . '/wpspx/single-shows.php'))
		{
			return get_template_directory()  . '/wpspx/single-shows.php';
		}
		else if(file_exists(plugin_dir_path( __FILE__ )  . '/lib/templates/single-shows.php'))
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
		if(file_exists(get_template_directory()  . '/wpspx/wpspx-basket.php'))
		{
			$page_template = get_template_directory() . '/wpspx/wpspx-basket.php';
		}
		else if(file_exists(plugin_dir_path( __FILE__ )  . '/lib/templates/wpspx-basket.php'))
		{
			$page_template = dirname( __FILE__ ) . '/lib/templates/wpspx-basket.php';
		}
	}
    if ( is_page( 'book-online' ) )
    {
		if(file_exists(get_template_directory()  . '/wpspx/wpspx-book-online.php'))
		{
			$page_template = get_template_directory() . '/wpspx/wpspx-book-online.php';
		}
		else if(file_exists(plugin_dir_path( __FILE__ )  . '/lib/templates/wpspx-book-online.php'))
		{
			$page_template = dirname( __FILE__ ) . '/lib/templates/wpspx-book-online.php';
		}
    }
    if ( is_page( 'checkout' ) )
    {
		if(file_exists(get_template_directory()  . '/wpspx/wpspx-checkout.php'))
		{
			$page_template = get_template_directory() . '/wpspx/wpspx-checkout.php';
		}
		else if(file_exists(plugin_dir_path( __FILE__ )  . '/lib/templates/wpspx-checkout.php'))
		{
			$page_template = dirname( __FILE__ ) . '/lib/templates/wpspx-checkout.php';
		}
    }
    if ( is_page( 'my-account' ) )
    {
		if(file_exists(get_template_directory()  . '/wpspx/wpspx-my-account.php'))
		{
			$page_template = get_template_directory() . '/wpspx/wpspx-my-account.php';
		}
		else if(file_exists(plugin_dir_path( __FILE__ )  . '/lib/templates/wpspx-my-account.php'))
		{
			$page_template = dirname( __FILE__ ) . '/lib/templates/wpspx-my-account.php';
		}
    }
	if ( is_page( 'upcoming' ) )
	{
		if(file_exists(get_template_directory()  . '/wpspx/wpspx-upcoming.php'))
		{
			$page_template = get_template_directory() . '/wpspx/wpspx-upcoming.php';
		}
		else if(file_exists(plugin_dir_path( __FILE__ )  . '/lib/templates/wpspx-upcoming.php'))
		{
			$page_template = dirname( __FILE__ ) . '/lib/templates/wpspx-upcoming.php';
		}
	}
	if ( is_page( 'memberships' ) )
	{
		if(file_exists(get_template_directory()  . '/wpspx/wpspx-memberships.php'))
		{
			$page_template = get_template_directory() . '/wpspx/wpspx-memberships.php';
		}
		else if(file_exists(plugin_dir_path( __FILE__ )  . '/lib/templates/wpspx-memberships.php'))
		{
			$page_template = dirname( __FILE__ ) . '/lib/templates/wpspx-memberships.php';
		}
	}
	if ( is_page( 'gift-cards' ) )
	{
		if(file_exists(get_template_directory()  . '/wpspx/wpspx-giftcards.php'))
		{
			$page_template = get_template_directory() . '/wpspx/wpspx-giftcards.php';
		}
		else if(file_exists(plugin_dir_path( __FILE__ )  . '/lib/templates/wpspx-giftcards.php'))
		{
			$page_template = dirname( __FILE__ ) . '/lib/templates/wpspx-giftcards.php';
		}
	}
	if ( is_page( 'donate' ) )
	{
		if(file_exists(get_template_directory()  . '/wpspx/wpspx-donate.php'))
		{
			$page_template = get_template_directory() . '/wpspx/wpspx-donate.php';
		}
		else if(file_exists(plugin_dir_path( __FILE__ )  . '/lib/templates/wpspx-donate.php'))
		{
			$page_template = dirname( __FILE__ ) . '/lib/templates/wpspx-donate.php';
		}
	}
    return $page_template;
}

// Async load
function wpspx_webc_scripts($url)
{
	?>
	<script src="https://webcomponents.spektrix.com/stable/webcomponents-loader.js"></script>
	<script src="https://webcomponents.spektrix.com/stable/spektrix-component-loader.js" data-components="spektrix-basket-summary,spektrix-memberships,spektrix-donate,spektrix-gift-vouchers" async></script>
	<?php
}
add_filter( 'wp_head', 'wpspx_webc_scripts', 999 );


// Create placeholder image
function wpspx_placeholder()
{
	$placeholder = WP_CONTENT_DIR.'/plugins/wpspx/lib/assets/wpspx-image-portrait.jpg';
	$placeholder_theme = WP_CONTENT_DIR . '/uploads/wpspx/wpspx-image-portrait.jpg';
	if (!is_dir(WP_CONTENT_DIR . '/uploads/wpspx/')) {
		mkdir(dirname($placeholder_theme), 0777, true);
	}
	if(!@copy($placeholder,$placeholder_theme))
	{
		$errors= error_get_last();
		echo "COPY ERROR: ".$errors['type'];
		echo "<br />\n".$errors['message'];
	} else {
		// File copied to uploads;
	}
}
add_action( 'wp_head', 'wpspx_placeholder' );


$api = New Spektrix();
