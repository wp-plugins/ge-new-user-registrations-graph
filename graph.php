<?php

// Produce the graph for the number of new users per day

ob_start();

require_once("../../../wp-config.php");
require_once("../../../wp-load.php");
require_once("../../../wp-includes/wp-db.php");


$days = 7;
if (isset($_GET['days'])) {
	if (is_numeric($_GET['days'])) {
		$days = $_GET['days'];
	}
}
$odays = $days;
$width = 535;
if (isset($_GET['w'])) {
	if (is_numeric($_GET['w'])) {
		$width = $_GET['w'];
	}
}

$dayarray = array();
$x = 0;
while ($days > 0) {
	$dayarray[date("Y-m-d",time() -((60*60*24) * $x))]=0;
	$x++;
	$days--;
}
$x--;

// Now run the SQL query and fill the date array values.
global $wpdb;
$sql = "SELECT COUNT(*) as c,user_registered FROM " . $wpdb->prefix . "users WHERE user_registered>='" . date("Y-m-d",time() -((60*60*24) * $x)). "' GROUP BY DAY(user_registered),MONTH(user_registered),YEAR(user_registered) ORDER BY YEAR(user_registered) DESC,MONTH(user_registered) DESC,DAY(user_registered) DESC";
$p = $wpdb->get_results($sql);
foreach ($p as $row) {
	$dayarray[date("Y-m-d", strtotime($row->user_registered))] = $row->c;	
}
$dayarray = array_reverse($dayarray);
$data = array();
$vals = array();
$x = 0;
$maxvalue = 0;
foreach ($dayarray as $key=>$value) {
	array_push($data,$value);
	if ($value > $maxvalue) { $maxvalue = $value; }
	$ppp = date("d",strtotime($key));
	if ($odays > 30) { $ppp = ""; }
	array_push($vals,$ppp);
	$x++;
	if ($x == $days) {echo "break";  break; }
}
if ($maxvalue < 20) { $maxvalue = 20; }

 include("pChart/pData.class");     
 include("pChart/pChart.class");     
   
 // Dataset definition      
 $DataSet = new pData();     
 $DataSet->AddPoint($data,"Serie1");
 $DataSet->AddAllSeries();
 $DataSet->AddPoint($vals,"Serie2");
 $DataSet->SetAbsciseLabelSerie("Serie2");     
   
 // Initialise the graph     
 $Test = new pChart($width,$width * 0.56);     
 $Test->setFontProperties("Fonts/tahoma.ttf",8);     
 $Test->setGraphArea(30,20,$width - 15,($width * 0.56)-30);     
 $Test->drawGraphArea(255,255,255,FALSE);  
 $Test->setFixedScale(0,$maxvalue);
 $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_START0,120,120,120,TRUE,0,0,TRUE);     
 $Test->clearShadow();
   
 // Draw the line graph  
 $Test->LineWidth=1;
 $Test->drawLineGraph($DataSet->GetData(),$DataSet->GetDataDescription());
 $Test->drawFilledLineGraph($DataSet->GetData(),$DataSet->GetDataDescription(),100,TRUE);     
 $Test->LineWidth=1;
 $Test->drawGrid(1,FALSE,190,190,190,0);  
 if ($odays <31) {
 	$Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie1");
 }
   
 // Finish the graph     
 ob_end_clean();
 $Test->Stroke(); 
?>
