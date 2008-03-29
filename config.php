<?
//echo $_POST[sox];
if (isset($_POST[colour])){

    $add = @fopen("/stweb.conf",'w');
    fwrite($add,"COLOUR=$_POST[colour]\n");
    fwrite($add,"TITLE=$_POST[title]\n");
    fwrite($add,"LOGO=$_POST[logo]\n");
    fwrite($add,"TEXT=$_POST[text]\n");
    fwrite($add,"SOX=$_POST[sox]\n");
    fwrite($add,"USERID=$_POST[userid]\n");
    fwrite($add,"LICENCE=$_POST[licencekey]\n");
    fwrite($add,"CDR_HOST=$_POST[CDR_HOST]\n");
    fwrite($add,"CDR_USER=$_POST[CDR_USER]\n");
    fwrite($add,"CDR_PASS=$_POST[CDR_PASS]\n");
    fwrite($add,"CDR_DB=$_POST[CDR_DB]\n");
    fwrite($add,"CDR_TABLE=$_POST[CDR_TABLE]\n");
    fwrite($add,"MENU_HOME=$_POST[MENU_HOME]\n");
    fwrite($add,"MENU_CAMPAIGNS=$_POST[MENU_CAMPAIGNS]\n");
    fwrite($add,"MENU_NUMBERS=$_POST[MENU_NUMBERS]\n");
    fwrite($add,"MENU_DNC=$_POST[MENU_DNC]\n");
    fwrite($add,"MENU_MESSAGES=$_POST[MENU_MESSAGES]\n");
    fwrite($add,"MENU_SCHEDULES=$_POST[MENU_SCHEDULES]\n");
    fwrite($add,"MENU_CUSTOMERS=$_POST[MENU_CUSTOMERS]\n");
    fwrite($add,"MENU_QUEUES=$_POST[MENU_QUEUES]\n");
    fwrite($add,"MENU_SERVERS=$_POST[MENU_SERVERS]\n");
    fwrite($add,"MENU_TRUNKS=$_POST[MENU_TRUNKS]\n");
    fwrite($add,"MENU_ADMIN=$_POST[MENU_ADMIN]\n");
    fwrite($add,"MENU_LOGOUT=$_POST[MENU_LOGOUT]\n");
    fwrite($add,"DATE_COLOUR=$_POST[DATE_COLOUR]\n");
    fwrite($add,"MAIN_PAGE_TEXT=$_POST[MAIN_PAGE_TEXT]\n");
    fwrite($add,"CURRENCY_SYMBOL=$_POST[CURRENCY_SYMBOL]\n");
    fwrite($add,"PER_MINUTE=$_POST[PER_MINUTE]\n");
    fclose($add);

}
include "header.php";
/*================= Log Access ======================================*/
$sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_COOKIE[user]', 'Viewed the admin page')";
$result=mysql_query($sql, $link);
/*================= Log Access ======================================*/

