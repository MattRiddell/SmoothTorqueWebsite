<?php
$id=$_GET[id];
if ($id<1){
        exit(0);
}

global $chart;
$first=true;
$lastRate=0;
$lastPerc=0;
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
}}
$lines=file("/tmp/Sm".$id.".console");
$lines2=file("/tmp/Sm".$id.".console2");

for ($i=1;$i<241;$i++){
        $chart [ 'chart_data' ][ 0 ][ $i ] = 240-$i;
        $chart [ 'chart_data' ][ 1 ][ $i ] = 0;
        $chart [ 'chart_data' ][ 2 ][ $i ] = 0;
        $chart [ 'chart_data' ][ 3 ][ $i ] = ($mrs/2000)*100;
}
$count = 0;
foreach ($lines as $line_num => $line) {
        if ($count<240){
                if (strlen(trim($line))>0){
                        $count++;
                        $avgPerc+=trim($line);
                        $chart [ 'chart_data' ][ 1 ][ $count ] = trim($line);
                        $lastPerc=trim($line);

                }
        }
}
$count = 0;
foreach ($lines2 as $line_num2 => $line2) {
        if ($count<240){
                if (strlen(trim($line2))>0){
                        $count++;
                        $chart [ 'chart_data' ][ 2 ][ $count ] = trim($line2);
                        $lastSpeed=trim($line2);
                }

        }
}

include ( "./jpgraph.php");
include ("./jpgraph_line.php");
include ("./jpgraph_bar.php");
$graph2 = new Graph(1040, 400);
$graph2->SetScale('textlin',0,125,1,239);
$graph2->xaxis->SetTickLabels($aaa);
$graph2->xaxis->SetTextLabelInterval(1);
$graph2->SetTickDensity(TICKD_SPARSE,TICKD_VERYSPARSE); // Many Y-ticks
$graph2->yaxis->SetWeight(1);
$graph2->xgrid->SetColor('white@0.3','white@0.7');
$graph2->ygrid->SetColor('white@0.3','white@0.7');
$graph2->xgrid->Show(true,true);
$graph2->ygrid->Show(true,true);
$graph2->SetFrame(false,'darkblue',2);
$graph2->SetBackgroundGradient('purple@0.9','lightblue2@0.2',GRAD_HOR,BGRAD_PLOT);
for ($i=0;$i<240;$i++){
        $datax[$i] = "".(int)($i);
}
$graph2->xaxis->SetTickLabels($datax);
$graph2->img->SetAntiAliasing();
$dplot[] = new LinePLot($chart [ 'chart_data' ][ 2 ]);
$dplot2[] = new LinePLot($chart [ 'chart_data' ][ 1 ]);
$dplot3[] = new LinePLot($chart [ 'chart_data' ][ 3 ]);
$dplot[0]->SetFillGradient("#0000ff@0.8","#00ff00@0.7");
$dplot2[0]->SetFillGradient("#00ff00","#0000ff");
//$dplot3[0]->SetFillGradient("#ff0000@0.5","#ff0000@0.5");
//$dplot[0]->SetWeight(4);
$dplot3[0]->SetWeight(2);
$dplot[0]->SetColor("#0000ff");
$dplot3[0]->SetColor("#ff0000");
$graph2->legend->SetLayout(LEGEND_HOR);
$graph2->img->SetMargin(35,23,05,25);
$graph2->img->SetAntiAliasing();
$graph2->Add($dplot2[0]);
$graph2->Add($dplot3[0]);
$graph2->Add($dplot[0]);
$graph2->SetShadow();

$graph2->Stroke();
?>
