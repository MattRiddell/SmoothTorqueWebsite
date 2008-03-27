<?
include "../admin/db_config.php";
mysql_select_db("SineDialer", $link) or die ("Unable to connect to database");;
?>
<CENTER>
<br />
<br />
Are you Sure you want to remove this queue?<BR><BR>
<?

$sql = 'SELECT * FROM queue_table WHERE name=\''.$_GET[name].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());
while ($row = mysql_fetch_assoc($result)) {
    echo "<B>".$row[name]." - ".$row[strategy]."</B><BR><BR>";
    echo '<A HREF="deletequeue.php?name='.$_GET[name].'&sure=yes" onclick="closeMessage()"><img src="/images/tick.png" border="0">Yes, Remove Them</A><BR><br />';
    echo '<A HREF="queues.php" onclick="closeMessage()"><img src="/images/cancel.png" border="0">No, Don\'t Remove Them</A></CENTER>';
}
?>
</FORM>
