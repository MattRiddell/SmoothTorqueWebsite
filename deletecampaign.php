<?
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);

$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');

$_GET = array_map(mysql_real_escape_string, $_GET);

if (isset($_GET[sure])){
    $id=$_GET[id];
    $sql="DELETE FROM campaign where id=$id";
    $result=mysql_query($sql, $link) or die (mysql_error());;
    include("campaigns.php");
    exit;
}
require "header.php";
require "header_campaign.php";

?>
           
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2">
<TR><TD>
<CENTER>Are you Sure You want to delete this record?<BR><BR>
</TD></TR>
<TR><TD>
<?

$sql = 'SELECT * FROM campaign WHERE id='.($_GET[id]).' limit 1';
$result=mysql_query($sql, $link) or die (mysql_error());;
while ($row = mysql_fetch_assoc($result)) {
    echo "<CENTER><B>".$row[name]." - ".$row[description]."</B><BR><BR>";
    echo '<A HREF="deletecampaign.php?id='.($_GET[id]).'&sure=yes">Yes, Delete it</A><BR>';
    echo '<A HREF="campaigns.php">No, Don\'t Delete It</A></CENTER>';
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

