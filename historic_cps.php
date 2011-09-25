#!/usr/bin/php
<?
require "admin/db_config.php";
require "functions/sanitize.php";
while (1) {
    $sql = "select campaign.id, campaign_stats.* from campaign, campaign_stats, queue where campaign_stats.campaignid=queue.campaignID and queue.status = 101 and campaign_stats.campaignid = campaign.id";
    $result = mysql_query($sql);
    while ($row = mysqL_fetch_assoc($result)) {
        echo $row['id']."=".(1000/$row['ms_sleep'])."\n";
        $total['cps']+=round(1000/$row['ms_sleep'],2);
        $result = mysql_query("INSERT INTO historic_cps (camapign_id, cps) VALUES (".sanitize($row['id']).",".sanitize((1000/$row['ms_sleep'])).")");
    }
    echo "Total: ".$total['cps']."\n";
    $result = mysql_query("INSERT INTO historic_cps (camapign_id, cps) VALUES (0,".sanitize($total['cps']).")");
    sleep(10);
}
