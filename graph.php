<?php
include "admin/db_config.php";
mysql_select_db("SineDialer", $link);

/* The number of seconds we monitor */
$MAX_ENTRIES = 720;

/* The campaign ID */
$id=$_GET[id];
if ($id<1){
        exit(0);
}

$first=true;
$lastRate=0;
$lastPerc=0;

$result = mysql_query("SELECT * FROM config WHERE parameter = 'm_c_stats'");
if (mysql_num_rows($result) > 0) {
    $result = mysql_query("SELECT * FROM campaign_stats WHERE campaignid = $id");
    if (mysql_num_rows($result) == 0) {
        exit(0);
    }
    $row = mysql_fetch_assoc($result);
    $min = $row[min_agents];
    $busy = $row[busy_agents];
    $max = $row[total_agents];
    $dialed = $row[dialed];
    $multiplyer = $row[speed_multiplyer];
    $mrs = $row[max_running_speed];
    $adjuster = $row[adjuster];
    $weighted = $row[weighted];
    $cad = $row[cummulative_area_diff];
    $ms = $row[ms_sleep];
    $m2 = $row[max_delay_calc];
    $o1 = $row[overs_1];
    $o2 = $row[overs_2];
    $timespent = $row[time_spent];
    $timespentM = floor($timespent/60);
    $timespentS = $timespent%60;
    if ($timespentS < 10) {
        $timespentS = "0".$timespentS;
    }
    if ($timespentM < 10) {
        $timespentM = "0".$timespentM;
    }
} else {
    /* We do not have a MySQL stat backend */
	if (!file_exists("/tmp/Sm".$id.".campaignProperties")) {
	    exit(0);
	}
	$lines2=file("/tmp/Sm".$id.".campaignProperties");
	$first=true;
	$total_count_x = 0;
	foreach ($lines2 as $line_num => $line) {
        if (strlen(trim($line))>0){
            if (substr(trim($line),0,3)=="Min") {
                $min=substr(trim($line),5);
            }
            if (substr(trim($line),0,3)=="Tot") {
                $busy=substr(trim($line),6);
            }
            if (substr(trim($line),0,3)=="Max") {
                $max = trim(substr(trim($line),5));
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
                if ($ms == "inf" || $ms == "NAN") {
                    $ms = 20000;
                }
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
                if ($timespentS < 10) {
                    $timespentS = "0".$timespentS;
                }
                if ($timespentM < 10) {
                    $timespentM = "0".$timespentM;
                }
	        }
	    }
	}
}
/* END OF CAMPAIGN PROPERTIES */



$lines=file("/tmp/Sm".$id.".console");
$lines2=file("/tmp/Sm".$id.".console2");
$lines3=file("/tmp/Sm".$id.".console3");

for ($i=1;$i<721;$i++){
        $chart [ 'chart_data' ][ 0 ][ $i ] = 720-$i;
        $chart [ 'chart_data' ][ 1 ][ $i ] = 0;
        $chart [ 'chart_data' ][ 2 ][ $i ] = 0;
        $array1[$i] = 0;
        $array3[$i] = 0;
        $array2[$i] = $mrs/2000*100;
        $array5[$i] = ($mrs/2000*100);
}
$count = 0;
$highest = 0;
foreach ($lines as $line_num => $line) {
        $total_count_x++;
        if ($count<720){
                if (strlen(trim($line))>0){
                        $count++;
                        //if ($timespent > $count) {
                            $avgPerc+=trim($line);
                        //}
                        if(trim($line)>$highest){
                            $highest = trim($line);
                        }
                        $array1[ $count ] = trim($line);
                        $lastPerc=trim($line);
                }
        }
}
$count = 0;
foreach ($lines2 as $line_num2 => $line2) {
        $total_count_x++;
        if ($count<720){
                if (strlen(trim($line2))>0){
                        $count++;
                       if (trim($line2) == "nan") {
                               $line2 = "0";
                       }
                        $array3[ $count ] = trim($line2);
                        $array4[ $count+1 ] = $array3[ $count ]-0.5;
                        $lastSpeed=trim($line2);
                        $xdata[$count] = $count;
                }
        }
}
$highest_ms = 1;
if ($_GET[debug]>0){
    $count = 0;
    foreach ($lines3 as $line_num3 => $line3) {
            //$total_count_x++;
            if ($count<720){
                    if (strlen(trim($line3))>0){
                            $count++;
                            if (trim($line3) > $highest_ms) {
                                $highest_ms = trim($line3);
                            }
                    }
            }
    }
    $count = 0;
    foreach ($lines3 as $line_num3 => $line3) {
            //$total_count_x++;
            if ($count<720){
                    if (strlen(trim($line3))>0){
                            $count++;
                            if (trim($line3) > 0) {
                                //$newnumber = trim($line3)/1000;
                                //$array_ms[ $count ] = 100-(20 * (log10($newnumber) + 2.7));
                               if (!($highest_ms > 0)) {
                                       $highest_ms = 1;
                               }
                                $array_ms[ $count ] = 101-(trim($line3)/($highest_ms)* 100);

//                                $array_ms[ $count ] = 100-(20 * (log10($newnumber) + 2.7));
                            } else {
                                $array_ms[ $count ] = 0;
                            }
                            $lastSpeed_ms=trim($line3);
                    }
            }
    }
}

