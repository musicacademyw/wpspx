<?php if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

/**
 * Performance Class
 *
 * A performance is an Instance in Spektrix
 * From their API:
 * "An instance represents an occurrence of an event and contains the Start time, Duration and status of that occurrence of the event. It references the price list and plan that are being used at that time."
 */
class WPSPX_Performance extends WPSPX_Spektrix
{
	public $id;
	public $start_time;
	public $start_time_utc;
	public $start_selling_at;
	public $start_selling_at_utc;
	public $stop_selling_at;
	public $stop_selling_at_utc;

	public $pricelist_id;
	public $plan_id;
	public $show_id;
	public $cancelled;

	public $is_on_sale;

	function __construct($performance)
	{
		//Load a performance by ID if not object loaded in
		if(!is_object($performance)){
			$performance = $this->get_performance($performance);
		}

		$this->id = $performance->id;

		$this->start_time = new DateTime((string) $performance->start);
		$this->start_time_utc = new DateTime((string) $performance->startUtc);
		$this->start_selling_at = new DateTime((string) $performance->startSellingAtWeb);
		$this->start_selling_at_utc = new DateTime((string) $performance->startSellingAtWebUtc);
		$this->stop_selling_at = new DateTime((string) $performance->stopSellingAtWeb);
		$this->stop_selling_at_utc = new DateTime((string) $performance->stopSellingAtWebUtc);

		$this->show_id = $performance->event;
		$this->plan_id = $performance->planId;
		$this->pricelist_id = $performance->priceList;

		$this->is_on_sale = $performance->isOnSale == 'true' ? true : false;

	}

	static function find_all()
	{
		$api = new WPSPX_Spektrix();
		$performances = $api->get_performances();
		return $api->collect_performances($performances);
	}

	static function find_all_in_future($by_show = false)
	{
		$api = new WPSPX_Spektrix();
		$eternity = time() + (60 * 60 * 24 * 7 * 500);
		$performances = $api->get_performances_until($eternity);
		if($by_show) {
			return $api->collect_performances_by_show($performances);
		} else {
			return $api->collect_performances($performances);
		}
	}

	function end_time($show_duration,$format = 'G.i'){
		$unix_start = $this->start_time->format('U');
		$duration_seconds = wpspx_convert_to_seconds($show_duration);
		$unix_end = $unix_start + $duration_seconds;
		return date($format,$unix_end);
	}
}

function is_in_past($performances){
	$last_performance = array_pop($performances);
	$now = new DateTime();
	if($now > $last_performance->start_time){
		return true;
	} else {
		return false;
	}
}

function get_performance_months($performances){
	$months = array();
	foreach($performances as $performance){
		$months[] = $performance->start_time->format('F Y');
	}
	$months = array_unique($months);
	return $months;
}

function get_performance_dates($performances){
	$dates = array();
	foreach($performances as $performance){
		$dates[] = $performance->start_time->format('Ymd');
	}
	$dates = array_unique($dates);
	return $dates;
}

function get_performance_range($performances,$prefix = true){
	if($prefix){
		$string = !is_in_past($performances) ? "Showing " : "Performed ";
	} else {
		$string = "";
	}

	$from = '';
	$to = '';
	$first_show = reset($performances)->start_time->format('D j');
	$first_show_month = reset($performances)->start_time->format('M');
	$first_show_year = reset($performances)->start_time->format('Y');
	$last_show = end($performances)->start_time->format('D j');
	$last_show_month = end($performances)->start_time->format('M');
	$last_show_year = end($performances)->start_time->format('Y');

	if($first_show == $last_show){
		$from .= $first_show . ' ' . $first_show_month;
	} else {
		$to = $last_show . ' ' . $last_show_month . ' ' . $last_show_year;
		if($first_show_month == $last_show_month){
			$from = $first_show;
		} else {
			if($first_show_year == $last_show_year){
				$from = $first_show . ' ' . $first_show_month;
			} else {
				$from = $first_show . ' ' . $first_show_month;
			}
		}
	}

	$from = str_replace(' ','&nbsp;',$from);
	$to = str_replace(' ','&nbsp;',$to);

	if($to == ''){
		if($prefix) $string.= 'on ';
			$string.= $from;
	} else {
		if($prefix) $string.= 'from ';
			$string.= $from . ' &mdash; ' . $to;
	}
	return $string;
}
