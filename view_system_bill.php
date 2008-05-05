<?
include "admin/db_config.php";
mysql_select_db("SineDialer", $link);
$resultx = mysql_query("select distinct groupid from system_billing");
$x = 0;
while ($rowx = mysql_fetch_assoc($resultx)) {

$result = mysql_query("select * from system_billing where groupid = ".$rowx[groupid]." order by timestamp desc LIMIT 100");
$x = 0;
echo "<b>Group ID: $rowx[groupid]</b><br />";
echo '<img src="system_bill_graph.php?groupid='.$rowx[groupid].'">';
echo "<hr>";
}
?>
