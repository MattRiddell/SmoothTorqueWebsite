<?/*
<script>
    $(function () {

        var d1 = [];
        for (var i = 0; i<14; i += 0.5) {
            d1.push([i, Math.sin(i)]);
        }

        var d2 = [[0, 3], [4, 8], [8, 5], [9, 13]];

        // A null signifies separate line segments

        var d3 = [[0, 12], [7, 12], null, [7, 2.5], [12, 2.5]];

        $.plot("#placeholder", [d1, d2, d3]);

        // Add the Flot version string to the footer

        $("#footer").prepend("Flot " + $.plot.version + " &ndash; ");
    });
</script>
<div id="placeholder" style="height: 50%; background: #fff" class="img-rounded"></div>
<?
exit(0);
*/

$max_val = $_GET[max];
$size = $_GET[size];
if (isset($_GET[xsize])) {
    $xsize = $_GET[xsize];
    $ysize = $_GET[ysize];
} else {
    $xsize = 620;
    $ysize = 280;
}
for ($i = 0; $i < $size; $i++) {
    $xdata[$i] = 0;
}
include "admin/db_config.php";
mysql_select_db("SineDialer", $link);
$resultx = mysql_query("select distinct system_billing.groupid, customer.* from system_billing left join customer on system_billing.groupid=customer.campaigngroupid");
while ($rowx = mysql_fetch_assoc($resultx)) {

    if ($_GET[groupid] < 0) {
        $result = mysql_query("select totalcost from system_billing where groupid = ".$rowx[groupid]." order by timestamp desc LIMIT $size");
    } else {
        $result = mysql_query("select totalcost from system_billing where groupid = ".$_GET[groupid]." order by timestamp desc LIMIT $size");
    }
    $x = 0;
    //echo "<b>Group ID: $_GET[groupid]</b><br />";
    while ($row = mysql_fetch_assoc($result)) {

        /* This is one page of customer billing (i.e. the last $size 5 minute values */
        /* in here should be a graph for that customer showing how much they've */
        /* spent in the last $size blocks */
        $z = $size - $x;
        //echo $z."<br />";
        if ($_GET[groupid] < 0) {
            $xdata[$z] += $row[totalcost];
//            echo $xdata[$z]."<br \>";
        } else {
            $xdata[$z] = $row[totalcost];
        }

        $x++;
    }


}

include("./jpgraph.php");
include("./jpgraph_line.php");
include("./jpgraph_bar.php");

$graph2 = new Graph($xsize, $ysize);
if ($xsize < 620) {
    $graph2->SetMargin(50, 0, 0, 0);
} else {
    $graph2->SetMargin(50, 20, 20, 20);
}
$graph2->legend->SetFillColor("blue@0.8");
$graph2->SetShadow();
$graph2->SetScale('textlin', 0, $max_val, 0, $size - 1);
//$graph2->xaxis->SetTickLabels($aaa);
$graph2->xaxis->SetTextLabelInterval(1);
//$graph2->SetTickDensity(TICKD_SPARSE,TICKD_VERYSPARSE); // Many Y-ticks
$graph2->yaxis->SetWeight(1);
$graph2->xgrid->SetColor('white@0.3', 'white@0.7');
$graph2->ygrid->SetColor('white@0.3', 'white@0.7');
$graph2->xgrid->Show(TRUE, TRUE);
$graph2->SetFrame(FALSE, 'darkblue', 2);
$graph2->SetBackgroundGradient('blue@0.999', 'lightblue2@0.7', GRAD_HOR, BGRAD_PLOT);
for ($i = 0; $i < $size; $i++) {
    $datax[$i] = "".(int)($i);
}
$graph2->xaxis->SetTickLabels($datax);
$graph2->img->SetAntiAliasing();
$dplot[] = new LinePLot($xdata);
//$graph2->title->Set(ucfirst($_COOKIE[user]));
//for ($i=0;$i<$size;$i++){
//$datax[$i] = "".(int)(100-$i);
//}
//$graph->xaxis->SetTickLabels($datax);
//$dplot[0]->SetWidth(0.7);
$dplot[0]->SetFillColor('blue', 'red@0.95');
//$dplot[0]->SetFillGradient('blue','blue@0.85',GRAD_HOR);

//$ap2 = new AccLinePlot($dplot2);
// Add the accumulated line plot to the graph


//$dplot[0]->SetColor('black');
//$dplot[0]->SetGradient('black','white');
//$dplot[0]->SetFillGradient('red','red@0.85');
$dplot[0]->SetFillGradient('blue', 'white@0.25');
// Then add them together to form a accumulated plot
//$ap = new AccLinePlot($dplot);

$dplot[0]->SetColor('black');
//if ($_GET[lang]="en"){
$dplot[0]->SetLegend("Total Cost");

// Add the accumulated line plot to the graph
$graph2->legend->SetLayout(LEGEND_HOR);

//$graph2->legend->Pos(0.52, 0.93, 'center');

/*for ($xx=0;$xx<24;$xx++){
//        $urls[$xx]="hour2.php?hour=$xx&account=".$_COOKIE[user]."&day=".$_GET[day];
$urls[$xx]="#";
	if ($ydata[$xx]>0){
		$aloc=round(($yyyyyydata[$xx]/$ydata[$xx]),2);
	} else {
		$aloc="N/A";
	}
    if ($y2data[$xx]>0){
    $asr=round((($ydata[$xx]/$y2data[$xx])*100));
    }

        $alts[$xx]="Hour $xx:00-".($xx+1).":00 - ".$ydata[$xx]." Calls, ".round($yyyyyydata[$xx])." Minutes, ".$asr."%% ASR, ALOC: ".$aloc;
}*/
//$dplot2[0]->SetCSIMTargets($urls);

//$dplot[0]->mark->SetType(MARK_FILLEDCIRCLE);
//$dplot[0]->mark->SetWidth(5);
//$dplot[0]->mark->SetColor('black');
//$dplot[0]->mark->SetFillColor('red');
//$dplot[0]->SetCSIMTargets($urls,$alts);
//$dplot2[0]->SetCSIMTargets($urls,$alts);


$graph2->Add($dplot[0]);
//$graph2->Add($dplot2[0]);
$graph2->SetShadow();
// Display the graph
//$graph2->StrokeCSIM('system_bill_graph.php');
$graph2->Stroke();
?>
