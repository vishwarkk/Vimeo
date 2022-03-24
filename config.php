<?php 

define('DB_HOST','localhost');
define('DB_NAME','u872931566_vimeo');
define('USER','u872931566_vishwa12');
define('PW','0770499787kK#1');

if(!$con = mysqli_connect(DB_HOST,USER,PW,DB_NAME)){
	die("Failed To connect");
}
?>