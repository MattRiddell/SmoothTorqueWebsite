#!/usr/bin/php
<?
require "admin/db_config.php";
require "functions/sanitize.php";
while (1) {
    $sql = "select campaign.id, campaign_stats.* from campaign, campaign_stats, queue where campaign_stats.campaignid=queue.campaignID and queue.status = 101 and campaign_stats.campaignid = campaign.id";
    $result = mysql_query($sql) or die(mysql_error());
    if (mysql_num_rows($result) > 0) {
        while ($row = mysqL_fetch_assoc($result)) {
            echo $row['id']."=".(1000/$row['ms_sleep'])."\n";
            $total['cps']+=round(1000/$row['ms_sleep'],2);
            $result_new2 = mysql_query("INSERT INTO historic_cps (campaign_id, cps) VALUES (".sanitize($row['id']).",".sanitize((1000/$row['ms_sleep'])).")");
        }
        echo "Total: ".$total['cps']."\n";
        $result_new = mysql_query("INSERT INTO historic_cps (campaign_id, cps) VALUES (0,".sanitize($total['cps']).")");
    }
    sleep(10);
}
