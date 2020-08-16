<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );
/*
* Cache price bands from Spektrix
*/


$api = New WPSPX_Spektrix();
$bands = $api->get_data("bands");


?>