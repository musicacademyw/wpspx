<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );
/*
* Cache seating plans bands from Spektrix
*/


$api = New WPSPX_Spektrix();
$plans = $api->get_data('plans');


?>