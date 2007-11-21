<?
include "../admin/db_config.php";
mysql_select_db("SineDialer", $link) or die ("Unable to connect to database");;
?>
<CENTER>
<br />
<br />
Are you Sure you want to remove this customer?<BR><BR>
<?

$sql = 'SELECT * FROM customer WHERE id='.$_GET[id];
$result=mysql_query($sql, $link) or die (mysql_error());
while ($row = mysql_fetch_assoc($result)) {
    echo "<B>".$row[company]." - ".$row[city]."</B><BR><BR>";
    echo '<A HREF="deletecustomer.php?id='.$_GET[id].'&sure=yes" onclick="closeMessage()"><img src="/images/tick.png" border="0">Yes, Remove Them</A><BR><br />';
    echo '<A HREF="customers.php" onclick="closeMessage()"><img src="/images/cancel.png" border="0">No, Don\'t Remove Them</A></CENTER>';
}
?>
</FORM>
