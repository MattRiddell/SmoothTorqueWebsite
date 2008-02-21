<?
require "header.php";
require "header_campaign.php";
$id=$_GET[id];
$debug=$_GET[debug];
if ($id<1){
        exit(0);
}
?><script>
var webcamimage;
var imgBase="/graph.php?id=<?echo $id;?>&debug=<?echo $debug;?>&x="
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
  setInterval("count()",3000);
 }
}
window.onload = init;
</script>
<a href="stopcampaign.php?id=<?echo $id;?>"><img src="/images/control_stop_blue.png"  border="0"> Stop This Campaign</a>&nbsp;
<a href="report.php?type=today&id=<?echo $id;?>"><img src="/images/chart_pie.png"  border="0"> View Number Stats</a><br />
<img src="graph.php?id=<?echo $id;?>&debug=<?echo $debug;?>" name="image" id="webcamimage" border="0"><br />
