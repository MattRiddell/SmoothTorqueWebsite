<?
/* If you do not want to pull the config information for the default host
 * then you _MUST_ change the value url to the url you would like to use.
 * I.E. call.venturevoip.com
 */
$url = "default";

/* Get the database connection values */
include "admin/db_config.php";
mysql_select_db("SineDialer", $link) or die("Unable to connect: ".mysql_error());
$totalcost = array();
echo "Loading config information...\n";
$result_config = mysql_query("SELECT * FROM web_config WHERE LANG = 'en' AND url = '$url'") or die(mysql_error());
if (mysql_num_rows($result_config) == 0) {
    echo "Even though we were sucessful reading the config, it has no values.  Please send an email to smoothtorque@venturevoip.com";
    exit(0);
}
/* Now that we have the config values, put them into the array */
while ($header_row = mysql_fetch_assoc($result_config) ) {
    foreach ($header_row as $key=>$value) {
        if ($key != "contact_text") {
            $config_values[strtoupper($key)] = $value;
        } else {
            $config_values["TEXT"] = $value;
        }
    }
}
echo "Config loaded\n";
$currency = $config_values['CURRENCY_SYMBOL'];
$db_host=$config_values['CDR_HOST'];
$db_user=$config_values['CDR_USER'];
$db_pass=$config_values['CDR_PASS'];

/* ==================================================================== */
/* End of config information */
/* ==================================================================== */

