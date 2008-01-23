<?
//echo date().date('H:i:s');
/*    flush();
exit(0);*/

/*$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);
*/
if (isset($_GET[campaigngroupid])){
    $campaigngroupid = ($_GET[campaigngroupid]);
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
//$backend = mysql_result($result,0,'value');
$row = mysql_fetch_assoc($result);
$backend=$row[value];

$level=$_COOKIE[level];
if ($level==sha1("level100")) {
    $sql = 'SELECT * FROM campaign';
} else {
    $sql = 'SELECT * FROM campaign WHERE groupid='.$campaigngroupid;
}
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
Numbers Left
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

$row = array_map(stripslashes,$row);

if ($toggle){
$toggle=false;
$class=" class=\"tborder2\"  onmouseover=\"style.backgroundColor='#84DFC1';\" onmouseout=\"style.backgroundColor='#f8f8f8'\"   ";
} else {
$toggle=true;
$class=" class=\"tborderx\"  onmouseover=\"style.backgroundColor='#84DFC1';\" onmouseout=\"style.backgroundColor='#f0f0f0'\" ";
}

?>
<TR <?echo $class;?>>
<td></td>
<TD>
<?
if (strlen($row[name])<15){
echo "<A title=\"Edit this campaign\" HREF=\"editcampaign.php?id=".$row[id]."\"><img src=\"/images/pencil.png\" border=\"0\" align=\"right\" title=\"Edit This Campaign\">".$row[name]."</A>";
} else {
echo "<A title=\"Edit this campaign\" HREF=\"editcampaign.php?id=".$row[id]."\"><img src=\"/images/pencil.png\" border=\"0\" align=\"right\" title=\"Edit This Campaign\">".trim(substr($row[name],0,15))."...</A>";
}
?>
</TD>
<TD>
<?
$max_str_len = 25;

iF (strlen($row[description])<$max_str_len){
echo $row[description];
} else {
echo trim(substr($row[description],0,$max_str_len))."...";
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
$sql = 'SELECT count(*) as count from number where campaignid='.$row[id].' and status="dialed"';
$result2=mysql_query($sql, $link) or die (mysql_error());;
$row2 = mysql_fetch_assoc($result2);
$count=$row2[count];

$sql = 'SELECT count(*) from number where campaignid='.$row[id].' and status="dialing"';
$result2=mysql_query($sql, $link) or die (mysql_error());;
$countx=mysql_result($result2,0,'count(*)');

$sql = 'SELECT count(*) from number where campaignid='.$row[id];
$result2=mysql_query($sql, $link) or die (mysql_error());;
$count2=mysql_result($result2,0,'count(*)');

$sql = 'SELECT status, flags, maxcalls, progress from queue where campaignid='.$row[id];
$resultx=mysql_query($sql, $link) or die (mysql_error());;
$rowx = mysql_fetch_assoc($resultx);

$status=$rowx[status];
$flags=$rowx[flags];
$maxcalls=$rowx[maxcalls];
$progress=$rowx[progress];

?>
<TD>
<?
if ($progress>0){
/*
progress = done in this run
count    = dailed in db
count2   = all numbers
countx   = dialing
*/
    echo "<b>Current: ".$progress."</b> (Done: $count/$count2) (Remaining:".($count2-$count-$dialing).")(Dialing: $countx)";
} else {
    //echo ((($count/$count2)*100)*1.2);
	if ($count2>0){
        if ($countx>0){ // Some are dialing
//
            ?>
            <img src="/images/percentImage.png" title="<?
            echo "".($count2-$count)."/".$count2." Numbers Remaining ($countx being dialed)";?>"
            class="percentImage"
            style="background-position: -<?echo ((($count/$count2)*100)*1.2)-1; ?>px 0pt;" border="0" />
            <?
        } else {
//            echo "".$count."/".$count2;
            ?>
            <img src="/images/percentImage.png" title="<?
            echo "".($count2-$count)."/".$count2." Numbers Remaining";?>"
            class="percentImage"
            style="background-position: -<?echo ((($count/$count2)*100)*1.2)-1; ?>px 0pt;" border="0" />
            <?
        }
    } else {
            ?>
            <img src="/images/percentImage.png" title="<?
            echo "".($count2-$count)."/".$count2." Numbers Remaining";?>"
            class="percentImage"
            style="background-position: -<?echo ((($count/$count2)*100)*1.2)-1; ?>px 0pt;" border="0" />
            <?
    }

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
<a title="Stop running this campaign" href="stopcampaign.php?id=<?echo $row[id];?>"><img src="/images/control_stop_blue.png" border="0"></a>
<?} else {?>
<a href="#" title="Stop campaign (Not running)"><img src="/images/control_stop_blue.png" border="0"></a>
<?
}
} else {

?>
<?if ($user!="demo"){?>
<a title="Start running this campaign" href="startcampaign.php?id=<?echo $row[id];?>&astqueuename=<?echo $row[astqueuename];?>&clid=<?echo $row[clid];?>&trclid=<?echo $row[trclid];?>&agents=<?echo $row[maxagents];?>&did=<?echo $row[did];?>&context=<?echo $row[context];?>">
<IMG SRC="/images/control_play_blue.png" BORDER="0"></a><br>
<?} else {?>
<a href="#" title="Start campaign (Already started)"><IMG SRC="/images/control_play_blue.png" BORDER="0"></a><br>
<?}?>
</TD>
<td>
<img src="/images/control_stop.png" border="0" title="Stop running campaign">
<?
}
?>
</td>
<TD>
<?if ($backend == 0) {?>
<a title="View the graph for this campaign" href="test.php?id=<?echo $row[id];?>" class="abcd"><img src="/images/chart_curve.png" border="0"></a>&nbsp;
<?}?>
<?
if ($user!="demo"){
echo "<A title=\"Delete this campaign\" HREF=\"deletecampaign.php?id=".$row[id]."\"><IMG SRC=\"/images/delete.png\" BORDER=\"0\"></A>";
} else {
echo "<A title=\"Delete this campaign\" HREF=\"#\"><IMG SRC=\"/images/delete.png\" BORDER=\"0\"></A>";
}
?>
</TD>

<td>

<img src="/images/percentImage.png" title="<?echo
$perc;?>% of staff are busy"
class="percentImage"
style="background-position: -<?echo 119-($perc*1.2); ?>px 0pt;" border="0" />

</td>

<td></td>
</TR>

<?
}
?>

</TABLE>
