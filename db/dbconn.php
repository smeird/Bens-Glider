<?php
$host="localhost";
$port=3306;
$socket="/var/lib/mysql/mysql.sock";
$user="ben";
$password="BenPass0!";
$dbname="ben";

$con = new mysqli($host, $user, $password, $dbname, $port, $socket)
	or die ('Could not connect to the database server' . mysqli_connect_error());
//$con->close();
echo mysqli_connect_error();
?>
