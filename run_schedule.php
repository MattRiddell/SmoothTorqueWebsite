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

/* Load in the database connection values and chose the database name */
include "admin/db_config.php";
mysql_select_db("SineDialer", $link);

echo "x";
$hour = date("H");
$minute = date("i");
echo "Hour: $hour Minute: $minute";

echo "Starting now:<br />";
$result = mysql_query("SELECT * FROM schedule WHERE start_hour = $hour AND start_minute = $minute");
while ($row = mysql_fetch_assoc($result)) {
    print_pre($row);
}

echo "Ending now:<br />";
$result = mysql_query("SELECT * FROM schedule WHERE end_hour = $hour AND end_minute = $minute");
while ($row = mysql_fetch_assoc($result)) {
    print_pre($row);
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
