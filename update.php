<?php


include("db/dbconn.php");

$humidity=$_POST["HUMIDITY"];
$temp=$_POST["TEMP"];


$query="
INSERT INTO `ben`.`data_log` (`date_time`, `data_type`, `data`,`id_location`) VALUES (now(),2,$humidity,1),(now(),1,$temp,1);

";

if ($stmt = $con->prepare($query)) {
    $stmt->execute();
    $stmt->close();
}

?>
