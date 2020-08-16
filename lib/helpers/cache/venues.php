<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );
/*
* Cache venue informatio from Spektrix
*/


$api = New WPSPX_Spektrix();
$venues = $api->get_data('venues');


?>