<?
require "header.php";
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
if ($level==sha1("level10")){
    echo '<a href="addfunds.php">Add funds to a registered account</a><br />';
    echo '<a href="billinglog.php">View Billing Log</a><br />';

} else {
echo $config_values['MAIN_PAGE_TEXT'];
}
?><br />
</td>
<td>
</td></tr>
</table>
</center>
<?
require "footer.php";
?>
