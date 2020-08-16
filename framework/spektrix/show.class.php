<?php if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

/**
 * This is the Show class.
 *
 * A Show is an Event in Spektrix
 * From their API docs:
 * "An event in Spektrix can be thought of as a ‘show’. It encapsulates the descriptive data about an event, such as its Name and Description. It is a container for instances."
 */
class WPSPX_Show extends WPSPX_Spektrix
{
	public $id;
	public $name;
	public $short_description;
	public $long_description;
	public $image_url;
	public $image_thumb;
	public $duration;
	public $is_on_sale;
	public $instance_dates;
	public $instances;


	function __construct($event)
	{
		//If the $event variable isn't the event object, create it from the ID
		if(!is_object($event)) {
			$event = $this->get_event($event);
		}

		$this->id = $event->id;
		$this->name = (string) $event->name;
		$this->short_description = (string) $event->description;
		$this->long_description = (string) $event->htmlDescription;
		$this->image_url = (string) $event->imageUrl;
		$this->image_thumb = (string) $event->thumbnailUrl;
		$this->duration = (integer) $event->duration;
		$this->is_on_sale = (boolean) $event->isOnSale;
		$this->instance_dates = (string) $event->instanceDates;
		if (isset($event->instances)) {
			$this->instances = (array) $event->instances;
		}

	}

	static function find_all_in_future()
	{
		$api = new WPSPX_Spektrix();
		$eternity = time() + (60 * 60 * 24 * 7 * 500);
		$shows = $api->get_shows_until($eternity);
		return $api->collect_shows($shows);
	}

	static function find_all_in_future_with_instances()
	{
		$api = new WPSPX_Spektrix();
		$eternity = time() + (60 * 60 * 24 * 7 * 500);
		$shows = $api->get_shows_until_expanded($eternity);
		return $api->collect_shows($shows);
	}

	static function this_week()
	{
		$api = new WPSPX_Spektrix();
		$next_week = time() + (60 * 60 * 24 * 7);
		$shows = $api->get_shows_until($next_week);
		return $api->collect_shows($shows);
	}

	function get_show_performances($show_id)
	{
		$performances = $this->get_object('events/'.$show_id.'/instances');
		return $this->collect_performances($performances);
	}

	function get_performances()
	{
		return $this->get_show_performances($this->id);
	}
}

function convert_to_array_of_ids($collection){
	$ids = array();
	foreach($collection as $c){
		$ids[] = $c->id;
	}
	return $ids;
}

function get_wp_shows_from_spektrix_shows($shows) {
	$show_ids = convert_to_array_of_ids($shows);

	$db_shows = get_posts(array(
		'post_type' => 'shows',
		'posts_per_page' => -1,
		'meta_query' => array(
			array(
				'key'     => '_spektrix_id',
				'value'   => $show_ids,
				'compare' => 'IN'
			)
		)
	));

	$wp_shows = array();
	foreach($db_shows as $db_show):
		$spektrix_id = get_post_meta($db_show->ID,'_spektrix_id',true);
		$wp_shows[$spektrix_id] = $db_show->ID;
	endforeach;

	return $wp_shows;
}

function filter_published($shows,$wp_shows){
	$published = array();
	foreach($shows as $k => $show):
		if(array_key_exists($show->id,$wp_shows)):
			$published[$k] = $show;
		endif;
	endforeach;
	return $published;
}

function filter_shows_by_spektrix_tag($all_shows,$term_slugs){
	$term_slugs = is_array($term_slugs) ? $term_slugs : array($term_slugs);
	$shows = array();
	foreach($all_shows as $show):
		$match_array = array_intersect($term_slugs,$show->tags);
		if(!empty($match_array)):
			$shows[] = $show;
		endif;
	endforeach;
	return $shows;
}
