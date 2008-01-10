<?
//require "header.php";
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);
$passwordHash = sha1($_POST['pass']);


$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);

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
    header("Location: /main.php");
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