if ($total_count_x == 0) {
    $count = 720;
    for ($i=1;$i<721;$i++){
        $array1[$i] = 0;
        $array2[$i] = 0;
        $array3[$i] = 0;
        $array4[$i] = 0;
        $array5[$i] = 0;
        $array_ms[$i] = 0;
        $ms = 1000;
    }
} else if ($count > 0) {
    if ($timespent > $count) {
        $avgPerc/=$count;
    } else {
        $avgPerc/=$timespent;
    }
}
/*if ($timespent<$MAX_ENTRIES) {
        $avgPerc=0;
        for ($i=0;$i<$MAX_ENTRIES;$i++){
                $avgPerc+=$chart [ 'chart_data' ][ 1 ][ $i ];
        }
        $avgPerc/=$timespent;
}*/
include ( "./jpgraph.php");
include ("./jpgraph_line.php");
include ("./jpgraph_bar.php");
include "./jpgraph_regstat.php";
//$res="300";
//$spline  = new Spline($xdata, $array2);
//$spline2  = new Spline($xdata, $array1);
//$spline3  = new Spline($xdata, $array3);
//list( $sdatax, $chart [ 'chart_data' ][ 2 ]) =  $spline->Get($res);
//list( $sdatax, $chart [ 'chart_data' ][ 1 ]) =  $spline2->Get($res);
//list( $sdatax, $chart [ 'chart_data' ][ 3 ]) =  $spline3->Get($res);
$chart [ 'chart_data' ][ 2 ] = $array2;
$chart [ 'chart_data' ][ 1 ] = $array1;
$chart [ 'chart_data' ][ 3 ] = $array3;

$graph2 = new Graph(1040, 400);
//echo $highest;
if ($highest>100){
	$graph2->SetScale('linlin',0,$highest,1,719);
//	$graph2->SetYScale(0,'lin',0,$highest);
//	$graph2->SetXScale(0,'lin',1,719);
} else {
//	$graph2->SetScale('linlin',0,$highest,1,239);
	$graph2->SetScale('linlin',0,100,1,719);
}



//$graph2->SetScale('linlin');
$graph2->xaxis->SetTickLabels($aaa);
$graph2->xaxis->SetTextLabelInterval(1);
//$graph2->SetTickDensity(TICKD_SPARSE,TICKD_SPARSE); // Many Y-ticks
$graph2->yaxis->SetWeight(1);
$graph2->xgrid->SetColor('white@0.3','white@0.7');
$graph2->ygrid->SetColor('white@0.3','white@0.7');
$graph2->xgrid->Show(true,true);
$graph2->ygrid->Show(true,true);
$graph2->SetFrame(false,'darkblue',2);
$graph2->SetBackgroundGradient('purple@0.9','lightblue2@0.2',GRAD_HOR,BGRAD_PLOT);
for ($i=0;$i<720;$i++){
        $datax[$i] = "".(int)(720-$i);
}
$graph2->xaxis->SetTickLabels($datax);
$speedAtractorPlot[] = new LinePLot($chart [ 'chart_data' ][ 2 ]);
$speedAtractorPlot2[] = new LinePLot($array5);
$dplot2[] = new LinePlot($chart [ 'chart_data' ][ 1 ]);
$runningSpeedPlot[] = new LinePLot($chart [ 'chart_data' ][ 3 ]);
$runningSpeedPlot2[] = new LinePLot($array4);
//$dplot[0]->SetFillColor("#ff0000@0.8");
$dplot2[0]->SetFillGradient("#0000ff@0.99","#009933");
$dplot2[0]->SetWeight(1);
//$speedAtractorPlot[0]->SetFillGradient("#ff0000@0.5","#ff0000@0.5");
$speedAtractorPlot[0]->SetWeight(1);
$speedAtractorPlot[0]->SetColor("#00FF00");
$speedAtractorPlot2[0]->SetWeight(3);
$speedAtractorPlot2[0]->SetColor("#000000");
//$graph2->mark
$runningSpeedPlot[0]->SetColor("#ff0000");
$runningSpeedPlot[0]->SetWeight(2);
$runningSpeedPlot[0]->SetStepStyle();
$runningSpeedPlot2[0]->SetColor("#000000@0.9");
$runningSpeedPlot2[0]->SetWeight(2);
$runningSpeedPlot2[0]->SetStepStyle(true);
$graph2->ygrid->Show(true ,true);
$graph2->xgrid->Show();
//$graph2->title->Set('Filled Y-grid');
$graph2->legend->SetLayout(LEGEND_HOR);
$graph2->img->SetMargin(35,23,05,80);

