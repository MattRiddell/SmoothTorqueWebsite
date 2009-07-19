<?
require "admin/db_config.php";
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

//if (isset($_POST['length'])) {
	//print_pre($_POST);
//	echo "
";

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
	echo $trunk_sql."
";
	$result = mysql_query($trunk_sql) or die(mysql_error());
	$trunkid = mysql_insert_id();
		
	foreach ($run_array as $run_num=>$parameters) {
		echo "Run number ".($run_num+1)." starting at ".sec2hms($time_start)." - Max Chans: ".$parameters['chans']." Max CPS: ".$parameters['speed']." Agents: ".round($parameters['agents'])."
";
		//echo "Creating trunk with max chans of ".$parameters['chans']." and max cps of ".$parameters['speed']."
";
		$campaign_sql = 'INSERT INTO campaign (campaignconfigid, name, description, clid, maxagents, did, context) VALUES ('.round($parameters['chans']).',\'load-sim-'.$z.'\', \'Test with '.round($parameters['agents']).' agents\', \'ls'.($x+3).'\', '.round($parameters['agents']).', \'ls'.($x+3).'\', \'0\')';
		echo $campaign_sql."
";
		$result = mysql_query($campaign_sql) or die(mysql_error());
		$campaignid = mysql_insert_id();
		
		echo "Adding ".($length * $parameters['speed'])." numbers
";
		flush();
		for ($i = 0;$i < ($length * $parameters['speed']);$i++) {
			$number_sql = "INSERT INTO number (campaignid, phonenumber, status, random_sort) VALUES ('$campaignid','$i','new', RAND()*99999999)";
			$result = mysql_query($number_sql) or die(mysql_error());
			//echo "Number: $number_sql
";
			//flush();
		}
		
		
		$starttime = 'TIME(NOW() + INTERVAL '.($initial_delay + $time_start).' SECOND)';
		$stoptime = 'TIME(NOW() + INTERVAL '.($initial_delay + $length + $time_start).' SECOND)';
		$startdate = 'DATE(NOW())';
		$enddate = 'DATE(NOW())';
		$queue_start_sql = 'INSERT INTO queue (queuename, status, campaignID, starttime, endtime, startdate, enddate, did, clid, context, maxcalls, maxchans, expectedRate, trunk, trunkid, maxcps, customerID) VALUES ';
		$queue_start_sql .= '(\'load-sim-start-'.$z.'\', 1, '.$campaignid.', '.$starttime.', \'23:59:59\', '.$startdate.', '.$enddate.', \'ls'.($x+3).'\', \'ls'.($x+3).'\', 0, '.$parameters['agents'].', \''.round($parameters['chans']).'\', \''.$expectedrate.'\', \'Local/s@staff/${EXTEN}\', '.$trunkid.', \''.$parameters['speed'].'\', '.$x.')';
		echo $queue_start_sql."
";
		$result = mysql_query($queue_start_sql) or die(mysql_error());
		$queue_stop_sql = 'INSERT INTO queue (queuename, status, campaignID, starttime, endtime, startdate, enddate, did, clid, context, maxcalls, maxchans, expectedRate, trunk, trunkid, maxcps, customerID) VALUES ';
		$queue_stop_sql .= '(\'load-sim-stop-'.$z.'\', 2, '.$campaignid.', '.$stoptime.', \'23:59:59\', '.$startdate.', '.$enddate.', \'ls'.($x+3).'\', \'ls'.($x+3).'\', 0, '.$parameters['agents'].', \''.round($parameters['chans']).'\', \''.$expectedrate.'\', \'Local/s@staff/${EXTEN}\', '.$trunkid.', \''.$parameters['speed'].'\', '.$x.')';
		echo $queue_stop_sql."
";
		$result = mysql_query($queue_stop_sql) or die(mysql_error());
		//echo "Creating campaign with max agents of ".round($parameters['agents'])."
";
		//echo "Creating schedule to start at zero hour + ".sec2hms($time_start)." seconds and stop of ".sec2hms($time_start+$length)." 
";
		$x ++;
		if ($x == $simul) {
			$x = 0;
			$time_start += $delay + $length;
		}
		$z++;
		echo "<hr />";
		flush();
	}
	echo "End to end length: ".sec2hms($time_start+$length+$delay)."
";
    echo "Running for $length each run seconds
";
    echo "Expected Rate: $expected_rate
";
	echo "$z Total Runs
";
	
	//print_pre($run_array);
	
	require "footer.php";
	exit(0);
}
?>
