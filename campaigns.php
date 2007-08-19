<?
$pagenum="1";
require "header.php";
$sql = 'SELECT id FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$row1=$SMDB->executeQuery($sql);
$campaigngroupid=$row1[0]['id'];
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
Message 1
</TD>
<TD CLASS="thead">
Message 2
</TD>
<TD CLASS="thead">
Message 3
</TD>
<TD CLASS="thead">
Used
</TD>
<TD CLASS="thead">

</TD>
</TR>
<?
$sql = 'SELECT * FROM campaign WHERE groupid='.$campaigngroupid;
$row1=$SMDB->executeQuery($sql);
//$campaigngroupid=mysql_result($result,0,'campaigngroupid');
$countx=0;
while ($countx<sizeof($row1)) {
$row = $row1[$countx];
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
//$result2=mysql_query($sql, $link) or die (mysql_error());;
//$count=mysql_result($result2,0,'count(*)');
$row2=$SMDB->executeQuery($sql);
$count=$row2[0]['count(*)'];


$sql = 'SELECT count(*) from number where campaignid='.$row[id];
//$result2=mysql_query($sql, $link) or die (mysql_error());;
//$count2=mysql_result($result2,0,'count(*)');
$row2=$SMDB->executeQuery($sql);
$count2=$row2[0]['count(*)'];

?>
<TD>
<?echo $count."/".$count2;?>
</TD>


<TD>
<?echo "<A HREF=\"deletecampaign.php?id=".$row[id]."\"><IMG SRC=\"/images/cross.gif\" BORDER=\"0\"></A>";?>
</TD>
</TR>

<?
$countx++;
}
?>

</TABLE>
<?
require "footer.php";
?>
