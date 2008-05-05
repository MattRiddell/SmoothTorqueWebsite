<?
include "header.php";
include "admin/db_config.php";
mysql_select_db("SineDialer", $link);
$resultx = mysql_query("select distinct system_billing.groupid, customer.* from system_billing left join customer on system_billing.groupid=customer.campaigngroupid");
$x = 0;
$highest = 0;
while ($rowx = mysql_fetch_assoc($resultx)) {
    $result = mysql_query("select max(totalcost) from system_billing where groupid = ".$rowx[groupid]);
    $totalcost[$x] = mysql_result($result,0,0);
    if ($totalcost[$x] > $highest) {
        $highest = $totalcost[$x];
    }
    $company[$x] = $rowx[company];
    $groupid[$x] = $rowx[groupid];
    $x++;
}
for($i = 0;$i<$x;$i++) {
    $totalcost_cr = $config_values['CURRENCY_SYMBOL']." ".number_format($totalcost[$i],2);
    echo "<b>Company: $company[$i] ($totalcost_cr)</b><br />";
    echo '<a href="system_bill_graph.php?xsize=640&ysize=480&size=300&max='.$highest.'&groupid='.$groupid[$i].'"><img src="system_bill_graph.php?xsize=500&ysize=170&size=300&max='.$highest.'&groupid='.$groupid[$i].'" width="500" height="170" border="0"></a>';
    echo "<br>";

}
?>
