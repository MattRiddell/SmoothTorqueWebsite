<?
    $user = $_COOKIE[user];
    setcookie("loggedin","--",time()+6000);
    setcookie("user",$_POST[user],time()+6000);

    /*================= Log Access ======================================*/
    include "admin/db_config.php";
    mysql_select_db("SineDialer", $link);
    $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$user', 'Logout')";
    $result=mysql_query($sql, $link);
    /*================= Log Access ======================================*/

    header("Location: /index.php");
    exit;
?>

