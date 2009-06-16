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
echo "<b>MySQL Statistics:<br /></b>";
$result = mysql_query("SHOW STATUS");
while ($row = mysql_fetch_assoc($result)){
        if ($row[Variable_name] == 'Slow_queries') {
                $slow_queries = $row['Value'];
        }
        if ($row[Variable_name] == 'Questions') {
                $questions = $row['Value'];
        }
        if ($row[Variable_name] == 'Uptime') {
                $uptime = sec2hms($row['Value']);
        }
        if ($row[Variable_name] == 'Connections') {
                $connections = number_format($row['Value']);
        }
}
$result = mysql_query("SELECT value FROM SineDialer.config WHERE parameter = 'mysql_queue'") or die(mysql_error());
if (mysql_num_rows($result) > 0) {
        $pending = "Pending MySQL Writes: ".mysql_result($result,0,0)." ";
}
echo "Connections: $connections Queries: ".number_format($questions)." ".$pending."Uptime: $uptime<br /><br />";

echo "<b>Long Running MySQL Threads:</b><br />";
$result = mysql_query("SHOW PROCESSLIST");
echo "<br /><center><table border=\"0\" class=\"tborder\">";
while ($row = mysql_fetch_assoc($result)) {
	if ($row[Command] != 'Sleep' && $row[Time] > 2) {
		echo "<tr>";
		if ($row[State] == 'Locked') {
			echo "<td><p align=\"left\" style=\"color:#ff0000\"><b>Locked:</b></font> $row[Info]<br /><b>Time:</b> ".sec2hms($row[Time])." <b>Host:</b> ".$row[User]."@".$row[Host]."</td>";
		} else if ($row[State] == 'updating' ||$row[State] == 'Updating' ||$row[State] == 'update') {
			echo "<td><p align=\"left\" style=\"color:#000000\"><b>Updating:</b> $row[Info]<br /><b>Time:</b> ".sec2hms($row[Time])." <b>Host:</b> ".$row[User]."@".$row[Host]."</td>";
		} else if ($row[State] == 'Sorting result') {
			echo "<td><p align=\"left\" style=\"color:#000000\"><b>Sorting result:</b> $row[Info]<br /> <b>Time:</b> ".sec2hms($row[Time])." <b>Host:</b> ".$row[User]."@".$row[Host]."</td>";
		} else if ($row[State] == 'Sending data') {
			echo "<td><p align=\"left\" style=\"color:#008800\"><b>Sending data:</b> $row[Info]<br /> <b>Time:</b> ".sec2hms($row[Time])." <b>Host:</b> ".$row[User]."@".$row[Host]."</td>";
		} else {
			if ($row[Info] != "SHOW PROCESSLIST") {
				echo "<td><p align=\"left\">";
				foreach ($row as $key=>$value) {
					echo "<b>Key:</b> $key <b>Value:</b> $value<br />";
				}
				echo "</p></td>";
			}
		}
		echo "</tr>";
		echo "<tr><td><br /></td></tr>";
	}
}
echo "</table>";
?>
