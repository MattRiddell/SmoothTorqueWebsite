#!/usr/bin/php5
<?
$campaign_id = "89";


$db_host = "localhost";
$db_user = "root";
$db_pass = "";
mysql_connect($db_host, $db_user, $db_pass);
mysql_select_db("SineDialer");
$result = mysql_query("SELECT * FROM number where status not in ('pressed1','pressedf','pressed-ni','pressed-nq') and campaignid = '".$campaign_id."'") or die(mysql_error());
if (mysql_num_rows($result) == 0) {
    // No rows
    echo "No rows available for recycling\n";
} else {
    while ($row = mysql_fetch_assoc($result)) {
        $random_sort = rand(1,999999999);
        $sql = "UPDATE number SET status = 'new', random_sort='$random_sort', times_called=times_called+1 WHERE phonenumber = '".$row['phonenumber']."' and campaignid = '".$campaign_id."'";
        mysql_query($sql) or die(mysql_error());
        echo "Ran $sql\n";
    }
}
?>