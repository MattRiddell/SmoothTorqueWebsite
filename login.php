<?
//require "header.php";
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);
$passwordHash = sha1($_POST['pass']);


$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);

$sql = "INSERT INTO log (username, activity) VALUES ('$_POST[user]', 'Attempted login')";
$result=mysql_query($sql, $link);
if (mysql_error() == "Table 'SineDialer.log' doesn't exist") {
$sql = "
CREATE TABLE `log` (
  `timestamp` timestamp NULL default NULL on update CURRENT_TIMESTAMP,
  `activity` varchar(255) default NULL,
  `username` varchar(255) default NULL
)";
$result=mysql_query($sql, $link);
$sql = "INSERT INTO log (username, activity) VALUES ('$_POST[user]', 'Attempted login')";
$result=mysql_query($sql, $link);

}
/*

*/

$sql = 'SELECT password, security FROM customer WHERE username=\''.$_POST[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$dbpass=mysql_result($result,0,'password');
if (trim($dbpass)==trim($passwordHash)){
    setcookie("loggedin",sha1("LoggedIn".$_POST[user]),time()+6000);
    setcookie("user",$_POST[user],time()+6000);
    if (mysql_result($result,0,'security')==100){
        $level=sha1("level100");
    } else {
        $level=sha1("level0");
    }
    setcookie("level",$level,time()+6000);
    if (strlen($_GET[redirect]) > 0) {
        header("Location: ".$_GET[redirect]);
    } else {
        header("Location: /main.php");
    }
    exit;
} else {
    setcookie("loggedin","--",time()+6000);
    setcookie("user",$_POST[user],time()+6000);
    header("Location: index.php?error=Incorrect%20UserName%20or%20Password");
    exit;
?>
<?
}
require "footer.php";
?>
