<?
include "../admin/db_config.php";
mysql_select_db("SineDialer", $link) or die ("Unable to connect to database");;
?>
<CENTER>
<br />
Are you Sure you want to remove this trunk?<BR><BR>
<?

$sql = 'SELECT * FROM trunk WHERE id='.$_GET[id];
$result=mysql_query($sql, $link) or die (mysql_error());
while ($row = mysql_fetch_assoc($result)) {
    //print_r($row);
    echo "<B>".$row[name]."</b><br /><br />Dial String: ".$row[dialstring]."<BR><BR>";
    echo '<A HREF="deletetrunk.php?id='.$_GET[id].'&sure=yes" onclick="closeMessage()"><img src="/images/tick.png" border="0">Yes, Remove It</A><BR><br />';
    echo '<A HREF="trunks.php" onclick="closeMessage()"><img src="/images/cancel.png" border="0">No, Don\'t Remove It</A></CENTER>';
}
?>
</FORM>
