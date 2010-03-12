<?


/* Find out what the base directory name is for two reasons:
 *  1. So we can include files
 *  2. So we can explain how to set up things that are missing
 */
$current_directory = dirname(__FILE__);
if (isset($override_directory)) {
	$current_directory = $override_directory;
}
/* What page we are currently on - this is used to highlight the menu
 * system as well as to not cache certain pages like the graphs
 */
$self=$_SERVER['PHP_SELF'];

/* Load in the functions we may need - these are the list of available
 * custom functions - for more information, read the comments in the
 * functions.php file - most functions are in their own file in the
 * functions subdirectory
 */
require "/".$current_directory."/functions/functions.php";
/* Load in the database connection values and chose the database name */
include "/".$current_directory."/admin/db_config.php";


/* If we have no language set, let's use English - this is mainly because
 * header.php is also called from index.php where we couldn't possibly
 * know the language.
 */
if ((!(isset($_COOKIE['language'])))||$_COOKIE['language'] == "--") {
    $_COOKIE['language'] = "en";
}
/* Same goes for the server name */
if (!isset($_COOKIE['url']) ||$_COOKIE['url'] == "--") {
    $_COOKIE['url'] = $_SERVER['SERVER_NAME'];
}

/* Set a variable so we don't need to keep reading the cookies */
$url = $_COOKIE['url'];

/* We now have a language and a server name */
$result_config = mysql_query("SELECT * FROM web_config WHERE LANG = ".sanitize($_COOKIE['language'])." AND url = ".sanitize($url)) or die(mysql_error());
if (mysql_num_rows($result_config) == 0) {
    /* No entry found for this url - use the default */
    $sql = "SELECT * FROM web_config WHERE LANG = ".sanitize($_COOKIE['language'])." AND url = 'default'";
    $result_config = mysql_query($sql) or die("Unable to load config information from mysql: ".mysql_error());
}

if (mysql_num_rows($result_config) == 0) {
    echo "Even though we were sucessful reading the config, it has no values.  Please send an email to smoothtorque@venturevoip.com";
    exit(0);
}

/* Now that we have the config values, put them into the array */
while ($header_row = mysql_fetch_assoc($result_config) ) {
    foreach ($header_row as $key=>$value) {
        if ($key != "contact_text") {
            $config_values[strtoupper($key)] = $value;
        } else {
            $config_values["TEXT"] = $value;
        }
    }
}

$sql = 'SELECT value FROM config WHERE parameter=\'sugar_user\'';
$result=mysql_query($sql, $link) or die (mysql_error());
if (mysql_num_rows($result) > 0) {
    $config_values['SUGAR_USER'] = mysql_result($result,0,'value');
}

$sql = 'SELECT value FROM config WHERE parameter=\'sugar_host\'';
$result=mysql_query($sql, $link) or die (mysql_error());
if (mysql_num_rows($result) > 0) {
    $config_values['SUGAR_HOST'] = mysql_result($result,0,'value');
}

$sql = 'SELECT value FROM config WHERE parameter=\'sugar_pass\'';
$result=mysql_query($sql, $link) or die (mysql_error());
if (mysql_num_rows($result) > 0) {
    $config_values['SUGAR_PASS'] = mysql_result($result,0,'value');
}

$sql = 'SELECT value FROM config WHERE parameter=\'sugar_db\'';
$result=mysql_query($sql, $link) or die (mysql_error());
if (mysql_num_rows($result) > 0) {
    $config_values['SUGAR_DB'] = mysql_result($result,0,'value');
}


$result = mysql_query("SELECT * FROM urgent_lead_sources") or die(mysql_error());        
$db_u_l_s = Array();
if (mysql_num_rows($result) > 0) {
    $urgent_sources = "(";
    while ($row = mysql_fetch_assoc($result)) {
        $urgent_sources .= sanitize($row['name']).",";
    }
    $urgent_sources = substr($urgent_sources,0,strlen($urgent_sources)-1).")";
} else {
    $urgent_sources = "('')";
}

$tz_db = array();
$result = mysql_query("SELECT * FROM time_zones");
if (mysql_num_rows($result) > 0) {
    while ($row = mysql_fetch_assoc($result)) {
        $tz_db_name[] = $row['name'];
        $tz_db_start[] = $row['start'];
        $tz_db_end[] = $row['end'];
    }
}


