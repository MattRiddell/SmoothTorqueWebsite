<?
$override_directory = dirname(__FILE__)."/../../";

include $override_directory.'/ofc-library/open-flash-chart.php';
require_once $override_directory."/admin/db_config.php";
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
 
$result = mysql_query("select * from campaign") or die (mysql_error());
$month_names = array("January","February","March","April","May","June","July","August","September","October","November","December");

if (!mysql_num_rows($result) > 0) {
	/* This customer has no campaigns */
	echo "No campaigns for $campaigngroupid ($_COOKIE[user])";
} else {
	/* This customer has campaigns - iterate through them */
	while ($row = mysql_fetch_assoc($result)) {
		$result_campaign = mysql_query("select distinct(status), count(*), date(datetime) from SineDialer.number where status !='new' and status != 'unknown' group by date(datetime), status order by date(datetime) desc");
		while ($row_campaign = mysql_fetch_assoc($result_campaign)) {
			$date = $row_campaign['date(datetime)'];;
			
			$bar2[$date][$row_campaign['status']] += $row_campaign['count(*)'];
		}
	}
	//print_pre($results);
}
foreach ($bar2 as $key=>$value) {
	$keys[] = $key;
}
sort($keys);
foreach ($keys as $key) {
	$bar[$key] = $bar2[$key];
}
//asort($bar);

//$result = mysql_query("select distinct(status), count(*), date(datetime), campaignid from SineDialer.number where date(datetime) = date(NOW())and  status !='new' and status != 'unknown' group by date(datetime), status");
/*$result = mysql_query("select distinct(status), count(*), date(datetime), campaignid from SineDialer.number where campaignid=$_GET[campaignid] and status !='new' and status != 'unknown' group by date(datetime), status");
while ($row = mysql_fetch_assoc($result)) {
    $bar[$row['date(datetime)']][$row['status']] = $row['count(*)'];
}*/
$title = new title( 'Campaign Overview' );
$title->set_style( "{font-size: 20px; color: #444444; text-align: center;}" );

$bar_stack = new bar_stack();

// set a cycle of 3 colours:
$colors = array('#0000FF','#888888','#00FF00','#FF0000','#004444', '#FF0000', '#000000','#666600') ;
//$bar_stack->set_colours( $colors );
$max = 0;
$status_names = array();
foreach ($bar as $key=>$value) {
//	echo "<pre>";
//	print_r($bar);
//    echo "Key: $key Value:";
//    print_r($value);
    unset($counts);
    unset($count_total);
    $labels[] = $key;
//    $labels[] = date("M Y",strtotime($key));
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
$y->set_range( 0, $max, round($max/10) );

$x = new x_axis();
//$x->set_labels_from_array( $labels );

$x_lab = new x_axis_labels();
$x_lab->set_labels($labels);
$x_lab->rotate("90");
$x->set_labels($x_lab);

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
$chart->set_bg_colour('#ffffff');
echo $chart->toPrettyString();
?>

