<?
/* select distinct(status), count(*), date(datetime), campaignid from number where campaignid=74 group by date(datetime), status; */
error_reporting(0);

include 'ofc-library/open-flash-chart.php';
require_once "admin/db_config.php";
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

$title = new title( 'Call Responses' );
$title->set_style( "{font-size: 20px; color: #F24062; text-align: center;}" );

$bar_stack = new bar_stack();

// set a cycle of 3 colours:
$bar_stack->set_colours( array( '#C4D318', '#50284A', '#7D7B6A' ) );

// add 3 bars:
$bar_stack->append_stack( array( 2.5, 5, 2.5 ) );

// add 4 bars, the fourth will be the same colour as the first:
$bar_stack->append_stack( array( 2.5, 5, 1.25, 1.25 ) );


$bar_stack->append_stack( array( 5, new bar_stack_value(5, '#ff0000') ) );
$bar_stack->append_stack( array( 2, 2, 2, 2, new bar_stack_value(2, '#ff00ff') ) );

$bar_stack->set_keys(
    array(
        new bar_stack_key( '#C4D318', 'Kiting', 13 ),
        new bar_stack_key( '#50284A', 'Work', 13 ),
        new bar_stack_key( '#7D7B6A', 'Drinking', 13 ),
        new bar_stack_key( '#ff0000', 'XXX', 13 ),
        new bar_stack_key( '#ff00ff', 'What rhymes with purple? Nurple?', 13 ),
        )
    );
$bar_stack->set_tooltip( 'X label [#x_label#], Value [#val#]<br>Total [#total#]' );



$y = new y_axis();
$y->set_range( 0, 14, 2 );

$x = new x_axis();
$x->set_labels_from_array( array( 'Winter', 'Spring', 'Summer', 'Autmn' ) );

$tooltip = new tooltip();
$tooltip->set_hover();

$chart = new open_flash_chart();
$chart->set_title( $title );
$chart->add_element( $bar_stack );
$chart->set_x_axis( $x );
$chart->add_y_axis( $y );
$chart->set_tooltip( $tooltip );

echo $chart->toPrettyString();
?>
