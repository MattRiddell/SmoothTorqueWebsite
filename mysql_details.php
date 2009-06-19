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
?>
<meta http-equiv="Pragma" content="no-cache" />
<?
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
		$uptime_s = $row['Value'];
        }
        if ($row[Variable_name] == 'Connections') {
                $connections = number_format($row['Value']);
        }
}
$result = mysql_query("SELECT value FROM SineDialer.config WHERE parameter = 'mysql_queue'") or die(mysql_error());
if (mysql_num_rows($result) > 0) {
        $pending = "Queued Writes: ".mysql_result($result,0,0)." ";
}
$result = mysql_query("select count(*) from SineDialer.number where random_sort = 0 or random_sort = NULL") or die(mysql_error());
$unrandomized = mysql_result($result,0,0);

echo "Queries: ".number_format($questions)." (".number_format($questions/$uptime_s,3)."/sec) ".$pending."Uptime: $uptime Unrandomized Numbers: $unrandomized<br />";

$result = mysql_query("SHOW FULL PROCESSLIST") or die(mysql_error());
$output = "";
if (mysql_num_rows($result) > 0) {
    $count = 0;
    while ($row = mysql_fetch_assoc($result)) {
    	if ($row[Command] != 'Sleep' && $row[Time] > 2) {
            $count++;
    		$output.= "<tr>";
    		$link = '<td><a href="mysql_stats.php?kill='.$row['Id'].'"><img src="images/delete.png" border="0">&nbsp;Kill this process</a></td>';
    		if ($row[State] == 'Locked') {
    			$output.= "<td><p align=\"left\" style=\"color:#ff0000\"><b>Locked:</b></font> $row[Info]<br /><b>Time:</b> ".sec2hms($row[Time])." <b>Host:</b> ".$row[User]."@".$row[Host]."$link</td>";
    		} else if ($row[State] == 'copy to tmp table') {
    			$output.= "<td><p align=\"left\" style=\"color:#880088\"><b>Copying to temp table:</b></font> $row[Info]<br /><b>Time:</b> ".sec2hms($row[Time])." <b>Host:</b> ".$row[User]."@".$row[Host]."$link</td>";
    		} else if ($row[State] == 'updating' ||$row[State] == 'Updating' ||$row[State] == 'update') {
    			$output.= "<td><p align=\"left\" style=\"color:#000000\"><b>Updating:</b> $row[Info]<br /><b>Time:</b> ".sec2hms($row[Time])." <b>Host:</b> ".$row[User]."@".$row[Host]."$link</td>";
    		} else if ($row[State] == 'Sorting result') {
    			$output.= "<td><p align=\"left\" style=\"color:#000000\"><b>Sorting result:</b> $row[Info]<br /> <b>Time:</b> ".sec2hms($row[Time])." <b>Host:</b> ".$row[User]."@".$row[Host]."$link</td>";
    		} else if ($row[State] == 'Sending data') {
    			echo "<td><p align=\"left\" style=\"color:#008800\"><b>Sending data:</b> $row[Info]<br /> <b>Time:</b> ".sec2hms($row[Time])." <b>Host:</b> ".$row[User]."@".$row[Host]."$link</td>";
    		} else {
    			if ($row[Info] != "SHOW PROCESSLIST") {
    				$output.= "<td><p align=\"left\">";
    				foreach ($row as $key=>$value) {
    					$output.= "<b>Key:</b> $key <b>Value:</b> $value<br />";
    				}
    				$output.= "</p>$link</td>";
    			}
    		}
    		$output.= "</tr>";
    	}
    }
    if ($count > 0) {
        echo "<br /><b>Long Running MySQL Threads:</b><br />";
	echo "<br /><center><table border=\"0\" class=\"tborder\">";
        echo $output;
        echo "</table>";
    }
}
?>
