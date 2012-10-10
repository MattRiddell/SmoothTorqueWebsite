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
$sql = "SELECT count(*) from number where status = 'new' and campaignid = $campaignid";
echo "Running: $sql\n";
$result = mysql_query($sql) or die(mysql_error());
$count = mysql_result($result,0,0);
//echo $count;
srand(make_seed());
if ($count <= $min_new_records) {
    echo "Not enough records ($count <= $min_new_records)\n";
    echo "Copy existing data to backup table\n";
    $result = mysql_query("INSERT IGNORE INTO number_backup(SELECT * FROM number where campaignid = $campaignid where status != 'new')");
    echo "Delete existing data (leave press 1s)\n";
    $result = mysql_query("DELETE FROM number WHERE camapaignid = $campaignid and status != 'pressed1' and status != 'new'");
    echo "Get new data\n";
    $contents = file_get_contents($url.$number_to_get);
    $lines = explode("\r\n",$contents);
    echo "Import new data\n";
    $x = 0;
    foreach ($lines as $line) {
        if (strlen(trim($line)) > 0) {
            $random_sort = rand(1,999999999);
            $sql = "INSERT INTO number (campaignid, phonenumber, random_sort, status) VALUES ($campaignid, $line, $random_sort, 'new')";
            echo $sql."\n";
            $result = mysql_query($sql) or die(mysql_error());
            $x++;
            if ($x % 1000 == 0) {
                echo ($x/$number_to_get*100)."% done\n";
            }
        }
    }
    echo "Scrub against DNC\n";
    $result = mysql_query("SELECT number.phonenumber FROM number LEFT JOIN dncnumber ON number.phonenumber=dncnumber.phonenumber WHERE dncnumber.phonenumber IS NOT NULL AND number.campaignid=$campaignid") or die(mysql_error());
    $x = 0;
    while ($row = mysql_fetch_assoc($result)) {
        $x++;
        echo $row['phonenumber']." (".round($x/mysql_num_rows($result)*100,2).")\n";
        $resultx = mysql_query("UPDATE number set status='indnc' WHERE phonenumber = '".$row['phonenumber']."'") or die(mysql_error());
    }
    echo "Run TimeZone script\n";
    $result = mysql_query("select time_zones.start, time_zones.end, prefix from time_zones, timezone_prefixes where timezone_prefixes.timezone = time_zones.id");
    $count = mysql_num_rows($result);
    $x = 0;
    while ($row = mysql_fetch_assoc($result)) {
        $x++;
        if ($x % 100) {
            echo "Progress: ".round($x/$count*100)."\n";
        }
        $sql = "UPDATE number set start_time = '".$row['start']."', end_time = '".$row['end']."' WHERE phonenumber like '".$row['prefix']."%' and campaignid=$campaignid";
        $result2 = mysql_query($sql);
    }

} else {
    echo "We have enough records ($count > $min_new_records)\n";
}
?>