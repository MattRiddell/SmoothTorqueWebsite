<?
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);

if (isset($_GET[campaignid])){
    $sql="DELETE FROM number where campaignid=$_GET[campaignid] and phonenumber=$_GET[number]";
    $result=mysql_query($sql, $link) or die (mysql_error());;
    include("numbers.php");
    exit;
}
