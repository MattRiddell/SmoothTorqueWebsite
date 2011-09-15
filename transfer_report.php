<?

if (isset($_GET['live_calls'])) {
    require "admin/db_config.php";
    $field[0] = "channel";
    $field[1] = "context";
    $field[2] = "exten";
    $field[3] = "priority";
    $field[4] = "status";
    $field[5] = "application";
    $field[6] = "data";
    $field[7] = "callerid";
    $field[8] = "accountcode";
    $field[9] = "amaflags";
    $field[10] = "duration";
    $field[11] = "bridged";
    
    
    /* Get Asterisk Details */    
    $result = mysql_query("SELECT * FROM servers WHERE status = 1");
    while ($row = mysqL_fetch_assoc($result)) {
        //print_pre($row);
        // Connect to Asterisk
        $wrets = "";
        flush();
        unset($exploded);
        unset($line_exp);
        $socket = fsockopen($row['address'],"5038", $errno, $errstr, 5);
        if ($socket) {
            fputs($socket, "Action: Login\r\n");
            fputs($socket, "UserName: $row[username]\r\n");
            fputs($socket, "Secret: $row[password]\r\n");
            fputs($socket, "Events: off\r\n\r\n");
            fputs($socket, "Action: Command\r\n");
            fputs($socket, "Command: show channels concise\r\n\r\n");
            fputs($socket, "Action: Logoff\r\n\r\n");
            while (!feof($socket)) {
                //echo "Reading From $row[name]<br />";
                flush();
                $wrets .= fread($socket, 8192);
                
            }
            $wrets = str_replace("\r","",$wrets);
            $exploded = explode("\n",$wrets);
            //print_pre($exploded);
            $started = false;
            $count = 0;
            foreach ($exploded as $line) {
                if ($started) {
                    if ($line == "--END COMMAND--") {
                        $started = false;
                    } else {
                        $line_exp = explode("!",$line);
                        $line_exp[6] = str_replace("SIP/","",$line_exp[6]);
                        $line_exp[6] = str_replace("dialmaxx","",$line_exp[6]);                        
                        $line_exp[6] = str_replace("@","",$line_exp[6]);                        
                        for ($i = 0;$i<count($line_exp);$i++) {
                            $channels[$line_exp[0]][$field[$i]] = $line_exp[$i];
                        }
                        $count++;            
                    }
                } else if ($line == "Privilege: Command") {
                    $started = true;
                }
            }
            fclose($socket);
            
        }
    }
    ?>
    <h3>Live Calls</h3>
    <table class="transfer_history">
    <tr>
    <th class="transfer_history">CID Num</th><th class="transfer_history">Dest</th><th class="transfer_history">Created</th><th class="transfer_history">Duration</th><th class="transfer_history">Minutes</th><th class="transfer_history">Total Duration</th>
    </tr>
    <?
    if (count($channels) > 0) {
        foreach ($channels as $chan=>$values) {
            if (1 || $values['context'] == "dial-cc") {
                echo "<tr>";
                echo "<td class=\"transfer_history\">$values[callerid]</td>";
                echo "<td class=\"transfer_history\">$values[data]</td>";
                $start = @mktime(@date("h"),@date("i"), @date("s")-$values['duration'], @date("m")  , @date("d"), @date("Y"));
                $created = @Date("m/d/Y H:i:s",$start);
                echo "<td class=\"transfer_history\">$created</td>";
                
                echo "<td class=\"transfer_history\">".$channels[$values['bridged']]['duration']."</td>";
                echo "<td class=\"transfer_history\">".round($channels[$values['bridged']]['duration']/60,2)."</td>";
                echo "<td class=\"transfer_history\">".$values['duration']."</td>";
                echo "<tr>";
            }
        }
    }
    echo "</table>";
    exit(0);
}

require "header.php";
require "header_surveys.php";
if (isset($_GET['recordings'])) {
    box_start(400);
    echo "<center><h3>Recordings</h3>";
    $result = mysql_query("SELECT * FROM files, cdr WHERE files.uniqueid = cdr.uniqueid and files.uniqueid is not null") or die (mysql_error());
    while ($row = mysqL_fetch_assoc($result)) {
        print_pre($row);
    }
    box_end();
    require "footer.php";
    exit(0);
}
if (isset($_GET['all_campaigns'])) {
    ?>
    <script src="js/jquery.min.1.6.3.js" type="text/javascript"></script>
    
    <?
    box_start(800);
    echo "<center><h3>All Campaigns</h3>";
    
    $totals = array();
    $billables = array();
    $group_0_to_29 = array();
    $group_30_to_119 = array();
    $group_120_to_299 = array();
    $group_300_to_600 = array();
    $group_600_to_900 = array();
    $group_900_plus = array();
    
    if (isset($_GET['span'])) {
        $result = mysql_query("SELECT userfield, billsec FROM cdr WHERE amaflags = '-1' AND calldate >= DATE_SUB(CURDATE(), INTERVAL ".sanitize($_GET['span'])." DAY)") or die(mysql_error());
    } else {
        $result = mysql_query("SELECT userfield, billsec FROM cdr WHERE amaflags = '-1'");
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
    //print_pre($totals);
    ?>
    <table class="transfer_history">
    <tr>
    <th class="transfer_history">Campaign</th><th class="transfer_history">Total Xfers</th><th class="transfer_history">Billable Xfers</th><th class="transfer_history">Less than half min</th><th class="transfer_history">0.5-2 mins</th><th class="transfer_history">2-5 mins</th><th class="transfer_history">5-10 mins</th><th class="transfer_history">10-15 mins</th><th class="transfer_history">15+ mins</th><th class="transfer_history">Billable Perc.</th>
    </tr>
    <?
    foreach ($totals as $name=>$entry) {
        $campaign_name = mysql_result(mysql_query("SELECT name FROM campaign WHERE id = ".sanitize($name)),0,0);
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
        echo "<td class=\"transfer_history\">".$perc."</td>";
        echo "</tr>";
        //print_pre($entry);
    }
    echo "</table>";
    
    ?>
    <div id="live_calls">
    </div>
    <script>
    jQuery('#live_calls').load('transfer_report.php?live_calls=1');
    setInterval(function(){ jQuery('#live_calls').load('transfer_report.php?live_calls=1'); }, 5000);
    
    </script>
    <?
    
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
    <th class="transfer_history">Campaign</th><th class="transfer_history">Total Xfers</th><th class="transfer_history">Billable Xfers</th><th class="transfer_history">Less than half min</th><th class="transfer_history">0.5-2 mins</th><th class="transfer_history">2-5 mins</th><th class="transfer_history">5-10 mins</th><th class="transfer_history">10-15 mins</th><th class="transfer_history">15+ mins</th><th class="transfer_history">Billable Perc.</th>
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
        echo "<td class=\"transfer_history\">".round((count($billables[$date])/count($entry))*100,2)."</td>";
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
<a href="transfer_report.php?all_campaigns=1"><img src="images/folder.png">&nbsp;All Campaigns All Time</a><br /><br />
<a href="transfer_report.php?all_campaigns=1&span=0"><img src="images/folder.png">&nbsp;All Campaigns Today</a><br /><br />
<a href="transfer_report.php?all_campaigns=1&span=7"><img src="images/folder.png">&nbsp;All Campaigns Last 7 Days</a><br /><br />
<a href="transfer_report.php?historical=1"><img src="images/calendar.png">&nbsp;Historical Transfers</a><br />
<br />
<?
box_end();
require "footer.php";
?>