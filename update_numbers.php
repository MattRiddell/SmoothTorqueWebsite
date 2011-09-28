#!/usr/bin/php
<?
/* Cron Job for changing the status of numbers between "new" and "new_nodial" 
 * 
 * This is used for timezone management.  It is designed to be run every 30
 * minutes and swap the statuses of numbers.  Code by Matt Riddell - development
 * funded by VentureVoIP Canada and TSOA International.
 */

function print_pre($text) {
    echo "<pre>";
    print_r($text);
    echo "</pre>";
}

/* Change this to your time zone */
date_default_timezone_set("EST");

$current_time = date("H:i:s");

/* MySQL Connection details */
$db_host="127.0.0.1";
$db_user="root";
$db_pass="";

/* Connect to MySQL */
$link = mysql_connect($db_host, $db_user, $db_pass) OR die(mysql_error());

/* Select the appropriate database */
mysql_select_db("SineDialer");

/* Get all of the timezone prefixes and times */
$result = mysql_query("select time_zones.start, time_zones.end, prefix from time_zones, timezone_prefixes where timezone_prefixes.timezone = time_zones.id");

while ($row = mysql_fetch_assoc($result)) {
    $sql = "UPDATE number set start_time = '".$row['start']."', end_time = '".$row['end']."' WHERE phonenumber like '".$row['prefix']."%'";
    $result2 = mysql_query($sql);
    echo $sql."<br /";
    flush();
    //print_pre($row);
}
?>