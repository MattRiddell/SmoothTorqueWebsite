<?
include 'ofc-library/open-flash-chart.php';
require_once "admin/db_config.php";
	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

$result = mysql_query("SELECT count(*) FROM SineDialer.number where campaignid = $_GET[campaignid]") or die(mysql_error());
$absolute_total = mysql_result($result,0,0);
$result = mysql_query("SELECT distinct(status), count(*) FROM SineDialer.number where campaignid = $_GET[campaignid] and status != 'new' and status != 'unknown' group by status order by status") or die(mysql_error());
$max = 0;
$count = 0;
$colors = array('#FF0000','#006600','#0000FF','#FF8888','#004444', '#224466', '#000000','22FF66') ;
$size = sizeof($colors);
$total = 0;
while ($row = mysql_fetch_assoc($result)) {
	if ($row['count(*)'] > 0) {
		$status_name = $row['status'];
        $total+= $row['count(*)'];
		switch ($status_name) {
			case "amd":
				$status_name = "A. Machine";
				break;
			case "indnc":
				$status_name = "Do Not Call";
				break;
			default:
				$status_name = ucfirst($status_name);
				break;
		}
		$bar = new bar_value(intval($row['count(*)']));
		if ($count > $size) {
			$count = 0;
		}
		$bar->set_colour( $colors[$count] );
		$bar->set_tooltip ($status_name." (".$row['count(*)'].")");
		$x_labels[] = $status_name;
		$status[] = $bar;
		$count++;
	}
	if ($row['count(*)'] > $max) {
		$max = $row['count(*)'];
	}
}
$bar = new bar_glass();
$bar->set_values($status);
$chart = new open_flash_chart();
$chart->set_title( null );
$chart->add_element( $bar );
$x = new x_axis();
$x->set_offset( true );
$x->set_labels_from_array( $x_labels );
$chart->set_x_axis( $x );
$y = new y_axis();
$y->set_range(0,$max+1);
//$y->set_steps(round($max/10));
$chart->set_y_axis( $y );
$chart->set_bg_colour( '#FFFFFF' );
$remaining = ($absolute_total-$total);
if ($absolute_total > 0) {
    $rem_perc = round((($remaining/$absolute_total) * 100),2);
} else {
    $rem_perc = "100";
}
$title = new title("Dialed: $total Remaining: $remaining ($rem_perc %)");

$chart->set_title( $title );
echo $chart->toPrettyString();
?>
