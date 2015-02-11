<?
if (isset($_GET['save_disposition'])) {
    require "admin/db_config.php";
    require "functions/sanitize.php";
    
    $result = mysql_query("REPLACE INTO cdr_dispositions (uniqueid, disposition, notes) VALUES (".sanitize(str_replace("__",".",$_POST['uniqueid'])).",".sanitize($_POST['disp']).",".sanitize($_POST['notes']).")") or die(mysql_error());
    //print_r($_POST);
    exit(0);
}
if (isset($_GET['transfer_cdrs_print'])) {
    require "admin/db_config.php";
    require "functions/sanitize.php";
    
    header("Content-type: application/csv"); 
    header("Content-Disposition: attachment; filename=".$_POST['accountcode']."_".@date('l jS \of F Y h:i:s A').".csv");
    header("Pragma: no-cache");
    header("Expires: 0");
    
    $sql ="select billsec, calldate as start, date_add(calldate, interval billsec second) as end, left(userfield, 10) as phonenumber, accountcode  from cdr where amaflags = -1 and billsec > 30 and LOWER(accountcode) = ".sanitize("stl-".strtolower($_POST['accountcode']))." and calldate > ".sanitize($_POST['startdate'])." and calldate < ".sanitize($_POST['enddate'])." order by calldate asc";
    //echo $sql;
    $result = mysql_query($sql);
    $x = 0;
    echo "Start, End, Phone Number, Duration\n";
    while ($row = mysql_fetch_assoc($result)) {
        $x++;
        if ($x > 100) {
            flush();
            $x = 0;
        }
        echo $row['start'].",".$row['end'].",".$row['phonenumber'].",".$row['billsec']."\n";
    }
    exit(0);
}

if (isset($_GET['live_cps'])) {
	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
    require "admin/db_config.php";
    $sql = "select campaign.name, campaign_stats.* from campaign, campaign_stats, queue where campaign_stats.campaignid=queue.campaignID and queue.status = 101 and campaign_stats.campaignid = campaign.id";
    $result = mysql_query($sql);
    ?>
    <h3>MySQL Queue: 
    <?
    $resultxx = mysql_query("SELECT value FROM SineDialer.config WHERE parameter = 'mysql_queue'") or die(mysql_error());
    $pending = 0;
    if (mysql_num_rows($resultxx) > 0 && mysql_result($resultxx,0,0) > 0) {
        if (mysql_result($resultxx,0,0) > 10000) {
            $pending = "<font color=\"red\">".mysql_result($resultxx,0,0)." (Over capacity)</font> ";
        } else {
            $pending = "".mysql_result($resultxx,0,0)."";
        }
    }
    echo $pending;
    ?>
    </h3>
    <h3>Running Campaign Stats</h3>
    <table class="transfer_history">
    <tr>
    <th class="transfer_history">Campaign</th><th class="transfer_history">Busy</th><th class="transfer_history">Calls Per Second</th>
    </tr>
    <?
    while ($row = mysqL_fetch_assoc($result)) {
        echo "<tr>";
        echo '<td class="transfer_history">'.$row['name'].'</td>';
        echo '<td class="transfer_history">'.round($row['busy_agents']/$row['total_agents']*100,2).'%</td>';
        echo '<td class="transfer_history">'.round(1000/$row['ms_sleep'],2).'</td>';
        echo "</tr>";
        $total['busy_agents']+=$row['busy_agents'];
        $total['total_agents']+=$row['total_agents'];
        $total['cps']+=round(1000/$row['ms_sleep'],2);
    }
    echo "<tr>";
    echo '<th class="transfer_history">Total</th>';
    if ($total['total_agents'] == 0) {
        $total['total_agents'] = 1;
    }
    echo '<th class="transfer_history">'.round($total['busy_agents']/$total['total_agents']*100,2).'%</th>';
    echo '<th class="transfer_history">'.$total['cps'].'</th>';
    echo "</tr>";
    echo "</table>";
    exit(0);
}
if (isset($_GET['live_calls'])) {    
	//header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	//header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
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
            fputs($socket, "Command: core show channels concise\r\n\r\n");
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
            
        } else {
            echo "Unable to connect to ".$row['name']."<br />";
        }
    }
    ?>
    <h3>Live Calls</h3>
    <table class="transfer_history">
    <tr>
    <th class="transfer_history">CID Num</th><th class="transfer_history">Dest</th><th class="transfer_history">Created</th><th class="transfer_history">Accountcode</th><th class="transfer_history">Duration</th><th class="transfer_history">Bridged Minutes</th><th class="transfer_history">Total Minutes</th>
    </tr>
    <?
    if (count($channels) > 0) {
        foreach ($channels as $chan=>$values) {
            if ($values['context'] == "dial-cc") {
                echo "<tr>";
                echo "<td class=\"live_cc\">$values[callerid]</td>";
                echo "<td class=\"live_cc\">$values[data]</td>";
                $start = @mktime(@date("h"),@date("i"), @date("s")-$values['duration'], @date("m")  , @date("d"), @date("Y"));
                $created = @Date("m/d/Y H:i:s",$start);
                echo "<td class=\"live_cc\">$created</td>";
                echo "<td class=\"live_cc\">".$values['accountcode']."</td>";
                echo "<td class=\"live_cc\">".$channels[$values['bridged']]['duration']."</td>";
                echo "<td class=\"live_cc\">".round($channels[$values['bridged']]['duration']/60,2)."</td>";
                echo "<td class=\"live_cc\">".round($values['duration']/60,2)."</td>";
                echo "<tr>";
            } else if ($values['data'] == "survey.php") {
                echo "<tr>";
                echo "<td class=\"live_survey\">$values[callerid]</td>";
                echo "<td class=\"live_survey\">$values[data]</td>";
                $start = @mktime(@date("h"),@date("i"), @date("s")-$values['duration'], @date("m")  , @date("d"), @date("Y"));
                $created = @Date("m/d/Y H:i:s",$start);
                echo "<td class=\"live_survey\">$created</td>";
                echo "<td class=\"live_survey\">".$values['accountcode']."</td>";
                
                echo "<td class=\"live_survey\">".$channels[$values['bridged']]['duration']."</td>";
                echo "<td class=\"live_survey\">".round($channels[$values['bridged']]['duration']/60,2)."</td>";
                echo "<td class=\"live_survey\">".round($values['duration']/60,2)."</td>";
                echo "<tr>";
                
            }
        }
    } else {
        echo "No channels";
    }
    echo "</table>";
    exit(0);
}

