<?
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);

if (isset($_GET[id])){
    $id=$_GET[id];
    $sql="update trunk set current=0";
    $result=mysql_query($sql, $link) or die (mysql_error());;
    $sql="update trunk set current=1 where id=$_GET[id]";
    $result=mysql_query($sql, $link) or die (mysql_error());;
    include("trunks.php");
    exit;
}
require "footer.php";
?>
