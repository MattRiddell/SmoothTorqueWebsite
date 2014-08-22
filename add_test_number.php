<?
require "header.php";
$sql = "INSERT INTO SineDialer.number (campaign, status, phonenumber, random_sort, start_time, end_time) VALUES (".sanitize($_GET['campaign']).","'new','".$config_values['test_number']."',1,'00:00:00','25:59:59')";
$result = mysql_query($sql);
redirect("campaigns.php?type=".$_GET['type'],"Inserted test number into campaign");
require "footer.php";
?>