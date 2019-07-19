<?php
include('header.php');
include("db/dbconn.php");
$location = $_GET['LOC'];
$data_type = $_GET['TYPE'];
$graph = $_GET['GRAPH'];

$query2 = "SELECT name,descr,units FROM ben.data_type where id_data_type=$_GET[TYPE]";


if ($stmt = $con->prepare($query2)) {
    $stmt->execute();
    $stmt->bind_result($name, $descr, $units);
    $stmt->fetch();
	$stmt->close();
}

$query3 = "SELECT name FROM ben.location where id_location=$_GET[LOC]";


if ($stmt = $con->prepare($query3)) {
    $stmt->execute();
    $stmt->bind_result($locationname);
    $stmt->fetch();
	$stmt->close();
}
?>

<div id="container" style="height: 600px; min-width: 310px"></div>
<script type="text/javascript">
$(function () {
    /**
     * Load new data depending on the selected min and max
     */
    function afterSetExtremes(e) {



       // var chart = $('#container').highcharts();
	   var chart = Highcharts.charts[0];

        chart.showLoading('Loading data from server...');
        $.getJSON('http://ben.smeird.com/cubedata.php?start=' + Math.round(e.min) + '&end=' + Math.round(e.max) + '&callback=?' + '&location=<?php echo $location; ?>&data_type=<?php echo $data_type; ?>', function (data) {

            chart.series[0].setData(data);
            chart.hideLoading();
        });
    }

    // See source code from the JSONP handler at https://github.com/highcharts/highcharts/blob/master/samples/data/from-sql.php
    $.getJSON('http://ben.smeird.com/cubedata.php?callback=?' + '&location=<?php echo $location; ?>&data_type=<?php echo $data_type; ?>', function (data) {

        // Add a null value for the end date
       // data = [].concat(data, [[Date.UTC(2016, 4, 1, 19, 59), null, null, null, null]]);
		//data = [].concat(data, [[Date.UTC(2011, 9, 14, 19, 59), null, null, null, null]]);
        // create the chart
        //$('#container').highcharts('StockChart', {
		Highcharts.stockChart('container', {
            chart : {
                type: '<?php echo $graph; ?>',
                zoomType: 'x'
            },

            navigator : {
                adaptToUpdatedData: false,
                series : {
                    data : data
                }
            },

            scrollbar: {
                liveRedraw: false
            },

            title: {
                text: '<?php echo $locationname; ?>'
            },

            subtitle: {
                text: '<?php echo $descr; ?>'
            },

            rangeSelector : {
                buttons: [{
                    type: 'hour',
                    count: 1,
                    text: '1h'
                }, {
                    type: 'day',
                    count: 1,
                    text: '1d'
                }, {
                    type: 'week',
                    count: 1,
                    text: '1w'

                }, {
                    type: 'month',
                    count: 1,
                    text: '1m'
                }, {
                    type: 'year',
                    count: 1,
                    text: '1y'
                }, {
                    type: 'all',
                    text: 'All'
                }],
                inputEnabled: false, // it supports only days
                selected : 5 // all
            },

             xAxis: {
                events: {
                    afterSetExtremes: afterSetExtremes
                },
                minRange: 600 * 1000 // one hour
            },

            yAxis: {
                title: {
                	text: '<?php echo $units; ?>'
                	}
            },

            series : [{
                data : data,
				dataGrouping: {
                    enabled: false
                }

            }]
        });
    });
});

document.title = "<?php echo $locationname." - ".$descr; ?>"
</script>
