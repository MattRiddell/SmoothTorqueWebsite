<?
/*
 *  sugar_servers.php
 *  SmoothTorque Website
 *
 *  Created by Matt Riddell on 22/02/10.
 *  Copyright 2010 VentureVoIP. All rights reserved.
 *
 */

require "header.php";
require "header_server.php";
box_start(400);
echo "<center>";
if (isset($_GET['verify_connection'])) {
    if (!isset($_POST['number'])) {
        ?>
        <h3>Verify Server Connection</h3>
        <form action="sugar_servers.php?verify_connection=1" method="post">
        <input type="text" name="number">
        <input type="submit" value="Lookup Number">
        </form>
        <br />
        <br />
        <?
    } else {
        // Lookup number
        $db_host = $config_values['SUGAR_HOST'];
        $db_user = $config_values['SUGAR_USER'];
        $db_pass = $config_values['SUGAR_PASS'];
        //echo "Connecting to: $db_host User: $db_user Pass:$db_pass<br />";
        $link = mysql_connect($db_host, $db_user, $db_pass);
        if (!$link) {
            die('Could not connect ' . mysql_error());
        }//OR die(mysql_error());
        mysql_select_db($config_values['SUGAR_DB'], $link);
        echo "1";
        //        print_pre($link);
        $result = mysql_query("SELECT id FROM ".$config_values['SUGAR_DB'].".leads WHERE phone_home = '$_POST[number]' or phone_mobile='$_POST[number]' order by date_modified DESC limit 1");
        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_assoc($result)) {
                ?>
                Screen Pop Executed to: 'http://<?=$config_values['SUGAR_HOST']?>/sugarcrm/index.php?module=Leads&offset=5&action=DetailView&record=<?=$row['id']?>'<br />
                <script language="javascript">
                window.open('http://<?=$config_values['SUGAR_HOST']?>/sugarcrm/index.php?module=Leads&offset=5&action=DetailView&record=<?=$row['id']?>');
                </script>
                <?
            }
        }
    }
} else if (isset($_GET['stats'])) {
    ?>
    <br /><br />
    
    <b>Total Leads: </b><?
    $db_host = $config_values['SUGAR_HOST'];
    $db_user = $config_values['SUGAR_USER'];
    $db_pass = $config_values['SUGAR_PASS'];
    //echo "Connecting to: $db_host User: $db_user Pass:$db_pass<br />";
    $link = mysql_connect($db_host, $db_user, $db_pass) or die(mysql_error());
    mysql_select_db($config_values['SUGAR_DB'], $link);
    
    $result = mysql_query("SELECT count(*) FROM leads where  deleted = 0 ");
    echo number_format(mysql_result($result,0,0));

    $result = mysql_query("SELECT id FROM lc_customstatus WHERE name = 'new'");
    $new_status = mysql_result($result,0,0);

    $result = mysql_query("SELECT id FROM lc_customstatus WHERE name like 'CA - Left Message%'");
    $status_left_messages = "(";
    while ($row = mysql_fetch_assoc($result)) {
	$status_left_messages .= sanitize($row['id']).",";
    }
    $status_left_messages = substr($status_left_messages,0,strlen($status_left_messages)-1).")";

    ?>
    <br />
    <br />
    
    <b>NEW not left message Stage 1</b> (Entered Last 5 Days): <?
    $result = mysql_query("SELECT count(*) FROM leads WHERE DATE_SUB(CURDATE(),INTERVAL 5 DAY) <= date_entered and leads.deleted = 0 and status='$new_status'");
    echo number_format(mysql_result($result,0,0));
    ?>
    <br />
<hr>
<b>Numbers:</b><br />
<?
    $result = mysql_query("SELECT phone_home, phone_mobile FROM leads WHERE DATE_SUB(CURDATE(),INTERVAL 5 DAY) <= date_entered and leads.deleted = 0 and status='$new_status'");
while ($row = mysql_fetch_assoc($result)) {
if (isset($row['phone_mobile']) && $row['phone_mobile'] != $row['phone_home']) {
echo "Home Phone: ".$row['phone_home'].",  Mobile Phone: ".$row['phone_mobile']."<br />";
} else {
echo "Home Phone: ".$row['phone_home']."<br />";
}
}
?>

<hr />
    <br />
    
    <b>New or left message Stage 1</b> (Entered Last 5 Days): <?
    $sql = "SELECT count(*) FROM leads WHERE DATE_SUB(CURDATE(),INTERVAL 5 DAY) <= date_entered and leads.deleted = 0 and (status='$new_status' or status in $status_left_messages)";
    $result = mysql_query($sql) or die(mysql_error());
    echo number_format(mysql_result($result,0,0));
    ?>
    <br />
    
    
    
    
    <b>Stage 2</b> (Entered 6-10 days ago): <?
    $result = mysql_query("SELECT count(*) FROM leads WHERE DATE_SUB(CURDATE(),INTERVAL 5 DAY) > date_entered and DATE_SUB(CURDATE(),INTERVAL 10 DAY) <= date_entered and leads.deleted = 0  and (status='$new_status' or status in $status_left_messages)");
    echo number_format(mysql_result($result,0,0));
    ?>
    <br />
    
    
    <b>Stage 3</b> (Entered 11-20 days ago): <?
    $result = mysql_query("SELECT count(*) FROM leads WHERE DATE_SUB(CURDATE(),INTERVAL 10 DAY) > date_entered and DATE_SUB(CURDATE(),INTERVAL 20 DAY) <= date_entered and leads.deleted = 0  and (status='$new_status' or status in $status_left_messages)");
    echo number_format(mysql_result($result,0,0));
    ?>
    <br />
    
    
    <b>Stage 4</b> (Entered 21-30 days ago): <?
    $result = mysql_query("SELECT count(*) FROM leads WHERE DATE_SUB(CURDATE(),INTERVAL 20 DAY) > date_entered and DATE_SUB(CURDATE(),INTERVAL 30 DAY) <= date_entered and leads.deleted = 0  and (status='$new_status' or status in $status_left_messages)");
    echo number_format(mysql_result($result,0,0));
    ?>
    <br />
    
    
    <b>Stage 5</b> (Entered 31-60 days ago): <?
    $result = mysql_query("SELECT count(*) FROM leads WHERE DATE_SUB(CURDATE(),INTERVAL 30 DAY) > date_entered and DATE_SUB(CURDATE(),INTERVAL 60 DAY) <= date_entered and leads.deleted = 0  and (status='$new_status' or status in $status_left_messages)");
    echo number_format(mysql_result($result,0,0));
    ?>
    <br />
    <br />
    
    <b>Old</b> (Entered >60 days ago): <?
    $result = mysql_query("SELECT count(*) FROM leads WHERE DATE_SUB(CURDATE(),INTERVAL 60 DAY) > date_entered and leads.deleted = 0  and (status='$new_status' or status in $status_left_messages)");
    echo number_format(mysql_result($result,0,0));
    ?>
    <br />
    
    
    
    
    
    
    <br />
    
    
    
    
    
    
    <b>Leads</b> (Modified Last 24 Hours): <?
    $result = mysql_query("SELECT count(*) FROM leads WHERE DATE_SUB(CURDATE(),INTERVAL 1 DAY) <= date_modified and leads.deleted = 0 ");
    echo number_format(mysql_result($result,0,0));
    ?>
    <br />
    
    
    
    
    <b>Leads</b> (Modified Last Week): <?
    $result = mysql_query("SELECT count(*) FROM leads WHERE DATE_SUB(CURDATE(),INTERVAL 7 DAY) <= date_modified and leads.deleted = 0 ");
    echo number_format(mysql_result($result,0,0));
    ?>
    <br />
    
    
    
    
    <b>Leads</b> (Modified Last Month): <?
    $result = mysql_query("SELECT count(*) FROM leads WHERE DATE_SUB(CURDATE(),INTERVAL 1 MONTH) <= date_modified and leads.deleted = 0 ");
    echo number_format(mysql_result($result,0,0));
    ?>
    <br />
    
    
    
    
    <br />
    
    
    
    <b>Timezones:</b> <br /><?
    $result = mysql_query("select count(*), time_zone_c from leads, leads_cstm where leads.id = leads_cstm.id_c and leads.deleted = 0 group by time_zone_c");
    while ($row = mysql_fetch_assoc($result)) {
        if (strlen($row['time_zone_c']) == 0) {
            $row['time_zone_c'] = "Not Set";
        }
        echo $row['time_zone_c'].": ".$row['count(*)']."<br />";
    }
    ?>
    <br />
    
    
    <br />
    
    
    <b>Statuses:</b> <br /><?
    $result = mysql_query("select count(*), lc_customstatus.name as name from leads, lc_customstatus where leads.status = lc_customstatus.id and leads.deleted = 0 group by leads.status order by count(*) desc");
    while ($row = mysql_fetch_assoc($result)) {
        echo $row['name'].": ".$row['count(*)']."<br />";
    }
    ?>
    <br />
    
    
    <br />    
    
    
    
    <b>Lead Sources:</b> <br /><?
    $result = mysql_query("select count(*), lead_source from leads where deleted = 0 group by lead_source order by count(*) desc");
    while ($row = mysql_fetch_assoc($result)) {
        echo $row['lead_source'].": ".$row['count(*)']."<br />";
    }
    ?>
    <br />
    <br />
    
    
    
    
    
    <b>Below 10k:</b> <?
    $result = mysql_query("select count(*) from leads, leads_cstm where leads.id = leads_cstm.id_c and debt_amt_c < 10000 and leads.deleted = 0");
    while ($row = mysql_fetch_assoc($result)) {
        echo $row['count(*)']."<br />";
    }
    ?>
    <br />
    
    <b>Above 10k:</b> <?
    $result = mysql_query("select count(*) from leads, leads_cstm where leads.id = leads_cstm.id_c and debt_amt_c > 10000 and leads.deleted = 0");
    while ($row = mysql_fetch_assoc($result)) {
        echo $row['count(*)']."<br />";
    }
    ?>
    <br />
    
    <b>Above 100k:</b> <?
    $result = mysql_query("select count(*) from leads, leads_cstm where leads.id = leads_cstm.id_c and debt_amt_c > 100000 and leads.deleted = 0");
    while ($row = mysql_fetch_assoc($result)) {
        echo $row['count(*)']."<br />";
    }
    ?>
    <br />
    
    
    
    
    
    <?    
} else if (isset($_GET['vm'])) {
    //1, 2, 3, 4, 5, 6, 10, 14, 19, 31, 36, 41, 46, 51, 56
    if (isset($_GET['save'])) {
//        print_pre($_POST);
        $vm_0_5=$config_values['vm_0_5'];
        $vm_6_10=$config_values['vm_6_10'];
        $vm_11_20=$config_values['vm_11_20'];
        $vm_21_30=$config_values['vm_21_30'];
        $vm_31_60=$config_values['vm_31_60'];
        $sql = "INSERT IGNORE INTO config (parameter, value) VALUES ('vm_0_5', ".sanitize($_POST['vm_0_5']).")";
        $result = mysql_query($sql);
        $sql = "INSERT IGNORE INTO config (parameter, value) VALUES ('vm_6_10', ".sanitize($_POST['vm_6_10']).")";
        $result = mysql_query($sql);
        $sql = "INSERT IGNORE INTO config (parameter, value) VALUES ('vm_11_20', ".sanitize($_POST['vm_11_20']).")";
        $result = mysql_query($sql);
        $sql = "INSERT IGNORE INTO config (parameter, value) VALUES ('vm_21_30', ".sanitize($_POST['vm_21_30']).")";
        $result = mysql_query($sql);
        $sql = "INSERT IGNORE INTO config (parameter, value) VALUES ('vm_31_60', ".sanitize($_POST['vm_31_60']).")";
        $result = mysql_query($sql) or die(mysql_error());
        echo "Saved";
        
        
    } else {
//        print_pre($config_values);
        $vm_0_5=$config_values['vm_0_5'];
        $vm_6_10=$config_values['vm_6_10'];
        $vm_11_20=$config_values['vm_11_20'];
        $vm_21_30=$config_values['vm_21_30'];
        $vm_31_60=$config_values['vm_31_60'];
//        $vm_0_5 = "3";
        ?>
        <form action="sugar_servers.php?vm=1&save=1" method="post">
        <h3>VoiceMail Logic</h3>
        0-5 days old: 
        <select name="vm_0_5">
        <option value="0" <?=$vm_0_5=="0"?"selected":""?>>No VoiceMail</option>
        <option value="1" <?=$vm_0_5=="1"?"selected":""?>>Every Day</option>
        <option value="2" <?=$vm_0_5=="2"?"selected":""?>>Every Second Day</option>
        <option value="3" <?=$vm_0_5=="3"?"selected":""?>>Every Week</option>
        </select><br />
        
        6-10 days old: 
        <select name="vm_6_10">
        <option value="0" <?=$vm_6_10=="0"?"selected":""?>>No VoiceMail</option>
        <option value="1" <?=$vm_6_10=="0"?"selected":""?>>Every Day</option>
        <option value="2" <?=$vm_6_10=="0"?"selected":""?>>Every Second Day</option>
        <option value="3" <?=$vm_6_10=="0"?"selected":""?>>Every Week</option>
        </select><br />
        
        11-20 days old: 
        <select name="vm_11_20">
        <option value="0" <?=$vm_11_20=="0"?"selected":""?>>No VoiceMail</option>
        <option value="1" <?=$vm_11_20=="1"?"selected":""?>>Every Day</option>
        <option value="2" <?=$vm_11_20=="2"?"selected":""?>>Every Second Day</option>
        <option value="3" <?=$vm_11_20=="3"?"selected":""?>>Every Week</option>
        </select><br />
        
        21-30 days old: 
        <select name="vm_21_30">
        <option value="0" <?=$vm_21_30=="0"?"selected":""?>>No VoiceMail</option>
        <option value="1" <?=$vm_21_30=="1"?"selected":""?>>Every Day</option>
        <option value="2" <?=$vm_21_30=="2"?"selected":""?>>Every Second Day</option>
        <option value="3" <?=$vm_21_30=="3"?"selected":""?>>Every Week</option>
        </select><br />
        
        31-60 days old: 
        <select name="vm_31_60">
        <option value="0" <?=$vm_31_60=="0"?"selected":""?>>No VoiceMail</option>
        <option value="1" <?=$vm_31_60=="1"?"selected":""?>>Every Day</option>
        <option value="2" <?=$vm_31_60=="2"?"selected":""?>>Every Second Day</option>
        <option value="3" <?=$vm_31_60=="3"?"selected":""?>>Every Week</option>
        </select><br />
        <br />
        
        <input type="submit" value="Save Changes">
        <br />
        <br />
        </form>
        <?
    }
} else if (isset($_GET['call'])) {
    ?>
    <form action="sugar_servers.php?call=1&save=1" method="post">
    0-5 days old: 
    <select name="call_0_5">
    <option value="3">3 times per day</option> 
    <option value="2">2 times per day</option> 
    <option value="1">1 times per day</option> 
    <option value="0">Don't Call</option> 
    <option value="0.5">Every Second Day</option> 
    </select>
    </form>
    <?
} else {
    ?>
    
    <br />
    <a href="sugar_servers.php?verify_connection=1">
    Screen Pop a Sugar CRM record
    </a>
    <br />
    
    <a href="sugar_servers.php?stats=1">
    Database Statistics
    </a>
    <br />
    
    <a href="sugar_servers.php?vm=1">
    VoiceMail leaving logic
    </a>
    <br />
    
    <a href="sugar_servers.php?call=1">
    Call logic
    </a>
    <br />
    
    
    
    <br />
    
    <?
}
echo "</center>";
box_end();
require "footer.php";
?>
