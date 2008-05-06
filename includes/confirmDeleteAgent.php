<?
include "../admin/db_config.php";
mysql_select_db("SineDialer", $link) or die ("Unable to connect to database");;
?>
<CENTER>
<br />
<br />
Are you Sure you want to remove this agent?<BR><BR>
<?

$sql = 'SELECT * FROM queue_member_table WHERE membername=\''.$_GET[name].'\' and queue_name = \''.$_GET[queue_name].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());
while ($row = mysql_fetch_assoc($result)) {
    echo "<B>".$row[membername]." from ".$row[queue_name]."</B><BR><BR>";
    echo '<A HREF="deleteagent.php?name='.$row[membername].'&queue_name='.$row[queue_name].'" onclick="closeMessage()"><img src="/images/tick.png" border="0">Yes, Remove Them</A><BR><br />';
    echo '<A HREF="agents.php?name='.$row[queue_name].'" onclick="closeMessage()"><img src="/images/cancel.png" border="0">No, Don\'t Remove Them</A></CENTER>';
}
?>
</FORM>
