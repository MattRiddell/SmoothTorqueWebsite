<?
include "admin/db_config.php";
mysql_select_db("SineDialer", $link);

/* we're using '=' in the query for the phone number, so it can only ever delete 1 number, so may as well limit it to 1 */
if (isset($_GET[campaignid])){
    $sql="DELETE FROM number where campaignid=".mysql_real_escape_string($_GET[campaignid])." and phonenumber=".mysql_real_escape_string($_GET[number])." limit 1";
    $result=mysql_query($sql, $link) or die (mysql_error());;
    include("numbers.php");
    exit;
}
