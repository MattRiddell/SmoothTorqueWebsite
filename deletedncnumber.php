<?
require "header.php";
$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);

/*
   seeing as we're using '=' in the comparison, we can only ever delete a single value for the database at a time,
   so might as well make it more difficult for people to delete everyting via an injection
*/
$sql="DELETE FROM dncnumber where phonenumber=".($_GET[number]);
$result=mysql_query($sql, $link) or die (mysql_error());;
/*================= Log Access ======================================*/
$sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_COOKIE[user]', 'Deleted a DNC number')";
$result=mysql_query($sql, $link);
/*================= Log Access ======================================*/

redirect("viewdncnumbers.php");
exit;
?>
