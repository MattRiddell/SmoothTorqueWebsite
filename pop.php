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
$result = mysql_query("SELECT id FROM ".$config_values['SUGAR_DB'].".leads WHERE phone_home = '$_GET[number]' or phone_mobile='$_GET[number]' order by date_modified DESC limit 1");
if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_assoc($result)) {
?>
<script language="javascript">
window.location='http://192.168.1.17/sugarcrm/index.php?module=Leads&offset=5&action=DetailView&record=<?=$row['id']?>';
</script>

<?
        }
}
}
?>
