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
	$cached_files = WP_CONTENT_DIR . '/wpspx-cache/*.json';
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

/*================================================
=            CONVERT SECONDS TO WORDS            =
================================================*/
function secondsToWords($seconds)
{
    $ret = "";

    /*** get the days ***/
    $days = intval(intval($seconds) / (3600*24));
    if($days> 0)
    {
        $ret .= "$days days ";
    }

    /*** get the hours ***/
    $hours = (intval($seconds) / 3600) % 24;
    if($hours > 0)
    {
        $ret .= "$hours hours ";
    }

    /*** get the minutes ***/
    $minutes = (intval($seconds) / 60) % 60;
    if($minutes > 0)
    {
        $ret .= "$minutes minutes ";
    }

    /*** get the seconds ***/
    $seconds = intval($seconds) % 60;
    if ($seconds > 0) {
        $ret .= "$seconds seconds";
    }

    return $ret;
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

/**
 * WPSPX Cache filter
 */
function wpspx_cache_query_vars_filter($vars) {
  $vars[] .= 'resource';
  return $vars;
}
add_filter( 'query_vars', 'wpspx_cache_query_vars_filter' );

function wpspx_cache_query_template( $template ) {
    if ( isset( $_GET['resource'] ) ) {
        $resource = $_GET['resource'];
        include plugin_dir_path( __FILE__ ) . 'cache/'.$resource.'.php';
        die;
    }
}
add_filter( 'init', 'wpspx_cache_query_template' );