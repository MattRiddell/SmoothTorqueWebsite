<?
include "header.php";
include "admin/db_config.php";
mysql_select_db("SineDialer", $link);
$resultx = mysql_query("select distinct system_billing.groupid, customer.* from system_billing left join customer on system_billing.groupid=customer.campaigngroupid");
$x = 0;
while ($rowx = mysql_fetch_assoc($resultx)) {
    $result = mysql_query("select max(totalcost) from system_billing where groupid = ".$rowx[groupid]);
    $totalcost = mysql_result($result,0,0);
    echo "<b>Company: $rowx[company] ($totalcost)</b><br />";
    echo '<a href="system_bill_graph.php?max=10000&groupid='.$rowx[groupid].'"><img src="system_bill_graph.php?max=10000&groupid='.$rowx[groupid].'" width="100" border="0"></a>';
    echo "<br>";
}
?>
