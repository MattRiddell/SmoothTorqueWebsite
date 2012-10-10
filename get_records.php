#!/usr/bin/php5
<?
function make_seed()
{
    list($usec, $sec) = explode(' ', microtime());
    return (float) $sec + ((float) $usec * 100000);
}
$number_to_get = 10;
$min_new_records = 1000000;
$campaignid = 63;
$url = "http://someserver/getleads/leads.aspx?apikey=x&qty=";
require "admin/db_config.php";
$result = mysql_query("SELECT count(*) from number where status = 'new' and campaignid = $campaignid") or die(mysql_error());
$count = mysql_result($result,0,0);
//echo $count;
srand(make_seed());
if ($count <= $min_new_records) {
    echo "Not enough records ($count <= $min_new_records)\n";
    echo "Copy existing data to backup table\n";
    $result = mysql_query("INSERT IGNORE INTO number_backup(SELECT * FROM number where campaignid = $campaignid)");
    echo "Delete existing data (leave press 1s)\n";
    $result = mysql_query("DELETE FROM number WHERE camapaignid = $campaignid and status != 'pressed1'");
    echo "Get new data\n";
    $contents = file_get_contents($url.$number_to_get);
    $lines = explode("\n",$contents);
    echo "Import new data\n";
    $x = 0;
    foreach ($lines as $line) {
        $random_sort = rand(1,999999999);
        $result = mysql_query("INSERT INTO number (campaignid, phonenumber, random_sort) VALUES ($campaignid, $line, $random_sort");
        $x++;
        if ($x % 1000 == 0) {
            echo ($x/$number_to_get*100)."% done\n";
        }
    }
    echo "Scrub against DNC\n";
    echo "Run TimeZone script\n";
} else {
    echo "We have enough records ($count > $min_new_records)\n";
}
?>