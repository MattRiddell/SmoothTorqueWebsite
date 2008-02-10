<?
require "header.php";
require "header_campaign.php";
$id=$_GET[id];
$debug=$_GET[debug];
$type=$_GET[type];
if ($id<1){
        exit(0);
}
?><script>
var webcamimage;
var imgBase="/graph2.php?id=<?echo $id;?>&debug=<?echo $debug;?>&type=<?echo $type;?>&x="
var c = 0;
function count()
{
 webcamimage.src=imgBase + (++c);
}
function init()
{
 webcamimage = document.getElementById("webcamimage");
 if( webcamimage )
 {
  setInterval("count()",10000);
 }
}
window.onload = init;
</script>
<a href="test.php?id=<?echo $id;?>"><img src="/images/chart_curve.png"  border="0"> View Engine Status</a>&nbsp;
<?
if ($_GET[type]!="today") {
?>
<a href="report.php?type=today&id=<?echo $id;?>"><img src="/images/chart_pie.png"  border="0"> Today</a>&nbsp;
<?
} else {
    echo '<b><img src="/images/chart_pie.png"  border="0"> Today</b>&nbsp;';
}
if ($_GET[type]!="") {
?>
<a href="report.php?id=<?echo $id;?>"><img src="/images/chart_pie.png"  border="0"> All Time</a>&nbsp;
<?
} else {
    echo '<b><img src="/images/chart_pie.png"  border="0"> All Time</b>&nbsp;';
}
if ($_GET[type]!="yesterday") {
?>
<a href="report.php?type=yesterday&id=<?echo $id;?>"><img src="/images/chart_pie.png"  border="0"> Yesteday</a><br />
<?
} else {
    echo '<b><img src="/images/chart_pie.png"  border="0"> Yesterday</b>&nbsp;<br />';
}
?>
<img src="graph2.php?id=<?echo $id;?>&type=<?echo $type;?>&debug=<?echo $debug;?>" name="image" id="webcamimage" border="0"><br />