if (isset($_POST[userid])){
/*================= Log Access ======================================*/
$sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_COOKIE[user]', 'Config Updated')";
$result=mysql_query($sql, $link);
/*================= Log Access ======================================*/

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
    <td CLASS="thead" colspan="2">Settings</td>
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
<tr><td colspan=2><br /><a href="log.php">View System Logs</a><br /><br /></td></tr>
<form action="config.php" name="config" method="post">
<tr  class="tborder2">
<td>
Sox Path:
</td>
<td>
<input type="Text" name="sox" value="<?echo $config_values['SOX'];?>">
</td>
</tr>

<tr  class="tborder2">
<td>
CDR Host:
</td>
<td>
<input type="Text" name="CDR_HOST" value="<?echo $config_values['CDR_HOST'];?>">
</td>
</tr>

<tr  class="tborder2">
<td>
CDR Username:
</td>
<td>
<input type="Text" name="CDR_USER" value="<?echo $config_values['CDR_USER'];?>">
</td>
</tr>

<tr  class="tborder2">
<td>
CDR Pass:
</td>
<td>
<input type="password" name="CDR_PASS" value="<?echo $config_values['CDR_PASS'];?>">
</td>
</tr>

<tr  class="tborder2">
<td>
CDR Database:
</td>
<td>
<input type="Text" name="CDR_DB" value="<?echo $config_values['CDR_DB'];?>">
</td>
</tr>

<tr  class="tborder2">
<td>
CDR Table:
</td>
<td>
<input type="Text" name="CDR_TABLE" value="<?echo $config_values['CDR_TABLE'];?>">
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
$handle = fopen("http://www.venturevoip.com/licencest.php?userid=$userid&licence=$licencekey", "rb");
$contents2 = '';
while (!feof($handle)) {
  $contents2 .= fread($handle, 8192);
}
$contents3 = explode("\n",$contents2);
//print_r($contents3);
fclose($handle);
$contents = $contents3[0];
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

<? /*******************************************************************/ ?>
<? /*                           Menu Text                             */ ?>
<? /*******************************************************************/ ?>

<tr  class="tborder2">
<td>
Home Menu Text:
</td>
<td>
<input type="Text" name="MENU_HOME" value="<?echo $config_values['MENU_HOME'];?>">
</td>
</tr>
<tr  class="tborder2">
<td>
Campaigns Menu Text:
</td>
<td>
<input type="Text" name="MENU_CAMPAIGNS" value="<?echo $config_values['MENU_CAMPAIGNS'];?>">
</td>
</tr>
<tr  class="tborder2">
<td>
Numbers Menu Text:
</td>
<td>
<input type="Text" name="MENU_NUMBERS" value="<?echo $config_values['MENU_NUMBERS'];?>">
</td>
</tr>
<tr  class="tborder2">
<td>
DNC Numbers Menu Text:
</td>
<td>
<input type="Text" name="MENU_DNC" value="<?echo $config_values['MENU_DNC'];?>">
</td>
</tr>
<tr  class="tborder2">
<td>
Messages Menu Text:
</td>
<td>
<input type="Text" name="MENU_MESSAGES" value="<?echo $config_values['MENU_MESSAGES'];?>">
</td>
</tr>
<tr  class="tborder2">
<td>
Schedules Menu Text:
</td>
<td>
<input type="Text" name="MENU_SCHEDULES" value="<?echo $config_values['MENU_SCHEDULES'];?>">
</td>
</tr>
<tr  class="tborder2">
<td>
Customers Menu Text:
</td>
<td>
<input type="Text" name="MENU_CUSTOMERS" value="<?echo $config_values['MENU_CUSTOMERS'];?>">
</td>
</tr>
<tr  class="tborder2">
<td>
Queues Menu Text:
</td>
<td>
<input type="Text" name="MENU_QUEUES" value="<?echo $config_values['MENU_QUEUES'];?>">
</td>
</tr>
<tr  class="tborder2">
<td>
Servers Menu Text:
</td>
<td>
<input type="Text" name="MENU_SERVERS" value="<?echo $config_values['MENU_SERVERS'];?>">
</td>
</tr>
<tr  class="tborder2">
<td>
Trunks Menu Text:
</td>
<td>
<input type="Text" name="MENU_TRUNKS" value="<?echo $config_values['MENU_TRUNKS'];?>">
</td>
</tr>
<tr  class="tborder2">
<td>
Admin Menu Text:
</td>
<td>
<input type="Text" name="MENU_ADMIN" value="<?echo $config_values['MENU_ADMIN'];?>">
</td>
</tr>
<tr  class="tborder2">
<td>
Logout Menu Text:
</td>
<td>
<input type="Text" name="MENU_LOGOUT" value="<?echo $config_values['MENU_LOGOUT'];?>">
</td>
</tr>

<tr  class="tborder2">
<td>
Main Page Text:
</td>
<td>
<input type="Text" name="MAIN_PAGE_TEXT" value="<?echo $config_values['MAIN_PAGE_TEXT'];?>">
</td>
</tr>

<tr  class="tborder2">
<td>
Currency Symbol (i.e. $):
</td>
<td>
<input type="Text" name="CURRENCY_SYMBOL" value="<?echo $config_values['CURRENCY_SYMBOL'];?>">
</td>
</tr>

<tr  class="tborder2">
<td>
Per Minute Wording in CDR
</td>
<td>
<input type="Text" name="PER_MINUTE" value="<?echo $config_values['PER_MINUTE'];?>">
</td>
</tr>

<tr  class="tborder2">
<td>
Date/Time Colour:
</td>
<td>
<script language=JavaScript src="/js/picker.js"></script>
<input type="Text" name="DATE_COLOUR" value="<?echo $config_values['DATE_COLOUR'];?>">
<a href="javascript:TCP.popup(document.forms['config'].elements['DATE_COLOUR'], 1)"><img width="15" height="13" border="0" alt="Click Here to Pick the color" src="img/sel.gif"></a>

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
