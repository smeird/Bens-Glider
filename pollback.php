<?php

include("db/dbconn.php");


# get last update time

if(isset($_GET["NEWJOB"])) {
$query = "SELECT   * FROM    ben.power";
 $rows = array();
if ($stmt = $con->prepare($query)) {
    $stmt->execute();
    $stmt->bind_result($data);
    while ($stmt->fetch()) {
             $rows = "$data";
    }
    $stmt->close();
}
echo $data;
}


# update the last update time.
if(isset($_GET["ACCEPTJOB"])) {
$query3 = "UPDATE `ben`.`power` SET `idpower` = 0;";
if ($stmt = $con->prepare($query3)) {    $stmt->execute();    $stmt->close();}
}


?>
