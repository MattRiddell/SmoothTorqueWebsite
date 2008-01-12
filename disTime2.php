<?
    echo "refresh".date('s');
    exit(0);

$_GET = array_map(mysql_real_escape_string,$_GET);
$_POST = array_map(mysql_real_escape_string,$_POST);

if (isset($_GET[campaigngroupid])){
    $campaigngroupid = ($_GET[campaigngroupid]);
    echo "refresh".date('s');
    exit(0);
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
$sql = 'SELECT * FROM campaign WHERE groupid='.$campaigngroupid;
$result=mysql_query($sql, $link) or die (mysql_error());;
//$campaigngroupid=mysql_result($result,0,'campaigngroupid');
if (mysql_num_rows($result)==0){
?>
<br /><br />
<center>
<table background="/images/sdbox.png" width="300" height="200" class="dragme22">
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

?>

<table class="" align="center" border="0" cellpadding="2" cellspacing="0">
<TR>
<TD CLASS="thead">
Name
</TD>
<TD CLASS="thead">
Description
</TD>
<TD CLASS="thead">
Human
</TD>
<TD CLASS="thead">
Answer Machine
</TD>
<TD CLASS="thead">
Transfer
</TD>
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
<TD>
<?echo $row[messageid];?>
</TD>
<TD>
<?echo $row[messageid2];?>
</TD>
<TD>
<?echo $row[messageid3];?>
</TD>
<?
$sql = 'SELECT count(*) from number where campaignid='.$row[id].' and status="dialed"';
$result2=mysql_query($sql, $link) or die (mysql_error());;
$count=mysql_result($result2,0,'count(*)');
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
    echo "".$progress;
} else {
echo "".$count."/".$count2;
}
?>
</TD>


<TD>
<?echo "<A HREF=\"deletecampaign.php?id=".$row[id]."\"><IMG SRC=\"/images/cross.gif\" BORDER=\"0\"></A>";?>
</TD>
<TD>

<?

//echo $flags;
$perc=round(($flags/$maxcalls)*100);
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
<img src="/images/nothing.gif" border="0">
</TD>
<td>
<a href="stopcampaign.php?id=<?echo $row[id];?>"><img src="/images/stop.gif" border="0"></a>
<?
} else {
?>
<a href="#" onclick="displayMessage('includes/livestart.php?id=<?echo $row[id];?>');return false"><IMG SRC="/images/play.gif" BORDER="0"></a><br>
</TD>
<td>
<img src="/images/nothing.gif" border="0">
<?
}
?>
</td>
<td>
<img src="/images/percentImage.png" alt="<?echo $perc;?>%" class="percentImage" style="background-position: -<?echo 119-($perc*1.2); ?>px 0pt;" />

</td>
</TR>

<?
}
?>

</TABLE>
