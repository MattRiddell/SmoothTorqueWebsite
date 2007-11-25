<?php
$debug = false;
$MAX_ENTRIES=241;
include "charts.php";

$chart [ 'chart_data' ][ 0 ][ 0 ] = "";
$chart [ 'chart_data' ][ 1 ][ 0 ] = "Percentage On Phone";
$chart [ 'chart_data' ][ 2 ][ 0 ] = "Current Running Speed";
$chart [ 'chart_data' ][ 3 ][ 0 ] = "Max Running Speed";

$id=$_GET[id];
if ($id<1){
	exit(0);
}

$lines2=file("/tmp/Sm".$id.".campaignProperties");
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
			$dialed = substr(trim($line),6);
                }
                if (substr(trim($line),0,4)=="Mult") {
			$multiplyer = substr(trim($line),6);
                }
                if (substr(trim($line),0,3)=="MRS") {
			$mrs = substr(trim($line),5);
                }
                if (substr(trim($line),0,3)=="Adj") {
			$adjuster = substr(trim($line),5);
                }
                if (substr(trim($line),0,3)=="Wei") {
			$weighted = substr(trim($line),5);
                }
                if (substr(trim($line),0,3)=="CAD") {
			$cad = substr(trim($line),5);
                }
                if (substr(trim($line),0,2)=="MS") {
                    $ms = substr(trim($line),4);
                }
                if (substr(trim($line),0,2)=="M2") {
                    $m2 = substr(trim($line),4);
                }
                if (substr(trim($line),0,2)=="O1") {
                    $o1 = substr(trim($line),4);
                }
                if (substr(trim($line),0,2)=="O2") {
                    $o2 = substr(trim($line),4);
                }
                if (substr(trim($line),0,3)=="TSP") {
			$timespent = substr(trim($line),5);
			$timespentM = floor($timespent/60);
			$timespentS = $timespent%60;
//			$timespentS = $timespent;

                }
        }
}


$lines=file("/tmp/Sm".$id.".console");
$lines2=file("/tmp/Sm".$id.".console2");
$count=0;
$first=true;
$lastRate=0;
$lastPerc=0;

for ($i=1;$i<$MAX_ENTRIES;$i++){
	$chart [ 'chart_data' ][ 0 ][ $i ] = $MAX_ENTRIES-$i;
	$chart [ 'chart_data' ][ 1 ][ $i ] = 0;
	$chart [ 'chart_data' ][ 2 ][ $i ] = 0;
	$chart [ 'chart_data' ][ 3 ][ $i ] = ($mrs/2000)*100;
}

