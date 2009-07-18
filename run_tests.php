<?

require "header.php";
if (isset($_POST['length'])) {
	//print_pre($_POST);
	echo "<br />";
	$length = $_POST['length'];
	$agents_low = $_POST['agents_low'];
    $agents_high = $_POST['agents_high'];
    $cps_high = $_POST['cps_high'];
    $chans_low = $_POST['chans_low'];
    $chans_high = $_POST['chans_high'];
    $expected_rate = $_POST['expected_rate'];
    $runs = $_POST['runs_speed'] -1;
    $runs_chans = $_POST['runs_chans'] -1;
    $runs_agents = $_POST['runs_agent']-1;
    $delay = $_POST['delay'];
    $simul = $_POST['simul'];
    $initial_delay = 30;
    foreach ($_POST as $key=>$value) {
    	if (substr($key,0,6) == "server") {
    		$server_ids[] = substr($key,7);
    	}
    }
    $z = 0;
	
		/* If there are more than two, use the start and end in the array */
		for ($i = 0; $i <= $runs; $i++) {
			for ($x = 0; $x <= $runs_agents; $x++) {
				for ($k = 0; $k <= $runs_chans; $k++) {
					$run_array[$z][speed] =  $cps_high;
					$run_array[$z][agents] = (((($x)/$runs_agents)*($agents_high-$agents_low))+$agents_low);
					$run_array[$z][chans] = (((($k)/$runs_chans)*($chans_high-$chans_low))+$chans_low);
					$run_array[$z][length] = $length;
					$z++;
				}
			}
		}
	
	/* ==================== */
	$time_start = 0;
	$x = 0;
	$z = 0;
	echo "<hr />";
	$result = mysql_query("DELETE from queue where queuename like 'load-sim-%'");
	$result = mysql_query("SELECT id FROM campaign WHERE name like 'load-sim%'");
	if (mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_assoc($result)) {
			$sql = "DELETE FROM number WHERE campaignid = ".$row[id];
			$result_delete = mysql_query($sql);
		}
	}
	$result = mysql_query("DELETE from campaign where name like 'load-sim-%'");
	$result = mysql_query("DELETE from trunk where name like 'load-sim-%'");
	//exit(0);
	$trunk_sql = 'INSERT INTO trunk (name, dialstring, maxchans, maxcps) VALUES (\'load-sim-0\', \'Local/s@staff/${EXTEN}\', '.$chans_high.', \''.$cps_high.'\')';
	echo $trunk_sql."<br />";
	$result = mysql_query($trunk_sql) or die(mysql_error());
	$trunkid = mysql_insert_id();
		
	foreach ($run_array as $run_num=>$parameters) {
		echo "<b>Run number ".($run_num+1)."</b> starting at ".sec2hms($time_start)." - Max Chans: ".$parameters['chans']." Max CPS: ".$parameters['speed']." Agents: ".round($parameters['agents'])."<br />";
		//echo "Creating trunk with max chans of ".$parameters['chans']." and max cps of ".$parameters['speed']."<br />";
		$campaign_sql = 'INSERT INTO campaign (campaignconfigid, name, description, clid, maxagents, did, context) VALUES ('.round($parameters['chans']).',\'load-sim-'.$z.'\', \'Test with '.round($parameters['agents']).' agents\', \'ls'.($x+3).'\', '.round($parameters['agents']).', \'ls'.($x+3).'\', \'0\')';
		echo $campaign_sql."<br />";
		$result = mysql_query($campaign_sql) or die(mysql_error());
		$campaignid = mysql_insert_id();
		
		echo "<p>Adding ".($length * $parameters['speed'])." numbers</p><br />";
		flush();
		for ($i = 0;$i < ($length * $parameters['speed']);$i++) {
			$number_sql = "INSERT INTO number (campaignid, phonenumber, status) VALUES ('$campaignid','$i','new')";
			$result = mysql_query($number_sql) or die(mysql_error());
			//echo "Number: $number_sql<br />";
			//flush();
		}
		
		
		$starttime = 'TIME(NOW() + INTERVAL '.($initial_delay + $time_start).' SECOND)';
		$stoptime = 'TIME(NOW() + INTERVAL '.($initial_delay + $length + $time_start).' SECOND)';
		$startdate = 'DATE(NOW())';
		$enddate = 'DATE(NOW())';
		$queue_start_sql = 'INSERT INTO queue (queuename, status, campaignID, starttime, endtime, startdate, enddate, did, clid, context, maxcalls, maxchans, expectedRate, trunk, trunkid, maxcps, customerID) VALUES ';
		$queue_start_sql .= '(\'load-sim-start-'.$z.'\', 1, '.$campaignid.', '.$starttime.', \'23:59:59\', '.$startdate.', '.$enddate.', \'ls'.($x+3).'\', \'ls'.($x+3).'\', 0, '.$parameters['agents'].', \''.round($parameters['chans']).'\', \''.$expectedrate.'\', \'Local/s@staff/${EXTEN}\', '.$trunkid.', \''.$parameters['speed'].'\', '.$x.')';
		echo $queue_start_sql."<br />";
		$result = mysql_query($queue_start_sql) or die(mysql_error());
		$queue_stop_sql = 'INSERT INTO queue (queuename, status, campaignID, starttime, endtime, startdate, enddate, did, clid, context, maxcalls, maxchans, expectedRate, trunk, trunkid, maxcps, customerID) VALUES ';
		$queue_stop_sql .= '(\'load-sim-stop-'.$z.'\', 2, '.$campaignid.', '.$stoptime.', \'23:59:59\', '.$startdate.', '.$enddate.', \'ls'.($x+3).'\', \'ls'.($x+3).'\', 0, '.$parameters['agents'].', \''.round($parameters['chans']).'\', \''.$expectedrate.'\', \'Local/s@staff/${EXTEN}\', '.$trunkid.', \''.$parameters['speed'].'\', '.$x.')';
		echo $queue_stop_sql."<br />";
		$result = mysql_query($queue_stop_sql) or die(mysql_error());
		//echo "Creating campaign with max agents of ".round($parameters['agents'])."<br />";
		//echo "Creating schedule to start at zero hour + ".sec2hms($time_start)." seconds and stop of ".sec2hms($time_start+$length)." <br />";
		$x ++;
		if ($x == $simul) {
			$x = 0;
			$time_start += $delay + $length;
		}
		$z++;
		echo "<hr />";
		flush();
	}
	echo "End to end length: ".sec2hms($time_start+$length+$delay)."<br />";
    echo "Running for $length each run seconds<br />";
    echo "Expected Rate: $expected_rate<br />";
	echo "$z Total Runs<br />";
	
	//print_pre($run_array);
	
	require "footer.php";
	exit(0);
}
?>

