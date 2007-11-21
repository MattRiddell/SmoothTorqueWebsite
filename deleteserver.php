<?
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);

if (isset($_GET[sure])){
    $id=$_GET[id];
    $sql="DELETE FROM servers where id=$id";
    $result=mysql_query($sql, $link) or die (mysql_error());;
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
