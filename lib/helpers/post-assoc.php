<?php if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

// Associate spektrix record with wp cpt

add_action('add_meta_boxes','spectrix_record_add_custom_box' );
add_action('save_post','wpspx_save_postdata');

// Adds a box to the main column on the Post and Page edit screens
function spectrix_record_add_custom_box() {
	global $pagenow;
	$meta_box_title = ($pagenow == 'post-new.php' && is_admin()) ? 'Choose record from spektrix' : 'You are editing';
	$screens = array('shows');
	foreach ($screens as $screen) {
		add_meta_box(
			'spectrix_record_sectionid',
			__($meta_box_title, 'wpspx' ),
			'spectrix_record_inner_custom_box',
			$screen,
			'advanced',
			'high'
		);
	}
}

/* Prints the box content */
function spectrix_record_inner_custom_box( $post ) {
	global $pagenow;
	if($pagenow == 'post-new.php'){
		// Use nonce for verification
		wp_nonce_field( plugin_basename( __FILE__ ), 'spectrix_data_nonce' );
		$shows_in_spectrix = Show::find_all_in_future();
		$shows_in_wordpress = get_posts(array('post_type'=>'shows','posts_per_page'=>-1));

    	// Create an array of IDs of shows in WP.
		// (We use this to ensure we don't ask the user to choose a shows in Spectrix that has already been added to WP)
		$wp_shows = array();
		foreach($shows_in_wordpress as $siw){
			$wp_shows[] = get_post_meta($siw->ID,'_spectrix_id',true);
		}

		echo '<label for="spectrix_data_field">';
			_e("Choose from show from spektrix", 'wpspx' );
		echo '</label> ';
		echo '<select id="spectrix_data_field" name="spectrix_data_field">';
		foreach($shows_in_spectrix as $show):
			if(!in_array($show->id,$wp_shows)){
				echo '<option value="'.$show->id.'|'.$show->name.'|'.$show->short_description.'">'.$show->name.'</option>';
			}
		endforeach;
		echo '</select>';
	} else {
		if(get_post_meta($post->ID,'_spectrix_id',true)){
			echo '<h1>'.$post->post_title . ' // Spektrix ID: ' . get_post_meta($post->ID,'_spectrix_id',true) . '</h1>';
		} else {
			echo '<h1>'.$post->post_title . ' // No Spektrix ID</h1>';
		}
	}
}

/* When the post is saved, saves our custom data */
function wpspx_save_postdata($post_id) {

	if ( ! current_user_can( 'edit_post', $post_id ) )
		return;

		// we need to check if the user intended to change this value.
		if ( ! isset( $_POST['spectrix_data_field'] ) || ! wp_verify_nonce( $_POST['spectrix_data_nonce'], plugin_basename( __FILE__ ) ) )
			return;

		//if saving in a custom table, get post_ID
		$post_ID = $_POST['post_ID'];
		$spectrix_data = sanitize_text_field($_POST['spectrix_data_field']);
		$spectrix_data = explode('|',$spectrix_data);
		$the_content = wpautop($spectrix_data[2]);

		//remove action to prevent infinite loop
		remove_action('save_post','wpspx_save_postdata');
		wp_update_post(
			array(
				'ID'			=> $post_ID,
				'post_title'	=> $spectrix_data[1],
				'post_name'		=> str_replace(' ','-',$spectrix_data[1]),
				'post_content'	=> $the_content,
			)
		);
		add_action('save_post','wpspx_save_postdata');

		add_post_meta($post_ID, '_spektrix_id', $spectrix_data[0], true) or
		update_post_meta($post_ID, '_spektrix_id', $spectrix_data[0]);
}

// Now move advanced meta boxes after the title:
function wpspx_move_deck() {

	// Get the globals:
	global $post, $wp_meta_boxes;

	// Output the "advanced" meta boxes:
	do_meta_boxes(get_current_screen(), 'advanced', $post);

	// Remove the initial "advanced" meta boxes:
	unset($wp_meta_boxes['shows']['advanced']);
}

add_action('edit_form_after_title', 'wpspx_move_deck');


// Hide the title on edit only
add_action('admin_init', 'wpspx_hide_title');
function wpspx_hide_title() {
	global $pagenow;

	if ( !empty($pagenow) ){
		remove_post_type_support('shows', 'title');
	}
}
