<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );
/*
* Cache tags from Spektrix
*/


$api = New WPSPX_Spektrix();
$taggroups = $api->get_data("tag-groups");


?>