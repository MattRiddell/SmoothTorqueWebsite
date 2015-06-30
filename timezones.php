<?
/* TimeZone Management - created for VentureVoIP Canada, June 2011 
 
 TimeZones work in the following way:
 
 There are two tables: 
 
 SineDialer.time_zones
 
 and
 
 SineDialer.time_zone_prefixes
 
 The definition of the time_zones table entries are as follows:
 
 id: autoincrement integer (record id - auto created)
 name: varchar - the name of the timezone
 start: varchar - the start time that this timezone can be called (Local) 
 end: varchar - the end time that this timezone can be called (Local) 
 
 The definition of the time_zone_prefixes table is:
 
 prefix: varchar number prefix (unique)
 timezone: id of the timezone this prefix is associated with
 
 There is a cron job (cron/cron_timezone.php) that runs every 30 minutes and does the following:
 
 1. Check whether timezone management is enabled (admin/advanced)
 2. If so, changes the status of numbers that have a status of "new" to "new_nodial" 
 if the number shouldn't be dialled yet
 3. Changes the status of numbers that have a status of "new_nodial" to "new" if
 the timezone now diallable.
 
 When numbers are imported into the database and timezone management is enabled,
 number are imported with new_nodial rather than new - in case someone imports
 numbers at a time they shouldn't be dialled.
 
 */

// Include Header
require "header.php";

