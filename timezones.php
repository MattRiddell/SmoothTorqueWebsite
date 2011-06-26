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

*/

require "header.php";
require "footer.php";
?>