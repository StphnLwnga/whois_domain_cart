<?php
session_start();
define('DB_SERVER', 'mysql.s801.sureserver.com:3306');
define('DB_USERNAME', 'beta');
define('DB_PASSWORD', 'K@ribu098!');
define('DB_DATABASE', 'slashdotpro_beta');
$db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

?>
