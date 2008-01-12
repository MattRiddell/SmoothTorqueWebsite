<?
require "header.php";
require "header_campaign.php";
$out=_get_browser();
//print_r($_POST);

$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);

?>
<br /><br /><br /><br />
<center>
<table background="/images/sdbox.png" width="300" height="200" class="dragme2">
<tr>
<td>
</td>
<td width="260">

<?
$sql1="delete from queue where campaignid=".$_GET[id];
$sql2="INSERT INTO queue (campaignid,queuename,status,details,flags,transferclid,
    starttime,endtime,startdate,enddate,did,clid,context,maxcalls,maxchans,maxretries
    ,retrytime,waittime) VALUES
    ('$_GET[id]','autostart-$_POST[id]','2','No details','0','0',
    '00:00:00','23:59:00','2005-01-01','2020-01-01','$_POST[did]','000',
    '$_POST[context]','$_POST[agents]','500','0'
    ,'0','30') ";
$resultx=mysql_query($sql1, $link) or die (mysql_error());;
$resultx=mysql_query($sql2, $link) or die (mysql_error());;


?>
</b>
<?
if ($out[browser]=="MSIE"){
?>
<script type="text/javascript" src="/ajax/jquery.js"></script>
        <script type="text/javascript">
        $(function(){ // jquery onload
                window.setInterval(function(){ // setInterval code
                        $('#ajaxDiv').loadIfModified('campaignstatus2.php?id=<?echo $_GET[id];?>');
                },2000);
        });

        </script>
 <?} else {?>
<script type="text/javascript" src="/ajax/jquery.js"></script>
        <script type="text/javascript">
        $(function(){ // jquery onload
                window.setInterval(function(){ // setInterval code
                        $('#ajaxDiv').load('campaignstatus2.php?id=<?echo $_GET[id];?>');
                },2000);
        });

        </script>

<?}?>
<div id="ajaxDiv">
<?
$id=$_GET[id];include "campaignstatus2.php";?>
</div>

<br />
</td>
<td>
</td></tr>
</table>
</center>
<?
require "footer.php";
?>
