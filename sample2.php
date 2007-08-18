<?php
$MAX_ENTRIES=240;
//$mrs=50;
//include charts.php to access the SendChartData function
include "charts.php";

//echo InsertChart ( "charts.swf", "/libraries/charts_library", "sample.php", 600, 400, "8844FF", false );




$chart [ 'chart_data' ][ 0 ][ 0 ] = "";
$chart [ 'chart_data' ][ 1 ][ 0 ] = "Percentage On Phone";
$chart [ 'chart_data' ][ 2 ][ 0 ] = "Speed";
$chart [ 'chart_data' ][ 3 ][ 0 ] = "Max Running Speed";

$lines2=file("/tmp/Sm1.campaignProperties");
$first=true;
foreach ($lines2 as $line_num => $line) {
        if (strlen(trim($line))>0){
                if (substr(trim($line),0,3)=="Min") {
			$min=substr(trim($line),5);
                }
                if (substr(trim($line),0,3)=="Tot") {
			$busy=substr(trim($line),6);
                }
                if (substr(trim($line),0,3)=="Max") {
			$max = substr(trim($line),5);
                }
                if (substr(trim($line),0,4)=="Done") {
			$dialed = substr(trim($line),5);
                }
                if (substr(trim($line),0,4)=="Mult") {
			$multiplyer = substr(trim($line),5);
                }
                if (substr(trim($line),0,3)=="MRS") {
			$mrs = substr(trim($line),5);
                }
        }
}


$lines=file("/tmp/Sm1.console");
$lines2=file("/tmp/Sm1.console2");
$count=0;
$first=true;
$lastRate=0;
$lastPerc=0;

for ($i=1;$i<$MAX_ENTRIES;$i++){
	$chart [ 'chart_data' ][ 0 ][ $i ] = $MAX_ENTRIES-$i+1;
	$chart [ 'chart_data' ][ 1 ][ $i ] = 0;
	$chart [ 'chart_data' ][ 2 ][ $i ] = 0;
	$chart [ 'chart_data' ][ 3 ][ $i ] = ($mrs/2000)*100;
}

foreach ($lines as $line_num => $line) {
	if ($count<$MAX_ENTRIES){
        	if (strlen(trim($line))>0){
	        	$count++;
			$chart [ 'chart_data' ][ 1 ][ $count ] = trim($line);
			$lastPerc=trim($line);

		} 
        }
}

$count=0;

foreach ($lines2 as $line_num2 => $line2) {
	if ($count<$MAX_ENTRIES){
        	if (strlen(trim($line2))>0){
		        $count++;
			$chart [ 'chart_data' ][ 2 ][ $count ] = trim($line2);
			$lastSpeed=trim($line2);
		} 
		
        }
}



//for ( $row = 1; $row <= 400; $row++ ) {
//      $chart [ 'chart_data' ][ 1 ][ $row ] = rand ( 0, 100 );
//}


//***************************************************************



$chart[ 'axis_ticks' ] = array ( 'value_ticks'=>true, 'category_ticks'=>true, 'major_thickness'=>1, 'minor_thickness'=>1, 
'minor_count'=>1, 'major_color'=>"000000", 'minor_color'=>"222222" ,'position'=>"outside" );

$chart[ 'axis_value' ] = array ( 'size'=>9, 'color'=>"000000", 'alpha'=>65, 'steps'=>10, 'suffix'=>"", 'max'=>400 );

$chart [ 'axis_category' ] = array (   'skip'          =>  59,
                                       'font'          =>  arial, 
                                       'bold'          =>  true, 
                                       'size'          =>  9, 
                                       'color'         =>  black, 
                                       'alpha'         =>  50,
                                       'orientation'   =>  horizontal,
                                       
                                       //area, stacked area, line charts
                                       'margin'        =>  true,
                                       
                                   ); 


$chart[ 'chart_border' ] = array ( 'color'=>"000000", 'top_thickness'=>2, 'bottom_thickness'=>2, 'left_thickness'=>2, 
'right_thickness'=>2 );

$chart[ 'chart_grid_h' ] = array ( 'alpha'=>10, 'color'=>"000000", 'thickness'=>1, 'type'=>"solid" );

$chart[ 'chart_grid_v' ] = array ( 'alpha'=>10, 'color'=>"000000", 'thickness'=>1, 'type'=>"solid" );

$chart[ 'chart_pref' ] = array ( 'line_thickness'=>2, 'point_shape'=>"none", 'fill_shape'=>false );

