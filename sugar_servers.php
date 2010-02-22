<?
/*
 *  sugar_servers.php
 *  SmoothTorque Website
 *
 *  Created by Matt Riddell on 22/02/10.
 *  Copyright 2010 VentureVoIP. All rights reserved.
 *
 */

require "header.php";
require "header_server.php";
box_start(400);
echo "<center>";
if (isset($_GET['verify_connection'])) {
    if (!isset($_POST['number'])) {
        ?>
        <h3>Verify Server Connection</h3>
        <form action="sugar_servers.php?verify_connection=1" method="post">
        <input type="text" name="number">
        <input type="submit" value="Lookup Number">
        </form>
        <br />
        <br />
        <?
    } else {
        // Lookup number
        $db_host = $config_values['SUGAR_HOST'];
        $db_user = $config_values['SUGAR_USER'];
        $db_pass = $config_values['SUGAR_PASS'];
        $link = mysql_connect($db_host, $db_user, $db_pass) OR die(mysql_error());
        $result = mysql_query("SELECT id FROM ".$config_values['SUGAR_DB'].".leads WHERE phone_home = '$_GET[number]' or phone_mobile='$_GET[number]' order by date_modified DESC limit 1");
        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_assoc($result)) {
                ?>
                <script language="javascript">
                window.open='http://<?=$config_values['SUGAR_HOST']?>/sugarcrm/index.php?module=Leads&offset=5&action=DetailView&record=<?=$row['id']?>';
                </script>
                <?
            }
        }
    }
} else {
    ?>
    <a href="sugar_servers.php?verify_connection=1">
    Verify SugarCRM Connection Details
    </a>
    <br /><br />
    
    <?
}
echo "</center>";
box_end();
require "footer.php";
?>