$avgPerc=0;
foreach ($lines as $line_num => $line) {
	if ($count<$MAX_ENTRIES){
        	if (strlen(trim($line))>0){
	        	$count++;
			$avgPerc+=trim($line);
			$chart [ 'chart_data' ][ 1 ][ $count ] = trim($line);
			$lastPerc=trim($line);

		}
        }
}
$avgPerc/=$count;
if ($timespent<$MAX_ENTRIES) {
	$avgPerc=0;
	for ($i=$MAX_ENTRIES;$i>=0;$i--){
		$avgPerc+=$chart [ 'chart_data' ][ 1 ][ $i ];
	}
	$avgPerc/=$timespent;
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

$chart [ 'axis_category' ] = array (   'skip'          =>  19,
                                       'font'          =>  arial,
                                       'bold'          =>  true,
                                       'size'          =>  9,
                                       'color'         =>  black,
                                       'alpha'         =>  70,
                                       'orientation'   =>  horizontal,

                                       //area, stacked area, line charts
                                       'margin'        =>  true,

                                   );


$chart[ 'chart_border' ] = array ( 'color'=>"000000", 'top_thickness'=>2, 'bottom_thickness'=>2, 'left_thickness'=>2,
'right_thickness'=>2 );

$chart[ 'chart_grid_h' ] = array ( 'alpha'=>10, 'color'=>"000000", 'thickness'=>1, 'type'=>"solid" );

$chart[ 'chart_grid_v' ] = array ( 'alpha'=>10, 'color'=>"000000", 'thickness'=>1, 'type'=>"solid" );

$chart[ 'chart_pref' ] = array ( 'line_thickness'=>2, 'point_shape'=>"none", 'fill_shape'=>false );

$chart[ 'chart_rect' ] = array ( 'x'=>40, 'y'=>25, 'width'=>950, 'height'=>400, 'positive_color'=>"ffffff",
'positive_alpha'=>80, 'negative_color'=>"ff0000",  'negative_alpha'=>10 );

$chart[ 'chart_type' ] = array ( "area", "line", "line" );

$chart[ 'chart_value' ] = array ( 'prefix'=>"", 'suffix'=>"", 'decimals'=>0, 'separator'=>"", 'position'=>"cursor",
'hide_zero'=>true, 'as_percentage'=>false, 'font'=>"arial", 'bold'=>true, 'size'=>12, 'color'=>"ffffff", 'alpha'=>75 );

$text4="Running Speed: ".(($lastSpeed/100)*2000)." (" .round($lastSpeed,2) ."%)";

if (!$debug){
$chart[ 'draw' ] = array (
array ( 'type'=>"text", 'color'=>"000000", 'alpha'=>2, 'font'=>"arial",
        'rotation'=>0, 'bold'=>true, 'size'=>75, 'x'=>240, 'y'=>-200,
        'width'=>620, 'height'=>300, 'text'=>"SmoothTorque", 'h_align'=>"left",
        'v_align'=>"bottom" ),
array ( 'type'=>"text", 'color'=>"444444", 'alpha'=>100, 'font'=>"arial", 'rotation'=>0,
'bold'=>true, 'size'=>15, 'x'=>765, 'y'=>160, 'width'=>300, 'height'=>300, 'text'=>"Done: $dialed",
'h_align'=>"left", 'v_align'=>"bottom" ),
array ( 'type'=>"text", 'color'=>"000000", 'alpha'=>13, 'font'=>"arial",
        'rotation'=>0, 'bold'=>true, 'size'=>25, 'x'=>390, 'y'=>170,
        'width'=>620, 'height'=>300, 'text'=>"Time (seconds ago)", 'h_align'=>"left",
        'v_align'=>"bottom" )

        );
} else {
$chart[ 'draw' ] = array (
array ( 'type'=>"text", 'color'=>"000000", 'alpha'=>3, 'font'=>"arial",
        'rotation'=>0, 'bold'=>true, 'size'=>75, 'x'=>240, 'y'=>120,
        'width'=>620, 'height'=>300, 'text'=>"SmoothTorque", 'h_align'=>"left",
        'v_align'=>"bottom" ),

array ( 'type'=>"text", 'color'=>"ffffff", 'alpha'=>15, 'font'=>"arial", 'rotation'=>-90,
'bold'=>true, 'size'=>50, 'x'=>-10, 'y'=>288, 'width'=>300, 'height'=>150, 'text'=>"Percent", 'h_align'=>"center",
'v_align'=>"top" ),
array ( 'type'=>"text", 'color'=>"000088", 'alpha'=>100, 'font'=>"arial", 'rotation'=>0,
'bold'=>true, 'size'=>15, 'x'=>270, 'y'=>160, 'width'=>300, 'height'=>300, 'text'=>"Busy Agents: $busy/$max ($lastPerc%)",
'h_align'=>"left",
'v_align'=>"bottom" ),
array ( 'type'=>"text", 'color'=>"444444", 'alpha'=>100, 'font'=>"arial", 'rotation'=>0,
'bold'=>true, 'size'=>15, 'x'=>270, 'y'=>200, 'width'=>300, 'height'=>300, 'text'=>"Ms Sleep: $ms",
'h_align'=>"left",
'v_align'=>"bottom" ),
array ( 'type'=>"text", 'color'=>"444444", 'alpha'=>100, 'font'=>"arial", 'rotation'=>0,
'bold'=>true, 'size'=>15, 'x'=>505, 'y'=>200, 'width'=>300, 'height'=>300, 'text'=>"MaxDelayCalc: $m2",
'h_align'=>"left",
'v_align'=>"bottom" ),
array ( 'type'=>"text", 'color'=>"444444", 'alpha'=>100, 'font'=>"arial", 'rotation'=>0,
'bold'=>true, 'size'=>15, 'x'=>765, 'y'=>160, 'width'=>300, 'height'=>300, 'text'=>"Done: $dialed",
'h_align'=>"left", 'v_align'=>"bottom" ),
array ( 'type'=>"text", 'color'=>"444444", 'alpha'=>100, 'font'=>"arial", 'rotation'=>0,
'bold'=>true, 'size'=>15, 'x'=>505, 'y'=>160, 'width'=>300, 'height'=>300, 'text'=>"".$text4,
'h_align'=>"left", 'v_align'=>"bottom" ),
array ( 'type'=>"text", 'color'=>"444444", 'alpha'=>100, 'font'=>"arial", 'rotation'=>0,
'bold'=>true, 'size'=>15, 'x'=>270, 'y'=>180, 'width'=>300, 'height'=>300, 'text'=>"Speed Multiplier: ".$multiplyer."x",
'h_align'=>"left", 'v_align'=>"bottom" ),
array ( 'type'=>"text", 'color'=>"444444", 'alpha'=>100, 'font'=>"arial", 'rotation'=>0,
'bold'=>true, 'size'=>15, 'x'=>505, 'y'=>180, 'width'=>300, 'height'=>300, 'text'=>"Max Running Speed: ".round($mrs,2)." (".round($mrs/2000*100)."%)",
'h_align'=>"left", 'v_align'=>"bottom" ),
array ( 'type'=>"text", 'color'=>"444444", 'alpha'=>100, 'font'=>"arial", 'rotation'=>0,
'bold'=>true, 'size'=>15, 'x'=>765, 'y'=>180, 'width'=>300, 'height'=>300, 'text'=>"Average: ".round($avgPerc,2)."%",
'h_align'=>"left", 'v_align'=>"bottom" ),
array ( 'type'=>"text", 'color'=>"444444", 'alpha'=>100, 'font'=>"arial", 'rotation'=>0,
'bold'=>true, 'size'=>15, 'x'=>765, 'y'=>200, 'width'=>300, 'height'=>300, 'text'=>"Overs: ".$o1."",
'h_align'=>"left", 'v_align'=>"bottom" ),
array ( 'type'=>"text", 'color'=>"444444", 'alpha'=>100, 'font'=>"arial", 'rotation'=>0,
'bold'=>true, 'size'=>15, 'x'=>45, 'y'=>220, 'width'=>300, 'height'=>300, 'text'=>"Adj Overs: ".$o2."",
'h_align'=>"left", 'v_align'=>"bottom" ),
array ( 'type'=>"text", 'color'=>"444444", 'alpha'=>100, 'font'=>"arial", 'rotation'=>0,
'bold'=>true, 'size'=>15, 'x'=>45, 'y'=>180, 'width'=>300, 'height'=>300, 'text'=>"TimeSpent: ".$timespentM.":".$timespentS,
'h_align'=>"left", 'v_align'=>"bottom" ),
array ( 'type'=>"text", 'color'=>"444444", 'alpha'=>100, 'font'=>"arial", 'rotation'=>0,
'bold'=>true, 'size'=>15, 'x'=>45, 'y'=>160, 'width'=>300, 'height'=>300, 'text'=>"Weighted: ".$weighted,
'h_align'=>"left", 'v_align'=>"bottom" ),
array ( 'type'=>"text", 'color'=>"444444", 'alpha'=>100, 'font'=>"arial", 'rotation'=>0,
'bold'=>true, 'size'=>15, 'x'=>45, 'y'=>200, 'width'=>300, 'height'=>300, 'text'=>"CAD: ".$cad,
'h_align'=>"left", 'v_align'=>"bottom" )



 );


}

$chart[ 'chart_pref' ] = array ( 'line_thickness'=>3, 'point_shape'=>"none", 'fill_shape'=>false );
$chart[ 'series_color' ] = array ( "6666ff", "ff0000", "000000", "564546", "784e3a", "677b75" );

$chart[ 'legend_label' ] = array ( 'layout'=>"horizontal", 'font'=>"arial", 'bold'=>true, 'size'=>12, 'color'=>"000000",
'alpha'=>90 );

$chart[ 'legend_rect' ] = array ( 'x'=>45, 'y'=>0, 'width'=>600, 'height'=>20, 'margin'=>5, 'fill_color'=>"ffffff",
'fill_alpha'=>100, 'line_color'=>"000000", 'line_alpha'=>20, 'line_thickness'=>1 );

$chart[ 'axis_value' ] = array (  'min'=>0, 'max'=>125, 'font'=>"arial", 'bold'=>true, 'size'=>10, 'color'=>"000000",
'alpha'=>90, 'steps'=>5, 'prefix'=>"", 'suffix'=>"%", 'decimals'=>0, 'separator'=>"", 'show_min'=>true );

$chart [ 'live_update' ] = array (   'url'    =>  "sample.php?id=".$id."&uniqueID=" . uniqid(rand(),true),
                                     'delay'  =>  1
                                );


//***************************************************************



SendChartData ($chart);

?>
