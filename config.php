<?
$level=$_COOKIE[level];

if ($level!=sha1("level100")) {
include "header.php";
$ip = $_SERVER['REMOTE_ADDR'];
echo "Attempted break in attempt from $ip ($_COOKIE[user])";
/*================= Log Access ======================================*/
$sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_COOKIE[user]', ' $ip attempted to view the admin page')";
$result=mysql_query($sql, $link);
/*================= Log Access ======================================*/

} else {
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
    fwrite($add,"MAIN_PAGE_USERNAME=$_POST[MAIN_PAGE_USERNAME]\n");
    fwrite($add,"MAIN_PAGE_PASSWORD=$_POST[MAIN_PAGE_PASSWORD]\n");
    fwrite($add,"MAIN_PAGE_LOGIN=$_POST[MAIN_PAGE_LOGIN]\n");
    fwrite($add,"CURRENCY_SYMBOL=$_POST[CURRENCY_SYMBOL]\n");
    fwrite($add,"PER_MINUTE=$_POST[PER_MINUTE]\n");
    fwrite($add,"USE_BILLING=$_POST[USE_BILLING]\n");
    fwrite($add,"SPARE1=$_POST[SPARE1]\n");
    fwrite($add,"SPARE2=$_POST[SPARE2]\n");
    fwrite($add,"SPARE3=$_POST[SPARE3]\n");
    fwrite($add,"SPARE4=$_POST[SPARE4]\n");
    fwrite($add,"SPARE5=$_POST[SPARE5]\n");
    fwrite($add,"ST_MYSQL_HOST=$_POST[ST_MYSQL_HOST]\n");
    fwrite($add,"ST_MYSQL_USER=$_POST[ST_MYSQL_USER]\n");
    fwrite($add,"ST_MYSQL_PASS=$_POST[ST_MYSQL_PASS]\n");
    fwrite($add,"ADD_CAMPAIGN=$_POST[ADD_CAMPAIGN]\n");
    fwrite($add,"VIEW_CAMPAIGN=$_POST[VIEW_CAMPAIGN]\n");
    fwrite($add,"PER_PAGE=$_POST[PER_PAGE]\n");
    fwrite($add,"NUMBERS_VIEW=$_POST[NUMBERS_VIEW]\n");
    fwrite($add,"NUMBERS_SYSTEM=$_POST[NUMBERS_SYSTEM]\n");
    fwrite($add,"NUMBERS_GENERATE=$_POST[NUMBERS_GENERATE]\n");
    fwrite($add,"NUMBERS_MANUAL=$_POST[NUMBERS_MANUAL]\n");
    fwrite($add,"NUMBERS_UPLOAD=$_POST[NUMBERS_UPLOAD]\n");
    fwrite($add,"NUMBERS_EXPORT=$_POST[NUMBERS_EXPORT]\n");
    fwrite($add,"NUMBERS_SEARCH=$_POST[NUMBERS_SEARCH]\n");
    fwrite($add,"NUMBERS_TITLE=$_POST[NUMBERS_TITLE]\n");

    fwrite($add,"BILLING_TEXT=$_POST[BILLING_TEXT]\n");
    fwrite($add,"CDR_TEXT=$_POST[CDR_TEXT]\n");

    fwrite($add,"USE_GENERATE=$_POST[USE_GENERATE]\n");
    fwrite($add,"DNC_NUMBERS_TITLE=$_POST[DNC_NUMBERS_TITLE]\n");
    fwrite($add,"DNC_VIEW=$_POST[DNC_VIEW]\n");
    fwrite($add,"DNC_UPLOAD=$_POST[DNC_UPLOAD]\n");
    fwrite($add,"DNC_ADD=$_POST[DNC_ADD]\n");
    fwrite($add,"PER_LEAD=$_POST[PER_LEAD]\n");
    fwrite($add,"SMTP_HOST=$_POST[SMTP_HOST]\n");
    fwrite($add,"SMTP_USER=$_POST[SMTP_USER]\n");
    fwrite($add,"SMTP_PASS=$_POST[SMTP_PASS]\n");
    fwrite($add,"SMTP_FROM=$_POST[SMTP_FROM]\n");

    fclose($add);

    /*$add = @fopen("./admin/db_config.php",'w');
    $script = '\<\?
$db_host="localhost";
$db_user="root";
$db_pass="";
$link = mysql_connect($db_host, $db_user, $db_pass) OR die(mysql_error());
\?\>
    ';
    fwrite($add,$script);
    fclose($add);*/


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
<tr><td colspan=2><br /><a href="log.php">View System Logs</a></td></tr>
<tr><td colspan=2><a href="billinglog.php">View <?echo $config_values['BILLING_TEXT'];?></a></td></tr>
<tr><td colspan=2><a href="view_system_bill.php">View Billing Graphs</a><br /><br /></td></tr>

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
<form action="config.php" name="config" method="post">
<tr  class="tborder2">
<td>
Sox Path:
</td>
<td>
<input type="Text" name="sox" value="<?echo $config_values['SOX'];?>">
</td>
</tr>

<tr><td colspan="2"><br /><br /></td></tr><tr><td CLASS="thead" colspan="2">Email Settings</td>

<tr  class="tborder2">
<td>
SMTP Host Name
</td>
<td>
<input type="Text" name="SMTP_HOST" value="<?echo $config_values['SMTP_HOST'];?>">
</td>
</tr>

<tr  class="tborder2">
<td>
SMTP User Name
</td>
<td>
<input type="Text" name="SMTP_USER" value="<?echo $config_values['SMTP_USER'];?>">
</td>
</tr>

<tr  class="tborder2">
<td>
SMTP Password
</td>
<td>
<input type="Text" name="SMTP_PASS" value="<?echo $config_values['SMTP_PASS'];?>">
</td>
</tr>

<tr  class="tborder2">
<td>
Email from address
</td>
<td>
<input type="Text" name="SMTP_FROM" value="<?echo $config_values['SMTP_FROM'];?>">
</td>
</tr>




<tr><td colspan="2"><br /><br /></td></tr><tr><td CLASS="thead" colspan="2">MySQL Settings</td>

<tr  class="tborder2">
<td>
SmoothTorque MySQL Host Name
</td>
<td>
<input type="Text" name="ST_MYSQL_HOST" value="<?echo $db_host;?>">
</td>
</tr>

<tr  class="tborder2">
<td>
SmoothTorque MySQL User Name
</td>
<td>
<input type="Text" name="ST_MYSQL_USER" value="<?echo $db_user;?>">
</td>
</tr>

<tr  class="tborder2">
<td>
SmoothTorque MySQL Password
</td>
<td>
<input type="Text" name="ST_MYSQL_PASS" value="<?echo $db_pass;?>">
</td>
</tr>


<tr  class="tborder2">
<td>
Asterisk MySQL CDR Host:
</td>
<td>
<input type="Text" name="CDR_HOST" value="<?echo $config_values['CDR_HOST'];?>">
</td>
</tr>

<tr  class="tborder2">
<td>
Asterisk MySQL CDR Username:
</td>
<td>
<input type="Text" name="CDR_USER" value="<?echo $config_values['CDR_USER'];?>">
</td>
</tr>

<tr  class="tborder2">
<td>
Asterisk MySQL CDR Pass:
</td>
<td>
<input type="password" name="CDR_PASS" value="<?echo $config_values['CDR_PASS'];?>">
</td>
</tr>

<tr  class="tborder2">
<td>
Asterisk MySQL CDR Database:
</td>
<td>
<input type="Text" name="CDR_DB" value="<?echo $config_values['CDR_DB'];?>">
</td>
</tr>

<tr  class="tborder2">
<td>
Asterisk MySQL CDR Table:
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
Date/Time Colour:
</td>
<td>
<script language=JavaScript src="/js/picker.js"></script>
<input type="Text" name="DATE_COLOUR" value="<?echo $config_values['DATE_COLOUR'];?>">
<a href="javascript:TCP.popup(document.forms['config'].elements['DATE_COLOUR'], 1)"><img width="15" height="13" border="0" alt="Click Here to Pick the color" src="img/sel.gif"></a>

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
<tr><td colspan="2"><br /><br /></td></tr><tr><td CLASS="thead" colspan="2">Menu Text</td>

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
<? /*******************************************************************/ ?>
<? /*                        DNC Numbers Section                      */ ?>
<? /*******************************************************************/ ?>

<tr><td colspan="2"><br /><br /></td></tr><tr><td CLASS="thead" colspan="2">DNC Numbers Section</td>

<tr  class="tborder2">
<td>
Number List Management Text (Title):
</td>
<td>
<input type="Text" name="DNC_NUMBERS_TITLE" value="<?echo $config_values['DNC_NUMBERS_TITLE'];?>">
</td>
</tr>

<tr  class="tborder2">
<td>
View existing DNC numbers Text (Title):
</td>
<td>
<input type="Text" name="DNC_VIEW" value="<?echo $config_values['DNC_VIEW'];?>">
</td>
</tr>

<tr  class="tborder2">
<td>
Upload DNC numbers Text (Title):
</td>
<td>
<input type="Text" name="DNC_UPLOAD" value="<?echo $config_values['DNC_UPLOAD'];?>">
</td>
</tr>

<tr  class="tborder2">
<td>
Add DNC numbers Text (Title):
</td>
<td>
<input type="Text" name="DNC_ADD" value="<?echo $config_values['DNC_ADD'];?>">
</td>
</tr>



<? /*******************************************************************/ ?>
<? /*                        Numbers Section                           */ ?>
<? /*******************************************************************/ ?>

<tr><td colspan="2"><br /><br /></td></tr><tr><td CLASS="thead" colspan="2">Numbers Section</td>

<tr  class="tborder2">
<td>
Number of entries to show per page:
</td>
<td>
<input type="Text" name="PER_PAGE" value="<?echo $config_values['PER_PAGE'];?>">
</td>
</tr>

<tr  class="tborder2">
<td>
Number List Management Text (Title):
</td>
<td>
<input type="Text" name="NUMBERS_TITLE" value="<?echo $config_values['NUMBERS_TITLE'];?>">
</td>
</tr>

<tr  class="tborder2">
<td>
View phone numbers text:
</td>
<td>
<input type="Text" name="NUMBERS_VIEW" value="<?echo $config_values['NUMBERS_VIEW'];?>">
</td>
</tr>

<tr  class="tborder2">
<td>
Use System Lists Text:
</td>
<td>
<input type="Text" name="NUMBERS_SYSTEM" value="<?echo $config_values['NUMBERS_SYSTEM'];?>">
</td>
</tr>

<tr  class="tborder2">
<td>
Search for a phone number Text:
</td>
<td>
<input type="Text" name="NUMBERS_SEARCH" value="<?echo $config_values['NUMBERS_SEARCH'];?>">
</td>
</tr>

<tr  class="tborder2">
<td>
Export Phone Numbers Text:
</td>
<td>
<input type="Text" name="NUMBERS_EXPORT" value="<?echo $config_values['NUMBERS_EXPORT'];?>">
</td>
</tr>

<tr  class="tborder2">
<td>
Upload numbers from a text file Text:
</td>
<td>
<input type="Text" name="NUMBERS_UPLOAD" value="<?echo $config_values['NUMBERS_UPLOAD'];?>">
</td>
</tr>

<tr  class="tborder2">
<td>
Add number(s) manually Text:
</td>
<td>
<input type="Text" name="NUMBERS_MANUAL" value="<?echo $config_values['NUMBERS_MANUAL'];?>">
</td>
</tr>

<tr  class="tborder2">
<td>
Generate numbers automatically Text:
</td>
<td>
<input type="Text" name="NUMBERS_GENERATE" value="<?echo $config_values['NUMBERS_GENERATE'];?>">
</td>
</tr>

<tr  class="tborder2">
<td>
Use the Generate numbers automatically option
</td>
<td>
<input type="radio" name="USE_GENERATE" value="YES" <?if ( $config_values['USE_GENERATE'] == "YES") {echo "checked";}?>> Yes
<input type="radio" name="USE_GENERATE" value="NO" <?if ( $config_values['USE_GENERATE'] != "YES") {echo "checked";}?>> No
</td>
</tr>



<tr><td colspan="2"><br /><br /></td></tr><tr><td CLASS="thead" colspan="2">Other Text</td>



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
Add Campaign Text:
</td>
<td>
<input type="Text" name="ADD_CAMPAIGN" value="<?echo $config_values['ADD_CAMPAIGN'];?>">
</td>
</tr>

<tr  class="tborder2">
<td>
View Campaigns Text:
</td>
<td>
<input type="Text" name="VIEW_CAMPAIGN" value="<?echo $config_values['VIEW_CAMPAIGN'];?>">
</td>
</tr>

<tr  class="tborder2">
<td>
Main Page Username Text:
</td>
<td>
<input type="Text" name="MAIN_PAGE_USERNAME" value="<?echo $config_values['MAIN_PAGE_USERNAME'];?>">
</td>
</tr>

<tr  class="tborder2">
<td>
Main Page Password Text:
</td>
<td>
<input type="Text" name="MAIN_PAGE_PASSWORD" value="<?echo $config_values['MAIN_PAGE_PASSWORD'];?>">
</td>
</tr>

<tr  class="tborder2">
<td>
Main Page Login Text:
</td>
<td>
<input type="Text" name="MAIN_PAGE_LOGIN" value="<?echo $config_values['MAIN_PAGE_LOGIN'];?>">
</td>
</tr>

<tr><td colspan="2"><br /><br /></td></tr><tr><td CLASS="thead" colspan="2">Billing Information</td>

<tr  class="tborder2">
<td>
Call Details Text (in header):
</td>
<td>
<input type="Text" name="CDR_TEXT" value="<?echo $config_values['CDR_TEXT'];?>">
</td>
</tr>

<tr  class="tborder2">
<td>
Billing Text (in header):
</td>
<td>
<input type="Text" name="BILLING_TEXT" value="<?echo $config_values['BILLING_TEXT'];?>">
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
Price per lead wording
</td>
<td>
<input type="Text" name="PER_LEAD" value="<?echo $config_values['PER_LEAD'];?>">
</td>
</tr>


<tr  class="tborder2">
<td>
Use the SmoothTorque Billing System
</td>
<td>
<input type="radio" name="USE_BILLING" value="YES" <?if ( $config_values['USE_BILLING'] == "YES") {echo "checked";}?>> Yes
<input type="radio" name="USE_BILLING" value="NO" <?if ( $config_values['USE_BILLING'] != "YES") {echo "checked";}?>> No
</td>
</tr>

<tr><td colspan="2"><br /><br /></td></tr><tr><td CLASS="thead" colspan="2">Custom contexts</td>


<tr  class="tborder2">
<td>
Description of spare1 context (optional)
</td>
<td>
<input type="Text" name="SPARE1" value="<?echo $config_values['SPARE1'];?>">
</td>
</tr>

<tr  class="tborder2">
<td>
Description of spare2 context (optional)
</td>
<td>
<input type="Text" name="SPARE2" value="<?echo $config_values['SPARE2'];?>">
</td>
</tr>

<tr  class="tborder2">
<td>
Description of spare3 context (optional)
</td>
<td>
<input type="Text" name="SPARE3" value="<?echo $config_values['SPARE3'];?>">
</td>
</tr>

<tr  class="tborder2">
<td>
Description of spare4 context (optional)
</td>
<td>
<input type="Text" name="SPARE4" value="<?echo $config_values['SPARE4'];?>">
</td>
</tr>

<tr  class="tborder2">
<td>
Description of spare5 context (optional)
</td>
<td>
<input type="Text" name="SPARE5" value="<?echo $config_values['SPARE5'];?>">
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
<?}
}?>
