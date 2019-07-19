<?php
include("db/dbconn.php");
$range    = $_GET['RANGE'];
$idlocation = $_GET['LOCATION'];
if(isset($range)){
$rangesql=" AND date_time BETWEEN CURDATE() - INTERVAL $range DAY AND CURDATE()";
} else {
$rangesql="";
}

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

//if (PHP_SAPI == 'cli')
//	die('This should only be run from a Web Browser');

/** Include PHPExcel */
require_once dirname(__FILE__) . '/PHPExcel/Classes/PHPExcel.php';

 $query = "SELECT 
    data_log.date_time,
    MAX(CASE
        WHEN data_log.data_type = 1 THEN data_log.data
        ELSE NULL
    END) AS Humidity,
    MAX(CASE
        WHEN data_log.data_type = 2 THEN data_log.data
        ELSE NULL
    END) AS Temprature,
    id_location
FROM
    data_log
where id_location=$idlocation $rangesql
GROUP BY data_log.date_time
";


		
// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Glider Managment System")
							 ->setLastModifiedBy("Dominic Bundy")
							 ->setTitle("Glider Trailer System")
							 ->setSubject("All Data")
							 ->setDescription("All Data records from the trailer.")
							 ->setKeywords("openxml php")
							 ->setCategory("Temprature Humidity");


$i = 1;
$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->setTitle('Data');
	$objPHPExcel->getActiveSheet()->setCellValue('B1', "Glider Managment System");
	$objPHPExcel->getActiveSheet()->setCellValue('A2',"Date & Time");
	$objPHPExcel->getActiveSheet()->setCellValue('B2',"Humidty");
    $objPHPExcel->getActiveSheet()->setCellValue('C2',"Temprature");
    $objPHPExcel->getActiveSheet()->setCellValue('D2',"Location");



$i++;
if ($stmt = $con->prepare($query)) {
    $stmt->execute();
    $stmt->bind_result($d1, $d2, $d3, $d4);
    while ($stmt->fetch()) {
		// Add some data
	
	$i++;
	$objPHPExcel->getActiveSheet()->setCellValue('A' . $i, "$d1");
	$objPHPExcel->getActiveSheet()->setCellValue('B' . $i, "$d2");
	$objPHPExcel->getActiveSheet()->setCellValue('C' . $i, "$d3");
	$objPHPExcel->getActiveSheet()->setCellValue('D' . $i, "$d4");

    }
    $stmt->close();
}							 
$objPHPExcel->getActiveSheet()->setShowGridlines(False);							 
$objPHPExcel->getActiveSheet()->getStyle('A2:E2')->getFont()->setBold(true);							 
$objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(65);						 
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);							 
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);	
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);

$objPHPExcel->getActiveSheet()->freezePane('A3');
$objPHPExcel->getActiveSheet()->setAutoFilter('A2:D'.$i);
//$objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension());
$objPHPExcel->getActiveSheet()->getStyle('A2:D'.$i)->getFont()->setName('Arial');
$objPHPExcel->getActiveSheet()->getStyle('A2:D2')->getFont()->setSize(13);
$objPHPExcel->getActiveSheet()->getStyle('B1:B1')->getFont()->setSize(26);
$objPHPExcel->getActiveSheet()->getStyle('A2:D2')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->getStyle('A2:D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('B3:D'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$objPHPExcel->getActiveSheet()->getStyle('A2:D'.$i)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
$objPHPExcel->getActiveSheet()->getStyle('A2:D2')->getAlignment()->setShrinkToFit(true);
$objDrawing = new PHPExcel_Worksheet_Drawing();
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
$objDrawing->setPath($_SERVER["DOCUMENT_ROOT"] . '/android-chrome-512x512.png');
$objDrawing->setName('logo');
//$objDrawing->setDescription('logo Image');

$objDrawing->setHeight(87);
$objDrawing->setCoordinates('A1');


$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(1);
$objPHPExcel->getActiveSheet()->setTitle('Chart');




$dataSeriesLabels=array(
                new PHPExcel_Chart_DataSeriesValues('String', 'Data!$B$2', NULL, 1),
                new PHPExcel_Chart_DataSeriesValues('String', 'Data!$C$2', NULL, 1)
                
            );

$xAxisTickValues=array(
                new PHPExcel_Chart_DataSeriesValues('String', 'Data!$A$3:$A'.$i, NULL, 11)
            );
            
$dataSeriesValues=array(
                new PHPExcel_Chart_DataSeriesValues('Number', 'Data!$B$3:$B'.$i, NULL, 11),
                new PHPExcel_Chart_DataSeriesValues('Number', 'Data!$C$3:$C'.$i, NULL, 11)
            );

$series = new PHPExcel_Chart_DataSeries(
	PHPExcel_Chart_DataSeries::TYPE_LINECHART,		// plotType
	PHPExcel_Chart_DataSeries::GROUPING_STANDARD,	// plotGrouping
	range(0, count($dataSeriesValues)-1),			// plotOrder
	$dataSeriesLabels,								// plotLabel
	$xAxisTickValues,								// plotCategory
	$dataSeriesValues								// plotValues
);


//	Set the series in the plot area
$plotArea = new PHPExcel_Chart_PlotArea(NULL, array($series));
//	Set the chart legend
$legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_TOPRIGHT, NULL, false);
$title = new PHPExcel_Chart_Title('Humidty & Temprature over time');
$yAxisLabel = new PHPExcel_Chart_Title('Humidty(%) and Temprature (C)');
$xAxisLabel = new PHPExcel_Chart_Title('Date & Time');
//	Create the chart
$chart = new PHPExcel_Chart(
	'chart1',		// name
	$title,			// title
	$legend,		// legend
	$plotArea,		// plotArea
	true,			// plotVisibleOnly
	0,				// displayBlanksAs
	$xAxisLabel,			// xAxisLabel
	$yAxisLabel		// yAxisLabel
);
//	Set the position where the chart should appear in the worksheet
$chart->setTopLeftPosition('A1');
$chart->setBottomRightPosition('AA60');
$objPHPExcel->getActiveSheet()->addChart($chart);






// Rename worksheet
//$objPHPExcel->getActiveSheet()->setTitle('All Data');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=\"Glider Managment System Report Data - Location $idlocation - ".date("Ymd").".xlsx\"");
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->setIncludeCharts(TRUE);
$objWriter->save('php://output');
exit;
?>

