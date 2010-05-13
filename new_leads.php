#!/usr/bin/php
<?


echo "<h2>Running Clean_tz</h2>";
require "clean_tz.php";

echo "<h2>Running Clean_debt</h2>";
require "clean_debt.php";





echo "<h2>Running tier stuff</h2>";


$sql1 = "UPDATE leads INNER JOIN leads_cstm ON leads.id = leads_cstm.id_c
    INNER JOIN lc_customstatus ON leads.status = lc_customstatus.id
SET leads_cstm.st_tier_c = 1, leads_cstm.st_vm_c = 1, leads_cstm.st_calls_c = 4, leads_cstm.st_priority_c = 90
WHERE 

CONVERT_TZ(leads.date_entered, '+0:00', 'SYSTEM') > date_sub(FROM_UNIXTIME(UNIX_TIMESTAMP()), interval 10 MINUTE)

AND (lc_customstatus.name = 'New' OR lc_customstatus.name = 'Left Message 01') AND leads.deleted = 0 AND leads_cstm.st_tier_c IS NULL AND leads_cstm.debt_amt_c > 19999";

$result = mysql_query($sql1) or die(mysql_error());
//echo $sql1;
//exit(0);
//leads.date_entered > '2010-04-29' 
//CONVERT_TZ(date_entered, '+0:00', 'SYSTEM') > date_sub(FROM_UNIXTIME(UNIX_TIMESTAMP()), interval 10 MINUTE)

$sql2 = "UPDATE leads INNER JOIN leads_cstm ON leads.id = leads_cstm.id_c
    INNER JOIN lc_customstatus ON leads.status = lc_customstatus.id
SET leads_cstm.st_tier_c = 2, leads_cstm.st_vm_c = 1, leads_cstm.st_calls_c = 4, leads_cstm.st_priority_c = 90
WHERE 
CONVERT_TZ(leads.date_entered, '+0:00', 'SYSTEM') > date_sub(FROM_UNIXTIME(UNIX_TIMESTAMP()), interval 10 MINUTE)

 AND (lc_customstatus.name = 'New' OR lc_customstatus.name = 'Left Message 01') AND leads.deleted = 0 AND leads_cstm.st_tier_c IS NULL AND leads_cstm.debt_amt_c < 20000";





$result = mysql_query($sql2) or die(mysql_error());






echo "<h2>Running New_leads</h2>";
$current_directory = dirname(__FILE__);
include "/".$current_directory."/admin/db_config.php";

$queue_channel_tier1 = "Local/775@agents/n";
$queue_channel_tier2 = "Local/774@agents/n";



$result = mysql_query("SELECT * FROM urgent_lead_sources") or die(mysql_error());
$urgent_sources = Array();
if (mysql_num_rows($result) > 0) {
    while ($row = mysql_fetch_assoc($result)) {
        $urgent_sources[] = $row['name'];
    }
}
$sources = "(";
foreach ($urgent_sources as $source) {
	$sources.="'$source',";
}
$sources = substr($sources,0,strlen($sources)-1).")";
//echo "<pre>";
//echo $sources;

//$link = mysql

$link = mysql_connect("192.168.1.17", "popper", "pass*()") OR die(mysql_error());

