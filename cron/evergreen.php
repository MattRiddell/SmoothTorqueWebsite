#!/usr/bin/php
<?
$hour = Date("H");
$min = Date("m");
$cmd = 'ssh root@x.x.x.x "asterisk -rx \"agent show\""|grep "logged in on"|wc -l';
$agents=exec($cmd);
if ($agents == 0) {
	echo "Currently No Agents Logged in - doing nothing\n";
	exit(0);
}
// else {
//echo $agents;
//exit(0);
//}
//	if (   ( ($hour ==7 && $min > 35) || $hour >7)  && ($hour < 21)) {
//	if (    ($hour ==6 || $hour >6)  && ($hour < 21)) {

//exit(0);
	$link = mysql_connect("localhost", "root", "") OR die(mysql_error());
	$result = mysql_query("SELECT id, name FROM SineDialer.campaign WHERE evergreen = 1 order by name");
	if (mysql_num_rows($result) > 0) {
		echo "========================================\n";
		while ($row = mysql_fetch_assoc($result)) {
			echo "Found EverGreen Campaign: $row[name]\n";
			$result2 = mysql_query("SELECT * FROM SineDialer.queue WHERE campaignID = ".$row['id']);
			if (mysql_num_rows($result2) > 0) {
				while ($row2 = mysql_fetch_assoc($result2)) {
//					if (($row2['status'] == 0 || $row2['status'] == -1) && substr($row2['queuename'],0,9) == "autostart") {
					if (($row2['status'] != 101 || $row2['status'] == -1) && substr($row2['queuename'],0,9) == "autostart") {
						$result_x = mysql_query("SELECT count(*) FROM SineDialer.number WHERE status = 'new' AND campaignid = '".$row['id']."'");
						$num_of_num = mysql_result($result_x,0,0);
						if ($num_of_num > 0) {
							echo "Numbers in campaign but not running\n";
							$result3 = mysql_query("UPDATE SineDialer.queue SET status = 1 WHERE queueID = ".$row2['queueID']);
						} else {
							echo "No numbers in campaign ".$row['id']."\n";
						}
					} else {
						echo "Not restarting because status of campaign id ".$row['id']." is $row2[status]\n";
					}
				}
			} else {
				echo "No queue entries found though\n";
			}
			echo "========================================\n";
		}
	} else {
		echo "No evergreen campaigns\n";
	}
//}
?>
