<?
require "header.php";
require "header_surveys.php";
if (isset($_GET['all_campaigns'])) {
    box_start();
    echo "<center><br />Coming Soon";
    require "footer.php";
    exit(0);
}
if (isset($_GET['historical_campaign'])) {
    box_start();
    echo "<center><br />Coming Soon";
    require "footer.php";
    exit(0);
}
if (isset($_GET['historical'])) {
    box_start();
    echo "<center><h3>Select Campaign:</h3>";
    //TODO: split by user/admin
    $result = mysql_query("SELECT * from campaign");
    ?>
    <form action="transfer_report.php?historical_campaign=1" method="post">
    <select name="campaign_id">
    <?
    while ($row = mysqL_fetch_assoc($result)) {
        echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
    }
    ?>
    </select>
    <input type="submit" value="Display Report">
    </form>
    <?
    require "footer.php";
    exit(0);
}
box_start(250);
echo "<center>";
?>
<h3>Transfer Reports</h3>
<a href="transfer_report.php?all_campaigns=1"><img src="images/folder.png">&nbsp;All Campaigns</a><br /><br />
<a href="transfer_report.php?historical=1"><img src="images/calendar.png">&nbsp;Historical Transfers</a><br />
<br />
<?
box_end();
require "footer.php";
?>