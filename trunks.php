<?
$pagenum="2";
require "header.php";
require "header_trunk.php";
$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');
?>
<table class="" align="center" border="0" cellpadding="2" cellspacing="0">
<TR>
<TD CLASS="thead">
Current
</TD>
<TD CLASS="thead">
Name
</TD>
<TD CLASS="thead">
Dial String
</TD>
<TD CLASS="thead">

</TD>
</TR>
<?
$sql = 'SELECT * FROM trunk ';
/*$row1=$SMDB->executeQuery($sql);
//$campaigngroupid=mysql_result($result,0,'campaigngroupid');
$countx=0;*/
$result=mysql_query($sql, $link) or die (mysql_error());;
//$campaigngroupid=mysql_result($result,0,'campaigngroupid');
while ($row = mysql_fetch_assoc($result)) {
//print_r($row);
//while ($countx<sizeof($row1)) {
//    $row = $row1[$countx];
    if ($toggle){
        $toggle=false;
        $class=" class=\"tborder2\"";
    } else {
        $toggle=true;
        $class=" class=\"tborderx\"";
    }
    if ($row["current"]==1){
        $class=" class=\"tborderxx\"";
    }

?>
<TR <?echo $class;?>>
<TD>
<?
if ($row["current"]==1){
?>
<IMG SRC="/images/tick.png" BORDER="1" WIDTH="16" HEIGHT="16" class="abcd">
<?
} else {
?>
<a href="setdefault.php?id=<?echo $row[id];?>"><IMG SRC="/images/ch.gif" BORDER="1" WIDTH="16" HEIGHT="16"></A>
<?
}

?>
</TD>
<TD>
<?
if (strlen($row[name])<15){
echo "<A HREF=\"edittrunk.php?id=".$row[id]."\">".$row[name]."</A>";
} else {
echo "<A HREF=\"edittrunk.php?id=".$row[id]."\">".trim(substr($row[name],0,15))."...</A>";
}
?>
</TD>
<TD>
<?
if (strlen($row[dialstring])<25){
echo $row[dialstring];
} else {
echo trim(substr($row[dialstring],0,25))."...";
}
?>
</TD>

<TD>
<a href="#" onclick="displaySmallMessage('includes/confirmDeleteTrunk.php?id=<?echo $row[id];?>');return false"><IMG SRC="/images/delete.png" BORDER="0"></a><br>
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
