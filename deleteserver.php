<?
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);

$_GET = array_map(mysql_real_escape_string,$_GET);
$_POST = array_map(mysql_real_escape_string,$_POST);

if (isset($_GET[sure])){
    $id=($_GET[id]);
    $sql="DELETE FROM servers where id=$id limit 1";
    $result=mysql_query($sql, $link) or die (mysql_error());;
/*================= Log Access ======================================*/
$sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_COOKIE[user]', 'Deleted a server')";
$result=mysql_query($sql, $link);
/*================= Log Access ======================================*/

    include("servers.php");
    exit;
}
require "header.php";
require "header_server.php";

?>
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2">
<TR><TD>
Are you Sure You want to delete this record?<BR><BR>
</TD></TR>
<TR><TD>
<?

$sql = 'SELECT * FROM servers WHERE id='.$_GET[id];
$result=mysql_query($sql, $link) or die (mysql_error());;
while ($row = mysql_fetch_assoc($result)) {
    echo "<CENTER><B>".$row[name]."</B><BR><BR>";
    echo '<A HREF="deleteserver.php?id='.$_GET[id].'&sure=yes">Yes, Delete it</A><BR>';
    echo '<A HREF="servers.php">No, Don\'t Delete It</A></CENTER>';
?>
</TD></TR>
<TR><TD>

</TD></TR>
</TABLE>
</FORM>
<?
}
require "footer.php";
?>
