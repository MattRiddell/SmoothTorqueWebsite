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

// Set this to true to use a custom SugarCRM install
$custom = false;

// Get a list of timezones
$tz_db = array();
$result = mysql_query("SELECT * FROM time_zones");
if (mysql_num_rows($result) > 0) {
    while ($row = mysql_fetch_assoc($result)) {
        $tz_db_name[] = $row['name'];
        $tz_db_start[] = $row['start'];
        $tz_db_end[] = $row['end'];
    }
}


/* Add rules for SugarCRM */
if (isset($_GET['add_rules'])) {
?>
<script>
function show_hide(id, show){
if(show == 1)
document.getElementById(id).style.display = "block";
else
document.getElementById(id).style.display = "none";
}
</script>
<?
    $result = mysql_query("SELECT * FROM SineDialer.campaign WHERE id = ".sanitize($_GET['add_rules']));
    if (mysql_num_rows($result) == 0) {
        echo "You have specified an invalid ID";
    } else {
        $row = mysql_fetch_assoc($result);
        box_start();
        echo "<center>";
        echo "<h3>".$row['name']."</h3><u>".$row['description']."</u><br /><br />";
        ?>
        <form action="sugar_servers.php?save_add_rule=1" method="post">
        <table>
        
        
        
        <tr>
        <td>
        Last Dialed:
        </td>
        <td>
        <select name="last_dialed">
        <option value="1">1 Day or More</option>
        <option value="2">2 Days or More</option>
        <option value="3">3 Days or More</option>
        <option value="4">4 Days or More</option>
        <option value="5">5 Days or More</option>
        <option value="6">6 Days or More</option>
        <option value="7">1 Week or More</option>
        <option value="14">2 Weeks or More</option>
        <option value="30">30 Days or More</option>
        <option value="90">90 Days or More</option>
        </select>
        </td>
        </tr>
        
        
        <tr>
        <td>
        Dial undialed records?
        </td>
        <td>
        <select name="include_undialed">
        <option value="1">Yes</option>
        <option value="0">No</option>
        </select>
        </td>
        </tr>
        
        <tr>
        <td>
        Use Status Based Dialling?
        </td>
        <td>
        <select name="use_status" onchange="show_hide('use_status',this.selectedIndex);">
        <option value="0">No</option>
        <option value="1">Yes</option>
        </select>
        <div id="use_status" style="display:none">
        <select name="status">
        <option value="New">New</option>
        <option value="Assigned">Assigned</option>
        <option value="In Process">In Process</option>
        <option value="Converted">Converted</option>
        <option value="Recycled">Recycled</option>
        <option value="Dead">Dead</option>
        </select>
        </div>
        
        </td>
        </tr>
        
        
                
        <tr>
        <td>
        Maximum times to call?
        </td>
        <td>
        <select name="max_calls">
        <option value="0">Unlimited</option>
        <option value="1">Once</option>
        <option value="2">Twice</option>
        <option value="3">3 Times</option>
        <option value="4">4 Times</option>
        <option value="5">5 Times</option>
        <option value="6">6 Times</option>
        <option value="7">7 Times</option>
        <option value="8">8 Times</option>
        <option value="9">9 Times</option>
        <option value="10">10 Times</option>
        </select>
        </td>
        </tr>
        
        
        
        <tr>
        <td>
        Use Home Number?
        </td>
        <td>
        <select name="use_home">
        <option value="1">Yes</option>
        <option value="0">No</option>
        </select>
        </td>
        </tr>
        
        <tr>
        <td>
        Use Work Number?
        </td>
        <td>
        <select name="use_work">
        <option value="1">Yes</option>
        <option value="0">No</option>
        </select>
        </td>
        </tr>
        
        <tr>
        <td>
        Use Mobile Number?
        </td>
        <td>
        <select name="use_mobile">
        <option value="1">Yes</option>
        <option value="0">No</option>
        </select>
        </td>
        </tr>
        

        
        
        </table>
        </form>
        <?
        echo "<br />";
        box_end();
    }
    require "footer.php";
    exit(0);
}
/* Set up business rules for importing records from SugarCRM */
if (isset($_GET['rules'])) {
    echo "<center>";
    echo "<br />";
    $result = mysql_query("SELECT * FROM SineDialer.campaign");//, SineDialer.sugar_pull_rules WHERE sugar_pull_rules.campaign_id = campaign.id") or die(mysql_error());
    if (mysql_num_rows($result) == 0) {
        echo "There are no campaigns available to use - please create a campaign first";
    } else {
        echo '<table border="0" cellpadding="3" cellspacing="0">';
        ?>
        <tr height="10">
        <th class="theadl" ></th>
        <th class="thead">Campaign Name</th>
        <th class="thead" width="100">CRM Rules</th>
        <th class="thead" width="100">Edit/Add Rules</th>
        <th class="thead" width="100">Delete Rules</th>
        <th class="theadr"></th>
        </tr>
        <?
        while ($row = mysql_fetch_assoc($result)) {
            if ($toggle){
                $toggle=false;
                $class=" class=\"tborder2\"  onmouseover=\"style.backgroundColor='#84DFC1';\" onmouseout=\"style.backgroundColor='#f8f8f8'\"   ";
            } else {
                $toggle=true;
                $class=" class=\"tborderx\"  onmouseover=\"style.backgroundColor='#84DFC1';\" onmouseout=\"style.backgroundColor='#f0f0f0'\" ";
            }
            echo "<tr$class><td>";
            echo "</td><td>";
            echo $row['name']."";
            echo "</td><td>";
            $result_rules = mysql_query("SELECT * FROM SineDialer.sugar_pull_rules WHERE campaign_id = ".$row['id']);
            if (mysql_num_rows($result_rules) == 0) {
                echo '<img src="images/cross.png" alt="No Rules Defined">';
            } else {
                echo '<img src="images/tick.png" alt="Rules Defined">';
            }
            echo "</td>";
            echo "<td>";
            if (mysql_num_rows($result_rules) == 0) {
                echo '<a href="sugar_servers.php?add_rules='.$row['id'].'"><img src="images/add.png" alt="No Rules Defined"> &nbsp;Add Rules</a>';
            } else {
                echo '<a href="sugar_servers.php?edit_rules='.$row['id'].'"><img src="images/pencil.png" alt="Edit Rules Defined"> &nbsp;Edit Rules</a>';
            }

            echo "</td><td>";
            echo '<a href="sugar_servers.php?delete_rules='.$row['id'].'"><img src="images/delete.png" alt="Delete Rules">&nbsp;Delete</a>';
            echo "</td><td>";
            echo "</td></tr>";
        }
        echo "</table>";
    }
    require "footer.php";
    exit(0);
}
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
        $result = mysql_query("SELECT id FROM ".$config_values['SUGAR_DB'].".leads WHERE phone_home = '$_POST[number]' or phone_mobile='$_POST[number]' or phone_work='$_POST[number]' order by date_modified DESC limit 1");
        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_assoc($result)) {
                if ($custom == true) {
                    ?>
                    Screen Pop Executed to: 'http://<?=$config_values['SUGAR_HOST']?>/ccs/index.php?module=Leads&offset=5&action=DetailView&record=<?=$row['id']?>'<br />
                    <script language="javascript">
                    window.open('http://<?=$config_values['SUGAR_HOST']?>/ccs/index.php?module=Leads&offset=5&action=DetailView&record=<?=$row['id']?>');
                    </script>
                    <?
                } else {
                    ?>
                    Screen Pop Executed to: 'http://<?=$config_values['SUGAR_HOST']?>/index.php?module=Leads&offset=1&stamp=1309417637080258700&return_module=Leads&action=DetailView&record=<?=$row['id']?>'<br />
                    <script language="javascript">
                    window.open('http://<?=$config_values['SUGAR_HOST']?>/index.php?module=Leads&offset=1&stamp=1309417637080258700&return_module=Leads&action=DetailView&record=<?=$row['id']?>');
                    </script>
                    <?
                }
            }
        }
    }
} else if (isset($_GET['create_note'])) {
    $db_host = $config_values['SUGAR_HOST'];
    $db_user = $config_values['SUGAR_USER'];
    $db_pass = $config_values['SUGAR_PASS'];
    //echo "Connecting to: $db_host User: $db_user Pass:$db_pass<br />";
    $link = mysql_connect($db_host, $db_user, $db_pass) or die("error:".mysql_error());
    //echo "Connected";
    mysql_select_db($config_values['SUGAR_DB'], $link) or die(mysql_error());
    
    $guid = sanitize(create_guid());
    $datetime = sanitize(date('Y-m-d H:i:s'));
    $name = sanitize($_GET['name']);
    $description = sanitize($_GET['description']);
    $parent_id = sanitize($_GET['parent_id']);
    $result = mysql_query("INSERT INTO notes 
    (id, date_entered, date_modified, modified_user_id, created_by, name, parent_type, parent_id, description) VALUES
    ($guid, $datetime, $datetime, 1, 1, $name, 'Leads', $parent_id, $description)") or die (mysql_error());
    ?><META HTTP-EQUIV=REFRESH CONTENT="0; URL=sugar_servers.php?stats=1"><?
} else if (isset($_GET['stats'])) {
    
    $result = mysql_query("SELECT * FROM urgent_lead_sources");        
    $db_u_l_s = Array();
    if (mysql_num_rows($result) > 0) {
        $urgent_sources = "(";
        while ($row = mysql_fetch_assoc($result)) {
            $urgent_sources .= sanitize($row['name']).",";
        }
        $urgent_sources = substr($urgent_sources,0,strlen($urgent_sources)-1).")";
    } else {
        $urgent_sources = "('')";
    }
    
    ?>
    <br /><br />
    
    <b>Total Leads: </b><?
    $db_host = $config_values['SUGAR_HOST'];
    $db_user = $config_values['SUGAR_USER'];
    $db_pass = $config_values['SUGAR_PASS'];
    //echo "Connecting to: $db_host User: $db_user Pass:$db_pass<br />";
    $link = mysql_connect($db_host, $db_user, $db_pass) or die("error:".mysql_error());
    //echo "Connected";
    mysql_select_db($config_values['SUGAR_DB'], $link) or die(mysql_error());
    //echo "Connected";
    $result = mysql_query("SELECT count(*) FROM leads where  deleted = 0 ");
    echo number_format(mysql_result($result,0,0))."<br /><br />";
    
    if ($custom == true) {
        $result = mysql_query("SELECT id FROM lc_customstatus WHERE name = 'new'");
        $new_status = mysql_result($result,0,0);
        
        $result = mysql_query("SELECT id FROM lc_customstatus WHERE name like 'Left Message%'");
        $status_left_messages = "(";
        while ($row = mysql_fetch_assoc($result)) {
            $status_left_messages .= sanitize($row['id']).",";
        }
        $status_left_messages = substr($status_left_messages,0,strlen($status_left_messages)-1).")";
        
        ?>
        <br />
        <br />
        
        
        
        
        <b>NEW not left message Stage 1 (Urgent)</b> (Entered Last 5 Days): <?
        $result = mysql_query("SELECT count(*) FROM leads WHERE DATE_SUB(CURDATE(),INTERVAL 5 DAY) <= date_entered and leads.deleted = 0 and status='$new_status' and lead_source in $urgent_sources");
        echo number_format(mysql_result($result,0,0));
        ?>
        <br />
        <hr>
        <b>Numbers:</b><br />
        <?
        
        $result = mysql_query("SELECT phone_home, phone_mobile, lead_source FROM leads WHERE DATE_SUB(CURDATE(),INTERVAL 5 DAY) <= date_entered and leads.deleted = 0 and status='$new_status'  and lead_source in $urgent_sources");
        while ($row = mysql_fetch_assoc($result)) {
            if (isset($row['phone_mobile']) && $row['phone_mobile'] != $row['phone_home']) {
                echo "Home Phone: ".$row['phone_home'].",  Mobile Phone: ".$row['phone_mobile']." Source: ".$row['lead_source']."<br />";
            } else {
                echo "Home Phone: ".$row['phone_home']." Source: ".$row['lead_source']."<br />";
            }
        }
    } else {
        
        /* This section contains the code to display some sample records from 
         * the Sugar Database to confirm all is working.
         */    
        
        $result = mysql_query("SELECT * FROM leads limit 5");
        // Doesn't need to be last 5 days
        //$result = mysql_query("SELECT * FROM leads WHERE DATE_SUB(CURDATE(),INTERVAL 5 DAY) <= date_entered limit 5");
        while ($row = mysql_fetch_assoc($result)) {
            //print_pre($row);
            $name = $row['salutation']." ".$row['first_name']." ".$row['last_name'];
            ?><a href="http://<?=$config_values['SUGAR_HOST']?>/index.php?module=Leads&offset=1&stamp=1309417637080258700&return_module=Leads&action=DetailView&record=<?=$row['id']?>">
            <? 
            echo $name."</a><br />";;
            if (strlen(trim($row['lead_source'])) == 0) {
                $row['lead_source'] = "No Lead Source";
            }
            $found_number = false;
            if (isset($row['phone_work'])) {
                echo "Work Phone: ".$row['phone_work']." ";
                $found_number = true;
            }
            if (isset($row['phone_mobile'])) {
                echo " Mobile Phone: ".$row['phone_mobile']." ";
                $found_number = true;
            } 
            if (isset($row['phone_home'])) {
                echo " Home Phone: ".$row['phone_home']." ";
                $found_number = true;
            }
            echo "Source: ".$row['lead_source']."<br />";
            $result2 = mysql_query("SELECT * FROM notes WHERE parent_id = ".sanitize($row['id'])) or die(mysql_error());
            if (mysql_num_rows($result2) > 0) {
                while ($row2 = mysql_fetch_assoc($result2)) {
                    //print_pre($row2);
                    echo "Note: ".$row2['name']." ";
                    echo $row2['date_entered']."<br />";
                }
            } else {
                echo "No notes<br />";
            }
            echo '<a href="sugar_servers.php?create_note=1&parent_id='.$row['id'].'&name=Phone Call&description=A test entry">Create a dummy note</a>';
            echo "<hr /><br />";
        }
        echo "Dummy GUID: ".create_guid()."<br />";
    }
    ?>
    
    <hr />
    <br />
    
    
    
    
    
    <?if ($custom == true) {?>
        <b>NEW not left message Stage 1</b> (Entered Last 5 Days): <?
        $result = mysql_query("SELECT count(*) FROM leads WHERE DATE_SUB(CURDATE(),INTERVAL 5 DAY) <= date_entered and leads.deleted = 0 and status='$new_status'");
        echo number_format(mysql_result($result,0,0));
        ?>
        <br />
        <hr>
        <b>Numbers:</b><br />
        <?
        $result = mysql_query("SELECT phone_home, phone_mobile, lead_source FROM leads WHERE DATE_SUB(CURDATE(),INTERVAL 5 DAY) <= date_entered and leads.deleted = 0 and status='$new_status'");
        while ($row = mysql_fetch_assoc($result)) {
            if (isset($row['phone_mobile']) && $row['phone_mobile'] != $row['phone_home']) {
                echo "Home Phone: ".$row['phone_home'].",  Mobile Phone: ".$row['phone_mobile']." Source: ".$row['lead_source']."<br />";
            } else {
                echo "Home Phone: ".$row['phone_home']." Source: ".$row['lead_source']."<br />";
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
        
        
        
        <?
    }
    ?>
    
    
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
    
    <?if ($custom == true) {?>
        
        <b>Timezones:</b> <br /><?
        $result = mysql_query("select count(*), time_zone_c from leads, leads_cstm where leads.id = leads_cstm.id_c and leads.deleted = 0 group by time_zone_c");
        while ($row = mysql_fetch_assoc($result)) {
            if (strlen($row['time_zone_c']) == 0) {
                $row['time_zone_c'] = "UNSET";
            }
            /*
             $tz_db_name[] = $row['name'];
             $tz_db_start[] = $row['start'];
             $tz_db_end[] = $row['end'];
             
             */
            //    $key = -1;
            //        if (in_array($row['time_zone_c'], $tz_db_name) {
            $key = array_search(trim($row['time_zone_c']), $tz_db_name);
            if ($key === false) {
                echo '<a href="sugar_servers.php?tz=1&add=1&name='.trim($row['time_zone_c']).'">TIME ZONE NOT FOUND!</a> - ';
                $key = array_search('UNSET', $tz_db_name);
                
            }
            //      }
            echo $row['time_zone_c'].": ".$row['count(*)']." (".$tz_db_start[$key]."-".$tz_db_end[$key].")<br />";
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
    }
    ?>
    
    
    
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
    <option value="0">Dont Call</option> 
    <option value="0.5">Every Second Day</option> 
    </select>
    </form>
    <?
} else if (isset($_GET['urgent'])) {
    $result = mysql_query("SELECT * FROM urgent_lead_sources");        
    $db_u_l_s = Array();
    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_assoc($result)) {
            $db_u_l_s[] = $row['name'];
        }
    }
    if (isset($_GET['save'])) {
        $post_db_u_l_s = $_POST['sources'];
        
        $in_both = array_intersect($db_u_l_s, $post_db_u_l_s);
        
        $in_db_not_post = array_diff($db_u_l_s, $post_db_u_l_s);
        
        $in_post_not_db = array_diff($post_db_u_l_s, $db_u_l_s);
        
        //        echo "IN BOTH: ";
        //      print_pre($in_both);
        
        /*echo "DB: ";
         print_pre($db_u_l_s);
         
         echo "POST: ";
         print_pre($post_db_u_l_s);*/
        
        //        echo "In DB, Not Post: ";
        //    print_pre($in_db_not_post);
        
        foreach ($in_db_not_post as $remove) {
            $result = mysql_query("DELETE FROM urgent_lead_sources WHERE name = ".sanitize($remove));
        }
        
        
        //        echo "In post not db: ";
        //  print_pre($in_post_not_db);
        
        foreach ($in_post_not_db as $add) {
            $result = mysql_query("INSERT INTO urgent_lead_sources (name) VALUES (".sanitize($add).")");
        }
        
        
    }
    
    $result = mysql_query("SELECT * FROM urgent_lead_sources");        
    $db_u_l_s = Array();
    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_assoc($result)) {
            $db_u_l_s[] = $row['name'];
        }
    }
    
    
    
    $db_host = $config_values['SUGAR_HOST'];
    $db_user = $config_values['SUGAR_USER'];
    $db_pass = $config_values['SUGAR_PASS'];
    //echo "Connecting to: $db_host User: $db_user Pass:$db_pass<br />";
    $link = mysql_connect($db_host, $db_user, $db_pass);
    if (!$link) {
        die('Could not connect ' . mysql_error());
    }//OR die(mysql_error());
    mysql_select_db($config_values['SUGAR_DB'], $link);
    
    $result = mysql_query("SELECT distinct lead_source FROM leads");
    while ($row = mysql_fetch_assoc($result)) {
        //print_pre($row);
        if (strlen($row['lead_source']) > 0) {
            $lead_sources[] = $row['lead_source'];
        }
    }
    ?>
    <form action="sugar_servers.php?urgent=1&save=1" method="post">
    <b>Realtime Lead Sources: </b><br /><br />
    <table><tr><td style="text-align: left">
    <?
    if (count($lead_sources) == 0) {
        echo "There are no lead sources in the database";
    } else {
        foreach ($lead_sources as $source) {
            if (in_array($source, $db_u_l_s)) {
                $checked = " checked";
            } else {
                $checked = "";
            }
            echo '<input type="checkbox" value="'.$source.'" name="sources[]"'.$checked.'> ';
            echo $source."<br />";
            
        }
        ?>
        <br />
        <input type="submit" value="Save Changes">
        </td></tr></table>
        </form>
        <?}
} else if (isset($_GET['tz'])) {
    if (isset($_GET['save'])) {
        //        print_pre($_POST);
        $result = mysql_query("UPDATE time_zones set name = ".sanitize($_POST['name']).", start = ".sanitize($_POST['start']).", end = ".sanitize($_POST['end'])." WHERE id = ".sanitize($_POST['id'])."");
    }
    if (isset($_GET['save_new'])) {
        //        print_pre($_POST);
        $result = mysql_query("INSERT INTO time_zones (name, start, end) VALUES (".sanitize($_POST['name']).", ".sanitize($_POST['start']).", ".sanitize($_POST['end']).")");
    }
    if (isset($_GET['delete_sure'])) {
        $result = mysql_query("DELETE FROM time_zones WHERE id = ".sanitize($_GET['delete_sure']));
        
    }
    
    if (isset($_GET['delete'])) {
        //        print_pre($_POST);
        ?>
        Are you sure you want to delete this timezone?<br />
        <br />
        <a href="sugar_servers.php?tz=1&delete_sure=<?=$_GET['delete']?>">Yes, I am sure</a><br />
        <br />
        <a href="sugar_servers.php?tz=1">No, leave it</a><br />
        
        <?
    } else     if (isset($_GET['add'])) {   
        ?>
        Add New TimeZone<br /><br />
        <form action="sugar_servers.php?tz=1&save_new=1" method="post">
        Name: <input type="text" name="name" value="<?=$_GET['name']?>"><br />
        Start: <input type="text" name="start"><br />
        End: <input type="text" name="end"><br />
        <br />
        <input type="submit" value="Add Timezone">
        </form>
        <?
    }else if (isset($_GET['edit'])) {
        $result = mysql_query("SELECT * FROM time_zones WHERE id = ".sanitize($_GET['edit']));
        $row = mysql_fetch_assoc($result);
        ?>
        <form action="sugar_servers.php?tz=1&save=1" method="post">
        <input type="hidden" name="id" value="<?=$row['id']?>">
        Name: <input type="text" name="name" value="<?=$row['name']?>"><br />
        Start: <input type="text" name="start" value="<?=$row['start']?>"><br />
        End: <input type="text" name="end" value="<?=$row['end']?>"><br />
        <input type="submit" value="Save Changes">
        </form>
        <?
    } else {
        echo '<a href="sugar_servers.php?tz=1&add=1">Add New TimeZone</a><br /><br />';
        $result = mysql_query("SELECT * FROM time_zones");
        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_assoc($result)) {
                echo $row['name']." Start: ".$row['start']." End: ".$row['end'].' <a href="sugar_servers.php?tz=&edit='.$row['id'].'">Edit</a>  <a href="sugar_servers.php?tz=&delete='.$row['id'].'">Delete</a><br />';
            }
        }
    }
    ?>
    <br />
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
    
    <a href="sugar_servers.php?rules=1">
    Record Transfer Rules
    </a>
    <br />
    
    <?if ($custom == true) {?>
        <a href="sugar_servers.php?urgent=1">
        Realtime Lead Sources
        </a>
        <br />
        
        <a href="sugar_servers.php?tz=1">
        Timezones
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
        <?}?>
    
    
    <br />
    
    <?
}
echo "</center>";
box_end();
require "footer.php";
?>
