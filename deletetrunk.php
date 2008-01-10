<?
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);

if (isset($_GET[id])){
    $id=mysql_real_escape_string($_GET[id]);
    $sql="delete from trunk where id=$_GET[id] limit 1";
    $result=mysql_query($sql, $link) or die (mysql_error());;
    include("trunks.php");
    exit;
}
require "footer.php";
?>
