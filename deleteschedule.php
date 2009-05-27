<?
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);

$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);

$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');
if (isset($_GET[sure])){
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
    $id=sanitize($_GET[id]);
    $sql="DELETE FROM schedule where id=$id limit 1";
    $result=mysql_query($sql, $link) or die (mysql_error());;
/*================= Log Access ======================================*/
$sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_COOKIE[user]', 'Deleted a schedule')";
$result=mysql_query($sql, $link);
/*================= Log Access ======================================*/

    include("schedule.php");
    exit;
}
require "header.php";
require "header_schedule.php";

?>
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2">
<TR><TD>
Are you Sure You want to delete this record?<BR><BR>
</TD></TR>
<TR><TD>
<?

$sql = 'SELECT * FROM schedule WHERE id='.sanitize($_GET[id]);
$result=mysql_query($sql, $link) or die (mysql_error());;
while ($row = mysql_fetch_assoc($result)) {
    echo "<CENTER><B>".$row[name]." - ".$row[description]."</B><BR><BR>";
    echo '<A HREF="deleteschedule.php?id='.$_GET[id].'&sure=yes">Yes, Delete it</A><BR>';
    echo '<A HREF="schedule.php">No, Don\'t Delete It</A></CENTER>';
?>
</TD></TR>
<TR><TD>

</TD></TR>
</TABLE>
</FORM>
<?
}
require "footer.php";
?>
