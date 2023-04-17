<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "plan";

$connect = mysqli_connect($servername, $username, $password, $dbname);
if (!$connect) {
	echo "Can't connect to Database!";
}
else {
	// echo "Connect to Database Successfully";
}
?>
