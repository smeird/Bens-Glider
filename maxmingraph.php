<script>
document.title = "GHS - Max Min"
</script>
<?php
include('header.php');
include("db/dbconn.php");
//$location = $_GET['LOC'];
$data_type = $_GET['TYPE'];
$graph = $_GET['GRAPH'];

if($_GET['LOC']) { $idl=$_GET['LOC'];} else { $idl=1;}

$query = "SELECT
    UNIX_TIMESTAMP(date_time) * 1000, MIN(data) as min, MAX(data) as max,round(AVG(data),1) as avg
FROM
    ben.data_log
WHERE
    data_type = 1 AND id_location = $idl
        AND date_time >= NOW() - INTERVAL 60 DAY
GROUP BY DATE_FORMAT(date_time, '%Y%m%d')";
 $rowa = array();
	$rowb = array();
//mysql_query("SET time_zone = '+00:00'");
if ($stmt = $con->prepare($query)) {
    $stmt->execute();
    $stmt->bind_result($date_time, $min,$max,$avg);
    while ($stmt->fetch()) {
             $rowa[] = "[$date_time,$min,$max]";
						 $rowb[] = "[$date_time,$avg]";
    }
    $stmt->close();
}
$query = "SELECT
    UNIX_TIMESTAMP(date_time) * 1000, MIN(data) as min, MAX(data) as max,round(AVG(data),1) as avg
FROM
    ben.data_log
WHERE
    data_type = 2 AND id_location = $idl
        AND date_time >= NOW() - INTERVAL 60 DAY
GROUP BY DATE_FORMAT(date_time, '%Y%m%d')";
 $rowaa = array();
	$rowbb = array();
//mysql_query("SET time_zone = '+00:00'");
if ($stmt = $con->prepare($query)) {
    $stmt->execute();
    $stmt->bind_result($date_time, $min,$max,$avg);
    while ($stmt->fetch()) {
             $rowaa[] = "[$date_time,$min,$max]";
						 $rowbb[] = "[$date_time,$avg]";
    }
    $stmt->close();
}
?>


<div class=container id="container" style="height: 550px; min-width: 310px"></div>
<script type="text/javascript">
$(function () {
  var ranges = [  <?php  echo join(",",$rowaa);    ?>    ],
    rangesb = [  <?php  echo join(",",$rowa);    ?>    ],
    averages = [  <?php  echo join(",",$rowbb);    ?>    ],
    averagesb = [  <?php  echo join(",",$rowb);    ?>    ];


Highcharts.chart('container', {

    title: {
        text: 'Daily Max, Min & Avg for the Last Month'
    },

    xAxis: {
        type: 'datetime'
    },

    yAxis: {
        title: {
            text: null
        }
    },

    tooltip: {
        crosshairs: true,
        shared: true

    },



    series: [{
        name: 'Temperature',
        data: averages,
        zIndex: 1,
        lineColor: Highcharts.getOptions().colors[8],
        tooltip: {
            valueSuffix: ' °C'
        },
        marker: {
            fillColor: 'white',
            lineWidth: 2,
            lineColor: Highcharts.getOptions().colors[8]
                }
    }, {
        name: 'Temprature Range',
        data: ranges,
        type: 'arearange',
        lineWidth: 0,
        linkedTo: ':previous',
        color: Highcharts.getOptions().colors[8],
        fillOpacity: 0.1,
        zIndex: 0,
        tooltip: {
            valueSuffix: ' °C'
        },
        marker: {
            enabled: false
        }
    }, {
        name: 'Humidity',
        data: averagesb,
        zIndex: 2,
        lineColor: Highcharts.getOptions().colors[0],
        tooltip: {
            valueSuffix: ' %'
        },
        marker: {
            fillColor: 'white',
            lineWidth: 2,
            lineColor: Highcharts.getOptions().colors[0]
                }
    },{
        name: 'Humidty Range',
        data: rangesb,
        type: 'arearange',
        lineWidth: 0,
        linkedTo: ':previous',
        color: Highcharts.getOptions().colors[0],
        fillOpacity: 0.1,
        zIndex: 0,
        tooltip: {
            valueSuffix: ' %'
        },
        marker: {
            enabled: false
        }
    }]
});
});


</script>
