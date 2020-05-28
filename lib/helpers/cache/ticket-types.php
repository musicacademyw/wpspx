<?php
require $_SERVER['DOCUMENT_ROOT'] . '/wp-blog-header.php';
$api = New Spektrix();
$tickettypes = $api->get_data("ticket-types");
?>
