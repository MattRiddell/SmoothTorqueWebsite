<?
require "header.php";
require "header_campaign.php";
$id=$_GET[id];
$debug=$_GET[debug];
if (file_exists("/tmp/Sm".$id.".campaignProperties") == false) {
if (isset($_GET[secondtime])) {
    echo "Your campaign has finished";
    } else {
    echo '<META HTTP-EQUIV=REFRESH CONTENT="3; URL=test.php?id='.$_GET['id'].'&secondtime=yes">';
    }
    exit(0);
}
if (file_exists("/tmp/Sm".$id.".console") == false) {
    echo "There is no information for this campaign (Console missing)";
    echo '<META HTTP-EQUIV=REFRESH CONTENT="3; URL=test.php?id='.$_GET['id'].'">';
    exit(0);
}
if (file_exists("/tmp/Sm".$id.".console2") == false) {
    echo "There is no information for this campaign (Console2 missing)";
    echo '<META HTTP-EQUIV=REFRESH CONTENT="3; URL=test.php?id='.$_GET['id'].'">';
    exit(0);
}
if (file_exists("/tmp/Sm".$id.".console3") == false) {
    echo "There is no information for this campaign (Console3 missing)";
    echo '<META HTTP-EQUIV=REFRESH CONTENT="3; URL=test.php?id='.$_GET['id'].'">';
    exit(0);
}
if ($id<1){
        exit(0);
}
?><script>
var webcamimage;
var imgBase="/graph.php?id=<?echo $id;?>&debug=<?echo $debug;?>&x="
var c = 0;
function count()
{
 alert("loading");
 webcamimage.src=imgBase + (++c);
 alert("loaded");
}
function init()
{
 webcamimage = document.getElementById("webcamimage");
 if( webcamimage )
 {
  setInterval("count()",6000);
 }
}
window.onload = init;
</script>
<a href="stopcampaign.php?id=<?echo $id;?>"><img src="/images/control_stop_blue.png"  border="0"> Stop This Campaign</a>&nbsp;
<a href="report.php?type=today&id=<?echo $id;?>"><img src="/images/chart_pie.png"  border="0"> View Number Stats</a><br />
<img src="graph.php?id=<?echo $id;?>&debug=<?echo $debug;?>" name="image" id="webcamimage" border="0"><br />
<?
require "footer.php";
?>
