<?
include "admin/db_config.php";
mysql_select_db("SineDialer", $link);

$result = mysql_query("select cost, groupid from campaign where cost is not NULL");
while ($row = mysql_fetch_assoc($result)) {
    if (isset($row['cost']) && ($row['cost']+0) > 0) {
        echo $row['cost']." - ".$row['groupid'];
        $test[$row['groupid']]+=$row['cost'];
        //echo "<br />";
    }
}
foreach ($test as $key => $value) {
    echo $value." ($key)\n";
    $sql = "INSERT INTO system_billing (groupid,totalcost) VALUES ($key, $value)";
    echo $sql;
    $result = mysql_query($sql);
}
?>
