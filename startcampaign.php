<?
require "header.php";
require "header_campaign.php";
$out=_get_browser();
//print_r($_POST);
                   sssaaa
?>
<br /><br /><br /><br />
<center>
<table background="/images/sdbox.png" width="300" height="200" class="dragme">
<tr>
<td>
</td>
<td width="260">
<?
/*Maximum Concurrent Calls:<b> <?echo $_POST[agents];?></b><br />
Call Center Phone Number:<b> <?echo $_POST[did];?></b><br />
Type of campaign:<b>

if ($_POST[context]==0) {
    echo "Load Simulation";
} else if ($_POST[context]==1) {
    echo "Answer Machine Only";
} else if ($_POST[context]==2) {
    echo "Immediate Live";
} else if ($_POST[context]==3) {
    echo "Press 1 Live";
} else if ($_POST[context]==4) {
    echo "Immediate Live and Answer Machine";
} else if ($_POST[context]==5) {
    echo "Press 1 Live and Answer Machine";
}
*/
$sql3="select * from trunk where current = 1";
$resultx=mysql_query($sql3, $link) or die (mysql_error());;
$dialstring=mysql_result($resultx,0,'dialstring');

$sql1="delete from queue where campaignid=".$_GET[id];
$sql2="INSERT INTO queue (campaignid,queuename,status,details,flags,transferclid,
    starttime,endtime,startdate,enddate,did,clid,context,maxcalls,maxchans,maxretries
    ,retrytime,waittime,trunk,astqueuename) VALUES
    ('$_GET[id]','autostart-$_GET[id]','1','No details','0','$_GET[trclid]',
    '00:00','23:59','2005-01-01','2020-01-01','$_GET[did]','$_GET[clid]',
    '$_GET[context]','$_GET[agents]','500','0'
    ,'0','30','".$dialstring."','$_GET[astqueuename]') ";
//    echo $sql2;
//exit(0);
//echo $sql2;
$resultx=mysql_query($sql1, $link) or die (mysql_error());;
$resultx=mysql_query($sql2, $link) or die (mysql_error());;


?>
</b>
<br />
<br />
<?
if ($out[browser]=="MSIE"){
?>
<script type="text/javascript" src="/ajax/jquery.js"></script>
        <script type="text/javascript">
        $(function(){ // jquery onload
                window.setInterval(function(){ // setInterval code
                        $('#ajaxDiv').loadIfModified('campaignstatus.php?id=<?echo $_GET[id];?>');
                },2000);
        });

        </script>
 <?} else {?>
<script type="text/javascript" src="/ajax/jquery.js"></script>
        <script type="text/javascript">
        $(function(){ // jquery onload
                window.setInterval(function(){ // setInterval code
                        $('#ajaxDiv').load('campaignstatus.php?id=<?echo $_GET[id];?>');
                },2000);
        });

        </script>

<?}?>
<div id="ajaxDiv">
<?
$id=$_GET[id];include "campaignstatus.php";?>
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
