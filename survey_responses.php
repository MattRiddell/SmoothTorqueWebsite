<?
require "header.php";
require "header_surveys.php";
box_start(400);
echo "<center>";
$result = mysql_query("SELECT campaign_id, count(*) as count FROM survey_results group by campaign_id");
if (mysql_num_rows($result) == 0) {
    echo "No survey results";
} else {
    while ($row = mysql_fetch_assoc($result)) {
        $campaign_names = mysql_query("SELECT name FROM campaign WHERE id = ".$row['campaign_id']);
        echo '<a href="survey_responses.php?campaign_id='.$row['campaign_id'].'">'.mysql_result($campaign_names,0,0)." Count: ".$row['count']."</a><br />";
    }
}
?>

<?
box_end();
require "footer.php";
?>