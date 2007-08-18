<?
$link = mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);

$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');
if (isset($_GET[sure])){
    $id=$_GET[id];
    $sql="DELETE FROM queue where queueID=$id";
    $result=mysql_query($sql, $link) or die (mysql_error());;
    include("schedule.php");
    exit;
}
require "header.php";
require "header_schedule.php";

?>
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2">
<TR><TD>
Are you Sure You want to delete this record?<BR><BR>
</TD></TR>
<TR><TD>
<?

$sql = 'SELECT * FROM queue WHERE queueID='.$_GET[id];
$result=mysql_query($sql, $link) or die (mysql_error());;
while ($row = mysql_fetch_assoc($result)) {
    echo "<CENTER><B>".$row[queuename]." - ".$row[details]."</B><BR><BR>";
    echo '<A HREF="deleteschedule.php?id='.$_GET[id].'&sure=yes">Yes, Delete it</A><BR>';
    echo '<A HREF="schedule.php">No, Don\'t Delete It</A></CENTER>';
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

