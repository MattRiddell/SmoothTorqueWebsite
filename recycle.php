<?
if (isset($_GET[type])){
    /* We now know what to reset so let's do it */
    include "admin/db_config.php";
    mysql_select_db("SineDialer", $link);

    if ($_GET[type]=="unknown") {
        $sql = 'UPDATE number SET status="new" where status like "unknown%" and campaignid='.$_GET[id];
    } else if ($_GET[type]=="all") {
        $sql = 'UPDATE number SET status="new" where campaignid='.$_GET[id];
    } else {
        $sql = 'UPDATE number SET status="new" where status="'.$_GET[type].'" and campaignid='.$_GET[id];
    }
    $result=mysql_query($sql, $link) or die (mysql_error());;
    /* Return to the campaign page */
    header("Location: campaigns.php?type=".$_GET[type_input]);
    exit(0);
} else {
    /* We don't know what to reset, so let's draw the choices */
    require "header.php";
    function print_count($id, $status, $text) {
        global $link;
        $sql = 'SELECT count(*) from number where campaignid='.$id.' and status="'.$status.'"';
        $result=mysql_query($sql, $link) or die (mysql_error());;
        if (mysql_result($result,0,0) > 0) {
            ?><a href="recycle.php?id=<?=$id;?>&type=<?=$status?>&type_input=<?echo $_GET[type_input];?>"><?
            echo "Reset ".mysql_result($result,0,0)." $text</a><br />";;
        }
    }
?>

<br /><br /><br /><br />
<center>
<?box_start();?>
<center><b>Recycle Numbers:</b>
<br />
<br />
<?
include "admin/db_config.php";
mysql_select_db("SineDialer", $link);
?>
<?
print_count($_GET[id], "failed", "failed numbers");
print_count($_GET[id], "busy", "busy numbers");
print_count($_GET[id], "congested", "congested numbers");
print_count($_GET[id], "dialed", "dialed numbers");
print_count($_GET[id], "dialing", "dialing numbers");
print_count($_GET[id], "amd", "answer machine numbers");
print_count($_GET[id], "timeout", "no answer numbers");
?>

<a href="recycle.php?id=<?echo $_GET[id];?>&type=unknown&type_input=<?echo $_GET[type_input];?>">
Reset <?
$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status like "unknown%"';
$result=mysql_query($sql, $link) or die (mysql_error());;
echo mysql_result($result,0,0);
?> Unknown numbers</a>
<br />
<?
print_count($_GET[id], "calldropped", "calldropped numbers");
print_count($_GET[id], "hungup", "hungup numbers");
?>

<a href="recycle.php?id=<?echo $_GET[id];?>&type=all&type_input=<?echo $_GET[type_input];?>">
Reset <?
$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status!="new" ';
$result=mysql_query($sql, $link) or die (mysql_error());;
echo mysql_result($result,0,0);
?> All numbers</a>
<?/*
<br />
<br />
</td>
<td>
</td></tr>
</table>
*/
box_end();?>
</center>
<?
}
require "footer.php";
?>
