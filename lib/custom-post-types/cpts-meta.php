<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

function wpspx_metaboxes()
{
	add_meta_box(
		'wpspx_poster_image',
		__( 'Show Poster Image', 'wpspx' ),
		'wpspx_meta_callback',
		'shows',
    	'side'
	);
}
add_action( 'add_meta_boxes', 'wpspx_metaboxes' );

/**
 * Outputs the content of the meta box
 */
function wpspx_meta_callback( $post ) {
	$wpspx_stored_meta = get_post_meta( $post->ID );
	?>
	<p>
		<label for="wpspx-poster-image" class="wpspx-row-title"><?php _e( 'Poster Image Upload', 'wpspx' )?></label>
		<input type="text" name="wpspx-poster-image" id="wpspx-poster-image" value="<?php if ( isset ( $wpspx_stored_meta['wpspx-poster-image'] ) ) echo $wpspx_stored_meta['wpspx-poster-image'][0]; ?>" />
		<input type="button" id="meta-image-button" class="button" value="<?php _e( 'Choose or Upload an Image', 'wpspx' )?>" />
	</p>
	<?php
}

// Checks for input and saves if needed
if( isset( $_POST[ 'wpspx-poster-image' ] ) ) {
	wp_nonce_field( $_POST[ 'wpspx-poster-image' ], 'wpspx-poster-image' );
	update_post_meta( $post->ID, 'wpspx-poster-image', my_sanitize_image($_POST[ 'wpspx-poster-image' ]) );
}
