<?
include "admin/db_config.php";
mysql_select_db("SineDialer", $link);

$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result = mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid = mysql_result($result, 0, 'campaigngroupid');

$sql = "INSERT INTO billing (customerid, accountcode) VALUES (".sanitize($_GET['id']).", ".sanitize($_GET['accountcode']).")";
$result = mysql_query($sql, $link) or die (mysql_error());;
/*================= Log Access ======================================*/
$sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '".sanitize($_COOKIE['user'])."', 'Added a billing record')";
$result = mysql_query($sql, $link);
/*================= Log Access ======================================*/

header("Location: billing.php?id=".$_GET['id']);
?>
