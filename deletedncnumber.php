<?
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);

$sql="DELETE FROM dncnumber where phonenumber=$_GET[number]";
$result=mysql_query($sql, $link) or die (mysql_error());;
include("viewdncnumbers.php");
exit;
?>
