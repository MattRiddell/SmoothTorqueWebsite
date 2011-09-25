#!/usr/bin/php
<?
require "admin/db_config.php";
$sql = "select campaign.id, campaign_stats.* from campaign, campaign_stats, queue where campaign_stats.campaignid=queue.campaignID and queue.status = 101 and campaign_stats.campaignid = campaign.id";
$result = mysql_query($sql);
while ($row = mysqL_fetch_assoc($result)) {
    echo $row['id']."=".(1000/$row['ms_sleep'])."\n";
    $total['cps']+=round(1000/$row['ms_sleep'],2);
}
echo "Total: ".$total['cps'];
exit(0);
