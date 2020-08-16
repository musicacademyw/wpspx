<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );
/*
* Cache memberships from Spektrix
*/


$api = New WPSPX_Spektrix();
$memberships = $api->get_data("memberships");


?>