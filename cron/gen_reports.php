#!/usr/bin/php
<?
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
mysql_connect($db_host, $db_user, $db_pass);

/* Select the appropriate database */
mysql_select_db("SineDialer");

/* Sanitize function */
if (!function_exists('sanitize') ) {
    function sanitize($var, $quotes = true) {
        if (is_array($var)) {   //run each array item through this function (by reference)
            foreach ($var as &$val) {
                $val = $this->sanitize($val);
            }
        }
        else if (is_string($var)) { //clean strings
            $var = mysql_real_escape_string($var);
            if ($quotes) {
                $var = "'". $var ."'";
            }
        }
        else if (is_null($var)) {   //convert null variables to SQL NULL
            $var = "NULL";
        }
        else if (is_bool($var)) {   //convert boolean variables to binary boolean
            $var = ($var) ? 1 : 0;
        }
        return $var;
    }
}

$_GET['span'] = 0;

if (isset($_GET['span'])) {
    $result = mysql_query("SELECT userfield, billsec FROM cdr WHERE amaflags = '-1' AND calldate >= DATE_SUB(CURDATE(), INTERVAL ".sanitize($_GET['span'])." DAY)") or die(mysql_error());
    
    $result_mins = mysql_query("SELECT accountcode, sum(rounded_billsec) FROM cdr WHERE amaflags != '-1' AND calldate >= DATE_SUB(CURDATE(), INTERVAL ".sanitize($_GET['span'])." DAY) group by accountcode") or die(mysql_error());
} else {
    $result = mysql_query("SELECT userfield, billsec FROM cdr WHERE amaflags = '-1'");
    $result_mins = mysql_query("SELECT accountcode, sum(rounded_billsec) FROM cdr WHERE amaflags != '-1' group by accountcode");
}

if (mysql_num_rows($result) > 0) {
    while ($row = mysqL_fetch_assoc($result)) {
        $userfield = split("-",$row['userfield']);
        $campaign_id = $userfield[1];
        //echo $campaign_id."<br />";;
        //echo "CallDate: ".$row['calldate']." Length: ".$row['billsec']."<br />";
        $totals[$campaign_id][] = $row['billsec'];
        if ($row['billsec'] < 30) {
            $group_0_to_29[$campaign_id][] = $row['billsec'];
        } else if ($row['billsec'] < 120) {
            $group_30_to_119[$campaign_id][] = $row['billsec'];
            $billables[$campaign_id][] = $row['billsec'];
        } else if ($row['billsec'] < 300) {
            $group_120_to_299[$campaign_id][] = $row['billsec'];
            $billables[$campaign_id][] = $row['billsec'];
        } else if ($row['billsec'] < 600) {
            $group_300_to_600[$campaign_id][] = $row['billsec'];
            $billables[$campaign_id][] = $row['billsec'];
        } else if ($row['billsec'] < 900) {
            $group_600_to_900[$campaign_id][] = $row['billsec'];
            $billables[$campaign_id][] = $row['billsec'];
        } else {
            $group_900_plus[$campaign_id][] = $row['billsec'];
            $billables[$campaign_id][] = $row['billsec'];
        }
    }
}
if (mysql_num_rows($result_mins) > 0) {
    while ($row_mins = mysqL_fetch_assoc($result_mins)) {
        $mins[strtolower(trim($row_mins['accountcode']))] = $row_mins['sum(rounded_billsec)'];
    }
}
//print_pre($mins);
/*
?>
<table class="transfer_history">

<th class="transfer_history">< half min</th><th class="transfer_history">30 secs-2 mins</th><th class="transfer_history">2-5 mins</th><th class="transfer_history">5-10 mins</th><th class="transfer_history">10-15 mins</th><th class="transfer_history">15+ mins</th><th class="transfer_history">Billable Perc.</th><th class="transfer_history">Minutes</th>

<?
*/
foreach ($totals as $name=>$entry) {
    $result = mysql_query("SELECT name, groupid FROM campaign WHERE id = ".sanitize($name));
    if (mysql_num_rows($result) == 0) {
        $campaign_name = "Unknown (".sanitize($name).")";
        $mins_text = ($mins[""]/60);
    } else {
        $campaign_name = mysql_result($result,0,0);
        $groupid = mysql_result($result,0,1);
        $result2 = mysql_query("SELECT username FROM customer WHERE campaigngroupid = ".sanitize($groupid));        
        $mins_text = number_format($mins[trim(strtolower("stl-".mysql_result($result2,0,0)))]/60)." (".mysql_result($result2,0,0).")";
    }
    
    $perc = round(count($billables[$name])/count($entry)*100,2);

    
    $sql = "INSERT INTO transfer_reports (campaign_id, campaign_name, report_date, total_transfers, under_30_secs, 30_to_2_mins, 2_to_5_mins, 5_to_10_mins, 10_to_15_mins, 15_plus_mins, billable_perc, total_mins) VALUES (".sanitize($name).",".sanitize($campaign_name).",".sanitize(count($entry)).",".sanitize(count($billables[$name])).",".sanitize(count($group_0_to_29[$name])).",".sanitize(count($group_30_to_119[$name])).",".sanitize(count($group_120_to_299[$name])).",".sanitize(count($group_300_to_600[$name])).",".sanitize(count($group_600_to_900[$name])).",".count($group_900_plus[$name]).",".sanitize($perc).",",sanitize($mins_text).")";
    
    echo $sql."\n";

}


?>