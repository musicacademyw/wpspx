<?php if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

/**
 * Spektrix is a base class for hitting the API and retrieving data
 */

class Spektrix
{

	private static $api_key = SPEKTRIX_API;
	private static $api_url = SPEKTRIX_API_URL;

	function build_url($resource,$params = array())
	{
		$params_string = "";
		if(empty($params)):
			$url = self::$api_url.$resource;
		else:

			foreach($params as $k => $v):
				$params_string .= $k . '=' . $v . '&';
			endforeach;

			// $url = self::$api_url.$resource."?".$params_string;
			$url = self::$api_url.$resource."?".substr($params_string, 0, -1);

		endif;

		// $url.= "?all=true";

		return $url;
	}

	function get_spektrix_data($obj_url, $method = 'GET')
	{

		$requestargs = array(
			'timeout' 		=> 120,
		);
		$request = wp_remote_get( $obj_url, $requestargs );
		if( is_wp_error( $request ) ) {
			return false; // Bail early
		}

		$json = wp_remote_retrieve_body( $request );
		return $json;
	}

	function get_object($resource,$params=array())
	{
		$file = new CachedFile($resource, $params);
		try
		{
			if($file->is_cached_and_fresh()){
				$spektrix_data = $file->retrieve();
			} else {
				$obj_url = $this->build_url($resource,$params);
				$spektrix_data = $this->get_spektrix_data($obj_url);
				$spektrix_data = $spektrix_data;
				$file->store($spektrix_data);
			}
			if($spektrix_data){
				$json_as_object = json_decode($spektrix_data, false);
				return $json_as_object;
			} else {
				throw new Exception('no data received from Spektrix');
			}
		}
		catch (Exception $e)
		{
			?>
			<div class="message is-warning">
				<div class="message-body">
					<?php echo $e->getMessage(); ?>. Double check you WPSPX settings are correct
				</div>
			</div>
			<?php
		}
	}

	function get_object_nocache($resource,$params=array())
	{
		try
		{
			$obj_url = $this->build_url($resource,$params);
			$spektrix_data = $this->get_spektrix_data($obj_url);
			$spektrix_data = $spektrix_data;
			if($spektrix_data){
				$json_as_object = json_decode($spektrix_data, false);
				return $json_as_object;
			} else {
				throw new Exception('no data received from Spektrix');
			}
		}
		catch (Exception $e)
		{
			?>
			<div class="message is-warning">
				<div class="message-body">
					<?php echo $e->getMessage(); ?>. Double check you WPSPX settings are correct
				</div>
			</div>
			<?php
		}
	}

	function get_event($id)
	{
		return $this->get_object('events/'.$id);
	}

	function get_events()
	{
		return $this->get_object('events');
	}

	function get_shows_until($unix_until_date)
	{
		$today = date('Y-m-d');
		$until_date = date('Y-m-d',$unix_until_date);
		return $this->get_object('events',array('instanceStart_from'=>$today,'instanceStart_to'=>$until_date));
	}

	function get_shows_until_expanded($unix_until_date)
	{
		$today = date('Y-m-d');
		$until_date = date('Y-m-d',$unix_until_date);
		return $this->get_object('events',array('instanceStart_from'=>$today,'instanceStart_to'=>$until_date, '$expand'=>'instances'));
	}

	function get_performances_until($unix_until_date)
	{
		$today = date('Y-m-d');
		$until_date = date('Y-m-d',$unix_until_date);
		return $this->get_object('instances',array('startFrom'=>$today,'startTo'=>$until_date));
	}

	protected function collect_shows($shows)
	{
		$collection = array();
		foreach($shows as $show){
			$id = $show->id;
			$collection[$id] = new Show($show);
		}
		return $collection;
	}

	function get_performance($id)
	{
		return $this->get_object('instances/'.$id);
	}

	function get_performances()
	{
		return $this->get_object('instances');
	}

	protected function collect_performances($performances)
	{
		$collection = array();
		foreach($performances as $performance){
			$collection[] = new Performance($performance);
		}
		return $collection;
	}

	function get_price_list($performance_id)
	{
		$pricelist = $this->get_object('instances/'.$performance_id.'/price-list');
		$collection = new PriceList($pricelist);
		return $collection;
	}

	protected function collect_performances_by_show($performances)
	{
		$collection = array();
		foreach($performances as $performance){
			$show_id = $performance->id;
			$collection[$show_id][] = new Performance($performance);
		}
		return $collection;
	}

	function get_plans($event_id)
	{
		return $this->get_object('plans',array('event_id'=>$event_id));
	}

	function get_ticket_types()
	{
		return $this->get_object('ticket-types');
	}

	function get_bands()
	{
		return $this->get_object('bands');
	}

	function get_availability($performance_id)
	{
		$availabilities = $this->get_object('instances/'.$performance_id.'/status', array('includeChildPlans'=>'true'));
		$collection = new Availability($availabilities);
		return $collection;
	}

	function get_memberships()
	{
		return $this->get_object('memberships');
	}

	function get_basket()
	{
		return $this->get_object_nocache('basket');
	}

}
