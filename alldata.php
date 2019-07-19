<?php
include('header.php');
include("db/dbconn.php");
$table=$_GET['table'];
$loctype = array();
$query2 = "SELECT DISTINCT     data_log_1h.data_type AS A, data_log_1h.id_location as B FROM     ben.data_log_1h,     ben.location,     ben.data_type WHERE     data_log_1h.data_type = data_type.id_data_type         AND data_log_1h.id_location = location.id_location";
$query="SELECT DISTINCT
   ben.location.name AS A, ben.data_type.name as B
FROM
   ben.data_log,
   ben.location,
   ben.data_type
WHERE
   data_log.data_type = data_type.id_data_type
       AND data_log.id_location = location.id_location";

if ($stmt = $con->prepare($query)) {
   $stmt->execute();
   $stmt->bind_result($A, $B);
   while ($stmt->fetch()) {
      $loctype[] = "`$A:$B`";
   }
   $stmt->close();
}


?>

<div class="container">

<div id="container" style="height: 950px; min-width: 100%"></div>
</div>


<script type="text/javascript">
document.title = "GHS - Combined Report"
$(function() {
 var seriesOptions = [],
   yAxisOptions = [],
   seriesCounter = 0,
   names = [<?php echo join(",", $loctype);?>]


 $.each(names, function(i, name) {

   $.getJSON('http://ben.smeird.com/alldatadata.php?table=<?php echo $table;?>&loctype='+ name +'&callback=?',	function(data) {

     seriesOptions[i] = {
       name: name,
       data: data,
       dataGrouping: {
         enabled: true
       }
     };

     // As we're loading the data asynchronously, we don't know what order it will arrive. So
     // we keep a counter and create the chart when all the data is loaded.
     seriesCounter++;

     if (seriesCounter == names.length) {
       createChart();
     }
   });
 });



 // create the chart when all data is loaded
 function createChart() {

   chart = new Highcharts.Chart({
       chart: {
           renderTo: 'container',
           type: 'line',
            zoomType: 'xy'

       },
       title: {
     text: 'Glider Health System'
   },
     subtitle: {
           text: 'All available data'

       },
       xAxis: {
              title:{text: 'Date'},
              type: 'datetime'

       },
       yAxis: {
         title: {

           text: 'Humidty (%) & Temprature (°C)'

         },

       },

       plotOptions: {
        series: {
            lineWidth: 1
        },
             spline: {
       marker: {
           enabled: false
       }
                   }
       },

       exporting: {
           sourceWidth: 1400,
           sourceHeight: 800,
           scale: 1 ,
           allowHTML: false,
           filename: 'Glider Health',
           chartOptions: {
               subtitle: {
                     text: 'Exported on :<?php echo date('l jS \of F Y h:i:s A');?>'

                 },
        }
        },
       tooltip: {
         shared: true,
         valueDecimals: 2,
          lineWidth:1,
         crosshairs: true

       },

       series: seriesOptions
   });
 }

});
</script>
