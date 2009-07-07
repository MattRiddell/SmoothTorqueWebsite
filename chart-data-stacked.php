<?
/* select distinct(status), count(*), date(datetime), campaignid from number where campaignid=74 group by date(datetime), status; */
error_reporting(0);
//echo "<pre>";
include 'ofc-library/open-flash-chart.php';
require_once "admin/db_config.php";
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
$id = $_GET[campaignid];
//$result = mysql_query("select distinct(status), count(*), date(datetime), campaignid from SineDialer.number where date(datetime) = date(NOW())and  status !='new' and status != 'unknown' group by date(datetime), status");
$result = mysql_query("select distinct(status), count(*), date(datetime), campaignid from SineDialer.number where campaignid=$_GET[campaignid] and status !='new' and status != 'unknown' group by date(datetime), status");
while ($row = mysql_fetch_assoc($result)) {
    $bar[$row['date(datetime)']][$row['status']] = $row['count(*)'];
}
$title = new title( 'Call Responses' );
$title->set_style( "{font-size: 20px; color: #F24062; text-align: center;}" );

$bar_stack = new bar_stack();

// set a cycle of 3 colours:
$colors = array('#0000FF','#888888','#00FF00','#FF0000','#004444', '#FF0000', '#000000','#666600') ;
//$bar_stack->set_colours( $colors );
$max = 0;
foreach ($bar as $key=>$value) {
//    echo "Key: $key Value:";
//    print_r($value);
    unset($counts);
    unset($count_total);
    $labels[] = $key;
    $i = 0;
    foreach ($value as $status=>$count) {
        if (!in_array($status,$status_names)) {
            $statuses[] = new bar_stack_key($colors[$i], $status, 13);
            $status_names[] = $status;
        }
//        $counts[] = intval($count);

	$stack_val = new bar_stack_value(intval($count), $colors[$i]);
	    $i++;
	$stack_val->text = $status;
	$stack_val->tip = $status." ($count)";
//	$stack_val->text = $key.":".$status." ($count)";
	$counts[] = $stack_val;
        $count_total+= $count;
    }
    if ($count_total > $max) {
        $max = $count_total;
    }
    $bar_stack->append_stack( $counts );
}
//$bar_stack->set_keys($statuses);




$y = new y_axis();
$y->set_range( 0, $max, 2 );

$x = new x_axis();
$x->set_labels_from_array( $labels );
//$x->set_labels_from_array( array( 'Winter', 'Spring', 'Summer', 'Autmn' ) );

$tooltip = new tooltip();
$tooltip->set_hover();
$tooltip->text = '#key#: #val#<br>Total: #total#' ;
//$bar_stack->set_tooltip( '#key#: #val#<br>Total: #total#' );

$chart = new open_flash_chart();
$chart->set_title( $title );
$chart->add_element( $bar_stack );
$chart->set_x_axis( $x );
$chart->add_y_axis( $y );
$chart->set_tooltip( $tooltip );

echo $chart->toPrettyString();
?>

