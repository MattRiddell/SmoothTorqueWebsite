<?
function rgbhex($red,$green,$blue) {
 $red = dechex($red);
 if (strlen($red<2)){
    //$red="0".$red;
 }
 $green = dechex($green);
 if (strlen($green<2)){
    $green="0".$green;
 }
 $blue = dechex($blue);
  if (strlen($blue<2)){
    $blue="0".$blue;
 }
 return "#".strtoupper($red.$green.$blue);
}

$link = mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);

if (isset($_GET[campaigngroupid])){
$campaigngroupid=$_GET[campaigngroupid];
}
//$sql = 'SELECT * FROM number WHERE campaignid='.$_GET[id]." LIMIT 50";
//if (isset($id)){
//$_POST[campaignid]=$id;
//$_GET[id]=$id;
//}
$sql = 'SELECT * FROM queue left join campaign ON queue.campaignid=campaign.id WHERE campaign.groupid='.$campaigngroupid;
//echo $sql;
$result=mysql_query($sql, $link) or die (mysql_error());;
?>
<table align="center" border="0" cellpadding="2" cellspacing="0">
<TR>
<TD CLASS="thead">
Name
</TD>
<TD CLASS="thead">
Details
</TD>
<TD CLASS="thead">
Campaign
</TD>
<TD CLASS="thead">
Start
</TD>
<TD CLASS="thead">
End
</TD>
<?/*<TD CLASS="thead">

</TD>
*/?>
<TD CLASS="thead">
Agents
</TD>
<TD CLASS="thead">
Progress
</TD>

</TD>
<TD CLASS="thead">
%

</TD>
<TD CLASS="thead">
Status
</TD>

<TD  CLASS="thead">
</TD>
</TR>

<?
while ($row = mysql_fetch_assoc($result)) {
$sql2= 'SELECT name from campaign where id='.$row[campaignID];
$result2=mysql_query($sql2, $link) or die (mysql_error());;
$name=mysql_result($result2,0,'name');

if ($toggle){
    $toggle=false;
    $class=" class=\"tborder2\"";
} else {
    $toggle=true;
    $class=" class=\"tborderx\"";
}
$perc=round(($row[flags]/$row[maxcalls])*100);
if ($perc>100){
    $perc=100;
}
if ($row[flags]>0){
    $class="  bgcolor=".rgbhex(200,205+$perc/2,255-$perc/1.2);

}
?>
<TR <?echo $class;?>>
<TD>
<?
if (strlen($row[queuename])>14){
?>
<A HREF="editschedule.php?id=<?echo $row[queueID]?>"><?echo trim(substr($row[queuename],0,15))."...";?></A>
<?
} else {
?>
<A HREF="editschedule.php?id=<?echo $row[queueID]?>"><?echo $row[queuename];?></A>

<?

}
?>

</TD>
<TD>
<?
if (strlen($row[details])>14){

echo trim(substr($row[details],0,15))."...";
} else{
echo $row[details];
}
?>
</TD>
<TD>
<?
if (strlen($name)>9){
?>
<A HREF="editcampaign.php?id=<?echo $row[campaignID]?>"><?echo trim(substr($name,0,10))."...";?></A>

<?
} else {
?>
<A HREF="editcampaign.php?id=<?echo $row[campaignID]?>"><?echo $name;?></A>

<?
}?>

</TD>
<TD>
<?echo $row[startdate]." ".$row[starttime];?>
</TD>
<TD>
<?echo $row[enddate]." ".$row[endtime];?>
</TD>
<?/*<TD>
<?
$time=$row[timespent];
$hours=floor($time/60/60);
if ($hours>0){
    $time=$time-($hours*60*60);
}
$minutes=floor($time/60);
if ($minutes>0){
    $time=$time-($minutes*60);
}
$seconds=$time;
echo "$hours:$minutes:$seconds";
?>
</TD>
*/?>
<TD><B>
<?
if ($row[status]==1|$row[status]==101){
echo $row[maxcalls];
}
?>
</B></TD>
<TD><B>
<?
if ($row[status]==101){
echo $row[progress];
}
?>
</B></TD>

<TD>
<?
//echo $row[flags]."/".$row[maxcalls];
//echo $perc;
if ($row[flags]>0){
?>
<img src="images/percentImage.png" alt="<?echo $perc;?>%" class="percentImage" style="background-position: -<?echo 119-($perc*1.2); ?>px 0pt;" />
<?}?>
</TD>
<TD>
<?
switch($row[status]){
case 1:
    echo "Queued";
    break;
case 2:
    echo "Stop - Not Run";
    break;
case 101:
    echo "Processed <A HREF=\"schedule.php?status=1&campaignid=".$_POST[campaignid]."&queueID=".$row[queueID]."\"><IMG SRC=\"images/reset.gif\" BORDER=\"0\" ALT=\"RESET\"></A>";
    break;
case 102:
    echo "Processed <A HREF=\"schedule.php?status=2&campaignid=".$_POST[campaignid]."&queueID=".$row[queueID]."\"><IMG SRC=\"images/reset.gif\" BORDER=\"0\" ALT=\"RESET\"></A>";
    break;
}
?>
</TD>

<TD>
<A HREF="deleteschedule.php?id=<?echo $row[queueID]?>"><IMG SRC="images/cross.gif" BORDER="0" ALT="DELETE"></A>
</TD>
</TR>
<?}?>
 </TABLE>
