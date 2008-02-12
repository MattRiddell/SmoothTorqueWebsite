<?
//echo $_POST[sox];
if (isset($_POST[colour])){
    $add = @fopen("/stweb.conf",'w');
    fwrite($add,"COLOUR=$_POST[colour]\n");
    fwrite($add,"TITLE=$_POST[title]\n");
    fwrite($add,"LOGO=$_POST[logo]\n");
    fwrite($add,"TEXT=$_POST[text]\n");
    fwrite($add,"SOX=$_POST[sox]\n");
    fclose($add);

}
include "header.php";
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
<td>Linux Backend (<b>Version <?echo $version;?></b>)</td>
</tr>
<tr  class="tborder2"><td><?if ($backend == 1) {?>
    <IMG SRC="/images/tick.png" BORDER="1" WIDTH="16" HEIGHT="16" class="abcd">
<?} else {?>
    <a href="setparameter.php?parameter=backend&value=1"><IMG SRC="/images/ch.gif" BORDER="1" WIDTH="16" HEIGHT="16"></a>
<?}?>
</td>
<td>Windows Backend</td></tr>

<tr  class="tborder2">
<td> <form action="config.php" name="config" method="post">
Sox Path:
</td>
<td>
<input type="Text" name="sox" value="<?echo $config_values['SOX'];?>">
</td>
</tr>

</table>

<?if ($backend == 0) {?>

<br /> <br />

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

<tr><td colspan="2">
<br /><br />
</td></tr>

<tr>
<td CLASS="thead" colspan="2">Look and Feel</td>
</tr>


<tr  class="tborder2">
<td>
Background Colour:
</td>
<td>
<script language=JavaScript src="/js/picker.js"></script>
<input type="Text" name="colour" value="<?echo $config_values['COLOUR'];?>">
<a href="javascript:TCP.popup(document.forms['config'].elements['colour'], 1)"><img width="15" height="13" border="0" alt="Click Here to Pick up the color" src="img/sel.gif"></a>

</td>
</tr>
<tr  class="tborder2">
<td>
Site Name:
</td>
<td>
<input type="Text" name="title" value="<?echo $config_values['TITLE'];?>">
</td>
</tr>

</td>
</tr>
<tr  class="tborder2">
<td>
Logo Filename:
</td>
<td>
<input type="Text" name="logo" value="<?echo $config_values['LOGO'];?>">
</td>
</tr>

<tr  class="tborder2">
<td>
Opening Text:
</td>
<td>
<input type="Text" name="text" value="<?echo $config_values['TEXT'];?>">
</td>
</tr>

<tr><td colspan="2">
<br /><br />
</td></tr>

<tr  class="tborder2">
<td colspan="2">
<input type="submit" value="Save Config Information">
</td>
</tr>
</table>
</form>
<?}?>
