<?php
require $_SERVER['DOCUMENT_ROOT'] . '/wp-blog-header.php';
$api = New Spektrix();
$funds = $api->get_data('funds');
?>
