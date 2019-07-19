<?php
include('header.php');
?>

<div class="container">

<div class="page-header">
<h3>Current Status</h3>
</div>

<?php
include("db/dbconn.php");

function humanTiming ($time){

    $time = time() - $time; // to get the time since that moment
    $time = ($time<1)? 1 : $time;
    $tokens = array (
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
    }

}


function getdata($query) {
include("db/dbconn.php");
if ($stmt = $con->prepare($query)) {
    $stmt->execute();
    $stmt->bind_result($data);
    $stmt->fetch();
	}

return $data;
}

$temp =getdata("SELECT data FROM ben.data_log where data_type=2 order by id_data_log desc limit 1");
$tempmax =getdata("SELECT max(data) FROM ben.data_log where data_type=2 order by id_data_log desc limit 1");
$tempp=100/(80/(20+$temp));

$humidty =getdata("SELECT    data FROM    ben.data_log where data_type=1 order by id_data_log desc limit 1");
$humidtymax =getdata("SELECT   max(data) FROM    ben.data_log where data_type=1 order by id_data_log desc limit 1");
?>



<style>#container1 {
    width: 100%;
    height: 100px;
}
#container2 {
    width: 100%;
    height: 100px;
}
</style>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/bullet.js"></script>
<div id="container1"></div>
<div id="container2"></div>
<script>
Highcharts.setOptions({
    chart: {
        inverted: true,
        marginLeft: 74,
        type: 'bullet'
    },
    title: {
        text: null
    },
    legend: {
        enabled: false
    },
    yAxis: {
        gridLineWidth: 1
    },
    plotOptions: {
        series: {
            pointPadding: 0.25,
            borderWidth: 0,
            color: '#000',
            targetOptions: {
                width: '300%'
            }
        }
    },
    credits: {
        enabled: false
    },
    exporting: {
        enabled: false
    }
});

Highcharts.chart('container1', {
    chart: {
        marginTop: 30
    },
    title: {
        text: 'Temprature'
    },
    xAxis: {
        categories: ['<span class="hc-cat-title">Temprature']
    },
    yAxis: {
        plotBands: [{
            from: -10,
            to: 10,
            color: 'blue'
        }, {
            from: 10,
            to: 20,
            color: 'orange'
        }, {
            from: 20,
            to: 40,
            color: 'red'
        }],
        title: null
    },
    series: [{
        data: [{
            y: <?php echo $temp; ?>,
            target: <?php echo $tempmax; ?>
        }]
    }],
    tooltip: {
        pointFormat: '<b>{point.y}</b> (with max at {point.target})'
    }
});

Highcharts.chart('container2', {
    chart: {
        marginTop: 30
    },
    title: {
        text: 'Humidty'
    },
    xAxis: {
        categories: ['<span class="hc-cat-title">Humidty']
    },
    yAxis: {
        plotBands: [{
            from: 0,
            to: 40,
            color: 'Grey'
        }, {
            from: 40,
            to: 70,
            color: 'lightblue'
        }, {
            from: 70,
            to: 100,
            color: 'blue'
        }],
        title: null
    },
    series: [{
        data: [{
            y: <?php echo $humidty; ?>,
            target: <?php echo $humidtymax; ?>
        }]
    }],
    tooltip: {
        pointFormat: '<b>{point.y}</b> (with max at {point.target})'
    }
});
</script>





<?php

$query = "
SELECT   date_time FROM    ben.data_log where data_type=1 order by id_data_log desc limit 1
    ";


if ($stmt = $con->prepare($query)) {
    $stmt->execute();
    $stmt->bind_result($date_time);
    while ($stmt->fetch()) {
      $since=humanTiming(strtotime($date_time));
echo  "<h4> $since since last measurement uploaded</h4>

		";
    }
    $stmt->close();
}
echo "";




 ?>



<script type="text/javascript">
$(document).ready(function() {
    $('#Running').DataTable({searching: false, paging: false,bInfo : false});
} );
</script>

<?php
include('footer.php');

?>
