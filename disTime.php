<?
require_once "sql.php";
$SMDB2=new SmDB();

function rgbhex($red,$green,$blue) {
 $red = dechex($red);
 if (strlen($red<2)){
    $red="0".$red;
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
if (isset($_GET[campaigngroupid])){
    $campaigngroupid=$_GET[campaigngroupid];
}
$sql = 'SELECT * FROM queue left join campaign ON queue.campaignid=campaign.id WHERE campaign.groupid='.$campaigngroupid;
$row1=$SMDB2->executeQuery($sql);
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
$xyz=0;
while ($xyz<sizeof($row1)) {
$sql2= 'SELECT name from campaign where id='.$row1[$xyz][campaignID];
$row2=$SMDB2->executeQuery($sql2);
$name=$row2[0]['name'];
if ($toggle){
    $toggle=false;
    $class=" class=\"tborder2\"";
} else {
    $toggle=true;
    $class=" class=\"tborderx\"";
}
$perc=round(($row1[$xyz][flags]/$row1[$xyz][maxcalls])*100);
if ($perc>100){
    $perc=100;
}
if ($row1[$xyz][flags]>0){
    $class="  bgcolor=".rgbhex(200,205+$perc/2,255-$perc/1.2);

}
?>
<TR <?echo $class;?>>
<TD>
<?
if (strlen($row1[$xyz][queuename])>14){
?>
<A HREF="editschedule.php?id=<?echo $row1[$xyz][queueID]?>"><?echo trim(substr($row1[$xyz][queuename],0,15))."...";?></A>
<?
} else {
?>
<A HREF="editschedule.php?id=<?echo $row1[$xyz][queueID]?>"><?echo $row1[$xyz][queuename];?></A>

<?

}
?>

</TD>
<TD>
<?
if (strlen($row1[$xyz][details])>14){

echo trim(substr($row1[$xyz][details],0,15))."...";
} else{
echo $row1[$xyz][details];
}
?>
</TD>
<TD>
<?
if (strlen($name)>9){
?>
<A HREF="editcampaign.php?id=<?echo $row1[$xyz][campaignID]?>"><?echo trim(substr($name,0,10))."...";?></A>

<?
} else {
?>
<A HREF="editcampaign.php?id=<?echo $row1[$xyz][campaignID]?>"><?echo $name;?></A>

<?
}?>

</TD>
<TD>
<?echo $row1[$xyz][startdate]." ".$row1[$xyz][starttime];?>
</TD>
<TD>
<?echo $row1[$xyz][enddate]." ".$row1[$xyz][endtime];?>
</TD>
<TD><B>
<?
if ($row1[$xyz][status]==1|$row1[$xyz][status]==101){
echo $row1[$xyz][maxcalls];
}
?>
</B></TD>
<TD><B>
<?
if ($row1[$xyz][status]==101){
echo $row1[$xyz][progress];
}
?>
</B></TD>

<TD>
<?
if ($row1[$xyz][flags]>0){
?>
<img src="images/percentImage.png" alt="<?echo $perc;?>%" class="percentImage" style="background-position: -<?echo 119-($perc*1.2); ?>px 0pt;" />
<?}?>
</TD>
<TD>
<?
switch($row1[$xyz][status]){
case 1:
    echo "Queued";
    break;
case 2:
    echo "Stop - Not Run";
    break;
case 101:
    echo "Processed <A HREF=\"schedule.php?status=1&campaignid=".$_POST[campaignid]."&queueID=".$row1[$xyz][queueID]."\"><IMG SRC=\"images/reset.gif\" BORDER=\"0\" ALT=\"RESET\"></A>";
    break;
case 102:
    echo "Processed <A HREF=\"schedule.php?status=2&campaignid=".$_POST[campaignid]."&queueID=".$row1[$xyz][queueID]."\"><IMG SRC=\"images/reset.gif\" BORDER=\"0\" ALT=\"RESET\"></A>";
    break;
}
?>
</TD>

<TD>
<A HREF="deleteschedule.php?id=<?echo $row1[$xyz][queueID]?>"><IMG SRC="images/cross.gif" BORDER="0" ALT="DELETE"></A>
</TD>
</TR>
<?$xyz++;
}?>
 </TABLE>
