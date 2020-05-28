<?php
require $_SERVER['DOCUMENT_ROOT'] . '/wp-blog-header.php';
$api = New Spektrix();
$stockitems = $api->get_data('stock-items');
?>
