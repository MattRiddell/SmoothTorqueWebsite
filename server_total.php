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

if ($_GET['ajax'] == 1) {
	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
}
?>

<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="1" />
<meta http-equiv="pragma" content="must-revalidate" />
<?
$tot =0;
$result = mysql_query("SELECT name FROM SineDialer.servers WHERE status = 1");
if (mysql_num_rows($result) > 0) {
    while ($row = mysql_fetch_assoc($result)) {
        $sql = 'SELECT value FROM SineDialer.config WHERE parameter = \'s_'.$row[name].'_calls\'';
        $resultx = mysql_query($sql) or die(mysql_error());
        if (mysql_num_rows($resultx) > 0) {
        	while ($row_x = mysql_fetch_assoc($resultx)) {
        		$tot+=$row_x[value];
        	}
        }
    }
}

/*$resulty = mysql_query("SELECT * from config where parameter = 'read_1'") or die(mysql_error());
if (mysql_num_rows($resulty) == 0) {
box_start();
echo "<br /><center><img src=\"images/icons/gtk-dialog-info.png\" border=\"0\" width=\"64\" height=\"64\">";
echo "<br /><br />I've added real time server control - you can now start and stop severs (even during a campaign) without restarting SmoothTorque";
echo "<br />";
echo "<br />";
echo '<a href="read.php">Click here once you have read the above notice</a>';
echo "<br />";
echo "<br />";
box_end();
}*/

if (!isset($_GET['nobox'])) {
    box_start();
}
echo "<center>Total channels across all servers: <b>$tot</b></center>";;
if (!isset($_GET['nobox'])) {
    box_end();
}

?>
