<?
require "header.php";
$sql = "REPLACE INTO SineDialer.number (campaignid, status, phonenumber, random_sort, start_time, end_time) VALUES (".sanitize($_GET['id']).",'new','".$config_values['test_number']."',1,'00:00:00','25:59:59')";
$result = mysql_query($sql) or die(mysql_error());
redirect("campaigns.php?type=".$_GET['type'],"Inserted test number into campaign");
require "footer.php";
?>