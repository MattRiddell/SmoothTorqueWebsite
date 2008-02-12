<?
if (isset($_GET[type])){
    include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
    mysql_select_db("SineDialer", $link);
    $sql = 'UPDATE number SET status="new" where status="'.$_GET[type].'" and campaignid='.$_GET[id];
    $result=mysql_query($sql, $link) or die (mysql_error());;
//    echo "Resetting status of $_GET[type] numbers in $_GET[id]";
    include "campaigns.php";
} else {
require "header.php";
?>

<br /><br /><br /><br />
<center>
<table background="/images/sdbox.png" width="300" height="200" class="dragme22">
<tr>
<td>
</td>
<td width="260">
<b>Recycle Numbers:<br />
<?
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);
$sql = 'SELECT * from campaign where id='.$_GET[id];
$result=mysql_query($sql, $link) or die (mysql_error());;
//$backend = mysql_result($result,0,'value');
$row = mysql_fetch_assoc($result);
echo $row[name];
?>
</b><br />
<a href="recycle.php?id=<?echo $_GET[id];?>&type=failed">
Reset <?
$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status="failed"';
$result=mysql_query($sql, $link) or die (mysql_error());;
echo mysql_result($result,0,0);
?> failed numbers</a>
<br />
<a href="recycle.php?id=<?echo $_GET[id];?>&type=busy">
Reset <?
$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status="busy"';
$result=mysql_query($sql, $link) or die (mysql_error());;
echo mysql_result($result,0,0);
?> busy numbers</a>
<br />
<a href="recycle.php?id=<?echo $_GET[id];?>&type=congested">
Reset <?
$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status="congested"';
$result=mysql_query($sql, $link) or die (mysql_error());;
echo mysql_result($result,0,0);
?> congested numbers</a>
<br />
<br />
</td>
<td>
</td></tr>
</table>
</center>
<?
}
require "footer.php";
?>
