<?php
date_default_timezone_set("Europe/London");
if (substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) ob_start("ob_gzhandler");
else ob_start();
//@ob_start('ob_gzhandler');
//@ini_set('zlib.output_compression_level', 999999999);


//
 /**
  * This file loads content from four different data tables depending on the required time range.
  * The stockquotes table containts 1.7 million data points. Since we are loading OHLC data and
  * MySQL has no concept of first and last in a data group, we have extracted groups by hours, days
  * and months into separate tables. If we were to load a line series with average data, we wouldn't
  * have to do this.
  *
  * @param callback {String} The name of the JSONP callback to pad the JSON within
  * @param start {Integer} The starting point in JS time
  * @param end {Integer} The ending point in JS time
  */
// get the parameters
 $start="";
 $end="";
if(isset($_GET['location'])){$location = $_GET['location'];}
if(isset($_GET['data_type'])){$data_type = $_GET['data_type'];}
 $callback = $_GET['callback'];
 if (!preg_match('/^[a-zA-Z0-9_]+$/', $callback))
     {
     die('Invalid callback name');
     }

 if(isset($_GET['start'])){$start = $_GET['start'];}
 if ($start && !preg_match('/^[0-9]+$/', $start))
     {
     die("Invalid start parameter: $start");
     }

 if(isset($_GET['end'])){$end = $_GET['end'];}
 if ($end && !preg_match('/^[0-9]+$/', $end))
     {
     die("Invalid end parameter: $end");
     }
 if (!$start) $start = ((mktime() - 4*7*60*60*24) * 1000) ;
 if (!$end) $end = mktime() * 1000;



// connect to MySQL
//require_once('../../configuration.php');
//$conf = new JConfig();
//mysql_connect($conf->host, $conf->user, $conf->password) or die(mysql_error());
//mysql_select_db($conf->db) or die(mysql_error());
include("db/dbconn.php");

if ($stmt = $con->prepare($sql2)) {
    $stmt->execute();
}
// set UTC time
$sql2="SET time_zone = '+00:00'";

// set some utility variables
 $range     = $end - $start;
 $startTime = gmstrftime('%Y-%m-%d %H:%M:%S', $start / 1000);
 $endTime   = gmstrftime('%Y-%m-%d %H:%M:%S', $end / 1000);

// find the right table
// two days range loads minute data
 if ($range < 2 * 24 * 3600 * 1000)
     {
     $table = 'data_log';

// one month range loads hourly data
     }
 elseif ($range < 31 * 24 * 3600 * 1000)
     {
     $table = 'data_log_1h';

// one year range loads daily data
     }
 elseif ($range < 6 * 31 * 24 * 3600 * 1000)
     {
     $table = 'data_log_1d';

// one+ year range loads weekly data
     }
 elseif ($range < 12 * 31 * 24 * 3600 * 1000)
     {
     $table = 'data_log_1w';	 
// greater range loads monthly data
     }
	elseif ($range < 24 * 31 * 24 * 3600 * 1000)
     {
     $table = 'data_log_1m';
// greater range loads monthly data
     }
 else
     {
     $table = 'data_log';
     }



 $sql = "select
unix_timestamp(date_time) * 1000 as datetime,
data as data
from $table
where date_time between '$startTime' and '$endTime' and data_type=$data_type and id_location=$location
order by date_time 
";

if ($stmt = $con->prepare($sql)) {
    $stmt->execute();
    $stmt->bind_result($datetime,$data);
    while ($stmt->fetch()) {
    
         $result[] = "[$datetime,$data]";
       
    }
    $stmt->close();
}
//echo $sql;


 

// print it
header("content-type: application/json");

echo "/* console.log(' table=$table,start = $start, end = $end, startTime = $startTime, endTime = $endTime '); */";
echo $callback ."([\n" . join(",\n", $result) ."\n]);";

?>
