<?php
include('header.php');
?>

<div class="container">
  <div class="page-header">

<h2> Reports</h1>
</div>


<script type="text/javascript">
  $(document).ready(function(){
	$("select#category").change(function(){
    var id = $("select#category option:selected").attr('value');
    $.post("select_type.php", {id:id}, function(data){
        $("select#type").html(data);
        });
    });
	   });
 </script>


<div class="form-group ">

         <form  class="form-inline" id="select_form" action="/cubegraph.php" method="get">
        <label>Choose a location:</label>
        <select class="form-control" name=LOC id="category">
        <option value="0">choose...</option>
            <?php echo $opt->ShowCategory(); ?>
        </select>


           <label>choose a data type:</label>
            <select class="form-control" name=TYPE id="type">

            </select>

            <label>choose a graph type:</label>
<select class="form-control" name=GRAPH id="graph">
                <option value="spline">Spline</option>
				<option value="line">Line</option>
				<option value="bar">Bar</option>
				<option value="column">Column</option>
				<option value="area">Area</option>
            </select>
    
<label>Run Report</label>
            <input  class="form-control" type="submit" value="confirm" />
        </form>
</div>
</div>

        <div id="result"></div>
