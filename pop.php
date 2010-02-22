<?
if (!isset($_GET['number'])) {
        ?>
        <form action="pop.php" method="get">
        <input type="text" name="number">
        <input type="submit" value="Lookup Number">
        </form>
        <?
} else {
$db_host = $config_value['SUGAR_HOST'];
$db_user = $config_value['SUGAR_USER'];
$db_pass = $config_value['SUGAR_PASS'];
$link = mysql_connect($db_host, $db_user, $db_pass) OR die(mysql_error());
$result = mysql_query("SELECT id FROM ".$config_value['SUGAR_DB'];.".leads WHERE phone_home = '$_GET[number]' or phone_mobile='$_GET[number]' order by date_modified DESC limit 1");
if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_assoc($result)) {
?>
<script language="javascript">
window.location='http://192.168.1.17/sugarcrm/index.php?module=Leads&offset=5&action=DetailView&record=<?=$row['id']?>';
</script>

<?
        }
}
}
?>
