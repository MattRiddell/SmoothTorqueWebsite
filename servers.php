<?
require "header.php";
require "header_server.php";
$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');
?>

<table class="" align="center" border="0" cellpadding="2" cellspacing="0">
<TR>
<TD CLASS="thead">
Name
</TD>
<TD CLASS="thead">
Username
</TD>
<TD CLASS="thead">
Address
</TD>
<TD CLASS="thead">
Status
</TD>
<TD CLASS="thead">
</TD>
</TR>
<?
$sql = 'SELECT * FROM servers';
$result=mysql_query($sql, $link) or die (mysql_error());;
//$campaigngroupid=mysql_result($result,0,'campaigngroupid');
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
if (strlen($row[name])<25){
echo "<A HREF=\"editserver.php?id=".$row[id]."\">".$row[name]."</A>";
} else {
echo "<A HREF=\"editserver.php?id=".$row[id]."\">".trim(substr($row[name],0,15))."...</A>";
}
?>
</TD>

<TD>
<?echo $row[username];?>
</TD>
<TD>
<?echo $row[address];?>
</TD>
<TD>
<?
if ($row[status] == 0){
    echo "<img src=\"/images/cross.png\">";
    echo "<a href=\"resetserver.php?id=$row[id]\"><img src=\"/images/control_play_blue.png\" border=\"0\"></a>";
} else if ($row[status] == 1){
    echo "<img src=\"/images/tick.png\">";
    echo "<a href=\"resetserver2.php?id=$row[id]\"><img src=\"/images/control_stop_blue.png\" border=\"0\"></a>";
} else if ($row[status] == 2){
    echo "<img src=\"/images/clock.png\">";
    echo "<a href=\"resetserver.php?id=$row[id]\"><img src=\"/images/control_play_blue.png\" border=\"0\"></a>";
}
?>
</TD>
<TD>
<a href="#" onclick="displaySmallMessage('includes/confirmDeleteServer.php?id=<?echo $row[id];?>');return false"><IMG SRC="/images/delete.png" BORDER="0"></a><br>
</TD>
</TR>

<?
}
?>

</TABLE>
<?
require "footer.php";
?>
