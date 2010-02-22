<?
box_start(480);
?><table align="center" width="100%" border="0" cellpadding="0" cellspacing="3"><TR>
    <TD class="subheader2"><A HREF="addserver.php"><img src="images/server_add.png" border="0" ><br />Add Asterisk Server</A>&nbsp;&nbsp;</TD>
    <TD class="subheader2"><A HREF="servers.php"><img src="images/server.png" border="0" ><br />Asterisk Servers</A>&nbsp;&nbsp;</TD>
<?
if (isset($_GET['debug'])) {
    ?> 
    <TD class="subheader2"><A HREF="freeswitch_servers.php"><img src="images/server.png" border="0" ><br />FreeSwitch Servers</A>&nbsp;&nbsp;</TD>
    <?
}
?>
<?
if (strlen($config_values['SUGAR_HOST']) > 0) {
    ?> 
    <TD class="subheader2"><A HREF="sugar_servers.php"><img src="images/database.png" border="0" ><br />SugarCRM Servers</A>&nbsp;&nbsp;</TD>
    <?
}
?>
<TD class="subheader2"><A HREF="mysql_stats.php"><img src="images/database.png" border="0" ><br />MySQL Status</A>&nbsp;&nbsp;</TD>
    </TR></table><?box_end();?>

<?flush();?>
