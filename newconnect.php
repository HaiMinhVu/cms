<?php 

$server = getenv('DB_HOST');
$dbname = getenv('DB_NAME');
$username = getenv('DB_USERNAME');
$password = getenv('DB_PASSWORD');

$cms_connect = new mysqli($server,$username,$password,$dbname);
if($cms_connect->connect_error){
	die("Connection failed: ".$cms_connect->connect_error);
}


session_set_cookie_params(0);
session_start();

error_reporting(E_ERROR | E_PARSE);

require __DIR__ . '/html/admin/vendor/autoload.php';

?>