$db_host = $config_values['SUGAR_HOST'];
$db_user = $config_values['SUGAR_USER'];
$db_pass = $config_values['SUGAR_PASS'];
$link = mysql_connect($db_host, $db_user, $db_pass) OR die(mysql_error());
mysql_select_db($config_values['SUGAR_DB'], $link);


$result = mysql_query("SELECT id FROM lc_customstatus WHERE name like 'CA - Left Message%'");
$status_left_messages = "(";
while ($row = mysql_fetch_assoc($result)) {
	$status_left_messages .= sanitize($row['id']).",";
}
$status_left_messages = substr($status_left_messages,0,strlen($status_left_messages)-1).")";

$result = mysql_query("SELECT id FROM lc_customstatus WHERE name = 'new'");
$new_status = mysql_result($result,0,0);


/*$sql = "SELECT count(*) FROM leads WHERE DATE_SUB(CURDATE(),INTERVAL 5 DAY) <= date_entered and leads.deleted = 0 and status='$new_status' and lead_source in $urgent_sources";
 //echo $sql;
 $result = mysql_query($sql) or die(mysql_error());
 echo number_format(mysql_result($result,0,0));*/

/*
 ?>
 <br />
 <hr>
 <b>Urgent Numbers:</b><br />
 <?
 
 /////////////////// CHECK IF NUMBERS HAVE BEEN SENT TO SMOOTHTORQUE ALREADY
 
 $result = mysql_query("SELECT phone_home, phone_mobile, lead_source FROM leads WHERE DATE_SUB(CURDATE(),INTERVAL 5 DAY) <= date_entered and leads.deleted = 0 and status='$new_status'  and lead_source in $urgent_sources");
 while ($row = mysql_fetch_assoc($result)) {
 if (isset($row['phone_mobile']) && $row['phone_mobile'] != $row['phone_home']) {
 echo "Home Phone: ".$row['phone_home'].",  Mobile Phone: ".$row['phone_mobile']." Source: ".$row['lead_source']."<br />";
 } else {
 echo "Home Phone: ".$row['phone_home']." Source: ".$row['lead_source']."<br />";
 }
 }
 */
/*
 $result = mysql_query("select count(*), st_tier_c from leads, leads_cstm where leads.id = leads_cstm.id_c and leads.deleted = 0 group by st_tier_c");
 while ($row = mysql_fetch_assoc($result)) {
 print_pre($row);
 }
 
 echo "<hr />";
 
 $result = mysql_query("select count(*), st_calls_c from leads, leads_cstm where leads.id = leads_cstm.id_c and leads.deleted = 0 group by st_calls_c");
 while ($row = mysql_fetch_assoc($result)) {
 print_pre($row);
 }
 echo "<hr />";
 
 $result = mysql_query("select count(*), st_vm_c from leads, leads_cstm where leads.id = leads_cstm.id_c and leads.deleted = 0 group by st_vm_c");
 while ($row = mysql_fetch_assoc($result)) {
 print_pre($row);
 }
 
 echo "<hr />";
 */
//echo "VoiceMails<br />";
/*$result = mysql_query("select leads.phone_home, leads.phone_mobile, leads_cstm.st_vm_c from leads, leads_cstm where leads.id = leads_cstm.id_c and leads.deleted = 0 and leads_cstm.st_vm_c > 0") or die(mysql_error());
 while ($row = mysql_fetch_assoc($result)) {
 print_pre($row);
 }
 */
/*$result = mysql_query("select count(*), leads_cstm.st_calls_c from leads, leads_cstm where leads.id = leads_cstm.id_c and leads.deleted = 0 and leads_cstm.st_calls_c > 0 group by leads_cstm.st_calls_c") or die(mysql_error());
 while ($row = mysql_fetch_assoc($result)) {
 print_pre($row);
 }
 */

/*
 
 $result = mysql_query("select count(*), leads_cstm.st_calls_c from leads, leads_cstm where leads.id = leads_cstm.id_c and leads.deleted = 0 and leads_cstm.st_calls_c > 0 group by leads_cstm.st_calls_c") or die(mysql_error());
 while ($row = mysql_fetch_assoc($result)) {
 print_pre($row);
 }
 */

/*
 
 
 */



/*
 
 9am - what happens.
 
 1. We look at the timezones and figure out which ones should be dialling now.
 $tz_db_name[] = $row['name'];
 $tz_db_start[] = $row['start'];
 $tz_db_end[] = $row['end'];
 
 
 */

