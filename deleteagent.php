<?
include "admin/db_config.php";
mysql_select_db("SineDialer", $link);

$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);

/* we're using '=' in the query for the phone number, so it can only ever delete 1 number, so may as well limit it to 1 */
if (isset($_GET[name])){
    $sql="DELETE FROM queue_member_table where membername='".($_GET[name])."'";
    $result=mysql_query($sql, $link) or die (mysql_error());;
    $sql="DELETE FROM sip_buddies where name='".($_GET[name])."'";
    $result=mysql_query($sql, $link) or die (mysql_error());;
    header("location: agents.php?name=".$_GET[queue_name]);
    exit;
}
