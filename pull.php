<?php

include("db/dbconn.php");


# get last update time

if(isset($_GET["LASTUPDATETIME"])) {
$query = "SELECT   * FROM    ben.update";
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



# insert new records
$query2="INSERT INTO `ben`.`data_log` (`date_time`, `data_type`, `data`,`id_location`) VALUES ";
$json = file_get_contents('php://input');
$obj = json_decode($json);
//print_r($obj);
foreach($obj as $key => $value) {
    //echo $value->date . ", " . $value->data . "<br>";
	$queryadd[]="('$value->date',$value->data_type,$value->data,$value->id_location)";
  }
$query2 = $query2 . join(",\n", $queryadd);
//echo $query2;
if ($stmt = $con->prepare($query2)) {
    $stmt->execute();
    $stmt->close();
}




# update the last update time.
if(isset($_GET["UPDATETIME"])) {
$query3 = "UPDATE ben.update 
SET 
    idupdate = (SELECT 
            date_time
        FROM
            ben.data_log
        WHERE
            id_location = 2 OR id_location = 3
                OR id_location = 4 order by date_time desc
        LIMIT 1);";
if ($stmt = $con->prepare($query3)) {    $stmt->execute();    $stmt->close();}
}

?>