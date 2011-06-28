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
 start: varchar - the start time that this timezone can be called (UTC) 
 end: varchar - the end time that this timezone can be called (UTC) 
 
 The definition of the time_zone_prefixes table is:
 
 id: autoincrement integer (record id - auto created)
 prefix: varchar number prefix
 timezone: id of the timezone this prefix is associated with
 
 There is a cron job (cron/cron_timezone.php) that runs every 30 minutes and does the following:
 
 1. Check whether timezone management is enabled (admin/advanced)
 2. If so, changes the status of numbers that have a status of "new" to "new_nodial" 
 if the number shouldn't be dialled yet
 3. Changes the status of numbers that have a status of "new_nodial" to "new" if
 the timezone now diallable.
 
 When numbers are imported into the database and they timezone management is enabled,
 number are imported with new_nodial rather than new - in case someone imports
 numbers at a time they shouldn't be dialled.
 
 */

// Include Header
require "header.php";

// Include Timezone specific header
require "header_timezones.php";

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
    <META HTTP-EQUIV=REFRESH CONTENT="3; URL=timezones.php?view_timezones=1"><?
    require "footer.php";
    exit(0);
}
if (isset($_GET['add'])) {
    box_start(400);
    ?>
    <form action="timezones.php?save_new=1" method="post">
    <table>
    <tr>
    <td>Timezone Name:</td>
    <td><input type="text" name="name"></td>
    </tr>
    <tr>
    <td>UTC Start Dialling Time:</td>
    <td><input type="text" name="start"></td>
    </tr>
    <tr>
    <td>UTC End Dialling Time:</td>
    <td><input type="text" name="end"></td>
    </tr>
    <tr>
    <td colspan="2">
    <input type="submit" value="Add Timezone"></td>
    </tr>
    </table>
    </form>
    <?
    box_end();
    // Don't fall through
    require "footer.php";
    exit(0);
}
if (isset($_GET['view_timezones'])) {
    // Get all the timezones
    $result = mysql_query("SELECT * FROM SineDialer.time_zones");
    if (mysql_num_rows($result) > 0) {
        //box_start(500);
        echo "<center>";
        echo '<table border="0" cellpadding="3" cellspacing="0">';
        echo '<tr height="10"><td class="theadl"></td><td class="thead">Timezone Name</td><td class="thead">UTC Start Dialling Time</td><td class="thead">UTC End Dialling Time</td><td class="thead">Delete</td><td class="theadr"></td></tr>';
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
            echo "<td>".'<a href="timezones.php?edit='.$row['id'].'">'.$row['name'].'&nbsp;<img src="images/pencil.png" border="0" alt="Edit TimeZone">'."</td>";
            echo "<td>".$row['start']."</td>";
            echo "<td>".$row['end']."</td>";
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
require "footer.php";
?>