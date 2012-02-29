<?
require_once ('jpgraph.php');
require_once ('jpgraph_line.php');
require_once ('jpgraph_date.php');
require "admin/db_config.php";

$result = mysql_query("SELECT * FROM historic_cps where date(record_timestamp) = date(now()) order by record_timestamp asc");
while ($row = mysql_fetch_assoc($result)) {
    $data[] = $row['cps'];
    $xdata[] = @strtotime($row['record_timestamp']);
}

// Create the new graph
$graph = new Graph(1020,700);

// Slightly larger than normal margins at the bottom to have room for
// the x-axis labels
$graph->SetMargin(40,40,30,130);

// Fix the Y-scale to go between [0,200] and use date for the x-axis
$graph->SetScale('datlin');
//$graph->title->Set("Example on Date scale");

// Set the angle for the labels to 90 degrees
$graph->xaxis->SetLabelAngle(90);

$line = new LinePlot($data,$xdata);
$line->SetLegend('Historic Calls Per Second');
$line->SetFillColor('lightblue@0.5');
$graph->xgrid->Show(true,false);
$graph->ygrid->Show(true,true);
$graph->SetFrame(false,'darkblue',2);
$graph->SetBackgroundGradient('blue@0.9','white@0.2',GRAD_HOR,BGRAD_PLOT);
$graph->yaxis->SetWeight(1);
$graph->xgrid->SetColor('white@0.3','white@0.7');
$graph->ygrid->SetColor('white@0.3','white@0.7');

$graph->Add($line);
$graph->Stroke();
?>