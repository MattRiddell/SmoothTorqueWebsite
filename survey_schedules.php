<?
/*
 Scheduler for Surveys - Created for TSOA International, October 2011
 
 Brief:
 
 Create a scheduler to be able to set leads per campaign and per hour (this will 
 allow us to set a certain number of leads per hour and per day which will shut 
 the dialing down when a campaign hits a certain amount of leads (this would be 
 great if we could also be able to select each hour amount of leads.  I.e. 9a.m-
 9: 59 am amount of leads 50 10 a.m. -10:59 a.m. 40 etc. In some cases it will 
 be the same amount for each hour but sometimes the rooms change shifts so it 
 would be nice to have option to adjust leads based on hour
 
 We therefore need the following:
 
 1. A table that contains campaigns that should be run, a field for number
 of leads, and a field for the hour.  The unique id is created by the hour 
 and the campaign id.
 
 2. A cron job that checks how many leads have been generated during each hour
 and stops campaigns once the total has been reached (run every minute).
 
 3. A cron job that starts campaigns every hour that have more than 0 leads 
 required for that hour.
 
 The above steps are covered by the following:
 
 1. survey_schedules table:
 
 CREATE TABLE `survey_schedules` (
 `campaign_id` int(11) unsigned NOT NULL,
 `start_hour` int(10) unsigned NOT NULL,
 `leads_required` int(10) unsigned NOT NULL,
 PRIMARY KEY (`campaign_id`,`start_hour`))
 
 2. cron/count_leads.php
 
 3. cron/start_surveys.php
 */

require "header.php";
require "header_surveys.php";

if (isset($_GET['delete_sure'])) {
    $result = mysql_query("DELETE FROM survey_schedules WHERE campaign_id = ".sanitize($_GET['delete_sure']));
    redirect("survey_schedules.php","Deleting the schedules");
    require "footer.php";
    exit(0);
}

if (isset($_GET['delete'])) {
    box_start();
    echo '<center>';
    echo '<br />';
    $result = mysql_query("SELECT name FROM campaign WHERE id = ".sanitize($_GET['delete']));
    
    echo "Are you sure you would like to delete the schedule entries for the following campaign:<br /><br /> ".mysql_result($result,0,0)."<br /><br />";
    echo '<a href="survey_schedules.php?delete_sure='.$_GET['delete'].'">Yes, I am sure, delete them</a><br /><br />';
    echo '<a href="survey_schedules.php">No, do not delete them</a><br />';
    echo '<br />';
    box_end();
    require "footer.php";
    exit(0);
}

if (isset($_GET['save_edit'])) { 
    $campaign_id = sanitize($_POST['campaign_id']);
    $sql = mysql_query("DELETE FROM survey_schedules WHERE campaign_id = $campaign_id");
    $result = mysql_query($sql);
    foreach ($_POST as $field=>$value) {
        if (substr($field,0,11) == "start_hour_") {
            $hour = sanitize(substr($field,11));
            $count = sanitize($value);
            $sql = "REPLACE INTO survey_schedules (campaign_id, start_hour, leads_required) values ($campaign_id, $hour, $count)";
            $result = mysql_query($sql);
        }
    }
    redirect("survey_schedules.php", "Saving your changes");
    require "footer.php";
    exit(0);
}

if (isset($_GET['edit'])) {
    $result_name = mysql_query("SELECT name from campaign WHERE id = ".sanitize($_GET['edit']));
    $name = mysql_result($result_name, 0, 0);
    $result = mysql_query("SELECT * from survey_schedules WHERE campaign_id = ".sanitize($_GET['edit']));    
    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_assoc($result)) {
            $leads_required[$row['start_hour']] = $row['leads_required'];
        }
    }
    box_start();
    echo "<center><h3>Edit Survey Schedule for $name</h3>";
    echo '<form action="survey_schedules.php?save_edit=1" method="post">';
    echo '<input type="hidden" name="campaign_id" value='.$_GET['edit'].'>';
    echo "<table>";
    
    for ($i = 0;$i<24;$i++) {
        if (isset($leads_required[$i])) {
            $count = $leads_required[$i];
        } else {
            $count = 0;
        }
        echo '<tr><td>'.$i.':00</td><td><input type="text" name="start_hour_'.$i.'" value="'.$count.'"></td></tr>';
    }
    echo '<tr><td colspan="2"><input class="btn btn-primary" type="submit" value="Save Schedule"></td></tr>';
    echo "</table></form>";        
    box_end();
    require "footer.php";
    exit(0);
}



