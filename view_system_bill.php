<?
include "header.php";
include "admin/db_config.php";
mysql_select_db("SineDialer", $link);
$resultx = mysql_query("select distinct groupid from system_billing");
$x = 0;
while ($rowx = mysql_fetch_assoc($resultx)) {
    echo "<b>Group ID: $rowx[groupid]</b><br />";
    echo '<a href="system_bill_graph.php?groupid='.$rowx[groupid].'"><img src="system_bill_graph.php?groupid='.$rowx[groupid].'" width="100" border="0"></a>';
    echo "<hr>";
}
?>
