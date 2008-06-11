<?
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);

$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);

if (isset($_GET[id])){

$count = 0;
$sql="SELECT count(*) from campaign where trunkid=".($_GET[id]);
$result=mysql_query($sql, $link) or die (mysql_error());
$count+=mysql_result($result,0,0);

if ($count > 0) {
    require "header.php";
    echo "<br /><br />Sorry this trunk is currently being used by a campaign";
?>    <meta http-equiv="refresh" content="3;url=/trunks.php"><?
} else {



    $id=($_GET[id]);
    $sql="delete from trunk where id=$_GET[id] limit 1";
    $result=mysql_query($sql, $link) or die (mysql_error());;
/*================= Log Access ======================================*/
$sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_COOKIE[user]', 'Deleted a trunk')";
$result=mysql_query($sql, $link);
/*================= Log Access ======================================*/

    include("trunks.php");
    exit;
}
}
require "footer.php";
?>
