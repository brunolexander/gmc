<?php

define ('DB_HOST', getenv('DB_HOST', true) ?? 'localhost');

define ('DB_USER', 'user');

define ('DB_PASSWORD', 'secret');

define ('DB_NAME', 'db_gmc');

define ('BASE_URL', 'http://localhost:8080/');

if (!defined('ROOT_DIR'))
	define ('ROOT_DIR', dirname(__FILE__));

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$conn->set_charset("utf8");

?>