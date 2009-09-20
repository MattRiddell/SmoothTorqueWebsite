<?box_start(480);?><table align="center" width="100%" border="0" cellpadding="0" cellspacing="3"><TR>
    <TD class="subheader"><A HREF="addserver.php"><img src="/images/server_add.png" border="0" align="left">Add Asterisk Server</A>&nbsp;&nbsp;</TD>
    <TD class="subheader"><A HREF="servers.php"><img src="/images/server.png" border="0" align="left">View Asterisk Servers</A>&nbsp;&nbsp;</TD>
<?
if (isset($_GET['debug'])) {
?> 
    <TD class="subheader"><A HREF="freeswitch_servers.php"><img src="/images/server.png" border="0" align="left">FreeSwitch Servers</A>&nbsp;&nbsp;</TD>
<?
}
?>
    <TD class="subheader"><A HREF="mysql_stats.php"><img src="/images/database.png" border="0" align="left">View MySQL Status</A>&nbsp;&nbsp;</TD>
    </TR></table><?box_end();?>

<?flush();?>
