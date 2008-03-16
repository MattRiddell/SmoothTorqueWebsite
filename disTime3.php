<?
if (isset($_GET[campaigngroupid])){
    $campaigngroupid = ($_GET[campaigngroupid]);
}
if (isset($_POST[id])){
    $id = $_POST[id];
}
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);
$sql = 'SELECT value FROM config WHERE parameter=\'backend\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$row = mysql_fetch_assoc($result);
$backend=$row[value];
$level=$_COOKIE[level];
if ($level==sha1("level100") && $_GET[type]=="all") {
    $sql = 'SELECT * FROM campaign order by name';
} else {
    $sql = 'SELECT * FROM campaign WHERE groupid='.$campaigngroupid.' order by name';
}
$result=mysql_query($sql, $link) or die (mysql_error());;
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
<?
$sql = 'SELECT count(*) from number where campaignid='.$row[id].' and status="new"';
$result2=mysql_query($sql, $link) or die (mysql_error());;
$new=mysql_result($result2,0,'count(*)');

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
            ?>
            <img src="/images/percentImage.png" title="<?
            echo "Remaining: $new/$count2\"";?>"
            class="percentImage"
            style="background-position: -<?echo ((100-(($new/$count2)*100))*1.2)-1; ?>px 0pt;" border="0" />
<?
} else {
            if ($count2 > 0) {
            ?>
            <img src="/images/percentImage.png" title="<?
            echo "Remaining: $new/$count2\"";?>"
            class="percentImage2"
            style="background-position: -<?echo ((100-(($new/$count2)*100))*1.2)-1; ?>px 0pt;" border="0" />
<?
            } else {
            ?>
            <img src="/images/percentImage.png" title="<?
            echo "Remaining: $new/$count2\"";?>"
            class="percentImage2"
            style="background-position: -<?echo ((100-((0)*100))*1.2)-1; ?>px 0pt;" border="0" />
<?
            }
}
?>
</TD>


<TD>

<?
if ($maxcalls>0){
$perc=round(($flags/$maxcalls)*100);
} else {
$perc=0;
}
if ($perc>100){
    $perc=100;
}
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
<?if ($backend == 0) {?>
<a title="View the report for this campaign" href="report.php?type=today&id=<?echo $row[id];?>" class="abcd"><img src="/images/chart_pie.png" border="0"></a>&nbsp;
<?}?>
<a title="Recycle Numbers" href="recycle.php?id=<?echo $row[id];?>" class="abcd"><img src="/images/arrow_refresh.png" border="0"></a>&nbsp;
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
<?

?>
