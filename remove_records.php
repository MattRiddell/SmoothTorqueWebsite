<?

function flush_now() {
    
    @apache_setenv('no-gzip', 1);
    @ini_set('output_buffering', 0);
    @ini_set('zlib.output_compression', 0);
    @ini_set('implicit_flush', 1);
    for ($i = 0; $i < ob_get_level(); $i++) { ob_end_flush(); }
    ob_implicit_flush(1);
    return true;
    
}
$tz = date_default_timezone_get();
date_default_timezone_set($tz);
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
$urgent_sources = Array();
if (mysql_num_rows($result) > 0) {
    while ($row = mysql_fetch_assoc($result)) {
        $urgent_sources[] = $row['name'];
    }
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


$result = mysql_query("SELECT id, name FROM lc_customstatus WHERE name like 'CA - Left Message%'");
$status_left_messages = "(";
$statuse_names = array();
while ($row = mysql_fetch_assoc($result)) {
	$status_left_messages .= sanitize($row['id']).",";
    $status_names[$row['id']] = $row['name'];
}
$status_left_messages = substr($status_left_messages,0,strlen($status_left_messages)-1).")";

$result = mysql_query("SELECT id FROM lc_customstatus WHERE name = 'new'");
$new_status = mysql_result($result,0,0);


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
flush_now();
if ($tz_count == 0) {
    echo "There are no timezones which should receive calls at the moment<br />";
    
    
    
} else {
    $tz = substr($tz,0,strlen($tz)-1).")";
    
    echo "Remove calls with time zones not in ".$tz."<br />";
    
    $sql = "select phone_home, phone_mobile from leads, leads_cstm where leads.id = leads_cstm.id_c and leads.deleted = 0 and leads_cstm.time_zone_c not in $tz and (leads.status = '$new_status' or leads.status in $status_left_messages)";
    //echo $sql;
    
    $result = mysql_query($sql) or die(mysql_error());
    
    //echo mysql_num_rows($result);
    if (mysql_num_rows($result) == 0) {
        echo "No numbers for now";
    } else {
        $sql = array();
        while ($row = mysql_fetch_assoc($result)) {
//            print_pre($row);
            $number_1 = ereg_replace("[^0-9]", "", $row['phone_home']);            
            $number_2 = ereg_replace("[^0-9]", "", $row['phone_mobile']);
            if (strlen($number_1) > 0) {
                $sql[] = "DELETE FROM SineDialer.number WHERE phonenumber = '$number_1' AND status = 'new'";
            }
            if (strlen($number_2) > 0) {
                $sql[] = "DELETE FROM SineDialer.number WHERE phonenumber = '$number_2' AND status = 'new'";
            }
        }
        include "/".$current_directory."/admin/db_config.php";
        echo "Removing ".count($sql)." records<br />";
        $x = 0;    
        foreach ($sql as $sql_entry) {
            echo ".";
            $x++;
            if ($x > 80) {
                echo "<br />";
                flush();
            }
            mysql_query($sql_entry);
        }
        echo "<br />Done";
    }
}



?>
