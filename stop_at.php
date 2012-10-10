#!/usr/bin/php5
<?
$campaignid_to_stop = 63;
$press1s_to_stop_at = 1500;
require "admin/db_config.php";
$result = mysql_query("SELECT count(*) from number where `datetime` > CURDATE() and campaignid = ".$campaignid_to_stop." and status = 'pressed1'");
$count = mysql_result($result,0,0);
echo $count;
if ($count >= $press1s_to_stop_at) {
    $sql1="delete from queue where campaignid=".$campaignid_to_stop;
    $sql2="INSERT INTO queue (campaignid,queuename,status,starttime,endtime,startdate,enddate) VALUES ('$campaignid_to_stop','scheduled-stop-$campaignid_to_stop','2','00:00:00','23:59:59','2005-01-01','2099-01-01') ";
    $resultx=mysql_query($sql1) or die (mysql_error());;
    $resultx=mysql_query($sql2) or die (mysql_error());;
}
?>