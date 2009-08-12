<?
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);


$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);

    $id=$_GET[id];
    $sql="update servers set status=1 where id=$id";
    $result=mysql_query($sql, $link) or die (mysql_error());;

    $sql="insert into queue (status, campaignID, flags, startdate, enddate, starttime, endtime) VALUES (4, '$id', 1, '2008-01-01', '2020-01-01', '00:00:00', '23:59:59')";
    $result=mysql_query($sql, $link) or die (mysql_error());;

    include("servers.php");
    exit;
?>
