<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );
/*
* Cache statementa from Spektrix
*/


$api = New WPSPX_Spektrix();
$statements = $api->get_data('statements');


?>