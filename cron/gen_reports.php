#!/usr/bin/php
<?
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
mysql_connect($db_host, $db_user, $db_pass);

/* Select the appropriate database */
mysql_select_db("SineDialer");

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
?>
<table class="transfer_history">
<tr>
<th class="transfer_history">Campaign</th><th class="transfer_history">Total Xfers</th><th class="transfer_history">Billable Xfers</th><th class="transfer_history">< half min</th><th class="transfer_history">30 secs-2 mins</th><th class="transfer_history">2-5 mins</th><th class="transfer_history">5-10 mins</th><th class="transfer_history">10-15 mins</th><th class="transfer_history">15+ mins</th><th class="transfer_history">Billable Perc.</th><th class="transfer_history">Minutes</th>
</tr>
<?
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
    
    echo "<tr>";
    echo "<td class=\"transfer_history\">$campaign_name</td>";
    echo "<td class=\"transfer_history\">".count($entry)."</td>";
    echo "<td class=\"transfer_history\">".count($billables[$name])."</td>";
    echo "<td class=\"transfer_history\">".count($group_0_to_29[$name])."</td>";
    echo "<td class=\"transfer_history\">".count($group_30_to_119[$name])."</td>";
    echo "<td class=\"transfer_history\">".count($group_120_to_299[$name])."</td>";
    echo "<td class=\"transfer_history\">".count($group_300_to_600[$name])."</td>";
    echo "<td class=\"transfer_history\">".count($group_600_to_900[$name])."</td>";
    echo "<td class=\"transfer_history\">".count($group_900_plus[$name])."</td>";
    $perc = round(count($billables[$name])/count($entry)*100,2);
    echo "<td class=\"transfer_history\">".$perc."%</td>";
    echo "<td class=\"transfer_history\">".$mins_text."</td>";
    echo "</tr>";
    //print_pre($entry);
}
echo "</table>";


?>