<? box_start(); ?>
    <A HREF="addserver.php" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> Add Asterisk Server</A>
    <A HREF="servers.php" class="btn btn-primary"><i class="glyphicon glyphicon-asterisk"></i> Asterisk Servers</A>
<?
if (isset($_GET['debug'])) {
    ?>
    <A HREF="freeswitch_servers.php" class="btn btn-primary"><i class="glyphicon glyphicon-list"></i> FreeSwitch Servers</A>
    <?
}
?>
<?
if (strlen($config_values['SUGAR_HOST']) > 0) {
    ?>
    <A HREF="sugar_servers.php" class="btn btn-primary"><i class="glyphicon glyphicon-list"></i> SugarCRM Servers</A>
    <?
}
?>
    <A HREF="mysql_stats.php" class="btn btn-primary"><i class="glyphicon glyphicon-hdd"></i> MySQL Status</A>
<? box_end(); ?>