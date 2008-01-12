<?
include "admin/db_config.php";
mysql_select_db("SineDialer", $link);

$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);

/* we're using '=' in the query for the phone number, so it can only ever delete 1 number, so may as well limit it to 1 */
if (isset($_GET[campaignid])){
    $sql="UPDATE number set status=\"new\" where campaignid=".($_GET[campaignid])." and phonenumber=".($_GET[number]);
    $result=mysql_query($sql, $link) or die (mysql_error());;
    $_POST[campaignid]=$_GET[campaignid];
    include("viewnumbers.php");
    exit;
}
