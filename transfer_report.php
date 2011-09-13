<?
require "header.php";
require "header_surveys.php";
if (isset($_GET['all_campaigns'])) {
    box_start();
    echo "<center><br />Coming Soon";
    require "footer.php";
    exit(0);
}
if (isset($_GET['historical_campaign'])) {
    box_start(800);
    echo "<center><br />";
    echo "<h3>".mysql_result(mysql_query("SELECT name FROM campaign WHERE id = ".sanitize($_POST['campaign_id'])),0,0)."</h3>";
    
    $totals = array();
    $billables = array();
    $group_0_to_29 = array();
    $group_30_to_119 = array();
    $group_120_to_299 = array();
    $group_300_to_600 = array();
    $group_600_to_900 = array();
    $group_900_plus = array();
    
    $result = mysql_query("SELECT DATE(calldate) as calldate, billsec FROM cdr WHERE userfield like '%-".sanitize($_POST['campaign_id'],false)."' and amaflags = '-1'");
    if (mysql_num_rows($result) > 0) {
        while ($row = mysqL_fetch_assoc($result)) {
            //echo "CallDate: ".$row['calldate']." Length: ".$row['billsec']."<br />";
            $totals[$row['calldate']][] = $row['billsec'];
            if ($row['billsec'] < 30) {
                $group_0_to_29[$row['calldate']][] = $row['billsec'];
            } else if ($row['billsec'] < 120) {
                $group_30_to_119[$row['calldate']][] = $row['billsec'];
                $billables[$row['calldate']][] = $row['billsec'];
            } else if ($row['billsec'] < 300) {
                $group_120_to_299[$row['calldate']][] = $row['billsec'];
                $billables[$row['calldate']][] = $row['billsec'];
            } else if ($row['billsec'] < 600) {
                $group_300_to_600[$row['calldate']][] = $row['billsec'];
                $billables[$row['calldate']][] = $row['billsec'];
            } else if ($row['billsec'] < 900) {
                $group_600_to_900[$row['calldate']][] = $row['billsec'];
                $billables[$row['calldate']][] = $row['billsec'];
            } else {
                $group_900_plus[$row['calldate']][] = $row['billsec'];
                $billables[$row['calldate']][] = $row['billsec'];
            }
        }
    }
    //print_pre($totals);
    ?>
    <table class="transfer_history">
    <tr>
    <th class="transfer_history">Date</th><th class="transfer_history">Total Xfers</th><th class="transfer_history">Billable Xfers</th><th class="transfer_history">0-29 secs</th><th class="transfer_history">30-119 secs</th><th class="transfer_history">2-5 mins</th><th class="transfer_history">5-10 mins</th><th class="transfer_history">10-15 mins</th><th class="transfer_history">15+ mins</th></th>
    </tr>
    <?
    foreach ($totals as $date=>$entry) {
        echo "<tr>";
        echo "<td class=\"transfer_history\">$date</td>";
        echo "<td class=\"transfer_history\">".count($entry)."</td>";
        echo "<td class=\"transfer_history\">".count($billables[$date])."</td>";
        echo "<td class=\"transfer_history\">".count($group_0_to_29[$date])."</td>";
        echo "<td class=\"transfer_history\">".count($group_30_to_119[$date])."</td>";
        echo "<td class=\"transfer_history\">".count($group_120_to_299[$date])."</td>";
        echo "<td class=\"transfer_history\">".count($group_300_to_600[$date])."</td>";
        echo "<td class=\"transfer_history\">".count($group_600_to_900[$date])."</td>";
        echo "<td class=\"transfer_history\">".count($group_900_plus[$date])."</td>";
        echo "</tr>";
        //print_pre($entry);
    }
    echo "</table>";
    require "footer.php";
    exit(0);
}
if (isset($_GET['historical'])) {
    box_start();
    echo "<center><h3>Select Campaign:</h3>";
    //TODO: split by user/admin
    $result = mysql_query("SELECT * from campaign");
    ?>
    <form action="transfer_report.php?historical_campaign=1" method="post">
    <select name="campaign_id">
    <?
    while ($row = mysqL_fetch_assoc($result)) {
        echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
    }
    ?>
    </select>
    <input type="submit" value="Display Report">
    </form>
    <?
    require "footer.php";
    exit(0);
}
box_start(250);
echo "<center>";
?>
<h3>Transfer Reports</h3>
<a href="transfer_report.php?all_campaigns=1"><img src="images/folder.png">&nbsp;All Campaigns</a><br /><br />
<a href="transfer_report.php?historical=1"><img src="images/calendar.png">&nbsp;Historical Transfers</a><br />
<br />
<?
box_end();
require "footer.php";
?>