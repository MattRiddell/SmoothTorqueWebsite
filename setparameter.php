<?
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);

$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);

if (isset($_GET[value])){
    $sql="update config set value='$_GET[value]' where parameter='$_GET[parameter]'";
    $result=mysql_query($sql, $link) or die (mysql_error());;
    include("config.php");
    exit;
}
require "footer.php";
?>
