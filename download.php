<?php
include('header.php');
?>

<div class="container">

<div class="page-header">
<h3>Download Data in Excel</h3>
</div>



<?php

for ($i = 1; $i <= 5; $i++) {
 echo $i;
echo "

<a style=\" margin-bottom:3px;\" href=\"download-xlsx.php?RANGE=1&LOCATION=$i\" class=\"btn btn-block btn-primary\">Download Last 1 Days Data</a>
<a style=\" margin-bottom:3px;\" href=\"download-xlsx.php?RANGE=7LOCATION=$i\" class=\"btn btn-block btn-primary\">Download Last 7 Days Data</a>
<a style=\" margin-bottom:3px;\" href=\"download-xlsx.php?RANGE=30LOCATION=$i\" class=\"btn btn-block btn-primary\">Download Last 30 Days Data</a>

<a style=\" margin-bottom:3px;\" href=\"download-xlsx.php?RANGE=90LOCATION=$i\" class=\"btn btn-block btn-primary\">Download Last 90 Days Data</a>

<a style=\" margin-bottom:3px;\" href=\"download-xlsx.php?RANGE=365LOCATION=$i\" class=\"btn btn-block btn-primary\">Download Last years Data</a>
<a style=\" margin-bottom:3px;\" href=download-xlsx.php?LOCATION=$i class=\"btn btn-block btn-primary\">Download All Avilable Data</a>
";
   
}

include('footer.php');
?>
