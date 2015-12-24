<? box_start(); ?>
    <A HREF="addserver.php" class="btn btn-default navbar-btn"><img src="images/server_add.png" border="0"><br/>Add Asterisk Server</A>


    <A HREF="servers.php" class="btn btn-default navbar-btn"><img src="images/server.png" border="0"><br/>Asterisk Servers</A>

<?
if (isset($_GET['debug'])) {
    ?>

    <A HREF="freeswitch_servers.php" class="btn btn-default navbar-btn"><img src="images/server.png" border="0"><br/>FreeSwitch Servers</A>

    <?
}
?>
<?
if (strlen($config_values['SUGAR_HOST']) > 0) {
    ?>

    <A HREF="sugar_servers.php" class="btn btn-default navbar-btn"><img src="images/database.png" border="0"><br/>SugarCRM Servers</A>

    <?
}
?>

    <A HREF="mysql_stats.php" class="btn btn-default navbar-btn"><img src="images/database.png" border="0"><br/>MySQL Status</A>
<? box_end(); ?>