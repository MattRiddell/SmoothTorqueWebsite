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

if (isset($_GET['view_timezones'])) {
    // Get all the timezones
    $result = mysql_query("SELECT * FROM SineDialer.time_zones");
    if (mysql_num_rows($result) > 0) {
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
        box_end();
        
    }
}
require "footer.php";
?>