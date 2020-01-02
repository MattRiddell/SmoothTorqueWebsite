<?
/* This file is used to add a number to the dialer
 * You provide a campaign id and phone number.  It adds the number to the
 * database, runs the timezone script (if enabled), DNC scrubs the number and
 * then starts the campaign.  If the campaign is already running the backend
 * will just ignore the request.
 */

/* Connect to the database */
require "admin/db_config.php";

/* Include the function to sanitize the input */
require "functions/sanitize.php";

/* Get the campaign ID */
$campaign_id = sanitize($_GET['id']);

/* Get the phone number */
$phone_number = sanitize($_GET['phone_number']);

/* Create a cleaned phone number */
$cleaned_number = preg_replace('[\D]', '', $_GET['phone_number']);

/* Check if the number is in DNC */
$result = mysql_query("SELECT * FROM dncnumber WHERE phonenumber = ".$phonenumber);
if (mysql_num_rows($result) > 0) {
    /* Number is in DNC - error out */
    echo "NUMBER IS IN DNC";
    exit(0);
}

/* Check if time zones are enabled - */
$result = mysql_query("SELECT value FROM config WHERE parameter='USE_TIMEZONES'");
if (mysql_num_rows($result) == 0) {
    /* Timezones not enabled */
    $timezones = 0;
} else {
    $tz_result = mysql_result($result,0,0);
    if ($tz_result == "YES") {
        $timezones = 1;
    } else {
        $timezones = 0;
    }
}

/* If we are using timezones */
if ($timezones == 1) {
    $found = 0;
    for ($i = strlen($cleaned_number);$i>0;$i++) {
        $result = mysql_query("SELECT timezone FROM timezone_prefixes WHERE prefix = ".substr($cleaned_number,0,$i));
        if (mysql_num_rows($result) > 0) {
            $timezone = mysql_result($result,0,0);
            $found = 1;
            break;
        }
    }
    if ($found == 1) {
        $result = mysql_query("SELECT * FROM time_zones WHERE id = ".$timezone);
        $row = mysql_fetch_assoc($result);
        $start_time = $row['start'];
        $end_time = $row['end'];
        /* Insert the number with TimeZone info */
        $sql = "INSERT INTO number (campaignid, phonenumber, status, random_sort, start_time, end_time) VALUES ($campaign_id, $phonenumber, 'new', 0, '$start_time', '$end_time')";
        $result = mysql_query($sql);
    }
} else {
    /* Insert the number without TimeZone info */
    $sql = "INSERT INTO number (campaignid, phonenumber, status, random_sort) VALUES ($campaign_id, $phonenumber, 'new', 0)";
    $result = mysql_query($sql);
}

/* Get the campaign group id for the campaign we want */
$sqlx = "SELECT groupid FROM campaign WHERE id=".$campaign_id;
$result_new_groupid=mysql_query($sqlx, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result_new_groupid,0,0);

/* Find out the username for the campaign group id */
$result_group_id = mysql_query("SELECT username FROM customer WHERE campaigngroupid = $campaigngroupid");
$new_username = mysql_result($result_group_id,0,0);

/* Find out more information from the username */
$sqlx = 'SELECT campaigngroupid, maxchans, maxcps FROM customer WHERE username=\''.$new_username.'\'';
$result=mysql_query($sqlx, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');
$maxchans=mysql_result($result,0,'maxchans');
$maxcps=mysql_result($result,0,'maxcps');
$username=$new_username;

/* Get the message id for the campaign we are looking at */
$sqlx = "SELECT messageid FROM campaign WHERE id=".$campaign_id;
$result=mysql_query($sqlx, $link) or die (mysql_error());
$messageid=mysql_result($result,0,'messageid');

/* Get the trunk id for the customer */
$sql4="select trunkid from customer where campaigngroupid = ".$campaigngroupid;
$resultx=mysql_query($sql4, $link) or die ("b:".mysql_error());;
$trunkid=mysql_result($resultx,0,'trunkid');

/* If it is the default trunk then find the details for it */
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

/* Scrub existing data */
$dncsql = "SELECT number.phonenumber FROM number LEFT JOIN dncnumber ON number.phonenumber=dncnumber.phonenumber WHERE dncnumber.phonenumber IS NOT NULL AND number.campaignid='$_GET[id]' and number.status = 'new'";

$resultdnc=mysql_query($dncsql, $link) or die ("e:".mysql_error());;

$num_rows_dnc = mysql_num_rows($resultdnc);
$count_dnc_rows_so_far = 0;

while ($row = mysql_fetch_assoc($resultdnc)) {
    $count_dnc_rows_so_far++;

    $removedncsql = "UPDATE number set status = 'indnc' where phonenumber='$row[phonenumber]'";
    $resultremovednc=mysql_query($removedncsql, $link) or die ("f:".mysql_error());;
}

$sql1="delete from queue where campaignid=".$_GET[id];
$did = str_replace("-","",$_GET[did]);
$did = str_replace("(","",$did);
$did = str_replace(")","",$did);
$did = str_replace(" ","",$did);

$dialstring = str_replace(" ","",$dialstring);
$dialstring = str_replace("(","",$dialstring);
$dialstring = str_replace(")","",$dialstring);

/*================= Log Access ======================================*/
$sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_COOKIE[user]', 'Starting campaign')";
$result=mysql_query($sql, $link);
/*================= Log Access ======================================*/


if (strlen($_GET[astqueuename])> 0) {
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


?>
