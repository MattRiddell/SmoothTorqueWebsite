<?
//echo date('H:i:s');
    //exit(0);
if (isset($_GET[campaigngroupid])){
    $campaigngroupid = $_GET[campaigngroupid];
    //echo "refresh".date('s');
    //exit(0);
}
if (isset($_POST[id])){
    $id = $_POST[id];
}
/*$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');
*/
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);
$sql = 'SELECT value FROM config WHERE parameter=\'backend\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$backend = mysql_result($result,0,'value');

$sql = 'SELECT * FROM campaign WHERE groupid='.$campaigngroupid;
$result=mysql_query($sql, $link) or die (mysql_error());;
//$campaigngroupid=mysql_result($result,0,'campaigngroupid');
if (mysql_num_rows($result)==0){
?>
<br /><br />
<center>
<table background="/images/sdbox.png" width="300" height="200">
<tr>
<td>
</td>
<td width="260">
<b>You don't have any campaigns created.</b><br />
<br />
A campaign is a collection of phone numbers you would like to call.<br />
<br />
To create your first campaign, please click the Add Campaign button above.<br />
</td>
<td>
</td></tr>
</table>
</center>

<?
exit(0);
}

$user = $_COOKIE[user];
?>
<table class="" align="center" border="0" cellpadding="2" cellspacing="0">
<TR>
<td style="background-image: url(/images/clb.gif);" width=2></td>

<TD CLASS="thead">
Name
</TD>
<TD CLASS="thead">
Description
</TD>
<?/*<TD CLASS="thead">
Human
</TD>
<TD CLASS="thead">
Answer Machine
</TD>
<TD CLASS="thead">
Transfer
</TD>
*/?>
<TD CLASS="thead">
Used
</TD>
<TD CLASS="thead">

</TD>
<TD CLASS="thead">

</TD>
<TD CLASS="thead">

</TD>
<TD CLASS="thead">
Percentage Busy
</TD>
<td style="background-image: url(/images/crb.gif);" width=2></td>
</TR>
<?
while ($row = mysql_fetch_assoc($result)) {
if ($toggle){
$toggle=false;
$class=" class=\"tborder2\"";
} else {
$toggle=true;
$class=" class=\"tborderx\"";
}

?>
<TR <?echo $class;?>>
<td></td>
<TD>
<?
if (strlen($row[name])<15){
echo "<A HREF=\"editcampaign.php?id=".$row[id]."\">".$row[name]."</A>";
} else {
echo "<A HREF=\"editcampaign.php?id=".$row[id]."\">".trim(substr($row[name],0,15))."...</A>";
}
?>
</TD>
<TD>
<?
if (strlen($row[description])<25){
echo $row[description];
} else {
echo trim(substr($row[description],0,25))."...";
}
?>
</TD>
<?/*
<TD>
<?echo $row[messageid];?>
</TD>
<TD>
<?echo $row[messageid2];?>
</TD>
<TD>
<?echo $row[messageid3];?>
</TD>
*/?>
<?
$sql = 'SELECT count(*) from number where campaignid='.$row[id].' and status="dialed"';
$result2=mysql_query($sql, $link) or die (mysql_error());;
$count=mysql_result($result2,0,'count(*)');
$sql = 'SELECT count(*) from number where campaignid='.$row[id].' and status="dialing"';
$result2=mysql_query($sql, $link) or die (mysql_error());;
$countx=mysql_result($result2,0,'count(*)');
$sql = 'SELECT count(*) from number where campaignid='.$row[id];
$result2=mysql_query($sql, $link) or die (mysql_error());;
$count2=mysql_result($result2,0,'count(*)');
$sql = 'SELECT status, flags, maxcalls, progress from queue where campaignid='.$row[id];
$resultx=mysql_query($sql, $link) or die (mysql_error());;
$status=mysql_result($resultx,0,'status');
$flags=mysql_result($resultx,0,'flags');
$maxcalls=mysql_result($resultx,0,'maxcalls');
$progress=mysql_result($resultx,0,'progress');

?>
<TD>
<?
if ($progress!=0){
/*
progress = done in this run
count    = dailed in db
count2   = all numbers
countx   = dialing
*/
    echo "<b>Current: ".$progress."</b> (Done: $count/$count2) (Remaining:".($count2-$count-$dialing).")(Dialing: $countx)";
} else {
	if ($count2>0){
    echo "".$count."/".$count2."($countx)";
}
}
?>
</TD>


<TD>
<?
if ($user!="demo"){
echo "<A HREF=\"deletecampaign.php?id=".$row[id]."\"><IMG SRC=\"/images/delete.png\" BORDER=\"0\"></A>";
} else {
echo "<A HREF=\"#\"><IMG SRC=\"/images/delete.png\" BORDER=\"0\"></A>";
}
?>
</TD>
<TD>

<?

//echo $flags;
if ($maxcalls>0){
$perc=round(($flags/$maxcalls)*100);
} else {
$perc=0;
}
if ($perc>100){
    $perc=100;
}
//echo $perc;
/* status can be one of:
1 awaiting start
101 started
2 awaiting stop
102 stopped
*/
if ($status==101){
?>
<IMG SRC="/images/control_play.png" BORDER="0">
</TD>
<td>
<?if ($user!="demo"){?>
<a href="stopcampaign.php?id=<?echo $row[id];?>"><img src="/images/control_stop_blue.png" border="0"></a>
<?} else {?>
<a href="#"><img src="/images/control_stop_blue.png" border="0"></a>
<?
}
} else {

?>
<?if ($user!="demo"){?>
<a href="#" onclick="displayMessage('includes/livestart.php?id=<?echo $row[id];?>');return false"><IMG SRC="/images/control_play_blue.png" BORDER="0"></a><br>
<?} else {?>
<a href="#"><IMG SRC="/images/control_play_blue.png" BORDER="0"></a><br>
<?}?>
</TD>
<td>
<img src="/images/control_stop.png" border="0">
<?
}
?>
</td>
<td>
<?if ($backend == 0) {?>
<a href="chart.php?id=<?echo $row[id];?>" target="_blank" class="abcd"><img src="/images/chart_curve.png" border="0"></a>&nbsp;
<?}?>
<img src="/images/percentImage.png" alt="<?echo
$perc;?>%"
class="percentImage"
style="background-position: -<?echo 119-($perc*1.2); ?>px 0pt;" border="0" />

</td>
<td></td>
</TR>

<?
}
?>

</TABLE>
