<?if (isset($_POST[id])) {
    $_GET[id]=$_POST[id];
}
    if (isset($_GET[id])){
        //require_once "sql.php";
        //$SMDB2=new SmDB();
        include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);

$sql="DELETE FROM campaignmessage WHERE id=".$_GET[id];
        $result=mysql_query($sql, $link) or die (mysql_error());;

        //$SMDB2->executeUpdate($sql);
    }

?>
<meta http-equiv="refresh" content="0;url=/messages.php">
