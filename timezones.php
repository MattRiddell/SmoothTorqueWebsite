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

*/

require "header.php";
require "footer.php";
?>