$time_now = strtotime(date("H:i:s")); 

echo "Time Now: ".$time_now." (".date("H:i:s").")<br /><br />";

$tz = "(";
$tz_count = 0;
for ($i = 0;$i < count($tz_db_start);$i++) {
    
    $tz_start = strtotime($tz_db_start[$i]);
    $tz_end = strtotime($tz_db_end[$i]);
    $tz_name = $tz_db_name[$i];
    
    echo "$tz_name Start: $tz_start ($tz_db_start[$i]) End: $tz_end ($tz_db_end[$i])";
    if ($tz_start <= $time_now) {
        echo " Start time right";
        if ($tz_end >= $time_now) {
            echo " End time right ";
            $tz.=sanitize($tz_name).",";
            $tz_count++;
        } else {
            echo " End time <b>not</b> right ";
        }
    } else {
        echo " <b>Too early to start</b> ";
        if ($tz_end >= $time_now) {
            echo " End time right ";
        } else {
            echo " End time <b>not</b> right ";
        }
    }
    echo "<br>";
}
if ($tz_count == 0) {
    echo "There are no timezones which should receive calls at the moment<br />";
} else {
$tz = substr($tz,0,strlen($tz)-1).")";

echo "Calls with time zones in ".$tz."<br />";

/*
 2. We look at the records which have these timezones, and have had less calls than there are in the st_calls_c column and have not had a call in the past 3 hours
 */
$sql = "select leads.id, phone_home, phone_mobile, st_calls_c from leads, leads_cstm where leads.id = leads_cstm.id_c and leads_cstm.st_calls_c > 0 and leads.deleted = 0 and leads_cstm.time_zone_c in $tz";
//echo $sql;

$result = mysql_query($sql) or die(mysql_error());

//echo mysql_num_rows($result);
if (mysql_num_rows($result) == 0) {
    echo "No numbers for now";
} else {
    while ($row = mysql_fetch_assoc($result)) {
        $result2 = mysql_query("SELECT count(*) from st_calls where id = ".sanitize($row['id'])." and CURDATE() = date(event_datetime)") or die(mysql_error());
        $done = mysql_result($result2,0,0);
        if (!($done < $row['st_calls_c'])) {
            echo "Done ".$done."/".$row['st_calls_c']." for the day<br />";
        } else {
            echo "Done ".$done."/".$row['st_calls_c']." for the day - ";
            $phone_home = trim($row['phone_home']);
            $phone_mobile = trim($row['phone_home']);
            
            if (strlen($phone_home) > 0 && strlen($phone_mobile) > 0) {
                // Both set
                $rand = rand(0,1);
                //echo "Rand: ".$rand;
                if ($rand == 0) {
                    $number = $phone_home;
                } else {
                    $number = $phone_mobile;
                }
            } else if (strlen($phone_home) > 0) {
                // Home set
                $number = $phone_home;
            } else if (strlen($phone_mobile) > 0) {
                // Mobile set
                $number = $phone_mobile;
            }
            $call = false;
            
            $result_x = mysql_query("SELECT event_datetime from st_calls WHERE id = ".sanitize($row['id'])." order by event_datetime desc limit 1");
            if (mysql_num_rows($result_x) > 0) {
                // Have a previous call
                $last_call = mysql_result($result_x,0,0);
                $last_time = strtotime($last_call);
                $hours_ago = round((($time_now - $last_time)/60/60),2);
                //echo "Last Call: $last_time vs $time_now (".$last_call.") for $number ($hours_ago hours ago)<br />";                
                if ($hours_ago > 3) {
                    echo "Last call was $hours_ago hours ago (i.e. more than 3 hours)<br />";
                    $call = true;
                } else {
                    echo "Last call was too recent<br />";
                    $call = false;
                }
            } else {
                echo "No last call for $number<br />";
                $call = true;
            }
            // Do call this number
        }
        
        if ($call) {
            $result_tier = mysql_query("SELECT st_tier_c FROM leads_cstm WHERE id_c = ".sanitize($row['id'])) or die (mysql_error());
            $tier = mysql_result($result_tier,0,0);
            echo "Sending across $number to tier $tier<br />";
        }
        // Find last call for this id
        
        
        
        //        print_pre($row);
        flush();
        //exit(0);
    }
}
}
/*
 3. Copy these numbers across
 
 */







?>
