<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );
/*
* Cache ticket types from Spektrix
*/


$api = New WPSPX_Spektrix();
$tickettypes = $api->get_data("ticket-types");


?>