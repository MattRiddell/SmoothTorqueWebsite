<?
if (isset($_GET[type])){
    include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
    mysql_select_db("SineDialer", $link);
    if ($_GET[type]=="unknown") {
        $sql = 'UPDATE number SET status="new" where status like "unknown%" and campaignid='.$_GET[id];
    } else {
        $sql = 'UPDATE number SET status="new" where status="'.$_GET[type].'" and campaignid='.$_GET[id];
    }
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
<a href="recycle.php?id=<?echo $_GET[id];?>&type=dialed">
Reset <?
$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status="dialed"';
$result=mysql_query($sql, $link) or die (mysql_error());;
echo mysql_result($result,0,0);
?> dialed numbers</a>
<br />
<a href="recycle.php?id=<?echo $_GET[id];?>&type=dialing">
Reset <?
$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status="dialing"';
$result=mysql_query($sql, $link) or die (mysql_error());;
echo mysql_result($result,0,0);
?> dialing numbers</a>
<br />
<a href="recycle.php?id=<?echo $_GET[id];?>&type=amd">
Reset <?
$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status="amd"';
$result=mysql_query($sql, $link) or die (mysql_error());;
echo mysql_result($result,0,0);
?> amd numbers</a>
<br />
<a href="recycle.php?id=<?echo $_GET[id];?>&type=timeout">
Reset <?
$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status="timeout"';
$result=mysql_query($sql, $link) or die (mysql_error());;
echo mysql_result($result,0,0);
?> No Answer numbers</a>
<br />
<a href="recycle.php?id=<?echo $_GET[id];?>&type=unknown">
Reset <?
$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status like "unknown%"';
$result=mysql_query($sql, $link) or die (mysql_error());;
echo mysql_result($result,0,0);
?> Unknown numbers</a>
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
