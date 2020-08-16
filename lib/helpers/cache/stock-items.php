<?php
if (!defined( 'ABSPATH' ) ) die( 'Forbidden' );
/*
* Cache stock items from Spektrix
*/


$api = New WPSPX_Spektrix();
$stockitems = $api->get_data('stock-items');


?>