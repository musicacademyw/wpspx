<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );
/*
* Cache events from Spektrix
*/

$api = New WPSPX_Spektrix();
$events = $api->get_data('events');

// Grab all future shows in spektrix and all shows in WordPress
$shows_in_spektrix = WPSPX_Show::find_all_in_future_with_instances();
$shows_in_wordpress = get_posts(array('post_type'=>'shows','posts_per_page'=>-1));

$wp_shows = array();
foreach($shows_in_wordpress as $siw){
	$wp_shows[] = get_post_meta($siw->ID,'_spektrix_id',true);
}

foreach($shows_in_spektrix as $show):
	if(!in_array($show->id,$wp_shows)){
		$spektrix_id = $show->id;
		$spektrix_name = $show->name;
		$spektrix_short_description = $show->short_description;
		// $api = new Spektrix();
		$performances = $show->get_performances();
		foreach ($performances as $performance) {
			$pricelists = $api->get_price_list($performance->id);
			$availabilities = $api->get_availability($performance->id);
		}
		$firstdate = reset($performances);
		$lastdate = end($performances);

		// Create post object
		$my_post = array(
			'post_type'		=> 'shows',
			'post_title'    => $spektrix_name,
			'post_content'  => $spektrix_short_description,
			'post_status'   => 'publish',
			'post_author'   => 1,
			'meta_input' 	=> array(
				'_spektrix_id'	=> $spektrix_id,
				'_spektrix_start'	=> $firstdate->start_time->format('U'),
				'_spektrix_end'	=> $lastdate->start_time->format('U'),
			),
		);

		// Insert the post into the database
		wp_insert_post( $my_post );
	}
endforeach;
flush_rewrite_rules( );

?>
