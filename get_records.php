#!/usr/bin/php5
<?
$number_to_get = 1000000;
$min_new_records = 1000000;
$campaignid = 63;
$url = "http://someserver/getleads/leads.aspx?apikey=x&qty=";
require "admin/db_config.php";
$result = mysql_query("SELECT count(*) from number where status = 'new'") or die(mysql_error());
$count = mysql_result($result,0,0);
//echo $count;
if ($count <= $min_new_records) {
    echo "Not enough records ($count <= $min_new_records)\n";
    echo "Copy existing data to backup table\n";
    echo "Delete existing data\n";
    echo "Import new data\n";
    echo "Scrub against DNC\n";
    echo "Run TimeZone script\n";
}
?>