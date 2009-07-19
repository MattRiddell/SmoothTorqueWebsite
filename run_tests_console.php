<?
/* Length of time for each campaign */
$length = 800;

/* Minimum number of agents to test with */
$agents_low = 5;

/* Maximum number of agents to test with */
$agents_high = 15;

/* Calls per second to use as max for trunks */
$cps_high = 25;

/* Minimum number of channels available to the campaign */
$chans_low = 100;

/* Maximum number of channels available to the campaign */
$chans_high = 1500;

/* Expected answer rate for the campaign (0-100 */
$expected_rate = 1;

/* How many speed runs to do - not currently used */
$runs = 1 ;

/* How many channel number variations to create */
$runs_chans = 3;

/* How many agent number variations to create */
$runs_agents = 5;

/* How long between campaigns - seconds */
$delay = 60;

/* How many campaigns to run at the same time */
$simul = 5;

/* How long to offset the initial start to                   */
/* (should be longer than it takes to create the db entries) */
$initial_delay = 300;

/* Offset */
$runs-=1;
$runs_chans-=1;
$runs_agents-=1;

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

echo "SmoothTorque Test bed\n";
echo "=====================\n";
echo "\n";
echo "Deleting existing load simulation queue entries...\n";
$result = mysql_query("DELETE from queue where queuename like 'load-sim-%'");
echo "Reading id of existing load simulation campaigns...\n";
$result = mysql_query("SELECT id FROM campaign WHERE name like 'load-sim%'");
if (mysql_num_rows($result) > 0) {
	while ($row = mysql_fetch_assoc($result)) {
		echo "Deleting numbers from campaign id ".$row[id]."...\n";
		$sql = "DELETE FROM number WHERE campaignid = ".$row[id];
		$result_delete = mysql_query($sql);
	}
}
echo "Deleting existing load simulation campaigns...\n";
$result = mysql_query("DELETE from campaign where name like 'load-sim-%'");
echo "Deleting existing load simulation trunks...\n";
$result = mysql_query("DELETE from trunk where name like 'load-sim-%'");
//exit(0);
echo "Creating new trunk for load sim...\n";
$trunk_sql = 'INSERT INTO trunk (name, dialstring, maxchans, maxcps) VALUES (\'load-sim-0\', \'Local/s@staff/${EXTEN}\', '.$chans_high.', \''.$cps_high.'\')';
echo $trunk_sql."\n";
$result = mysql_query($trunk_sql) or die(mysql_error());
$trunkid = mysql_insert_id();
	
foreach ($run_array as $run_num=>$parameters) {
	echo "Run number ".($run_num+1)." starting at ".sec2hms($time_start)." - Max Chans: ".$parameters['chans']." Max CPS: ".$parameters['speed']." Agents: ".round($parameters['agents'])."\n";
	//echo "Creating trunk with max chans of ".$parameters['chans']." and max cps of ".$parameters['speed']."\n";
	$campaign_sql = 'INSERT INTO campaign (campaignconfigid, name, description, clid, maxagents, did, context) VALUES ('.round($parameters['chans']).',\'load-sim-'.$z.'\', \'Test with '.round($parameters['agents']).' agents\', \'ls'.($x+3).'\', '.round($parameters['agents']).', \'ls'.($x+3).'\', \'0\')';
	echo $campaign_sql."\n";
	$result = mysql_query($campaign_sql) or die(mysql_error());
	$campaignid = mysql_insert_id();
	
	echo "Adding ".($length * $parameters['speed'])." numbers\n";
	flush();
	for ($i = 0;$i < ($length * $parameters['speed']);$i++) {
		$number_sql = "INSERT INTO number (campaignid, phonenumber, status, random_sort) VALUES ('$campaignid','$i','new', RAND()*99999999)";
		$result = mysql_query($number_sql) or die(mysql_error());
		//echo "Number: $number_sql\n";
		//flush();
	}
	
	
	$starttime = 'TIME(NOW() + INTERVAL '.($initial_delay + $time_start).' SECOND)';
	$stoptime = 'TIME(NOW() + INTERVAL '.($initial_delay + $length + $time_start).' SECOND)';
	$startdate = 'DATE(NOW())';
	$enddate = 'DATE(NOW())';
	$queue_start_sql = 'INSERT INTO queue (queuename, status, campaignID, starttime, endtime, startdate, enddate, did, clid, context, maxcalls, maxchans, expectedRate, trunk, trunkid, maxcps, customerID) VALUES ';
	$queue_start_sql .= '(\'load-sim-start-'.$z.'\', 1, '.$campaignid.', '.$starttime.', \'23:59:59\', '.$startdate.', '.$enddate.', \'ls'.($x+3).'\', \'ls'.($x+3).'\', 0, '.$parameters['agents'].', \''.round($parameters['chans']).'\', \''.$expectedrate.'\', \'Local/s@staff/${EXTEN}\', '.$trunkid.', \''.$parameters['speed'].'\', '.$x.')';
	echo $queue_start_sql."\n";
	$result = mysql_query($queue_start_sql) or die(mysql_error());
	$queue_stop_sql = 'INSERT INTO queue (queuename, status, campaignID, starttime, endtime, startdate, enddate, did, clid, context, maxcalls, maxchans, expectedRate, trunk, trunkid, maxcps, customerID) VALUES ';
	$queue_stop_sql .= '(\'load-sim-stop-'.$z.'\', 2, '.$campaignid.', '.$stoptime.', \'23:59:59\', '.$startdate.', '.$enddate.', \'ls'.($x+3).'\', \'ls'.($x+3).'\', 0, '.$parameters['agents'].', \''.round($parameters['chans']).'\', \''.$expectedrate.'\', \'Local/s@staff/${EXTEN}\', '.$trunkid.', \''.$parameters['speed'].'\', '.$x.')';
	echo $queue_stop_sql."\n";
	$result = mysql_query($queue_stop_sql) or die(mysql_error());
	//echo "Creating campaign with max agents of ".round($parameters['agents'])."\n";
	//echo "Creating schedule to start at zero hour + ".sec2hms($time_start)." seconds and stop of ".sec2hms($time_start+$length)." \n";
	$x ++;
	if ($x == $simul) {
		$x = 0;
		$time_start += $delay + $length;
	}
	$z++;
	echo "<hr />";
	flush();
}
echo "End to end length: ".sec2hms($time_start+$length+$delay)."\n";
echo "Running for $length each run seconds\n";
echo "Expected Rate: $expected_rate\n";
echo "$z Total Runs\n";

//print_pre($run_array);

//require "footer.php";
exit(0);

?>
