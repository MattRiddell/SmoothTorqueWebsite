<?
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);

$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);

$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');

$sql = "INSERT INTO billing (customerid, accountcode) VALUES ($_GET[id], '$_GET[accountcode]')";
$result=mysql_query($sql, $link) or die (mysql_error());;
header("Location: billing.php?id=".$_GET[id]);
?>
