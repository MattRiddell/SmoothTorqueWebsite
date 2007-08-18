<?
    setcookie("loggedin","--",time()+6000);
    setcookie("user",$_POST[user],time()+6000);
    header("Location: index.php");
    exit;
?>

