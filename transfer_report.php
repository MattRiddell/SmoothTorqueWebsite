<?
require "header.php";
require "header_surveys.php";
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