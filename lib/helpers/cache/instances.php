<?php
require $_SERVER['DOCUMENT_ROOT'] . '/wp-blog-header.php';
$api = New Spektrix();
$instances = $api->get_data('instances');
?>
