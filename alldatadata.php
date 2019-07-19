<?php

include("db/dbconn.php");
$callback = $_GET['callback'];

$a=$_GET['table'];

if($a==""){$length=196;}
if($a=="_1h"){$length=720;}
if($a=="_1d"){$length=1440;}
if($a=="_1w"){$length=8736;}
if($a=="_1m"){$length=15000;}


$loctype = explode(":",$_GET['loctype']);
$data_type=$loctype[1];
$id_location=$loctype[0];
if (!preg_match('/^[a-zA-Z0-9_]+$/', $callback)) {
	die('Invalid callback name');
}
$query = "SELECT      UNIX_TIMESTAMP(date_time)*1000, data FROM
    ben.data_log$a,
    ben.location,
    ben.data_type

 WHERE
   data_log$a.data_type = data_type.id_data_type
        AND data_log$a.id_location = location.id_location and

 data_type.name = '$data_type' AND location.name = '$id_location' AND date_time >= NOW() - INTERVAL $length HOUR";
 $rows = array();

//mysql_query("SET time_zone = '+00:00'");
if ($stmt = $con->prepare($query)) {
    $stmt->execute();
    $stmt->bind_result($date_time, $data);
    while ($stmt->fetch()) {
             $rows[] = "[$date_time,$data]";
    }
    $stmt->close();
}



// print it
header('Content-Type: text/javascript');

echo "/* console.log(' sql=$query ,start = $start, end = $end, startTime = $startTime, endTime = $endTime '); */";
echo $callback ."([\n" . join(",\n", $rows) ."\n]);";


?>
