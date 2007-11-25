<?include "header.php";
if (isset($_POST[userid])){
$sql = "UPDATE config SET value='$_POST[userid]' WHERE parameter='userid'";
$result=mysql_query($sql, $link) or die (mysql_error());

$sql = "UPDATE config SET value='$_POST[licencekey]' WHERE parameter='licencekey'";
$result=mysql_query($sql, $link) or die (mysql_error());

}
$sql = 'SELECT value FROM config WHERE parameter=\'backend\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$backend = mysql_result($result,0,'value');

$sql = 'SELECT value FROM config WHERE parameter=\'userid\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$userid = mysql_result($result,0,'value');

$sql = 'SELECT value FROM config WHERE parameter=\'licencekey\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$licencekey = mysql_result($result,0,'value');
?>
<br />
<br />
<table class="" align="center" border="0" cellpadding="2" cellspacing="0">
<tr>
    <td CLASS="thead"></td>
    <td CLASS="thead">Settings</td>
</tr>
<tr  class="tborderxx"><td>
<?if ($backend == 0) {?>
    <IMG SRC="/images/tick.png" BORDER="1" WIDTH="16" HEIGHT="16" class="abcd">
<?} else {?>
    <a href="setparameter.php?parameter=backend&value=0"><IMG SRC="/images/ch.gif" BORDER="1" WIDTH="16" HEIGHT="16"></a>
<?}?>
</td>
<td>Linux Backend</td>
</tr>
<tr  class="tborder2"><td><?if ($backend == 1) {?>
    <IMG SRC="/images/tick.png" BORDER="1" WIDTH="16" HEIGHT="16" class="abcd">
<?} else {?>
    <a href="setparameter.php?parameter=backend&value=1"><IMG SRC="/images/ch.gif" BORDER="1" WIDTH="16" HEIGHT="16"></a>
<?}?>
</td>
<td>Windows Backend</td></tr></table>

<?if ($backend == 0) {?>

<br />
<form action="config.php" method="post">
<table class="" align="center" border="0" cellpadding="2" cellspacing="0">
<tr>
    <td CLASS="thead" colspan="2">Licence Details</td>
</tr>
<tr  class="tborderxx">
<td>
User ID:
</td>
<td>
<input type="text" name="userid" value="<?echo $userid;?>">
</td>
</tr>
<tr  class="tborder2">
<td>
Licence Key:
</td>
<td>
<input type="text" name="licencekey" value="<?echo $licencekey;?>">
</td>
</tr>
<tr  class="tborderxx">
<td>
Licence Details:
</td>
<td>
<?
$handle = fopen("http://www.venturevoip.com/licence.php?userid=$userid&licence=$licencekey", "rb");
$contents = '';
while (!feof($handle)) {
  $contents .= fread($handle, 8192);
}
fclose($handle);
if ($contents<1000){
    if ($contents==0){
        echo "Unlicensed demo";
    } else if ($contents==1) {
        echo "Calls Per Second";
    } else if ($contents==2) {
        echo "Calls Per Second";
    } else if ($contents==3) {
        echo "1 Server (Unlimited Channels)";
    } else {
        $servers = $contents-2;
        echo $servers." Servers (Unlimited Channels)";
    }
} else {
    // Licenced on a per channel basis
    $test=$contents/10000;
//    echo round($test)." - ";
//    echo $contents." - ";
//    echo (10000*round($test))." - ";
	$maxchans=$contents-(10000*round($test));
	echo round($test-2)." Servers (Max. ".$maxchans." Channels)";
}
?>
</td>
</tr>
<tr  class="tborder2">
<td colspan="2">
<input type="submit" value="Save Licence Details">
</td>
</tr>
</table>
</form>
<?}?>
