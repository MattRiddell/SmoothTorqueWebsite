<?
include "admin/db_config.php";
mysql_select_db("SineDialer", $link);
if (isset($_GET[id])){
	$_GET = array_map(mysql_real_escape_string,$_GET);
    $id=$_GET[id];
}
$sql = 'SELECT status from queue where campaignid='.($id);
$resultx=mysql_query($sql, $link) or die (mysql_error());;
$status=mysql_result($resultx,0,'status');
if (mysql_num_rows($resultx)==0) {
       echo "Please Wait<br /> <br />Your campaign is being started<BR><BR><img src=\"/images/ajax-loader.gif\"><br /><br />";
} else {
    if ($status == -1) {
	echo "<b>The campaign has both started and finished <img src=\"/images/tick.gif\" border=\"0\" onLoad=\"window.location = 'campaigns.php';\"></b><br /><br />";
?>
    <script type="javascript">
    function delayer(){

    }
    setTimeout('delayer()', 5000);
    </script>
    <?
    }
    if ($status==101||$status==2||$status==102){
        echo "<b>Campaign Started <img src=\"/images/tick.gif\" border=\"0\" onLoad=\"window.location = 'test.php?id=".$id."';\"></b><br /><br />";
        ?>
    <script type="javascript">
    function delayer(){

    }
    setTimeout('delayer()', 1000);
    </script>
    <?
    } else {
       echo "Please Wait<br /> <br />Your campaign is being started<BR><BR><img src=\"/images/ajax-loader.gif\"><br /><br />";
    }
}
?>
