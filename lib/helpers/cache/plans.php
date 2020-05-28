<?php
require $_SERVER['DOCUMENT_ROOT'] . '/wp-blog-header.php';
$api = New Spektrix();
$plans = $api->get_data('plans');
?>
