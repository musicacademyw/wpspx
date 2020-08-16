<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );
/*
* Cache instances from Spektrix
*/


$api = New WPSPX_Spektrix();
$instances = $api->get_data('instances');


?>