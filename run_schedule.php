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
require "default_configs.php";

/* Load in the database connection values and chose the database name */
include "admin/db_config.php";
mysql_select_db("SineDialer", $link);

$hour = date("H");
$minute = date("i");
//echo "Hour: $hour Minute: $minute<br />";
//echo "Starting now:<br />";
$result = mysql_query("SELECT * FROM schedule WHERE start_hour = $hour AND start_minute = $minute");
while ($row = mysql_fetch_assoc($result)) {

    //print_pre($row);

    $out=_get_browser();
    $sql = "SELECT count(*) FROM number WHERE status='new' and campaignid='$row[campaignid]'";
    $resultz = mysql_query($sql);
    $num_numbers = mysql_result($resultz,0,0);
    if ($num_numbers <1) {
            /* No numbers available to dial */
    }
    $result_campaign = mysql_query( "SELECT * FROM campaign WHERE id = $row[campaignid]");
    $campaign_row = mysql_fetch_assoc($result_campaign);
    $sqlx = 'SELECT campaigngroupid, maxchans, maxcps FROM customer WHERE username=\''.$row[username].'\'';
    $resultz=mysql_query($sqlx, $link) or die (mysql_error());;
    $campaigngroupid=mysql_result($resultz,0,'campaigngroupid');
    $maxchans=mysql_result($resultz,0,'maxchans');
    $maxcps=mysql_result($resultz,0,'maxcps');
    $username=$row[username];

    $sqlx = "SELECT messageid FROM campaign WHERE id=$row[campaignid]";
    $resultz=mysql_query($sqlx, $link) or die (mysql_error());;
    $messageid=mysql_result($resultz,0,'messageid');

    if ( $config_values['USE_BILLING'] == "YES") {
        $sqlx = "SELECT length FROM campaignmessage WHERE id=$messageid";
        $resultz=mysql_query($sqlx, $link) or die (mysql_error());;
        unset($length);
        if (mysql_num_rows($resultz) > 0) {
        	$length=mysql_result($resultz,0,'length');
        }
        $sql = "Select credit, creditlimit, priceperminute, pricepercall, firstperiod from billing where accountcode = 'stl-$username'";
        $result_credit = mysql_query($sql, $link) or die("a:".mysql_error());
        if (mysql_num_rows($result_credit) > 0) {
            $credit = mysql_result($result_credit,0,"credit");
            $credit_limit = mysql_result($result_credit,0,"creditlimit");
            $priceperminute = mysql_result($result_credit,0,"priceperminute");
            $pricepercall = mysql_result($result_credit,0,"pricepercall");
            $firstperiod = mysql_result($result_credit,0,"firstperiod");
        } else {
            $credit = 0;
            $credit_limit = 0;
        }
        if ($credit <= 0) {
            if ($credit > 0-$credit_limit) {
                $allowed_to_start = true;
            } else {
                $allowed_to_start = false;
            }
        } else {
            $allowed_to_start = true;
        }
        if ($allowed_to_start) {
            if ($length < $firstperiod) {
                $length = $firstperiod;
            }
            $onecall = $priceperminute * ($length/60);
            $onecall += $pricepercall;
            $real_credit = $credit + $credit_limit;
    	    if ($onecall > 0) {
                $call = $real_credit/$onecall;
    	    } else {
    	    	$call = 999999999;
    	    }
            $maxcalls = floor($call);
            if ($maxcalls < 1) {
                $allowed_to_start = false;
            }
        }
        if (!$allowed_to_start) {
            /* Not enough credit - error and return */
            exit(0);
        }
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

    $dncsql = "SELECT number.phonenumber FROM number LEFT JOIN dncnumber ON number.phonenumber=dncnumber.phonenumber WHERE dncnumber.phonenumber IS NOT NULL AND number.campaignid='$row[campaignid]'";
    $resultdnc=mysql_query($dncsql, $link) or die ("e:".mysql_error());;
    while ($rowx = mysql_fetch_assoc($resultdnc)) {
        //echo "<!-- . -->";
        $removedncsql = "UPDATE number set status = 'indnc' where phonenumber='$rowx[phonenumber]'";
        $resultremovednc=mysql_query($removedncsql, $link) or die ("f:".mysql_error());;
    }

    if ( $config_values['USE_BILLING'] == "YES") {
        if ($campaign_row[context] != 0) {
            $credit_limit_sql = "UPDATE number SET status='no-credit' WHERE status='new' and campaignid='$row[campaignid]'";
            $result_credit_limit_sql=mysql_query($credit_limit_sql, $link) or die ("g:".mysql_error());;

            $credit_limit_sql2 = "UPDATE number SET status='new' WHERE status='no-credit' and campaignid='$row[campaignid]' limit $maxcalls";
            $result_credit_limit_sql2=mysql_query($credit_limit_sql2, $link) or die (mysql_error()." from ".$credit_limit_sql2);;
        } else {
            $credit_limit_sql2 = "UPDATE number SET status='new' WHERE status='no-credit' and campaignid='$row[campaignid]'";
            $result_credit_limit_sql2=mysql_query($credit_limit_sql2, $link) or die ("i:".mysql_error());;
        }
    }

    $sql1="delete from queue where campaignid=".$row[campaignid];
    $did = str_replace("-","",$campaign_row[did]);
    $did = str_replace("(","",$did);
    $did = str_replace(")","",$did);
    $did = str_replace(" ","",$did);

    $dialstring = str_replace(" ","",$dialstring);
    $dialstring = str_replace("(","",$dialstring);
    $dialstring = str_replace(")","",$dialstring);

    if (strlen($campaign_row[astqueuename])> 0) {
        $mode = 1;
    } else {
        $mode = 0;
    }
    $sql2="INSERT INTO queue (campaignid,queuename,status,details,flags,transferclid,
        starttime,endtime,startdate,enddate,did,clid,context,maxcalls,maxchans,maxretries
        ,retrytime,waittime,trunk,astqueuename, accountcode, trunkid, customerID, maxcps, mode) VALUES
        ('$row[campaignid]','autostart-$row[campaignid]','1','No details','0','$campaign_row[trclid]',
        '00:00','23:59','2005-01-01','2020-01-01','$did','$campaign_row[clid]',
        '$campaign_row[context]','$campaign_row[maxagents]','$maxchans','0'
        ,'0','30','".$dialstring."','$campaign_row[astqueuename]','stl-".$username."','$trunkid','$campaigngroupid','$maxcps','$mode') ";
    //echo $sql1."<br />";
    //echo $sql2."<br />";
    $resultx=mysql_query($sql1, $link) or die ("j:".mysql_error());;
    $resultx=mysql_query($sql2, $link) or die ("k:".mysql_error());;


}

//echo "Ending now:<br />";
$result = mysql_query("SELECT * FROM schedule WHERE end_hour = $hour AND end_minute = $minute");
while ($row = mysql_fetch_assoc($result)) {
    //print_pre($row);
    //Create a queue entry to stop a running campaign
    $sql1="delete from queue where campaignid=".$row[campaignid];
    $sql2="INSERT INTO queue (campaignid,queuename,status,
        starttime,endtime,startdate,enddate) VALUES
        ('$row[campaignid]','scheduled-stop-$row[campaignid]','2',
        '00:00:00','23:59:59','2005-01-01','2099-01-01') ";
    $resultx=mysql_query($sql1, $link) or die (mysql_error());;
    $resultx=mysql_query($sql2, $link) or die (mysql_error());;
}

?>
