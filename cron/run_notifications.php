#!/usr/bin/php
<?
/* This cron job is used to check what percentage of the numbers are remaining 
 and send out appropriate notifications if the remaining percentage falls below 
 the threshold for a particular notification */

/* This cron job assumes that you are using timezone based dialling */

$query_start = time();
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
mysql_connect($db_host, $db_user, $db_pass);

/* Select the appropriate database */
mysql_select_db("SineDialer");

/* First go through all the campaigns and check if there are any changes.  If 
 there are changes make a note of them */

$result1 = mysql_query("SELECT * FROM campaign");
$count_array = array();
if (mysql_num_rows($result1) > 0) {
    while ($row = mysql_fetch_assoc($result1)) {
        $sql = 'Select count(*) from number where status="new" and campaignid = '.$row['id']." and TIME(NOW()) between start_time and end_time";
        $result2=mysql_query($sql) or die (mysql_error());;
        $count = mysql_result($result2,0,'count(*)');
        $count_array[$row['id']]['remaining'] = $count;
        
        $sql = 'Select count(*) from number where campaignid = '.$row['id'];
        $result2=mysql_query($sql) or die (mysql_error());;
        $count = mysql_result($result2,0,'count(*)');
        $count_array[$row['id']]['total'] = $count;
    }
}

//print_r($count_array);

foreach ($count_array as $key=>$array) {
    if ($array['total'] > 0) {
        echo "Campaign ID $key has ".round($array['remaining']/$array['total']*100,2)." percent remaining\n";
    } else {
        echo "Campaign ID $key has 0.00 percent remaining\n";
    }
}
/*

$result = mysql_query("SELECT campaign_id FROM survey_schedules WHERE leads_required > 0 AND start_hour = ".date("H"));
if (mysql_num_rows($result) > 0) {
    while ($row = mysql_fetch_assoc($result)) {
*/