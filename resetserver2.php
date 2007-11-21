<?
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);

    $id=$_GET[id];
    $sql="update servers set status=0 where id=$id";
    $result=mysql_query($sql, $link) or die (mysql_error());;
    include("servers.php");
    exit;
?>
