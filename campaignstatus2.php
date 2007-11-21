<?
include "admin/db_config.php";
mysql_select_db("SineDialer", $link);
if (isset($_GET[id])){
    $id=$_GET[id];
}
$sql = 'SELECT status from queue where campaignid='.$id;
$resultx=mysql_query($sql, $link) or die (mysql_error());;
$status=mysql_result($resultx,0,'status');
if (mysql_num_rows($resultx)==0) {
         echo "Please Wait<br /><br />Your campaign is being stopped<BR><BR><img src=\"/images/ajax-loader.gif\"><br /><br />";
} else {
    if ($status==102){
        echo "<b>Campaign Stopped <img src=\"/images/tick.gif\" border=\"0\"></b><br /><br />";
        ?>
    <script language="javascript">
    function delayer(){
    window.location = "campaigns.php"
    }
    setTimeout('delayer()', 1000);
    </script>
    <?
    } else {
        echo "Please Wait<br /><br />Your campaign is being stopped<BR><BR><img src=\"/images/ajax-loader.gif\"><br /><br />";
    }
}
?>
