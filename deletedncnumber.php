<?
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);

$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);

/* 
   seeing as we're using '=' in the comparison, we can only ever delete a single value for the database at a time,
   so might as well make it more difficult for people to delete everyting via an injection
*/
$sql="DELETE FROM dncnumber where phonenumber=".($_GET[number])."limit 1";
$result=mysql_query($sql, $link) or die (mysql_error());;
include("viewdncnumbers.php");
exit;
?>