if (isset($_GET['save'])) {    
    $campaign_id = sanitize($_POST['campaign_id']);
    foreach ($_POST as $field=>$value) {
        if (substr($field,0,11) == "start_hour_") {
            $hour = sanitize(substr($field,11));
            $count = sanitize($value);
            $sql = "REPLACE INTO survey_schedules (campaign_id, start_hour, leads_required) values ($campaign_id, $hour, $count)";
            $result = mysql_query($sql);
        }
    }
    redirect("survey_schedules.php","Adding your schedules");
    require "footer.php";
    exit(0);
}

if (isset($_GET['add'])) {
    $result = mysql_query("SELECT id, name FROM campaign");
    if (mysql_num_rows($result) == 0) {
        echo "There are no campaigns - please create a campaign first";
    } else {
        box_start();
        echo "<center><h3>Add Survey Schedule</h3>";
        echo '<form action="survey_schedules.php?save=1" method="post">';
        echo "<table>";
        echo "<tr><td>Campaign:</td><td>";
        echo '<select name="campaign_id">';
        while ($row = mysql_fetch_assoc($result)) {
            echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
        }
        echo "</select>";
        echo "</td></tr>";
        for ($i = 0;$i<24;$i++) {
            echo '<tr><td>'.$i.':00</td><td><input type="text" name="start_hour_'.$i.'" value="0"></td></tr>';
        }
        echo '<tr><td colspan="2"><input class="btn btn-primary" type="submit" value="Add Schedule"></td></tr>';
        echo "</table></form>";        
        box_end();
    }
    require "footer.php";
    exit(0);
}

$result = mysql_query("SELECT distinct campaign_id, campaign.name FROM survey_schedules, campaign where survey_schedules.campaign_id = campaign.id");
if (mysql_num_rows($result) == 0) {
    box_start();
    ?>
    <center><br />
    There are currently no schedules defined<br />
    <br />
    <a href="survey_schedules.php?add=1">Click here</a> to create one</a>
    </center>
    <br />
    <?
    box_end();
} else {
    echo '<center><table border="0" cellpadding="3" cellspacing="0">';
    echo '<tr height="10"><td class="theadl"></td><td class="thead">Campaign Name</td><td class="thead">Delete</td><td class="theadr"></td></tr>';
    
    while ($row = mysql_fetch_assoc($result)) {
        /* Alternate the display */
        if ($toggle){
            $toggle=false;
            $class=" class=\"tborder2\"  onmouseover=\"style.backgroundColor='#84DFC1';\" onmouseout=\"style.backgroundColor='#f8f8f8'\"   ";
        } else {
            $toggle=true;
            $class=" class=\"tborderx\"  onmouseover=\"style.backgroundColor='#84DFC1';\" onmouseout=\"style.backgroundColor='#f0f0f0'\" ";
        }
        echo '<tr '.$class.'><td></td><td>';
        echo '<a href="survey_schedules.php?edit='.$row['campaign_id'].'">'.$row['name'].'&nbsp;<img src="images/pencil.png" border="0"></a>';
        echo '</td>';
        echo '<td>';
        echo '<a href="survey_schedules.php?delete='.$row['campaign_id'].'"><img src="images/delete.png"></a>';
        echo '</td>';
        echo '<td></td></tr>';
    }
    echo "</table>";
}
require "footer.php";
?>