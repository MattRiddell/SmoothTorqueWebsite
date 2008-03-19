<?
require "header.php";
$db_host=$config_values['CDR_HOST'];
$db_user=$config_values['CDR_USER'];
$db_pass=$config_values['CDR_PASS'];
$cdrlink = mysql_connect($db_host, $db_user, $db_pass) OR die(mysql_error());
mysql_select_db($config_values['CDR_DB'], $cdrlink);
$sql = "SELECT * from ".$config_values['CDR_TABLE']." LIMIT 100";
$result = mysql_query($sql,$cdrlink);
while ($row = mysql_fetch_assoc($result)) {
    echo $row[dcontext]." - ".$row[dst]."<br />";
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
