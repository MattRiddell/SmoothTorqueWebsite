<?
require "header.php";

$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);

if (isset($_GET['id'])){

$count = 0;
$sql="SELECT count(*) from customer where trunkid=".($_GET[id]);
$result=mysql_query($sql, $link) or die (mysql_error());
$count+=mysql_result($result,0,0);

if ($count > 0) {

    echo "<br /><br />Sorry this trunk is currently being used by a campaign";
?>    <meta http-equiv="refresh" content="3;url=trunks.php"><?
} else {



    $id=($_GET[id]);
    $sql="delete from trunk where id=$_GET[id] limit 1";
    $result=mysql_query($sql, $link) or die (mysql_error());;
/*================= Log Access ======================================*/
$sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_COOKIE[user]', 'Deleted a trunk')";
$result=mysql_query($sql, $link);
/*================= Log Access ======================================*/

    redirect("trunks.php");
    exit;
}
}
require "footer.php";
?>
