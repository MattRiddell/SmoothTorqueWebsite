#!/usr/bin/php
<?
/* Cron Job for changing the status of numbers between "new" and "new_nodial" 
 * 
 * This is used for timezone management.  It is designed to be run every 30
 * minutes and swap the statuses of numbers.  Code by Matt Riddell - development
 * funded by VentureVoIP Canada and TSOA International.
 */

/* Find out what the current time is in UTC */
date_default_timezone_set("UTC");
$current_time = date("H:i:s");

/* MySQL Connection details */
$db_host="127.0.0.1";
$db_user="root";
$db_pass="";

/* Connect to MySQL */
$link = mysql_connect($db_host, $db_user, $db_pass) OR die(mysql_error());

/* Select the appropriate database */
mysql_select_db("SineDialer");

/* Get a list of all number prefixes that should be running right now */
$result = mysql_query("SELECT * FROM time_zones where '".$current_time."' between TIME(start) AND TIME(end)") or die(mysql_error());

/* Create an array for timezones which can currently be called */
$allowed = array();

/* Fill the array */
if (mysql_num_rows($result) > 0) {
    while($row = mysql_fetch_assoc($result)) {
        $allowed[] = $row['id'];
    }
}

echo "<pre>";

/* Get a list of all number prefixes that should not be running right now */
$result = mysql_query("SELECT * FROM time_zones where '".$current_time."' NOT between TIME(start) AND TIME(end)") or die(mysql_error());

/* Create an array for timezones which can currently be called */
$not_allowed = array();

/* Fill the array */
if (mysql_num_rows($result) > 0) {
    while($row = mysql_fetch_assoc($result)) {
        $not_allowed[] = $row['id'];
    }
}

/*echo "Allowed: ";
print_r($allowed);

echo "Not Allowed: ";
print_r($not_allowed);
*/

/* Update the status of all numbers */

/* Add a log entry to say what we've done */

?>