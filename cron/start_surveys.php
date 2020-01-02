#!/usr/bin/php
<?
/* This cron job is designed to start campaigns once an hour if they require
 leads for the particular hour we're in */

/* Note that this cron job should only be run on days you want the campaign to
 actually start on - i.e. if you want the campaign to run on Monday to Friday
 then only run the cron job on these days */

//TODO: Fix GET Values

$query_start = time();
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
mysql_connect($db_host, $db_user, $db_pass);

/* Select the appropriate database */
mysql_select_db("SineDialer");

/* Go through all the campaigns that are scheduled to be run for this hour */

$result = mysql_query("SELECT campaign_id FROM survey_schedules WHERE leads_required > 0 AND start_hour = ".date("H"));
if (mysql_num_rows($result) > 0) {
    while ($row = mysql_fetch_assoc($result)) {
        $_GET['id'] = $row['campaign_id'];

        $sqlx = "SELECT groupid FROM campaign WHERE id=".$row['campaign_id'];
        $result_new_groupid=mysql_query($sqlx, $link) or die (mysql_error());;
        $campaigngroupid=mysql_result($result_new_groupid,0,0);

        $result_group_id = mysql_query("SELECT username FROM customer WHERE campaigngroupid = $campaigngroupid");
        $new_username = mysql_result($result_group_id,0,0);

        $sqlx = 'SELECT campaigngroupid, maxchans, maxcps FROM customer WHERE username=\''.$new_username.'\'';
        $result=mysql_query($sqlx, $link) or die (mysql_error());;
        $campaigngroupid=mysql_result($result,0,'campaigngroupid');
        $maxchans=mysql_result($result,0,'maxchans');
        $maxcps=mysql_result($result,0,'maxcps');
        $username=$new_username;

        $sqlx = "SELECT messageid FROM campaign WHERE id=".$row['campaign_id'];
        $result=mysql_query($sqlx, $link) or die (mysql_error());;
        $messageid=mysql_result($result,0,'messageid');

        $sqlx = "SELECT length FROM campaignmessage WHERE id=$messageid";
        $result=mysql_query($sqlx, $link) or die (mysql_error());;
        unset($length);
        if (mysql_num_rows($result) > 0) {
            $length=mysql_result($result,0,'length');
        }

        $sql4="select trunkid from customer where campaigngroupid = ".$campaigngroupid;
        $resultx=mysql_query($sql4, $link) or die ("b:".mysql_error());;
        $trunkid=mysql_result($resultx,0,'trunkid');

        if ($trunkid==-1){
            $sql3="select dialstring, id from trunk where current = 1";
            $resultx=mysql_query($sql3, $link) or die ("c:".mysql_error());;
            $dialstring=mysql_result($resultx,0,'dialstring');
            $trunkid = mysql_result($resultx,0,'id');
        } else {
            $sql3="select dialstring from trunk where id = ".$trunkid;
            $resultx=mysql_query($sql3, $link) or die ("d:".mysql_error());;
            $dialstring=mysql_result($resultx,0,'dialstring');
        }

        $dncsql = "SELECT number.phonenumber FROM number LEFT JOIN dncnumber ON number.phonenumber=dncnumber.phonenumber WHERE dncnumber.phonenumber IS NOT NULL AND number.campaignid='".$row['campaign_id']."'";
        $resultdnc=mysql_query($dncsql, $link) or die ("e:".mysql_error());;
        while ($row = mysql_fetch_assoc($resultdnc)) {
            echo "<!-- . -->";
            $removedncsql = "UPDATE number set status = 'indnc' where phonenumber='$row[phonenumber]'";
            $resultremovednc=mysql_query($removedncsql, $link) or die ("f:".mysql_error());;
        }

        $sql1="delete from queue where campaignid=".$_GET['id'];
        $did = str_replace("-","",$_GET['did']);
        $did = str_replace("(","",$did);
        $did = str_replace(")","",$did);
        $did = str_replace(" ","",$did);

        //$dialstring = str_replace("-","",$dialstring);
        $dialstring = str_replace(" ","",$dialstring);
        $dialstring = str_replace("(","",$dialstring);
        $dialstring = str_replace(")","",$dialstring);

        /*================= Log Access ======================================*/
        $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_COOKIE[user]', 'Starting campaign')";
        $result=mysql_query($sql, $link);
        /*================= Log Access ======================================*/


        if (strlen($_GET['astqueuename'])> 0) {
            $mode = 1;
        } else {
            $mode = 0;
        }
        $sql = 'SELECT value FROM config WHERE parameter=\'expected_rate\'';
        $result=mysql_query($sql, $link) or die (mysql_error());
        if (mysql_num_rows($result) > 0) {
            $expected_rate = mysql_result($result,0,'value');
        } else {
            $expected_rate = 100;
        }
        if (isset($_GET['drive_min'])) {
            $drive_min = $_GET['drive_min'];
            $drive_max = $_GET['drive_max'];
        } else {
            $drive_min = "43.0";
            $drive_max = "61.0";
        }


        $sql2="INSERT INTO queue (campaignid,queuename,status,details,flags,transferclid,
        starttime,endtime,startdate,enddate,did,clid,context,maxcalls,maxchans,maxretries
        ,retrytime,waittime,trunk,astqueuename, accountcode, trunkid, customerID, maxcps, mode, expectedRate, drive_min, drive_max) VALUES
        ('$_GET[id]','autostart-$_GET[id]','1','No details','0','$_GET[trclid]',
        '00:00','23:59','2005-01-01','2090-01-01','$did','$_GET[clid]',
        '$_GET[context]','$_GET[agents]','$maxchans','0'
        ,'0','30','".$dialstring."','$_GET[astqueuename]','stl-".$new_username."','$trunkid','$campaigngroupid','$maxcps','$mode', '$expected_rate','$drive_min','$drive_max') ";
        $resultx=mysql_query($sql1, $link) or die ("j:".mysql_error());;
        $resultx=mysql_query($sql2, $link) or die ("k:".mysql_error());;

    }
}
