<?php
include('header.php');

?>

<div class="container">
  <div class="page-header">

<h2> Download Data</h1>
</div>





<div class="form-group ">

         <form  class="form-inline" id="select_form" action="/download-xlsx.php" method="get">
        <label>Choose a location:</label>
        <select class="form-control" name=LOCATION id="category">
        <option value="0">choose...</option>
            <?php echo $opt->ShowCategory(); ?>
        </select>


         

            <label>choose a duration:</label>
<select class="form-control" name=RANGE id="graph">
                <option value=1>1 Day</option>
				<option value=7>7 Days</option>
				<option value=30>1 Month</option>
				<option value=180>6 Months</option>
				<option value=360>1 Year</option>
				<option value=>All</option>
            </select>
    <br><br>
  <div class="page-header">

<h4> Download Data</h4>
</div>
            <input  class="form-control btn btn-block btn-success" type="submit" value="Download Excel File" />
        </form>
</div>
</div>

        <div id="result"></div>