if (isset($_GET['get_recording'])) {
    require "admin/db_config.php";
    require "functions/sanitize.php";
    $result = mysql_query("SELECT address FROM servers WHERE name = ".sanitize($_GET['server']));
    $address = mysql_result($result,0,0);
    // Clean the file name 
    //$name = $_GET['get_recording'];
    $name = preg_replace('[^a-zA-Z0-9\.]', '', $_GET['get_recording']);
             
    $cmd = exec("/usr/bin/scp root@".$address.":/var/spool/asterisk/monitor/".sanitize($name.".wav")." /tmp/");
    $filename = "/tmp/".$name.".wav";
    $fp = fopen($filename, 'rb');
    
    // send the right headers
    header("Content-Type: audio/wav");
    header("Content-Length: " . filesize($filename));
    
    fpassthru($fp);

    require "footer.php";
    exit(0);
}
require "header.php";
require "header_surveys.php";
if (isset($_GET['recordings_date'])) {
    $result = mysql_query("SELECT * FROM cdr_disposition_names") or die(mysql_error());
    if (mysql_num_rows($result) == 0) {
        // No rows
    } else {
        while ($row = mysql_fetch_assoc($result)) {
            $dispositions[$row['id']] = $row['name'];
        }
    }
    //print_pre($dispositions);
    $result_campaigns = mysql_query("SELECT name, id FROM campaign") or die(mysql_error());
    while ($row = mysqL_fetch_assoc($result_campaigns)) {
        $campaign_names[$row['id']] = $row['name'];
    }
    $result = mysql_query("SELECT * FROM files, cdr WHERE files.uniqueid = cdr.uniqueid and cdr.uniqueid is not null and date(calldate) = ".sanitize($_POST['date'])." ") or die (mysql_error());
    box_start(1500);
    ?>
    <br />
    <center>
    <script src="js/jquery.min.1.6.3.js"></script>
    <table class="recordings" width="90%">
    <tr>
    <th class="recordings">Date/Time</th>
    <th class="recordings">CLID</th>
    <th class="recordings">Duration</th>
    <th class="recordings">Phone Number</th>
    <th class="recordings">Campaign</th>
    <th class="recordings">Disposition</th>
    <th class="recordings">Notes</th>
    </tr>
    <?
    $x = 0;
    while ($row = mysqL_fetch_assoc($result)) {
        //print_pre($row);
        $x++;
        $exploded = split("-",$row['userfield']);
        
        echo '<tr id="tr'.$x.'">';
        echo '<td class="recordings">';
        ?>
        <audio id="player-<?=$x?>" >
        <source id="source-<?=$x?>" src="recordings/<?=$row['uniqueid']?>.wav" />
        </audio>
        
        <div id="play-<?=$x?>" style="cursor: pointer" onclick="play<?=$x?>()"><?=$row['calldate']?> - Play Audio</div>
        <script>
        function play<?=$x?>() {
            var audio = jQuery("#player-<?=$x?>");
            audio[0].load();
            audio[0].play();
            
            
            jQuery("td").css("background-color","");
            //var foo = document.getElementById('player-<?=$x?>');
            //foo.play();
            <?
            for ($i = 0;$i <mysql_num_rows($result)+1;$i++) {
                ?>
                jQuery("#tr<?=$i?>").css("background-color","#ffffff");
                <?
            }
            ?>
            jQuery("#tr<?=$x?>").css("background-color","#cccccc");
        }
        </script>
        <?
        echo '</td>';
        echo '<td class="recordings">'.$row['clid'].'</td>';
        echo '<td class="recordings">'.$row['duration'].'</td>';
        echo '<td class="recordings">'.$exploded[0].'</td>';        
        echo '<td class="recordings">'.$campaign_names[$exploded[1]].'</td>';
        $resultx = mysql_query("SELECT * from cdr_dispositions WHERE uniqueid = ".sanitize($row['uniqueid']));
        if (mysql_num_rows($resultx) == 0) {
            $disposition = 0;
            $notes = "";
        } else {
            $rowx = mysql_fetch_assoc($resultx);
            $disposition = $rowx['disposition'];
            $notes = $rowx['notes'];
        }
        echo '<td class="recordings">';
        echo '<select name="disposition" id="disp_'.str_replace(".","__",$row['uniqueid']).'">';
        echo '<option value="-1">-</option>';
        foreach ($dispositions as $disp=>$name) {
            if ($disp == $disposition) {
                $selected = " SELECTED ";
            } else {
                $selected = "";
            }
            echo '<option value="'.$disp.'" '.$selected.'>'.$name.'</option>';
        }
        echo '</select>';
        echo '</td>';
        echo '<td class="recordings"><form><input type="text" value="'.$notes.'" name="notes_'.$row['uniqueid'].'" id="notes_'.str_replace(".","__",$row['uniqueid']).'"></form>';
        echo '<input type="submit" value="Save" onclick="clicker(\''.str_replace(".","__",$row['uniqueid']).'\')">';
        
        echo '</td>';
        echo '</tr>';
    }
    ?>
    </table>
    <script>
    function clicker(val) {
        var disp = jQuery("#disp_"+val).val();
        var notes = jQuery("#notes_"+val).val();
        //alert(disp);
        //alert (notes);
        jQuery.post("transfer_report.php?save_disposition=1", {disp: disp, notes: notes, uniqueid: val}).done(function( data ) {
                                                                            //alert( "Data Loaded: " + data );
                                                                            });;
    }
    </script>
    <br />
    <?
    box_end();
    require "footer.php";
    exit(0);
}
if (isset($_GET['recordings'])) {
    box_start(500);
    echo "<center><h3>Recordings</h3>";
    $result = mysql_query("SELECT count(*) as count, date(calldate) as date FROM files, cdr WHERE files.uniqueid = cdr.uniqueid and cdr.uniqueid is not null group by date(calldate) order by calldate desc") or die (mysql_error());
    ?>
    <form action = "transfer_report.php?recordings_date=1" method="post">
    Please select a date:
    <select name="date">
    <?
    while ($row = mysqL_fetch_assoc($result)) {
        echo '<option value="'.$row['date'].'">'.$row['date'].' ('.$row['count'].' recordings)</option>';
        //        print_pre($row);
    }
    ?>    
    </select>
    
    <input type="submit" value="Select Date">
    </form>
    <br />    <br />
    <?
    box_end();
    require "footer.php";
    exit(0);
}
if (isset($_GET['all_campaigns'])) {
    ?>
    <script src="js/jquery.min.1.6.3.js" type="text/javascript"></script>
    
    <?
    box_start(1000);
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
            $mins_text = number_format($mins[trim(strtolower("stl-".mysql_result($result2,0,0)))]/60);
//            $mins_text = number_format($mins[trim(strtolower("stl-".mysql_result($result2,0,0)))]/60)." (".mysql_result($result2,0,0).")";
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
    <br />
    <table>
    <tr>
    <td class="transfer_history">
    <span id="channels">
    </span>
    </td></tr></table>
    <br />
    <div id="live_cps">
    </div>
       
    <div id="live_calls">
    </div>
    <script>
    jQuery('#live_calls').load('transfer_report.php?live_calls=1');
    setInterval(function(){ jQuery('#live_calls').load('transfer_report.php?live_calls=1'); }, 10000);
    jQuery('#channels').load('server_total.php?ajax=1&nobox=1');
    setInterval(function(){ jQuery('#channels').load('server_total.php?ajax=1&nobox=1'); }, 10000);
    jQuery('#live_cps').load('transfer_report.php?live_cps=1');
    setInterval(function(){ jQuery('#live_cps').load('transfer_report.php?live_cps=1'); }, 10000);
    
    </script>
    <?
    
    require "footer.php";
    exit(0);
    
}
if (isset($_GET['historical_campaign'])) {
    box_start(1000);
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
    <th class="transfer_history">Campaign</th><th class="transfer_history">Total Xfers</th><th class="transfer_history">Billable Xfers</th><th class="transfer_history">Less than half min</th><th class="transfer_history">30 seconds-2 mins</th><th class="transfer_history">2-5 mins</th><th class="transfer_history">5-10 mins</th><th class="transfer_history">10-15 mins</th><th class="transfer_history">15+ mins</th><th class="transfer_history">Billable Perc.</th>
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
        echo "<td class=\"transfer_history\">".round((count($billables[$date])/count($entry))*100,2)."%</td>";
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

if (isset($_GET['transfer_cdrs'])) {
    box_start();
    echo "<center><h3>Select Customer:</h3>";
    //TODO: split by user/admin
    $result = mysql_query("SELECT * from customer");
    ?>
    <form action="transfer_report.php?transfer_cdrs_print=1" method="post">
    <select name="accountcode">
    <?
    while ($row = mysqL_fetch_assoc($result)) {
        //        print_pre($row);
        echo '<option value="'.$row['username'].'">'.$row['company'].' ('.$row['username'].')</option>';
    }
    ?>
    </select><br />
    <br />
    Select the dates you would like to view:<br />
    <br />
    <form action="viewcdr.php">
From: <input name="startdate">
    <input type=button value="select" onclick="displayDatePicker('startdate', false, 'ymd', '-');"><BR>
To: <input name="enddate">
    <input type=button value="select" onclick="displayDatePicker('enddate', false, 'ymd', '-');"><BR>
    <br />
    

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
<a href="transfer_report.php?all_campaigns=1&span=0"><img src="images/calendar_view_day.png">&nbsp;All Campaigns Today</a><br /><br />
<a href="transfer_report.php?all_campaigns=1&span=7"><img src="images/calendar_view_week.png">&nbsp;All Campaigns Last 7 Days</a><br /><br />
<a href="transfer_report.php?all_campaigns=1"><img src="images/calendar_view_month.png">&nbsp;All Campaigns All Time</a><br /><br />
<a href="transfer_report.php?historical=1"><img src="images/folder_explore.png">&nbsp;Select Campaign</a><br /><br />
<a href="transfer_report.php?transfer_cdrs=1"><img src="images/folder_explore.png">&nbsp;Transfer CDRs</a><br /><br />
<a href="transfer_report.php?recordings=1"><img src="images/sound.png">&nbsp;Call Recordings</a><br />
<br />
<?
box_end();
require "footer.php";
?>