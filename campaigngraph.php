<?
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);
if (isset($_GET[queueID])){
	$_GET = array_map(mysql_real_escape_string,$_GET);
    $sql = 'update queue set status='.($_GET[status]).' where queueID='.($_GET[queueID]);
    $result=mysql_query($sql, $link) or die (mysql_error());;
    header("Location: schedule.php?campaignid=".$_GET[campaignid]);
}

require "header.php";
$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');
require "header_campaign.php";
if (isset($_GET[campaignid])){
$_GET = array_map(mysql_real_escape_string,$_GET);
$_POST[campaignid]=($_GET[campaignid]);
}
$out=_get_browser();
if ($out[browser]=="MSIE"){
?>
 <?
flush();
?>
<script type="text/javascript" src="ajax/jquery.js"></script>
<script type="text/javascript">
        $(function(){ // jquery onload
                window.setInterval(function(){
                    $('#ajaxDiv').loadIfModified('inset.php?id=<?echo $_GET[id];?>');  // jquery ajax load into div
                },3000);
        });

</script>
<?} else {?>
<script type="text/javascript" src="ajax/jquery.js"></script>
<script type="text/javascript">

        $(function(){ // jquery onload
                window.setInterval(
                function(){
                    $('#ajaxDiv').load('/inset.php?id=<?echo $_GET[id]."&x=".date('l dS \of F Y h:i:s A');?>');  // jquery ajax load into div
                }
                ,10000);
        });
</script>
<div id="ajaxDiv">
<?

?>

</div>
<?}?>
<?

require "footer.php";
?>
