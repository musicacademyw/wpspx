<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

global $wpdb;
global $cpts;

function wpspx_activate() {

	$wpspx_pages = array(
		$basket = array(
			'Basket',
			'basket',
			'',
			'0',
		),
		$checkout = array(
			'Checkout',
			'checkout',
			'',
			'0',
		),
		$myaccount = array(
			'My Account',
			'my-account',
			'',
			'0',
		),
		$bookonline = array(
			'Book Online',
			'book-online',
			'',
			'0',
		),
		$upcomming = array(
			'Upcoming Shows',
			'upcoming',
			'',
			'0',
		),
	);

	foreach ( $wpspx_pages as $wpspx_page ) {
		wpspx_create_page (
			$wpspx_page[0], // title
			$wpspx_page[1], // slug
			$wpspx_page[2], // content
			$wpspx_page[3], // parent
		);
	}


	flush_rewrite_rules();

}

function wpspx_create_page($page_title = '', $slug, $page_content = '', $post_parent = 0 )
{
	global $wpdb;

	if ( strlen( $page_content ) > 0 ) {
		// Search for an existing page with the specified page content (typically a shortcode)
		$valid_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status NOT IN ( 'pending', 'trash', 'future', 'auto-draft' ) AND post_content LIKE %s LIMIT 1;", "%{$page_content}%" ) );
	} else {
		// Search for an existing page with the specified page slug
		$valid_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status NOT IN ( 'pending', 'trash', 'future', 'auto-draft' )  AND post_name = %s LIMIT 1;", $slug ) );
	}

	$valid_page_found = apply_filters( 'wpspx_create_page_id', $valid_page_found, $slug, $page_content );

	if ( $valid_page_found ) {
		return $valid_page_found;
	}

	// Search for a matching valid trashed page
	if ( strlen( $page_content ) > 0 ) {
		// Search for an existing page with the specified page content (typically a shortcode)
		$trashed_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status = 'trash' AND post_content LIKE %s LIMIT 1;", "%{$page_content}%" ) );
	} else {
		// Search for an existing page with the specified page slug
		$trashed_page_found = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='page' AND post_status = 'trash' AND post_name = %s LIMIT 1;", $slug ) );
	}

	if ( $trashed_page_found ) {
		$page_id   = $trashed_page_found;
		$page_data = array(
			'ID'             => $page_id,
			'post_status'    => 'publish',
		);
	 	wp_update_post( $page_data );
	} else {
		$page_data = array(
			'post_status'    => 'publish',
			'post_type'      => 'page',
			'post_author'    => 1,
			'post_name'      => $slug,
			'post_title'     => $page_title,
			'post_content'   => $page_content,
			'post_parent'    => $post_parent,
			'comment_status' => 'closed'
		);
		$page_id = wp_insert_post( $page_data );
	}

	return $page_id;
}


function wpspx_show_cpt() {
	$labels = array(
		'name' 					=> _x('Shows', 'post type general name'),
		'singular_name' 		=> _x('Show', 'post type singular name'),
	);

	$args = array(
		'labels' 				=> $labels,
		'menu_icon' 			=> 'dashicons-tickets-alt',
		'public' 				=> true,
		'show_ui' 				=> true,
		'show_in_rest'			=> true,
		'publicly_queryable' 	=> true,
		'query_var'	 			=> true,
		'capability_type' 		=> 'post',
		'hierarchical' 			=> false,
		'rewrite' 				=> true,
		'supports' 				=> array( 'title', 'editor', 'thumbnail', 'comments')
	);

	register_post_type('shows', $args );
}
add_action('init', 'wpspx_show_cpt');
