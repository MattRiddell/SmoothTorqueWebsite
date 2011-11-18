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

$result_num_of_num = mysql_query("SELECT * FROM num_of_num");
$num_array = array();
if (mysql_num_rows($result_num_of_num) > 0) {
    while ($row_num = mysql_fetch_assoc($result_num_of_num)) {
        $num_array[$row_num['campaignid']] = $row_num;
    }
}

foreach ($count_array as $key=>$array) {
    /* Find any notifications that relate to the campaign we're looking at.  
     This means any that have a campaign id of the one we're looking for or a 
     campaign id of -1 (i.e. they're interested in all campaigns) */
    $result = mysql_query("SELECT * FROM notifcations WHERE campaign_id = ".$key." or campaign_id = '-1'");
    if (mysql_num_rows($result) == 0) {
        echo "Nobody cares about Campaign ID ".$key."\n";
    } else {
        /* Somebody wants to be notified about this campaign running out of 
         numbers.  Check what level they'd like to be notified about */
        if ($array['total'] > 0) {
            $perc = round($array['remaining']/$array['total']*100,2);
        } else {
            $perc = 0;
        }
        /* Go through the interested parties and compare to the current percentage */
        while ($row = mysql_fetch_assoc($result)) {
            if ($perc <= $row['percent_remaining']) {
                /* They want to be notified - check if the previous percentage
                 was above the notification threshold */
                if ($num_array[$key]['total_count'] == 0) {
                    $prev_perc = 0;
                } else {
                    $prev_perc = round($num_array[$key]['remaining_count']/$num_array[$key]['total_count']*100,2);
                }
                echo "Comparing $prev_perc to ".$row['percent_remaining']."\n";
                /* If the last time it ran we had more than the threshold and
                 now we have less than the threshold then send an email */
                if ($prev_perc > $row['percent_remaining']) {
                    echo "Sending email to ".$row['email_address']."\n"
                    // TODO: Send the email
                }
                
            }
        }
    }
    /* Go through and update the num_of_num table */
    $result_x = mysql_query("REPLACE INTO num_of_num (campaignid, total_count, remaining_count) VALUES ('$key','".$num_array[$key]['total_count']."','".$num_array[$key]['remaining_count']."')";
}

