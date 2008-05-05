<?
include "admin/db_config.php";
mysql_select_db("SineDialer", $link);
$resultx = mysql_query("select distinct groupid from system_billing");
$x = 0;
while ($rowx = mysql_fetch_assoc($resultx)) {

$result = mysql_query("select * from system_billing where groupid = ".$rowx[groupid]." order by timestamp desc LIMIT 100");
$x = 0;
echo "<b>Group ID: $rowx[groupid]</b><br />";
while ($row = mysql_fetch_assoc($result)) {

/* This is one page of customer billing (i.e. the last 100 5 minute values */
/* in here should be a graph for that customer showing howmuch they've */
/* spent in the last 100 blocks */

$x++;
echo $row[totalcost];
echo ' - (';
echo $row[timestamp];
echo ")<br />";

}
echo "<hr>";
}
?>
