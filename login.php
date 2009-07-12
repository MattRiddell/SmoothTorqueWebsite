<?
/* Login.php                                                              */
/* =========                                                              */
/* This file processes the logins, checks that the website is up to date, */
/* and finally redirects to a location based on whether the login worked  */
/* or not.                                                                */

/* Include database configuration */
include "admin/db_config.php";

/* Include the common functions   */
$current_directory = dirname(__FILE__);
require "/".$current_directory."/functions/functions.php";

/* Check all connections are ok */
create_missing_tables($db_host,$db_user,$db_pass);

$passwordHash = sha1($_POST['pass']);


include "admin/db_config.php";
mysql_select_db("SineDialer") or die(mysql_error());


$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);

$sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_POST[user]', 'Attempted login')";
$result=mysql_query($sql, $link) or die(mysql_error());



/*

*/

$sql = 'SELECT password, security FROM customer WHERE username=\''.$_POST[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
if (mysql_num_rows($result) > 0) {
    $dbpass=mysql_result($result,0,'password');
} else {
    $dbpass = "";
}
if (trim($dbpass)==trim($passwordHash)){

    setcookie("loggedin",sha1("LoggedIn".$_POST[user]),time()+6000);
    setcookie("user",$_POST[user],time()+6000);
    setcookie("language",$_POST[language],time()+6000);

    /********************************************************/
    $url = $_SERVER[SERVER_NAME];
    $sql = "SELECT * FROM web_config WHERE LANG=".sanitize($_POST[language])." AND url = ".sanitize($url);
    $result_url = mysql_query($sql);
    if (mysql_num_rows($result_url) == 0) {
        $url = "default";
    }
    setcookie("url",$url,time()+6000);
    /********************************************************/

    if (mysql_result($result,0,'security')==100){
        $level=sha1("level100");
    } else if (mysql_result($result,0,'security')==0){
        $level=sha1("level0");
    } else if (mysql_result($result,0,'security')==5){
        $level=sha1("level5");
    } else {
        $level=sha1("level10");
    }
    setcookie("level",$level,time()+6000);
    $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_POST[user]', 'Successful login')";
    $result=mysql_query($sql, $link);
    if (strlen($_GET[redirect]) > 0) {
        header("Location: ".$_GET[redirect]);
    } else {
        header("Location: /main.php");
    }
    exit;
} else {
    setcookie("loggedin","--",time()+6000);
    setcookie("user",$_POST[user],time()+6000);
    $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_POST[user]', 'Unuccessful login')";
    $result=mysql_query($sql, $link);
    header("Location: index.php?error=Incorrect%20UserName%20or%20Password");
    exit;
?>
<?
}
require "footer.php";
?>
