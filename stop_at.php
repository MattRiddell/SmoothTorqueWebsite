#!/usr/bin/php5
<?
$campaignid_to_stop = 64;
$press1s_to_stop_at = 1500;
require "admin/db_config.php";
$result = mysql_query("SELECT count(*) from number where `datetime` > CURDATE() and campaignid = ".$campaignid_to_stop);
$count = mysql_result($result,0,0);
echo $count;
?>