$speedAtractorPlot[0]->SetLegend(' Speed Attractor');
$dplot2[0]->SetLegend(' Staff on Phone');
$runningSpeedPlot[0]->SetLegend(' Current Speed');

$graph2->legend->SetFrameWeight(1);
$graph2->legend->SetShadow('black@0.8',4);
$graph2->legend->SetFillColor('black@0.5');
$graph2->legend->SetColor('white');
$graph2->legend->SetFont(FF_FONT2,FS_BOLD);
$graph2->legend->SetPos(0.05,0.04,'left','top');
$graph2->legend->SetMarkAbsSize(13);

//$dplot[0]->SetStepStyle();
//$dplot2[0]->SetStepStyle();
//$dplot2[0]->SetStepStyle();
$graph2->Add($dplot2[0]);
$graph2->Add($runningSpeedPlot2[0]);
$graph2->Add($runningSpeedPlot[0]);
$graph2->Add($speedAtractorPlot2[0]);
$graph2->Add($speedAtractorPlot[0]);
if ($_GET[debug]>0){
//$graph2->SetYScale(2,'lin',0,500,1,719);

$msPlot[] = new LinePLot($array_ms);
    $msPlot[0]->SetWeight(3);
    $msPlot[0]->SetColor("#ffff00");

    $graph2->Add($msPlot[0]);
}
$graph2->SetShadow();
$graph2->img->SetAntiAliasing(true);

/*$sql = 'SELECT progress from queue where campaignid='.$id;
$resultx=mysql_query($sql, $link) or die (mysql_error());;
$rowx = mysql_fetch_assoc($resultx);

$progress=$rowx[progress];*/

$sql = 'SELECT status from queue where campaignid='.$id;
$resultx=mysql_query($sql, $link) or die (mysql_error());;
$rowx = mysql_fetch_assoc($resultx);
$status=$rowx[status];

if ($dialed>0){
    $progress=$dialed;
} else {
    $progress=$dialed;
}


if ($progress<0||$status < 0){
    $txt=new Text( "\n\n     This Campaign is Now Finished    \n\n");
    $txt->Pos( 500,122);
    $txt->SetAlign("center","","");
    $txt->SetFont(FF_FONT2,FS_BOLD);
    $txt->SetBox('#00ff88@0.2','navy@0.1','#000000@0.8',0,5);
    //$txt->SetColor("red");
} else {
//$txt = new Text($ms);
    if (!($ms > 0)) {
       $ms = 1;
       }
    $txt=new Text( "  Dialed: $progress   Busy Agents:$busy/$max   Average:".round($avgPerc)."%   Time Spent: $timespentM:$timespentS   Time between calls: ".round($ms/1000,3)." Seconds (".round(1/($ms/1000),3)." CPS)");
    $txt->Pos( 500,342);
    $txt->SetAlign("center","","");
    $txt->SetFont(FF_FONT2,FS_NORMAL);
    $txt->SetBox('#bbbbff','navy@0.1','#cccccc');
    //$txt->SetColor("red");
}
$graph2->AddText( $txt);
if ($_GET[debug]>0){
    $txt2=new Text( " Weighted: $weighted CAD: $cad Mult: $multiplyer Sleep: ".round($ms,2)."ms Max Delay Calc: ".round($m2,3)." Overs: ($o1/$timespent)");
    $txt2->Pos( 500,375);
    $txt2->SetAlign("center","","");
    $txt2->SetFont(FF_FONT2,FS_NORMAL);
    $txt2->SetBox('#bbffbb','navy@0.1','#cccccc');
    $graph2->AddText( $txt2);
}

//$graph2->ygrid->SetFill(true,'#EFEFEF@0.9','#BBCCFF@0.9');
//$graph2->xaxis->SetTextLabelInterval(1);
//$graph2->xaxis->SetTextTickInterval(1,1);

$graph2->SetBackgroundGradient('#CCCCFF@0.6','white@0.6',GRAD_HOR,BGRAD_PLOT);
$graph2->Stroke();
?>
