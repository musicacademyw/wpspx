<?php if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );

class WPSPX_Plan extends WPSPX_Spektrix
{
  public $id;
  public $name;
  public $subplans;

  function __construct($plan)
  {
    $this->id = (integer) $plan->attributes()->id;
    $this->name = (string) $plan->Name;
    $this->short_description = (string) $plan->Description;
  }
}