<center>
<?
box_start(600);
?>
<h2>Run Tests</h2>
<?/*
All tests run at same time
<p>
1. Read current settings<br />
2. Set up for load sim<br />
3. Restore previous settings<br />
4. View report<br />
</p>
*/?>
<p>
<form action = "run_tests.php" method="post">
Length of each run (in seconds): <input type="text" name="length" value="600"><br />
Lowest number of agents: <input type="text" name="agents_low" value="5"><br />
Highest number of agents: <input type="text" name="agents_high" value="15"><br />
Calls Per Second: <input type="text" name="cps_high" value="10"><br />
Lowest Max Channels: <input type="text" name="chans_low" value="100"><br />
Highest Max Channels: <input type="text" name="chans_high" value="300">
<input type="hidden" name="runs_speed" value="1"><br />
Number of agent tests to run: <input type="text" name="runs_agent" value="10"><br />
Number of max chan tests to run: <input type="text" name="runs_chans" value="3"><br />
Expected Transfer Rate (0-100): <input type="text" name="expected_rate" value="1"><br />
Delay between tests in seconds: <input type="text" name="delay" value="60"><br />
Simultaneous Campaigns: <input type="text" name="simul" value="5"><br />
Servers:<br />
<?
$result = mysql_query("SELECT name, id, status FROM servers");
if (mysql_num_rows($result) > 0) {
	while ($row = mysql_fetch_assoc($result)) {
		?>
		<input type="checkbox" name="server_<?=$row['id']?>" value="1" <?
		if ($row['status'] == 1) {
			echo " checked";
		}
		?>><?=$row['name']?><br />
		<?
	}
} else {
	echo "No servers available";
}
?>
<input type="submit">
</form>
</p>
<?
box_end();
require "footer.php";
?>
