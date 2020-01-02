#!/usr/bin/php
<?
/* This cron job is designed to stop campaigns once the maximum count of leads
 generated from a survey for the hour has been reached */

/* Note that this cron job should only be run on days you want the campaign to
 actually start on - i.e. if you want the campaign to run on Monday to Friday
 then only run the cron job on these days */

$query_start = time();
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
mysql_connect($db_host, $db_user, $db_pass);

/* Select the appropriate database */
mysql_select_db("SineDialer");

/* Create a MySQL date for the current hour */
$current_hour = "'".date("Y:m:d H:0:0")."'";

/* Check current hour - this is supposed to be run every hour */
$result = mysql_query("SELECT userfield, billsec FROM cdr WHERE amaflags = '-1' AND calldate >= $current_hour") or die(mysql_error());

if (mysql_num_rows($result) > 0) {
    while ($row = mysqL_fetch_assoc($result)) {
        $userfield = split("-",$row['userfield']);
        $campaign_id = $userfield[1];
        $totals[$campaign_id][] = $row['billsec'];
        if ($row['billsec'] < 30) {
            $group_unbillable[$campaign_id][] = $row['billsec'];
        } else {
            $billables[$campaign_id][] = $row['billsec'];
        }
    }
}

/* Go through all the campaigns that are scheduled to be run for this hour
 * if there are more or the same as required for the current hour, stop the
 * campaign.
 */

$result = mysql_query("SELECT campaign_id, leads_required FROM survey_schedules WHERE start_hour = ".date("H"));
if (mysql_num_rows($result) > 0) {
    while ($row = mysql_fetch_assoc($result)) {
        /* If we have calls for the campaign in question */
        if (isset($billables[$row['campaign_id']])) {
            /* If the number of calls is more than required */
            if ($billables[$row['campaign_id']] >= $row['leads_required']) {
                // Stop the campaign - it has reached the max leads
                $sql = "INSERT INTO queue (queuename, status, campaignID, starttime, endtime, startdate, enddate) VALUES ('lead_stop',2,".$row['campaign_id'].",'00:00','23:59','2005-01-01','2090-01-01')";
                $result = mysql_query($sql);
                echo "Running $sql because ".$billables[$row['campaign_id']].">=".$row['leads_required']."\n";
            } else {
                // Leave the campaign running - it doesn't have enough leads
                echo "Not stopping because ".$billables[$row['campaign_id']]."<".$row['leads_required']."\n";
            }
        }
    }
}