$chart[ 'chart_rect' ] = array ( 'x'=>40, 'y'=>25, 'width'=>800, 'height'=>200, 'positive_color'=>"ffffff", 
'positive_alpha'=>80, 'negative_color'=>"ff0000",  'negative_alpha'=>10 );

$chart[ 'chart_type' ] = "Area";

$chart[ 'chart_value' ] = array ( 'prefix'=>"", 'suffix'=>"", 'decimals'=>0, 'separator'=>"", 'position'=>"cursor", 
'hide_zero'=>true, 'as_percentage'=>false, 'font'=>"arial", 'bold'=>true, 'size'=>12, 'color'=>"ffffff", 'alpha'=>75 );

$chart[ 'draw' ] = array ( array ( 'type'=>"text", 'color'=>"ffffff", 'alpha'=>15, 'font'=>"arial", 'rotation'=>-90, 
'bold'=>true, 'size'=>50, 'x'=>-10, 'y'=>288, 'width'=>300, 'height'=>150, 'text'=>"Percent", 'h_align'=>"center", 
'v_align'=>"top" ),
array ( 'type'=>"text", 'color'=>"000088", 'alpha'=>100, 'font'=>"arial", 'rotation'=>0, 
'bold'=>true, 'size'=>15, 'x'=>270, 'y'=>-40, 'width'=>300, 'height'=>300, 'text'=>"Busy Agents: $busy/$max ($lastPerc%)", 
'h_align'=>"left", 
'v_align'=>"bottom" ),
array ( 'type'=>"text", 'color'=>"444444", 'alpha'=>100, 'font'=>"arial", 'rotation'=>0, 
'bold'=>true, 'size'=>15, 'x'=>505, 'y'=>-40, 'width'=>300, 'height'=>300, 'text'=>"Done: $dialed", 
'h_align'=>"left", 'v_align'=>"bottom" ),
array ( 'type'=>"text", 'color'=>"444444", 'alpha'=>100, 'font'=>"arial", 'rotation'=>0, 
'bold'=>true, 'size'=>15, 'x'=>645, 'y'=>-40, 'width'=>300, 'height'=>300, 'text'=>"Speed: ".($lastSpeed/100)*2000, 
'h_align'=>"left", 'v_align'=>"bottom" ),
array ( 'type'=>"text", 'color'=>"444444", 'alpha'=>100, 'font'=>"arial", 'rotation'=>0, 
'bold'=>true, 'size'=>15, 'x'=>270, 'y'=>-10, 'width'=>300, 'height'=>300, 'text'=>"Speed Multiplier: ".$multiplyer."x", 
'h_align'=>"left", 'v_align'=>"bottom" ),
array ( 'type'=>"text", 'color'=>"444444", 'alpha'=>100, 'font'=>"arial", 'rotation'=>0, 
'bold'=>true, 'size'=>15, 'x'=>505, 'y'=>-10, 'width'=>300, 'height'=>300, 'text'=>"Max Running Speed: ".$mrs."", 
'h_align'=>"left", 'v_align'=>"bottom" ),
array ( 'type'=>"text", 'color'=>"000000", 'alpha'=>15, 'font'=>"arial", 'rotation'=>0, 
'bold'=>true, 'size'=>20, 'x'=>40, 'y'=>-40, 'width'=>320, 'height'=>300, 'text'=>"Time Scale", 'h_align'=>"left", 
'v_align'=>"bottom" )



 );

$chart[ 'series_color' ] = array ( "0088ff", "88ff00", "cccccc", "564546", "784e3a", "677b75" );

$chart[ 'legend_label' ] = array ( 'layout'=>"horizontal", 'font'=>"arial", 'bold'=>true, 'size'=>12, 'color'=>"000000", 
'alpha'=>90 ); 

$chart[ 'legend_rect' ] = array ( 'x'=>45, 'y'=>0, 'width'=>600, 'height'=>20, 'margin'=>5, 'fill_color'=>"ffffff", 
'fill_alpha'=>100, 'line_color'=>"000000", 'line_alpha'=>20, 'line_thickness'=>1 ); 

$chart[ 'axis_value' ] = array (  'min'=>0, 'max'=>100, 'font'=>"arial", 'bold'=>true, 'size'=>10, 'color'=>"000000", 
'alpha'=>90, 'steps'=>10, 'prefix'=>"", 'suffix'=>"%", 'decimals'=>0, 'separator'=>"", 'show_min'=>true );

$chart [ 'live_update' ] = array (   'url'    =>  "sample2.php?uniqueID=" . uniqid(rand(),true), 
                                     'delay'  =>  3
                                ); 


//***************************************************************



SendChartData ($chart);

?>
