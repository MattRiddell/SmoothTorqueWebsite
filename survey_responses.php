<?
if (isset($_GET['campaign_id'])) {
    require "admin/db_config.php";
    require "functions/sanitize.php";
    $campaign_name = mysql_result(mysql_query("SELECT name FROM campaign WHERE id = ".sanitize($_GET['campaign_id'])),0,0);
    header("Content-type: application/csv"); 
    header("Content-Disposition: attachment; filename=".$campaign_name."_".@date('l jS \of F Y h:i:s A').".csv");
    header("Pragma: no-cache");
    header("Expires: 0");
    echo "Phone Number, Question, Choice, Date/Time\n";
    $result = mysql_query("SELECT * FROM survey_results WHERE campaign_id = ".sanitize($_GET['campaign_id'])." ORDER by phonenumber, datetime") or die(mysql_error());
    if (mysql_num_rows($result) == 0) {
        echo "There are no responses";
    } else {
        while ($row = mysql_fetch_assoc($result)) {
//            print_r($row);
            echo $row['phonenumber'].",".$row['question'].",".$row['choice'].",".$row['datetime']."\n";
        }
    }
//    require "footer.php";
    exit(0);
}
require "header.php";
require "header_surveys.php";
box_start(400);
echo "<center>";
$result = mysql_query("SELECT campaign_id, count(distinct phonenumber) as count FROM survey_results group by campaign_id");
if (mysql_num_rows($result) == 0) {
    echo "No survey results";
} else {
    while ($row = mysql_fetch_assoc($result)) {
        $campaign_names = mysql_query("SELECT name FROM campaign WHERE id = ".$row['campaign_id']);
        if (mysql_num_rows($campaign_names) == 0) {
            $name = "Unknown (deleted)";
        } else {
            $name = mysql_result($campaign_names,0,0);
        }
        echo '<a href="survey_responses.php?campaign_id='.$row['campaign_id'].'">'.$name." Count: ".$row['count']."</a><br />";
    }
}
box_end();
require "footer.php";
?>