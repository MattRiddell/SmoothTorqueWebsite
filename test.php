<?
require "header.php";
require "header_campaign.php";
include "admin/db_config.php";
mysql_select_db("SineDialer", $link);
$id=$_GET[id];
$debug=$_GET[debug];

$result = mysql_query("SELECT * FROM SineDialer.config WHERE parameter = 'm_c_stats'");
if (!(mysql_num_rows($result) > 0)) {


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
}
?><script>
var webcamimage;
var imgBase="/graph.php?id=<?echo $id;?>&debug=<?echo $debug;?>&x="
var c = 0;
function count()
{
 loading = document.getElementById("loading");
 loading.style.visibility = 'visible';
 webcamimage.src=imgBase + (++c) + '&rand='+Math.random();
}
function init()
{
 webcamimage = document.getElementById("webcamimage");
 if( webcamimage )
 {
  setInterval("count()",8000);
 }
 count();
}
function hide_image()
{
    loading = document.getElementById("loading");
    loading.style.visibility = 'hidden';
}
window.onload = init;
</script>
<a href="stopcampaign.php?id=<?echo $id;?>"><img src="/images/control_stop_blue.png"  border="0"> Stop This Campaign</a>&nbsp;
<a href="report.php?type=today&id=<?echo $id;?>"><img src="/images/chart_pie.png"  border="0"> View Number Stats</a><br />
<div id="loading" ><img src="/images/ajax-loader.gif"></div>
<img src="graph.php?id=<?echo $id;?>&debug=<?echo $debug;?>" name="image" id="webcamimage" border="0" onload="hide_image();"><br />
<?
require "footer.php";
?>
