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



if (!isset($_GET['number'])) {
        ?>
        <form action="pop.php" method="get">
        <input type="text" name="number">
        <input type="submit" value="Lookup Number">
        </form>
        <?
} else {
$db_host = $config_values['SUGAR_HOST'];
$db_user = $config_values['SUGAR_USER'];
$db_pass = $config_values['SUGAR_PASS'];

$link = mysql_connect($db_host, $db_user, $db_pass) OR die(mysql_error());
mysql_select_db($config_values['SUGAR_DB'], $link);


if ($_GET['queue'] == "incoming-appointment" || $_GET['queue'] == "incoming-new") {
	if (strlen(trim($_GET['number'])) == 0) {
		$_GET['number'] = $_GET['name'];
	}
} else {
if (substr($_GET['queue'],0,8) == "incoming") {
	if (isset($_GET['name'])) {
	$msg= "&msg=Incoming Call From ".$_GET['name'];
	} else {
	$msg= "&msg=Incoming Call From ".$_GET['queue'];
	}
?>
<script language="javascript">
window.location='http://192.168.1.17/sugarcrm/index.php?module=Leads&action=EditView&return_module=Leads&return_action=DetailView&record=<?=$row['id']?><?=$msg?>';
</script>

<?
}
}



$result = mysql_query("SELECT id FROM ".$config_values['SUGAR_DB'].".leads, ".$config_values['SUGAR_DB'].".leads_cstm WHERE (phone_home = '$_GET[number]' or phone_mobile='$_GET[number]') and leads.id = leads_cstm.id_c order by date_modified DESC limit 1") or die(mysql_error());
//$result = mysql_query("SELECT id FROM ".$config_values['SUGAR_DB'].".leads, ".$config_values['SUGAR_DB'].".leads_cstm WHERE (phone_home = '$_GET[number]' or phone_mobile='$_GET[number]') and leads.id = leads_cstm.id_c and leads_cstm.st_calls_c > 0 order by date_modified DESC limit 1") or die(mysql_error());
//SELECT id FROM sugarcrm.leads, sugarcrm.leads_cstm WHERE (phone_home = '$num' or phone_mobile = '$num') and deleted = 0 and sugarcrm.leads.id = sugarcrm.leads_cstm.id_c and sugarcrm.leads_cstm.st_calls_c > 0







if (mysql_num_rows($result) == 0) {
$result = mysql_query("SELECT id FROM ".$config_values['SUGAR_DB'].".leads, ".$config_values['SUGAR_DB'].".leads_cstm WHERE (phone_home = '$_GET[number]' or phone_mobile='$_GET[number]') and leads.id = leads_cstm.id_c order by date_modified DESC limit 1");
} 
if (false) {
} else {
        while ($row = mysql_fetch_assoc($result)) {




$sql = "select count(*) from st_vm where id = ".sanitize($row['id'])." and date(event_datetime) = CURDATE()";

$result = mysql_query($sql) or die(mysql_error());
$count_today = mysql_result($result,0,0);

//echo "/";

$sql = "select st_vm_c from leads_cstm where id_c = ".sanitize($row['id']);

$result = mysql_query($sql) or die(mysql_error());
if (mysql_num_rows($result) == 0) {
	$required_today = 1;
} else {
	$required_today = mysql_result($result,0,0);
	if (strlen($require_today) < 1) {
		$required_today = 1;
	}
}
$msg = "&msg=";

if ($count_today < $required_today) {
	$vm = "&vm=1";
	$msg.= "Please leave a voicemail";
} else {
	$vm = "";
	$msg.= "Don\'t leave a voicemail";
}






	$msg.= "";
echo "Redirecting to: ";
?>
http://192.168.1.17/sugarcrm/index.php?module=Leads&offset=5&action=DetailView&record=<?=$row['id']?><?=$msg.$vm?>
<script language="javascript">
window.location='http://192.168.1.17/sugarcrm/index.php?module=Leads&offset=5&action=DetailView&record=<?=$row['id']?><?=$msg.$vm?>';
</script>

<?


?>

<?
}

}
}
?>
