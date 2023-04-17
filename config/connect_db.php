<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "plan";

// $servername = "localhost";
// $username = "id20244759_rootdemo";
// $password = "wM53u+u^+v+fgRxe";
// $dbname = "id20244759_demo";
$connect = mysqli_connect($servername, $username, $password, $dbname);
if (!$connect) {
	echo "Can't connect to Database!";
}
else {
	// echo "Connect to Database Successfully";
}
?>