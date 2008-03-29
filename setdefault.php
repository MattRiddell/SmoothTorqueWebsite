<?
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);

$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);

if (isset($_GET[id])){
    $id=$_GET[id];
    $sql="update trunk set current=0";
    $result=mysql_query($sql, $link) or die (mysql_error());;
    $sql="update trunk set current=1 where id=$_GET[id]";
    $result=mysql_query($sql, $link) or die (mysql_error());;
/*================= Log Access ======================================*/
$sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_COOKIE[user]', 'Changed default trunk')";
$result=mysql_query($sql, $link);
/*================= Log Access ======================================*/

    include("trunks.php");
    exit;
}
require "footer.php";
?>
