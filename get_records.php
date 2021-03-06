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
    $result = mysql_query("INSERT IGNORE INTO number_backup(SELECT * FROM number where campaignid = $campaignid and status != 'new')");
    echo "Delete existing data (leave press 1s)\n";
    $result = mysql_query("DELETE FROM number WHERE campaignid = $campaignid and status != 'pressed1' and status != 'new'");
    echo "Get new data\n";
    $contents = file_get_contents($url.$number_to_get);
    $lines = explode("\r\n",$contents);
    echo "Import new data\n";
    $x = 0;
    foreach ($lines as $line) {
        if (strlen(trim($line)) > 0) {
            $random_sort = rand(1,999999999);
            $sql = "INSERT IGNORE INTO number (campaignid, phonenumber, random_sort, status) VALUES ($campaignid, $line, $random_sort, 'new')";
            //echo $sql."\n";
            $result = mysql_query($sql) or die(mysql_error());
            $x++;
            if ($x % 1000 == 0) {
                echo ($x/$number_to_get*100)."% done\r";
            }
        }
    }
    echo "\n";
    //echo "Remove records that have taken surveys\n";
    //$result = mysql_query("DELETE FROM number WHERE phonenumber in (SELECT phonenumber FROM survey_results)");
    echo "Scrub against DNC\n";
    $result = mysql_query("SELECT number.phonenumber FROM number LEFT JOIN dncnumber ON number.phonenumber=dncnumber.phonenumber WHERE dncnumber.phonenumber IS NOT NULL AND number.campaignid=$campaignid") or die(mysql_error());
    $x = 0;
    while ($row = mysql_fetch_assoc($result)) {
        $x++;
        echo "IN DNC: ".$row['phonenumber']." (".round($x/mysql_num_rows($result)*100,2).")                \r";
        $resultx = mysql_query("UPDATE number set status='indnc' WHERE phonenumber = '".$row['phonenumber']."'") or die(mysql_error());
    }
    echo "Scrub against Existing\n";
    $result = mysql_query("SELECT number.phonenumber, number.status FROM number LEFT JOIN pressed1 ON number.phonenumber=pressed1.phonenumber WHERE pressed1.phonenumber IS NOT NULL AND number.campaignid=$campaignid and number.status = 'new'") or die(mysql_error());
    $x = 0;
    while ($row = mysql_fetch_assoc($result)) {
        $x++;
        echo "Old Press1: ".$row['phonenumber']." (".round($x/mysql_num_rows($result)*100,2).")                \r";
        $resultx = mysql_query("UPDATE number set status='previous_press1' WHERE campaignid = $campaignid and phonenumber = '".$row['phonenumber']."'") or die(mysql_error());
    }
    
    echo "Remove State Omits\n";
    $result = mysql_query("select prefix from states where state in (select * from state_omits)");
    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_assoc($result)) {
            $result_x = mysql_query("delete from number where status = 'new' and campaignid = $campaignid and phonenumber like '1".$row['prefix']."%'");
        }
    }
    
    echo "Remove AreaCode Omits\n";
    $result = mysql_query("select areacode from areacode_omits");
    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_assoc($result)) {
            $result_x = mysql_query("delete from number where status = 'new' and campaignid = $campaignid and phonenumber like '".$row['areacode']."%'");
        }
    }
    
    
    echo "\n";
    echo "Run TimeZone script\n";
    $result = mysql_query("select time_zones.start, time_zones.end, prefix from time_zones, timezone_prefixes where timezone_prefixes.timezone = time_zones.id");
    $count = mysql_num_rows($result);
    $x = 0;
    while ($row = mysql_fetch_assoc($result)) {
        $x++;
        if ($x % 100) {
            echo "Timezone Progress: ".round($x/$count*100)."%\r";
        }
        $sql = "UPDATE number set start_time = '".$row['start']."', end_time = '".$row['end']."' WHERE phonenumber like '".$row['prefix']."%' and campaignid=$campaignid";
        $result2 = mysql_query($sql);
    }
    echo "\nAll done\n";
} else {
    echo "We have enough records ($count > $min_new_records)\n";
}
?>