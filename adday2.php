<?php
include ( "./jpgraph.php");
include ("./jpgraph_line.php");
include ("./jpgraph_bar.php");
$graph2 = new Graph(1220, 300);
$graph2->SetMargin(50,0,10,20);
$graph2->legend->SetFillColor("blue@0.8");
$graph2->SetShadow();
$highest=0;
set_time_limit(600);
$host = $_GET[host];
$from=$_GET["from"];
$connection = mysql_connect('localhost', 'root', '') or die(mysql_error());
mysql_select_db("route", $connection);
$query23 = 'SELECT * FROM pings WHERE host="'.$host.'" AND testfrom="'.$from.'" order by id DESC, host DESC, testfrom DESC';
//echo $query23;
$result=mysql_query($query23, $connection) or die (mysql_error());
for ($i=0;$i<100;$i++){
	$ydata[$i] = "0";
}
for ($i=0;$i<mysql_numrows($result);$i++){
	$ydata[mysql_result($result,$i,"id")] = mysql_result($result,$i,"ping");
	if (mysql_result($result,$i,"ping") > $highest) {
		$highest = mysql_result($result,$i,"ping");
	}
//	echo mysql_result($result,$i,"ping");
}

$query23 = 'SELECT * FROM pings WHERE host="'.$host.'" AND testfrom="'.'akl'.'" order by id DESC, host DESC, 
testfrom DESC';
//echo $query23;

$result=mysql_query($query23, $connection) or die (mysql_error());
for ($i=0;$i<100;$i++){
	$ydata2[$i] = "0";
}
for ($i=0;$i<mysql_numrows($result);$i++){
	$ydata2[mysql_result($result,$i,"id")] = mysql_result($result,$i,"ping");
	if (mysql_result($result,$i,"ping") > $highest2) {
		$highest2 = mysql_result($result,$i,"ping");
	}
}
if ($highest2>$highest){
	$highest = $highest2;
}
$graph2->SetScale('textlin',0,$highest,0,100);
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
for ($i=0;$i<100;$i++){
	$datax[$i] = "".(int)($i);
}
$graph2->xaxis->SetTickLabels($datax);
$graph2->img->SetAntiAliasing();
$dplot[] = new LinePLot($ydata);
$dplot2[] = new LinePLot($ydata2);
$dplot[0]->SetFillGradient("#EAECF6","#000000");
$dplot2[0]->SetFillGradient("#EAECF6","#00ff00");
$graph2->legend->SetLayout(LEGEND_HOR);

$graph2->Add($dplot2[0]);
$graph2->Add($dplot[0]);
$graph2->SetShadow();
$graph2->StrokeCSIM('adday2.php');
?>
