<?php
include "select.class.php";
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-UK" lang="en-UK">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta http-equiv="refreshh" content="600">
		
		<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png?v=BGAR4oMrzQ">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png?v=BGAR4oMrzQ">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png?v=BGAR4oMrzQ">
<link rel="manifest" href="/manifest.json?v=BGAR4oMrzQ">
<link rel="mask-icon" href="/safari-pinned-tab.svg?v=BGAR4oMrzQ" color="#5bbad5">
<link rel="shortcut icon" href="/favicon.ico?v=BGAR4oMrzQ">
<meta name="apple-mobile-web-app-title" content="Glider Trailer">
<meta name="application-name" content="Glider Trailer">
<meta name="theme-color" content="#ffffff">
		
		
		
		
		
      
        <title>Glider Health System (GHS)</title>
        <meta name="Keywords" content="Glider" />
        <meta name="Description" content="Bens Glider Health System (GHS)" />

        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>



		<script type='text/javascript' src="https://code.highcharts.com/stock/highstock.js"></script>
		<script type='text/javascript' src="https://code.highcharts.com/highcharts-more.js"></script>
    <script type='text/javascript' src="https://code.highcharts.com/highcharts.js"></script>
    <script type='text/javascript' src="https://code.highcharts.com/modules/exporting.js"></script>
    <script type='text/javascript' src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type='text/javascript' src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    <script type='text/javascript' src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css">







<meta name="viewport" content="width=device-width, initial-scale=1">
  <nav class="navbar navbar-default">
    <div class="container">
       <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
        <a class="navbar-brand" href="/">Glider Health System <span class="glyphicon glyphicon-plane"></span></a>
      </div>
      <div class="collapse navbar-collapse" id="myNavbar">
<ul class="nav navbar-nav navbar-right">
   <li >
     <a href='/'><span>Dash Board</span></a>
   </li>
   <li>
      <a href='/index2.php'><span>Reports</span></a>
    </li>
    <li>
       <a href='/alldata.php'><span>Combined Report</span></a>
     </li>
     <li> <a class="dropdown-toggle" data-toggle="dropdown" href=''>
        <span>Min Max Last Month</span>
        <span class="caret"></span></a>
          <ul class="dropdown-menu">
           <?php echo $opt->ShowLocation(); ?>
  
      </ul>
  
      <li>
        <a href='/index3.php'><span>Download Data</span></a>
      </li>
</div>
</div>
</div>
	</nav>
  <meta charset="utf-8">
  </head>
<body>
  <script>

      $(function () {
        $('[data-toggle=\"tooltip\"]').tooltip({html: true})
      })

  Highcharts.setOptions({
      chart: {
          style: {
              fontFamily: 'Helvetica'
          }
      }
     });
  </script>
 <div id="wrap">
