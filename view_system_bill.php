<?
include "admin/db_config.php";
mysql_select_db("SineDialer", $link);
$resultx = mysql_query("select distinct groupid from system_billing");
$x = 0;
while ($rowx = mysql_fetch_assoc($resultx)) {

$result = mysql_query("select * from system_billing where groupid = ".$rowx[groupid]." LIMIT 100");
$x = 0;
while ($row = mysql_fetch_assoc($result)) {
$x++;
echo $row[groupid];
echo ' - $';
echo $row[totalcost];
echo ' - (';
echo $row[timestamp];
echo ")<br />";

}
}
?>
