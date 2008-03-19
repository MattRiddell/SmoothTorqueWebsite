<?
require "header.php";
$db_host=$config_values['CDR_HOST'];
$db_user=$config_values['CDR_USER'];
$db_pass=$config_values['CDR_PASS'];
$cdrlink = mysql_connect($db_host, $db_user, $db_pass) OR die(mysql_error());
mysql_select_db($config_values['CDR_DB'], $cdrlink);
$sql = "SELECT * from ".$config_values['CDR_TABLE']." LIMIT 3000";
$result = mysql_query($sql,$cdrlink);
$i = 0;
while ($row = mysql_fetch_assoc($result)) {
    $calldate[$i] = $row[calldate];
    $dcontext[$i] = $row[dcontext];
    $dst[$i] = $row[dst];
    $src[$i] = $row[src];
    $clid[$i] = $row[clid];
    $channel[$i] = $row[channel];
    $dstchannel[$i] = $row[dstchannel];
    $lastapp[$i] = $row[lastapp];
    $lastdata[$i] = $row[lastdata];
    $duration[$i] = $row[duration];
    $billsec[$i] = $row[billsec];
    $disposition[$i] = $row[disposition];
    $amaflags[$i] = $row[amaflags];
    $accountcode[$i] = $row[accountcode];
    $userfield[$i] = $row[userfield];
    echo     $calldate[$i]." - ".$dcontext[$i]." - ".$dst[$i]." - ".
    $src[$i]." - ".$clid[$i]." - ".$channel[$i]." - ".$dstchannel[$i]." - ".
    $lastapp[$i]." - ".$lastdata[$i]." - ".$duration[$i]." - ".$billsec[$i]." - <b>".
    $disposition[$i]."</b> - ".$amaflags[$i]." - ".$accountcode[$i]." - ".$userfield[$i];
    echo "<br />";
    $i++;
}
exit(0);
?>

<br /><br /><br /><br />
<center>
<table background="/images/sdbox.png" width="300" height="200" class="dragme22">
<tr>
<td>
</td>
<td width="260">
<b>Welcome to <?echo $config_values['TITLE'];?>.</b><br />
<br />
<?
echo $config_values['MAIN_PAGE_TEXT'];
?><br />
</td>
<td>
</td></tr>
</table>
</center>
<?
require "footer.php";
?>