$result = mysql_query("SELECT id FROM sugarcrm.lc_customstatus WHERE name = 'new'");
$new_status = mysql_result($result,0,0);
//echo "<br />";
$sql = "select sugarcrm.leads.id, sugarcrm.leads_cstm.st_tier_c, lead_source, CONVERT_TZ(sugarcrm.leads.date_entered, '+0:00', 'SYSTEM') as date_entered, phone_home, phone_mobile from sugarcrm.leads, sugarcrm.leads_cstm
where lead_source in $sources and status = 'd79727e9-10b0-52f4-8a40-4a021a79adde' and deleted = 0
and CONVERT_TZ(sugarcrm.leads.date_entered, '+0:00', 'SYSTEM') > date_sub(FROM_UNIXTIME(UNIX_TIMESTAMP()),interval 1 minute)
and CONVERT_TZ(sugarcrm.leads.date_entered, '+0:00', 'SYSTEM') < date_add(FROM_UNIXTIME(UNIX_TIMESTAMP()),interval 1 second)
and sugarcrm.leads_cstm.id_c = sugarcrm.leads.id
";
$result = mysql_query($sql) or die(mysql_error());
$numbers = array();
$tiers = array();
if (mysql_num_rows($result)>0) {
	while ($row = mysql_fetch_assoc($result)) {
//		echo "Number: ".$row['phone_home']."<br />";
//		print_r($row);
		$phone_mobile = $row['phone_mobile'];
	        $phone_home = $row['phone_home'];
		$id = $row['id'];

		$phone_home_stripped = preg_replace('/\D/', '', $phone_home);
	        $phone_mobile_stripped = preg_replace('/\D/', '', $phone_mobile);

		if ($phone_home_stripped != $phone_home) {
			 $update_result = mysql_query("UPDATE sugarcrm.leads SET phone_home = '".$phone_home_stripped."' WHERE id = '".$id."' LIMIT 1");
		}
		if ($phone_mobile_stripped != $phone_mobile) {
			 $update_result = mysql_query("UPDATE sugarcrm.leads SET phone_mobile = '".$phone_mobile_stripped."' WHERE id = '".$id."' LIMIT 1");
		}
		
		$phone_home = $phone_home_stripped;
		$phone_mobile = $phone_mobile_stripped;
        	$time_to_call = $row['time_to_call'];
	        if (strlen($phone_home) > 0 && strlen($phone_mobile) > 0) {
        	        // Both set
                	$rand = rand(0,1);
	                //echo "Rand: ".$rand;
        	        if ($rand == 0) {
                	    $number = $phone_home;
	                } else {
        	            $number = $phone_mobile;
	                }
	        } else if (strlen($phone_home) > 0) {
        	        // Home set
                	$number = $phone_home;
	        } else if (strlen($phone_mobile) > 0) {
        	        // Mobile set
                	$number = $phone_mobile;
	        }
		$tier = $row['st_tier_c'];
		echo "Number: $number (Tier $tier)<br />";
		$numbers[] = $number;
		$ids[$number] = $id;
		if (strlen($tier) <1) {
			$tier = 1;
		}
		$tiers[$number] = $tier;
	}
}
//echo "</pre>";
//exit(0);

if (sizeof($numbers) > 0) {
	$oSocket = fsockopen("192.168.1.14", 5038, $errnum, $errdesc) or die("Connection to host failed");
	fputs($oSocket, "Action: login\r\n");
	fputs($oSocket, "Events: off\r\n");
	fputs($oSocket, "Username: agentpopper\r\n");
	fputs($oSocket, "Secret: poppass\r\n\r\n");
	sleep(2);
	foreach ($numbers as $number) {

		echo "Checking Timezone for $number<br />";
		// CHECK TIMEZONE IS OK
		$id = $ids[$number];
		$link = mysql_connect("192.168.1.17", "popper", "pass*()") OR die(mysql_error());


	        $result_tz = mysql_query("SELECT time_zone_c FROM sugarcrm.leads_cstm WHERE id_c = '$id'") or die (mysql_error());
                $tz = mysql_result($result_tz,0,0);
                mysql_pconnect("localhost","root","");
                $result_tz2 = mysql_query("SELECT * FROM SineDialer.time_zones WHERE name = '$tz'");
                if (mysql_num_rows($result_tz2) == 0) {
	                $result_tz2 = mysql_query("SELECT * FROM SineDialer.time_zones WHERE name = 'UNSET'");
			echo "Found Timezone UNSET<br />";
                } else {
			echo "Found Timezone $tz<br />";
                }
                $row_tz = mysql_fetch_assoc($result_tz2);
                $time_now = strtotime(date("H:i:s"));
                $tz_start = strtotime($row_tz['start']);
                $tz_end = strtotime($row_tz['end']);
                if (!($time_now< $tz_end && $time_now > $tz_start)) {
			echo "Not allowed to call this record at the moment ($number)<br />";
                } else {





		echo "Calling $number";
		fputs($oSocket, "Action: originate\r\n");
		if ($tiers[$number] == 1) {
			$queue_channel = $queue_channel_tier1;
			$context = "new-tier1";
		} else {
			$queue_channel = $queue_channel_tier2;
			$context = "new-tier2";
		}
		fputs($oSocket, "Channel: $queue_channel\r\n");
		fputs($oSocket, "Timeout: 3600000\r\n");
		fputs($oSocket, "CallerId: $number\r\n");
		fputs($oSocket, "Exten: $number\r\n");
		fputs($oSocket, "Context: $context\r\n");
		fputs($oSocket, "Priority: 1\r\n\r\n");
		echo fgets($oSocket);

		}


	}
	fputs($oSocket, "Action: Logoff\r\n\r\n");
	fclose($oSocket);
}
?>
