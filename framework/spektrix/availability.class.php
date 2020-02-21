<?php if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

class Availability extends Spektrix
{
  public $available;
  public $capacity;
  public $locked;
  public $reserved;
  public $selected;
  public $sold;

  function __construct($availability)
  {
    //Load a performance by ID if not object loaded in
    if(!is_object($availability)){
      $availability = $this->get_availability($availability);
    }

    $this->id = $availability->attributes()->id;

    $this->available = (integer) $availability->Available;
    $this->capacity = (integer) $availability->Capacity;
    $this->locked = (integer) $availability->Locked;
    $this->reserved = (integer) $availability->Reserved;
    $this->selected = (integer) $availability->Selected;
    $this->sold = (integer) $availability->Sold;
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
    $href = $av->available > 0 ? 'href="' . home_url('/book-online/'.$performance->id) . '"' : '';
    echo '<a ' . $href . ' class="'.$class.'">' . book_online_text($av) . '</a>';
  endif;
}
?>
