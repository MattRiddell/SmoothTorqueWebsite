<?
function print_pre($text) {
    echo "<pre>";
    print_r($text);
    echo "</pre>";
}

require "header.php";

/* Get all of the timezone prefixes and times */
$result = mysql_query("select time_zones.start, time_zones.end, prefix from time_zones, timezone_prefixes where timezone_prefixes.timezone = time_zones.id");

while ($row = mysql_fetch_assoc($result)) {
    $sql = "UPDATE number set start_time = '".$row['start']."', end_time = '".$row['end']."' WHERE phonenumber like '".$row['prefix']."%'";
    $result2 = mysql_query($sql);
    echo $sql."<br />";
    flush();
    //print_pre($row);
}
?>