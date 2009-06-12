<?
$level=$_COOKIE[level];
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

$url = "default";
include "admin/db_config.php";
mysql_select_db("SineDialer", $link) or die("Unable to connect: ".mysql_error());

$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);

$tot =0;
$sql = 'SELECT value FROM SineDialer.config WHERE parameter like \'s_%_calls\'';
$resultx = mysql_query($sql) or die(mysql_error());
if (mysql_num_rows($resultx) > 0) {
	while ($row_x = mysql_fetch_assoc($resultx)) {
		$tot+=$row_x[value];
	}
}
box_start();
echo "<center>Total channels across all servers: <b>$tot</b></center>";;
box_end();


?>