// Include Timezone specific header
require "header_timezones.php";
if (isset($_GET['delete_sure'])) {
    $result = mysql_query("DELETE FROM SineDialer.time_zones WHERE id = ".sanitize($_GET['delete_sure'])) or die(mysql_error());
    ?><center><img src="images/progress.gif" border="0"><br />Deleting your timezone...
    <META HTTP-EQUIV=REFRESH CONTENT="1; URL=timezones.php?view_timezones=1"><?
    
    require "footer.php";
    exit(0);    
}
if (isset($_GET['delete'])) {
    $result = mysql_query("SELECT * FROM SineDialer.time_zones WHERE id = ".sanitize($_GET['delete']));
    if (mysql_num_rows($result) == 0) {
        // Tried to delete something that didn't exist
        $ip = $_SERVER['REMOTE_ADDR'];
        echo "Attempted record deletion from $ip ($_COOKIE[user])";
        /*================= Log Access ======================================*/
        $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_COOKIE[user]', ' $ip attempted to delete a timezone that doesn\'t exist')";
        $result=mysql_query($sql, $link);
        /*================= Log Access ======================================*/        
    } else {
        $row = mysql_fetch_assoc($result);
        box_start();
        ?>
        <center><br />
        Are you sure you want to delete the timezone <b><?=$row['name']?></b>?<br />
        <br />
        <a href="timezones.php?delete_sure=<?=$row['id']?>">Yes, I'm Sure</a><br />
        <br />
        <a href="timezones.php?view_timezones=1">No, don't delete <?=$row['name']?></a><br />  <br />      
        <?
        box_end();
    }
    require "footer.php";
    exit(0);    
}
if (isset($_GET['save_new'])) {
    $sql = "INSERT INTO SineDialer.time_zones (";
    $sql2 = " VALUES (";
    foreach($_POST as $field=>$value) {
        $sql.=sanitize($field, false).",";
        $sql2 .= sanitize($value).",";
    }
    $sql = substr($sql,0,strlen($sql)-1).")".substr($sql2,0,strlen($sql2)-1).")";
    mysql_query($sql) or die(mysql_error());
    ?><center><img src="images/progress.gif" border="0"><br />Saving your timezone...
    <META HTTP-EQUIV=REFRESH CONTENT="1; URL=timezones.php?view_timezones=1"><?
    require "footer.php";
    exit(0);
}
if (isset($_GET['save_edit'])) {
    $sql = "UPDATE SineDialer.time_zones SET ";
    foreach($_POST as $field=>$value) {
        if ($field != "id") {
            $sql.=sanitize($field, false)."=";
            $sql.= sanitize($value).",";
        }
    }
    $sql = substr($sql,0,strlen($sql)-1)." where id = ".sanitize($_POST['id']);
    mysql_query($sql) or die(mysql_error());
    ?><center><img src="images/progress.gif" border="0"><br />Saving your timezone...
    <META HTTP-EQUIV=REFRESH CONTENT="1; URL=timezones.php?view_timezones=1"><?
 
    require "footer.php";
    exit(0);
}
if (isset($_GET['edit'])) {
    box_start(400);
    $result = mysql_query("SELECT * FROM time_zones where id = ".sanitize($_GET['edit']));
    $row = mysql_fetch_assoc($result);
    ?>
    <center><br />
    <form action="timezones.php?save_edit=1" method="post">
    <input type="hidden" name="id" value="<?=$row['id']?>">
    <table>
    <tr>
    <td>Timezone Name:</td>
    <td><input type="text" name="name" value="<?=$row['name']?>"></td>
    </tr>
    <tr>
    <td>Local Start Dialling Time:</td>
    <td><input type="text" name="start" value="<?=$row['start']?>"></td>
    </tr>
    <tr>
    <td>Local End Dialling Time:</td>
    <td><input type="text" name="end" value="<?=$row['end']?>"></td>
    </tr>
    <tr>
    <td colspan="2">
    <input class="btn btn-primary" type="submit" value="Save Changes"></td>
    </tr>
    </table>
    </form><br />
    <?
    box_end();
    // Don't fall through
    require "footer.php";
    exit(0);
}
if (isset($_GET['add'])) {
    box_start(400);
    ?>
    <center><br />
    <form action="timezones.php?save_new=1" method="post">
    <table>
    <tr>
    <td>Timezone Name:</td>
    <td><input type="text" name="name"></td>
    </tr>
    <tr>
    <td>Local Start Dialling Time:</td>
    <td><input type="text" name="start"></td>
    </tr>
    <tr>
    <td>Local End Dialling Time:</td>
    <td><input type="text" name="end"></td>
    </tr>
    <tr>
    <td colspan="2">
    <input class="btn btn-primary" type="submit" value="Add Timezone"></td>
    </tr>
    </table>
    </form><br />
    <?
    box_end();
    // Don't fall through
    require "footer.php";
    exit(0);
}
if (isset($_GET['view_timezones'])) {
    // Get all the timezones
    $result = mysql_query("SELECT * FROM SineDialer.time_zones order by start");
    if (mysql_num_rows($result) > 0) {
        //box_start(500);
        echo "<center>";
        echo '<table border="0" cellpadding="3" cellspacing="0" class="table">';
        echo '<tr height="10"><td class=""></td><td class="">Timezone Name</td><td class="">Local Start Dialling Time</td><td class="">Local End Dialling Time</td><td class="">Prefixes</td><td class="">Delete</td><td class=""></td></tr>';
        $toggle = false;
        while ($row = mysql_fetch_assoc($result)) {
            if ($toggle){
                $toggle=false;
                $class=" class=\"tborder2\"  onmouseover=\"style.backgroundColor='#84DFC1';\" onmouseout=\"style.backgroundColor='#f8f8f8'\"   ";
            } else {
                $toggle=true;
                $class=" class=\"tborderx\"  onmouseover=\"style.backgroundColor='#84DFC1';\" onmouseout=\"style.backgroundColor='#f0f0f0'\" ";
            }
            echo "<tr $class><td></td>";
            $result_prefixes = mysql_query("SELECT count(*) FROM timezone_prefixes WHERE timezone = ".sanitize($row['id']));
            $count_prefixes = mysql_result($result_prefixes, 0, 0);
            $result_rand = mysql_query("SELECT prefix FROM timezone_prefixes WHERE timezone = ".sanitize($row['id'])." ORDER BY RAND() LIMIT 1");
            if (mysql_num_rows($result_rand) > 0) {
                $random_example = mysql_result($result_rand,0,0);
            } else {
                $random_example = "No entries";
            }
            echo "<td>".'<a href="timezones.php?edit='.$row['id'].'">'.$row['name'].'&nbsp;<img src="images/pencil.png" border="0" alt="Edit TimeZone">'."</td>";
            echo "<td>".$row['start']."</td>";
            echo "<td>".$row['end']."</td>";
            echo "<td title=\"Random Example: ".$random_example."\">$count_prefixes prefixes</td>";
            echo "<td>".'<a href="timezones.php?delete='.$row['id'].'">&nbsp;<img src="images/delete.png" border="0" alt="Delete TimeZone">'."</td>";
            echo "<td></td></tr>";
        }
        echo "</table>";
        //box_end();
    } else {
        ?>
        <br /><br />
        <?box_start();
        echo "<br /><center><img src=\"images/icons/gtk-dialog-info.png\" border=\"0\" width=\"64\" height=\"64\"><br /><br />";
        ?>
        <b>You don't have any timezones created.</b><br />
        <br />
        In order to use timezone based dialling you will need at least one timezone defined.<br />
        <br />
        <a href="timezones.php?add=1">
        <img src="images/icons/gtk-add.png" border="0" width="64" height="64"><br />
        Click here to create your first timezone</a><br /> or click the Add Timezone button above.
        <br />
        <br />
        <?
        //'
        box_end();
        
    }
    // Don't fall through
    require "footer.php";
    exit(0);
}
if (isset($_GET['save_prefix'])) {
    $result = mysql_query("INSERT INTO timezone_prefixes (prefix, timezone) VALUES (".sanitize($_POST['prefix']).", ".sanitize($_POST['timezone']).")");
    ?><center><img src="images/progress.gif" border="0"><br />Adding your prefix...
    <META HTTP-EQUIV=REFRESH CONTENT="1; URL=timezones.php?view_prefixes=1"><?

    require "footer.php";
    exit(0);
}
if (isset($_GET['add_prefixes'])) {
    
    box_start();
    echo "<center>";
    ?>
    <br />Timezone prefixes are basically the area codes for phone numbers and their
    associated timezones.<br />
    <br />
    You can add a timezone area code by filling out the form below or by using
    the <a href="upload_prefixes.php">upload area codes</a> link.<br />
    <br />
    <form action="timezones.php?save_prefix=1" method="post">
    <table>
    <tr>
    <td>
    Prefix
    </td>
    <td>
    <input type="text" name="prefix">
    </td>
    </tr>
    <tr>
    <td>
    TimeZone
    </td>
    <td>
    <select name="timezone">
    <?
    $result_timezones = mysql_query("SELECT * FROM SineDialer.time_zones order by name");
    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_assoc($result_timezones)) {
            echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
        }
    }
    ?>
    </select>
    </td>
    </tr>
    <tr>
    <td colspan="2">
    <input class="btn btn-primary" type="submit" value="Add Area Code">
    </td>
    </tr>
    </table>
    </form>
    <br />
    <?
    box_end();
    // Don't fall through
    require "footer.php";
    exit(0);
}

if (isset($_GET['view_prefixes'])) {
    $result = mysql_query("SELECT count(*) FROM SineDialer.timezone_prefixes") or die(mysql_error());
    $count = mysql_result($result,0,0);
    if ($count == 0) {
        ?>
        <br /><br />
        <?box_start();
        echo "<br /><center><img src=\"images/icons/gtk-dialog-info.png\" border=\"0\" width=\"64\" height=\"64\"><br /><br />";
        ?>
        <b>You don't have any timezone prefixes created.</b><br />
        <br />
        In order to use timezone based dialling you will need at least one timezone prefix defined.<br />
        <br />
        <a href="timezones.php?add_prefixes=1">
        <img src="images/icons/gtk-add.png" border="0" width="64" height="64"><br />
        Click here to create your first timezone prefix</a><br /> or click the Add Timezone Prefix button above.
        <br />
        <br />
        <?
        //'
        box_end();
    } else {
        box_start();
        echo "Total Prefixes: $count<br />";
        box_end();
    }
    // Don't fall through
    require "footer.php";
    exit(0);
}

require "footer.php";
?>