$sql = "Select accountcode from billing";
$result_accounts = mysql_query($sql, $link);
/* Loop through the account codes which are defined in billing */
$total_count = 0;
$start_total = time();
while ($accounts = mysql_fetch_assoc($result_accounts)) {
    $accountcode_in = $accounts['accountcode'];
    echo "Checking $accountcode_in\n";
    $cdrlink = mysql_connect($db_host, $db_user, $db_pass) OR die("Error connecting to CDR database using $db_user:$db_pass@$db_host because: \n".mysql_error());
    mysql_select_db($config_values['CDR_DB'], $cdrlink);
    $sql = "SELECT * from ".$config_values['CDR_TABLE']." WHERE userfield2 IS NULL and accountcode='$accountcode_in' and dcontext!='load-simulation'
        and dcontext!='staff' and dcontext!='ls3' and userfield!='' order by calldate DESC limit 100000";
    //echo "Running: $sql\n";
    $result = mysql_query($sql,$cdrlink);
    $start = time();
    $count = mysql_num_rows($result);
    $i = 0;
    echo mysql_num_rows($result)." Records this run for $accountcode_in\n";
    while ($row = mysql_fetch_assoc($result)) {
        $calldate[$i] = $row[calldate];
        $dcontext[$i] = $row[dcontext];
        $dst[$i] = $row[dst];
        $src[$i] = $row[src];
        $clid[$i] = $row[clid];
        $channel[$i] = $row[channel];
        $dstchannel[$i] = $row[dstchannel];
        $lastapp[$i] = $row[lastapp];
        $lastdata[$i] = $row[lastdata];
        $duration[$i] = $row[duration];
        $billsec[$i] = $row[billsec];
        $disposition[$i] = $row[disposition];
        $amaflags[$i] = $row[amaflags];
        $accountcode[$i] = $row[accountcode];
        $userfield[$i] = $row[userfield];
        $userfield2[$i] = $row[userfield2];
        if ($userfield2[$i] != 1) {
            $userfield2[$i] = 0;
        }
        $display = true;
        if (!($customerid[$accountcode[$i]]>0)) {
            mysql_select_db("SineDialer", $link);

            $sqlx = "SELECT * from SineDialer.billing where accountcode = '".$accountcode[$i]."'";
            //echo $sqlx;

            $resultx = mysql_query($sqlx,$link);
            $priceperminute[$accountcode[$i]] = mysql_result($resultx, 0, 'priceperminute');
            //echo mysql_result($resultx, 0, 'priceperminute');
            $customerid[$accountcode[$i]] = mysql_result($resultx, 0, 'customerid');
            $firstperiod[$accountcode[$i]] = mysql_result($resultx, 0, 'firstperiod');
            $increment[$accountcode[$i]] = mysql_result($resultx, 0, 'increment');
            $firstperiod[$accountcode[$i]] = mysql_result($resultx, 0, 'firstperiod');
            $credit[$accountcode[$i]] = mysql_result($resultx, 0, 'credit');
            $credit_limit[$accountcode[$i]] = mysql_result($resultx, 0, 'creditlimit');
            $pricepercall[$accountcode[$i]] = mysql_result($resultx, 0, 'pricepercall');
            $priceperconnectedcall[$accountcode[$i]] = mysql_result($resultx, 0, 'priceperconnectedcall');
            $priceperpress1[$accountcode[$i]] = mysql_result($resultx, 0, 'priceperpress1');
        }
        $cost[$i] = 0;
        $costperpress1[$i] = 0;
        $costpercall[$i] = 0;
        $costperminute[$i] = 0;
        $costperconnect[$i] = 0;

        if ($pricepercall[$accountcode[$i]] > 0) {
            if ($display) {
                $costpercall[$i] = round($pricepercall[$accountcode[$i]],2);
                $cost[$i] += $costpercall[$i];
            }
        }
        if ($disposition[$i] == "ANSWERED") {
            if ($billsec[$i] > $firstperiod[$accountcode[$i]]) {
                if ($increment[$accountcode[$i]] == 1) {
                    $costperminute[$i] = (($priceperminute[$accountcode[$i]]/60) * $billsec[$i]);
                } else {
                    /* if the increment is 30 seconds and the call is 73 seconds they should be
                     * charged for 73/30 = 2.4 blocks - round up to 3 = 3*30 = 90*/
                    $blocks = ceil($billsec[$i]/$increment[$accountcode[$i]]);
                    $newsecs = $blocks * $increment[$accountcode[$i]];
                    $costperminute[$i] = (($priceperminute[$accountcode[$i]]/60) * $newsecs);
                }
                $cost[$i]+=$costperminute[$i];
            } else {
                $costperminute[$i] = (($priceperminute[$accountcode[$i]]/60) * $firstperiod[$accountcode[$i]]);
                $cost[$i]+=$costperminute[$i];
            }
            $costperconnect[$i] = ($priceperconnectedcall[$accountcode[$i]]);
            $cost[$i]+=$costperconnect[$i];
            if ($dst[$i] == "1") {
                $costperpress1[$i] = ($priceperpress1[$accountcode[$i]]);
                $cost[$i] += $costperpress1[$i];
            }
        }
        if ($display) {
            $totalcost[$accountcode[$i]]+=$cost[$i];
            $pos = strpos($userfield[$i], '-');
            if ($pos === false) {
                // This is not a split
            } else {
                mysql_select_db("SineDialer", $link);
                $campaignid = substr($userfield[$i], $pos + 1);
                $sql = "SELECT cost FROM SineDialer.campaign WHERE id = ".$campaignid;
                $result_campaign_cost = mysql_query($sql,$link);
                if (mysql_num_rows($result_campaign_cost) > 0) {
                    $campaign_cost = mysql_result($result_campaign_cost,0,0);
                } else {
    	            $campaign_cost = 0;
    	        }
    	        $sql = "UPDATE SineDialer.campaign set cost = '".($campaign_cost+$cost[$i])."' WHERE id = ".$campaignid;
				//echo $sql."\n";
    	        mysql_query($sql,$link);
    	    }
	    	mysql_select_db($config_values['CDR_DB'], $cdrlink);
    	    $sql = "update ".$config_values['CDR_TABLE']." set userfield2 = '1' where calldate = '$calldate[$i]' and duration = '$duration[$i]' and accountcode = '$accountcode[$i]' and userfield = '$userfield[$i]'";
	    	//echo $sql."\n";
	    if (time() - $start > 0 && $count > 0) {
    	    echo $i."/$count (".round(($i/$count)*100,2).")% (".round($i/(time() - $start))." per sec (".$campaign_cost+$cost[$i]."))             \n";
	    } else {
			echo "Starting up\r";
	    }
    	    $result_update = mysql_query($sql,$cdrlink) or die(mysql_error());
    	}
    	$i++;
	$total_count++;
    } /* end of while on records */

    $sqlx = "select credit,creditlimit from billing where accountcode = '$accountcode_in'";

    mysql_select_db("SineDialer", $link);
    $result_credit = mysql_query($sqlx,$link)  or die (mysql_error());
    if (mysql_num_rows($result_credit) > 0) {
        $credit = mysql_result($result_credit,0,'credit') or die (mysql_error());
        $credit_limit = mysql_result($result_credit,0,'creditlimit');
        if (($credit - $totalcost[$accountcode_in]) != $credit) {
            echo "[".$accountcode_in."] Credit was $credit and will now be ".($credit - $totalcost[$accountcode_in])."\n";
            $sql = "update billing set credit = ".($credit - $totalcost[$accountcode_in])." where accountcode = '$accountcode_in'";
            mysql_select_db("SineDialer", $link);

            $result_update=mysql_query($sql, $link);
            if ($credit - $totalcost[$accountcode_in] - $totalcost[$accountcode_in] - $totalcost[$accountcode_in] < 0 - $credit_limit[$accountcode_in]) {
                //This person will run out of money if they do this again
                mysql_select_db("SineDialer", $link);

                $sql1="delete from queue where campaignid=".$campaignid;
                $sql2="INSERT INTO queue (campaignid,queuename,status,details,flags,transferclid,
                    starttime,endtime,startdate,enddate,did,clid,context,maxcalls,maxchans,maxretries
                    ,retrytime,waittime) VALUES
                    ('$campaignid','creditstop-$campaignid','2','No details','0','0',
                    '00:00:00','23:59:00','2005-01-01','2020-01-01','123','000',
                    '0','10','500','0'
                    ,'0','30') ";
                $resultx=mysql_query($sql1, $link) or die (mysql_error());;
                $resultx=mysql_query($sql2, $link) or die (mysql_error());;
                $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), 'System', 'Stopped campaign id $campaignid because credit of $accountcode_in was low (Credit is now $credit and they spent $totalcost[$accountcode_in] in the last minute. Credit Limit is $credit_limit[$accountcode_in])')";
                $result=mysql_query($sql, $link) or die(mysql_error());
            }
        }
    }
} /* End of while on customers */
if ($total_count > 0 && (time() - $start_total) > 0) {
	echo "\n\nSpeed across all(".round($total_count/(time() - $start_total))." per sec)             \n";
} else if ((time() - $start_total) > 0) {
	echo "\n\nNo records updated\n";
}

?>
