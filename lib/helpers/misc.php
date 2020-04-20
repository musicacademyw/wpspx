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
	$cached_files = WPSPX_PLUGIN_DIR . '/wpspx-cache/*.json';
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

/*===========================================
=            CREATE POSTER IMAGE            =
===========================================*/

add_image_size( 'poster', 800, 1200, false );

/*===========================================
=            CUSTOM ADMIN COLUMS            =
// ===========================================*/

add_filter('manage_edit-shows_columns', 'wpspx_columns');
function wpspx_columns($columns) {
    $columns['spetrixid'] = 'Spektrix ID';
    $columns['starts'] = 'Starts';
    $columns['ends'] = 'Ends';
    return $columns;
}

add_action('manage_posts_custom_column',  'wpspx_show_columns');
function wpspx_show_columns($name) {
    global $post;
    $spektrix_id = get_post_meta($post->ID, '_spektrix_id', true);
    $firstdate = get_post_meta($post->ID, '_spektrix_start', true);;
    $lastdate = get_post_meta($post->ID, '_spektrix_end', true);;


    switch ($name) {
        case 'spetrixid':
            echo (integer) $spektrix_id;
            break;
        case 'starts':
        	echo date("d/m/Y", $firstdate);
			// echo '<span title="'.$firstdate->start_time->format("Y/m/d h:i:s a").'">'.$firstdate->start_time->format("d/m/Y").'</span>';
            break;
        case 'ends':
        	echo date("d/m/Y", $lastdate);
        	// echo '<span title="'.$lastdate->start_time->format("Y/m/d h:i:s a").'">'.$lastdate->start_time->format("d/m/Y").'</span>';
            break;
    }
}

add_action( 'pre_get_posts', 'my_slice_orderby' );
function my_slice_orderby( $query ) {
    if( ! is_admin() )
        return;

    $orderby = $query->get( 'orderby');

    if( 'starts' == $orderby ) {
        $query->set('meta_key','_spektrix_start');
        $query->set('orderby','meta_value_num');
    }
    if( 'ends' == $orderby ) {
        $query->set('meta_key','_spektrix_end');
        $query->set('orderby','meta_value_num');
    }
}

function wpspx_reorder_columns($columns) {
	$wpspx_columns = array();
	$comments = 'comments';
	foreach($columns as $key => $value) {
		if ($key==$comments){
			$wpspx_columns['spetrixid'] = ''; 	// Move author column before title column
			$wpspx_columns['starts'] = ''; 		// Move date column before title column
			$wpspx_columns['ends'] = '';		// Move author column before title column
		}
		$wpspx_columns[$key] = $value;
	}
	return $wpspx_columns;
}
add_filter('manage_posts_columns', 'wpspx_reorder_columns');

add_filter( 'manage_edit-shows_sortable_columns', 'wpspx_sortable_date_column' );
function wpspx_sortable_date_column( $columns ) {
    $columns['starts'] = 'starts';
    $columns['ends'] = 'ends';

    return $columns;
}


function wpspx_callback()
{
	$license = get_option( 'wpspx_licence_settings' );
	$key = $license['wpspx_license_key'];
	if (!$key) {
		?>
		<div class="notice notice-error">
			<p><?php _e( 'Plesase <a href="'.admin_url().'admin.php?page=wpspx-license">register</a> your copy of WPSPX!', 'sample-text-domain' ); ?></p>
		</div>
    <?php
	}
}
add_action( 'admin_notices', 'wpspx_callback' );

function wpspx_callback_validate($key)
{
	if ($key) {
		$response = wp_remote_get('https://wpspx.io/wp-json/lmfwc/v2/licenses/validate/'.$key.'?consumer_key=ck_db81190fd250b15d45a4a0dd393b3eef0df7f85e&consumer_secret=cs_919ec37967950a0c5a60725055ad9be43302ebf9', array('timeout' => 20, 'sslverify' => false));
		$body = wp_remote_retrieve_body($response);
		$json = json_decode($body);
		return $json;
	}
	else {
		return false;
	}
}

function wpspx_callback_activate($key)
{
	if ($key) {
		$response = wp_remote_get('https://wpspx.io/wp-json/lmfwc/v2/licenses/activate/'.$key.'?consumer_key=ck_db81190fd250b15d45a4a0dd393b3eef0df7f85e&consumer_secret=cs_919ec37967950a0c5a60725055ad9be43302ebf9', array('timeout' => 20, 'sslverify' => false));
		$body = wp_remote_retrieve_body($response);
		$json = json_decode($body);
		return $json;
	}
	else {
		return false;
	}
}


function wpspx_callback_retrieve($key)
{
	if ($key) {
		$response = wp_remote_get('https://wpspx.io/wp-json/lmfwc/v2/licenses/'.$key.'?consumer_key=ck_db81190fd250b15d45a4a0dd393b3eef0df7f85e&consumer_secret=cs_919ec37967950a0c5a60725055ad9be43302ebf9', array('timeout' => 20, 'sslverify' => false));
		$body = wp_remote_retrieve_body($response);
		$json = json_decode($body);
		return $json;
	}
	else {
		return false;
	}
}
