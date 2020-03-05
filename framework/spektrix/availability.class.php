<?php if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

class Availability extends Spektrix
{
	public $available;
	public $capacity;
	public $wheelchair_available;
	public $special_available;
	public $in_basket;
	public $unavailable;

	function __construct($availability)
	{
		//Load a performance by ID if not object loaded in
		if(!is_object($availability)){
		  $availability = $this->get_availability($availability);
		}

		$this->available = (integer) $availability->available;
		$this->capacity = (integer) $availability->capacity;
		$this->selected = (integer) $availability->wheelchairAvailable;
		$this->selected = (integer) $availability->specialAvailable;
		$this->selected = (integer) $availability->inBasket;
		$this->sold = (integer) $availability->unavailable;
	}
}

function book_online_text($av)
{
	$string = '';
	if($av->available == 0):
		$string = 'Sold Out';
	else:
		$string = 'Book Online';
	endif;
	return $string;
}

function book_online_button($av,$performance)
{
	$perfid = (integer) $performance->id;
	if($av->available == 0):
		$class = "button sold-out";
	else:
		$class = "button";
	endif;

	$now = new DateTime(current_time('mysql'));
	if($now < $performance->start_selling_at):
		echo '<a class="'.$class.'" href="#" disabled>Selling Soon</a>';
	elseif($now > $performance->stop_selling_at || !$performance->is_on_sale):
		echo '<a class="'.$class.'" href="#" disabled>Not Available</a>';
	else:
		$href = $av->available > 0 ? 'href="' . home_url('/book-online/'.$perfid) . '"' : '';
		echo '<a ' . $href . ' class="'.$class.'">' . book_online_text($av) . '</a>';
	endif;
}
?>
