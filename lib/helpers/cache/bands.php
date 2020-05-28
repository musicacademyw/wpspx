<?php
require $_SERVER['DOCUMENT_ROOT'] . '/wp-blog-header.php';
$api = New Spektrix();
$bands = $api->get_data("bands");
