#!/usr/bin/php5
<?
$number_to_get = 1000000;
$min_new_records = 1000000;
$campaignid = 63;
$url = "http://someserver/getleads/leads.aspx?apikey=x&qty=";
require "admin/db_config.php";
$result = mysql_query("SELECT count(*) from number where status = 'new'");
$count = mysql_result($result,0,0);
echo $count;
if ($count <= $min_new_records) {
    echo "Not enough records";
    // Copy existing data to backup table
    // Delete existing data
    // Import new data
    // Scrub against DNC
    // Run TimeZone script
}
?>