<?
require "header.php";

$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');

$_GET = array_map(mysql_real_escape_string, $_GET);

if (isset($_GET[sure])){
    $id=$_GET[id];
    $sql="DELETE FROM campaign where id=$id";
    $result=mysql_query($sql, $link) or die (mysql_error());;
    /*================= Log Access ======================================*/
$sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_COOKIE[user]', 'Deleted a campaign')";
$result=mysql_query($sql, $link);
/*================= Log Access ======================================*/

    redirect("campaigns.php");
    exit;
}

require "header_campaign.php";

box_start();
$sql = 'SELECT * FROM campaign WHERE id='.($_GET[id]).' limit 1';
$result=mysql_query($sql, $link) or die (mysql_error());;
while ($row = mysql_fetch_assoc($result)) {
	$row = array_map(stripslashes,$row);
    echo "<CENTER><B>".$row[name]." - ".$row[description]."</B><BR><BR>";
    echo '<A HREF="deletecampaign.php?id='.($_GET[id]).'&sure=yes"><img src="images/tick.png" border="0">&nbsp;Yes, delete it</A><BR>';
    echo '<A HREF="campaigns.php"><img src="images/cross.png" border="0">&nbsp;No, don\'t delete it</A></CENTER>';
box_end();
}
require "footer.php";
?>
