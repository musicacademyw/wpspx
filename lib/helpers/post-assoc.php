<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

/**
 * Associates spektrix record with wp cpt & caches json
 * files from Spekterix to speed up frontend delivery
 */


// Adds a sprktrix show title meta box to the main column on the post edit screens
add_action('add_meta_boxes','wpspx_record_add_custom_box' );
function wpspx_record_add_custom_box() {
	global $pagenow;
	$meta_box_title = ($pagenow == 'post-new.php' && is_admin()) ? 'Choose record from Spektrix' : 'You are editing';
	$screens = array('shows');
	foreach ($screens as $screen) {
		add_meta_box(
			'wpspx_record_sectionid',
			__($meta_box_title, 'wpspx' ),
			'wpspx_record_inner_custom_box',
			$screen,
			'advanced',
			'high'
		);
	}
}

// Adds a sprktrix cache meta box to the main column on the post edit screens
add_action('add_meta_boxes','wpspx_cache_add_custom_box' );
function wpspx_cache_add_custom_box() {
	global $pagenow;
	$meta_box_title = 'WPSPX Cache Check';
	$screens = array('shows');
	foreach ($screens as $screen) {
		add_meta_box(
			'wpspx_cache_section', 									// Unique ID
			__($meta_box_title, 'wpspx' ),							// Title
			'wpspx_cache_inner_custom_box',							// Callback function
			$screen, 												// Admin page (or post type)
			'side', 												// Context
			'high' 													// Priority
		);
	}
}

// prints title meta box content
function wpspx_record_inner_custom_box( $post ) {
	global $pagenow;
	if($pagenow == 'post-new.php'){
		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'wpspx_data_nonce' );
		$shows_in_spektrix = Show::find_all_in_future_with_instances();
		$shows_in_wordpress = get_posts(array('post_type'=>'shows','posts_per_page'=>-1));

    	// Create an array of IDs of shows in WP.
		// (We use this to ensure we don't ask the user to choose a shows in Spectrix that has already been added to WP)
		$wp_shows = array();
		foreach($shows_in_wordpress as $siw){
			$wp_shows[] = get_post_meta($siw->ID,'_spektrix_id',true);
		}

		echo "<p>Shows in Spektrix: " . count($shows_in_spektrix) . "<br>";
		echo "Shows in WordPress: " . count($shows_in_wordpress) . "</p>";

		if (count($shows_in_spektrix) === count($shows_in_wordpress)) {
			echo '<label for="wpspx_data_field">';
				_e("All shows added!", 'wpspx' );

			echo '</label> ';
		} else {
			echo '<label for="wpspx_data_field">';
				_e("Choose from show from Spektrix ", 'wpspx' );

			echo '</label> ';
			echo '<select id="wpspx_data_field" name="wpspx_data_field">';
			foreach($shows_in_spektrix as $show):
				if(!in_array($show->id,$wp_shows)){
					echo '<option value="'.$show->id.'|'.$show->name.'|'.$show->short_description.'">'.$show->name.'</option>';
				}
			endforeach;
			echo '</select>';
		}
	} else {
		if(get_post_meta($post->ID,'_spektrix_id',true)){
			echo '<h1>'.$post->post_title . ' // Spektrix ID: ' . get_post_meta($post->ID,'_spektrix_id',true) . '</h1>';
		} else {
			echo '<h1>'.$post->post_title . ' // No Spektrix ID</h1>';
		}
	}
}

// prints wpspx cache box content
function wpspx_cache_inner_custom_box( $post ) {
	global $pagenow;
	$api = new Spektrix();
	$tick = '<svg id="Capa_1" enable-background="new 0 0 515.556 515.556" height="512" viewBox="0 0 515.556 515.556" width="512" xmlns="http://www.w3.org/2000/svg"><path d="m0 274.226 176.549 176.886 339.007-338.672-48.67-47.997-290.337 290-128.553-128.552z"/></svg>';
	if($pagenow == 'post-new.php'){
		echo "<h4>Upon saving this show we will attempt to cache the necessary files needed from Spektrix</h4>";
	} else {
		echo "<h4>We have cached the following data from Spektrix for this show:</h4>";

		echo "<ul>";
			echo "<li>".$tick." Show Data</li>";
			echo "<li>".$tick." Performances</li>";
			echo "<li>".$tick." Price Lists</li>";
			echo "<li>".$tick." Availability</li>";
		echo "</ul>";

		$spektrix_id = get_post_meta($post->ID,'_spektrix_id',true);
		$show = new Show($spektrix_id);
		$api = new Spektrix();
		$performances = $show->get_performances();
		foreach ($performances as $performance) {
			$pricelists = $api->get_price_list($performance->id);
			$availabilities = $api->get_availability($performance->id);
		}

	}
}

/* When the post is saved, saves our custom data */
add_action('save_post','wpspx_save_postdata');
function wpspx_save_postdata($post_id) {

	if ( ! current_user_can( 'edit_post', $post_id ) )
		return;

		// we need to check if the user intended to change this value.
		if ( ! isset( $_POST['wpspx_data_field'] ) || ! wp_verify_nonce( $_POST['wpspx_data_nonce'], plugin_basename( __FILE__ ) ) )
			return;

		//if saving in a custom table, get post_ID
		$post_ID = $_POST['post_ID'];
		$spektrix_data = sanitize_text_field($_POST['wpspx_data_field']);
		$spektrix_data = explode('|',$spektrix_data);


		//remove action to prevent infinite loop
		remove_action('save_post','wpspx_save_postdata');
		wp_update_post(
			array(
				'ID'			=> $post_ID,
				'post_title'	=> $spektrix_data[1],
				'post_name'		=> str_replace(' ','-',$spektrix_data[1]),
				'post_content'	=> $spektrix_data[2]
			)
		);
		add_action('save_post','wpspx_save_postdata');

		add_post_meta($post_ID, '_spektrix_id', $spektrix_data[0], true) or
		update_post_meta($post_ID, '_spektrix_id', $spektrix_data[0]);

		$show = new Show($spektrix_data[0]);
		$performances = $show->get_performances();
	    $firstdate = reset($performances);
	    $lastdate = end($performances);

		add_post_meta($post_ID, '_spektrix_start', $firstdate, true) or
		update_post_meta($post_ID, '_spektrix_start', $firstdate);

		add_post_meta($post_ID, '_spektrix_end', $lastdate, true) or
		update_post_meta($post_ID, '_spektrix_end', $lastdate);
}

// Now move advanced meta boxes after the title:
add_action('edit_form_after_title', 'wpspx_move_deck');
function wpspx_move_deck() {

	// Get the globals:
	global $post, $wp_meta_boxes;

	// Output the "advanced" meta boxes:
	do_meta_boxes(get_current_screen(), 'advanced', $post);

	// Remove the initial "advanced" meta boxes:
	unset($wp_meta_boxes['shows']['advanced']);
}

// Hide the title on edit only
add_action('admin_init', 'wpspx_hide_title');
function wpspx_hide_title() {
	global $pagenow;

	if ( !empty($pagenow) ){
		// remove_post_type_support('shows', 'title');
	}
}
