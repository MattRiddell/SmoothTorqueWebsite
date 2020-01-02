<?
/* Find out what the base directory name is for two reasons:
 1. So we can include files
 2. So we can explain how to set up things that are missing */
$current_directory = dirname(__FILE__);

/* What page we are currently on - this is used to highlight the menu
 system as well as to not cache certain pages like the graphs */
$self=$_SERVER['PHP_SELF'];

/* Load in the functions we may need - these are the list of available
 custom functions - for more information, read the comments in the
 functions.php file - most functions are in their own file in the
 functions subdirectory */
require "/".$current_directory."/functions/functions.php";

/* Config File Parsing */
//require "default_configs.php";

/* Load in the database connection values and chose the database name */
include "admin/db_config.php";
mysql_select_db("SineDialer", $link);

$hour = date("H");
$minute = date("i");
//echo "Hour: $hour Minute: $minute<br />";
//echo "Starting now:<br />";
$result = mysql_query("SELECT * FROM schedule WHERE start_hour = $hour AND start_minute = $minute");
while ($row = mysql_fetch_assoc($result)) {
    $right_day = false;
    switch ($row['regularity']) {
        case "every-day":
            $right_day = true;
            break;
        case "mon-fri":
            if (date("N") >0 && date("N")<6) {
                $right_day = true;
            }
            break;
        case "mon-sat":
            if (date("N") >0 && date("N")<7) {
                $right_day = true;
            }
            break;

    }
    if ($right_day) {
        // Get the details of the campaign
        $result_campaign = mysql_query( "SELECT * FROM campaign WHERE id = $row[campaignid]");
        $campaign_row = mysql_fetch_assoc($result_campaign);

        // Get the details of the user
        $sqlx = 'SELECT campaigngroupid, maxchans, maxcps, trunkid FROM customer WHERE username=\''.$row['username'].'\'';
        $resultz=mysql_query($sqlx, $link) or die (mysql_error());;
        $campaigngroupid=mysql_result($resultz,0,'campaigngroupid');
        $maxchans=mysql_result($resultz,0,'maxchans');
        $maxcps=mysql_result($resultz,0,'maxcps');
        $trunkid=mysql_result($resultz,0,'trunkid');
        $username=$row['username'];

        // Get the trunk details
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

        // Scrub against DNC
        $dncsql = "SELECT number.phonenumber FROM number LEFT JOIN dncnumber ON number.phonenumber=dncnumber.phonenumber WHERE dncnumber.phonenumber IS NOT NULL AND number.campaignid='$row[campaignid]'";
        $resultdnc=mysql_query($dncsql, $link) or die ("e:".mysql_error());;
        while ($rowx = mysql_fetch_assoc($resultdnc)) {
            $removedncsql = "UPDATE number set status = 'indnc' where phonenumber='$rowx[phonenumber]'";
            $resultremovednc=mysql_query($removedncsql, $link) or die ("f:".mysql_error());;
        }

        // Clean up the DDI number for the call center
        $did = str_replace("-","",$campaign_row['did']);
        $did = str_replace("(","",$did);
        $did = str_replace(")","",$did);
        $did = str_replace(" ","",$did);

        // Clean up the dialstring
        $dialstring = str_replace(" ","",$dialstring);
        $dialstring = str_replace("(","",$dialstring);
        $dialstring = str_replace(")","",$dialstring);

        // Check if it is in Queue Mode
        if (strlen($campaign_row['astqueuename'])> 0) {
            $mode = 1;
        } else {
            $mode = 0;
        }

        // Clear out the queue table
        $sql1="delete from queue where campaignid=".$row['campaignid'];

        // Create the actual queue entry
        $sql2="INSERT INTO queue (campaignid,queuename,status,details,flags,transferclid,
        starttime,endtime,startdate,enddate,did,clid,context,maxcalls,maxchans,maxretries
        ,retrytime,waittime,trunk,astqueuename, accountcode, trunkid, customerID, maxcps, mode) VALUES
        ('$row[campaignid]','autostart-$row[campaignid]','1','No details','0','$campaign_row[trclid]',
        '00:00','23:59','2005-01-01','2090-01-01','$did','$campaign_row[clid]',
        '$campaign_row[context]','$campaign_row[maxagents]','$maxchans','0'
        ,'0','30','".$dialstring."','$campaign_row[astqueuename]','stl-".$username."','$trunkid','$campaigngroupid','$maxcps','$mode') ";
        $resultx=mysql_query($sql1, $link) or die ("j:".mysql_error());;
        $resultx=mysql_query($sql2, $link) or die ("k:".mysql_error());;
        echo "Started ".$row['campaignid']."\n";
    }

}

$result = mysql_query("SELECT * FROM schedule WHERE end_hour = $hour AND end_minute = $minute");
while ($row = mysql_fetch_assoc($result)) {
    //print_pre($row);
    //Create a queue entry to stop a running campaign

    $right_day = false;
    switch ($row['regularity']) {
        case "every-day":
            $right_day = true;
            break;
        case "mon-fri":
            if (date("N") >0 && date("N")<6) {
                $right_day = true;
            }
            break;
        case "mon-sat":
            if (date("N") >0 && date("N")<7) {
                $right_day = true;
            }
            break;

    }
    if ($right_day) {
        $sql1="delete from queue where campaignid=".$row['campaignid'];
        $sql2="INSERT INTO queue (campaignid,queuename,status,
        starttime,endtime,startdate,enddate) VALUES
        ('$row[campaignid]','scheduled-stop-$row[campaignid]','2',
        '00:00:00','23:59:59','2005-01-01','2099-01-01') ";
        $resultx=mysql_query($sql1, $link) or die (mysql_error());;
        $resultx=mysql_query($sql2, $link) or die (mysql_error());;
        echo "Stopped ".$row['campaignid']."\n";
    }
}

?>
