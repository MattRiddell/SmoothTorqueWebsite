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
        //echo "Connecting to: $db_host User: $db_user Pass:$db_pass<br />";
        $link = mysql_connect($db_host, $db_user, $db_pass);
        if (!$link) {
            die('Could not connect ' . mysql_error());
        }//OR die(mysql_error());
        mysql_select_db($config_values['SUGAR_DB'], $link);
        echo "1";
//        print_pre($link);
        $result = mysql_query("SELECT id FROM ".$config_values['SUGAR_DB'].".leads WHERE phone_home = '$_POST[number]' or phone_mobile='$_POST[number]' order by date_modified DESC limit 1");
        if (mysql_num_rows($result) > 0) {
            while ($row = mysql_fetch_assoc($result)) {
                ?>
                Screen Pop Executed to: 'http://<?=$config_values['SUGAR_HOST']?>/sugarcrm/index.php?module=Leads&offset=5&action=DetailView&record=<?=$row['id']?>'<br />
                <script language="javascript">
                window.open('http://<?=$config_values['SUGAR_HOST']?>/sugarcrm/index.php?module=Leads&offset=5&action=DetailView&record=<?=$row['id']?>');
                </script>
                <?
            }
        }
    }
} else {
    ?>
    <a href="sugar_servers.php?verify_connection=1">
    Screen Pop a Sugar CRM record
    </a>
    <br /><br />
    
    <b>Total Leads: </b><?
    $db_host = $config_values['SUGAR_HOST'];
    $db_user = $config_values['SUGAR_USER'];
    $db_pass = $config_values['SUGAR_PASS'];
    //echo "Connecting to: $db_host User: $db_user Pass:$db_pass<br />";
    $link = mysql_connect($db_host, $db_user, $db_pass) or die(mysql_error());
    mysql_select_db($config_values['SUGAR_DB'], $link);
    
    $result = mysql_query("SELECT count(*) FROM leads where  deleted = 0 ");
    echo number_format(mysql_result($result,0,0));
    ?>
    <br />
    <br />
    
    
    <b>Stage 1</b> (Entered Last 5 Days): <?
    $result = mysql_query("SELECT count(*) FROM leads WHERE DATE_SUB(CURDATE(),INTERVAL 5 DAY) <= date_entered and leads.deleted = 0 ");
    echo number_format(mysql_result($result,0,0));
    ?>
    <br />
    
    
    
    
    <b>Stage 2</b> (Entered 6-10 days ago): <?
    $result = mysql_query("SELECT count(*) FROM leads WHERE DATE_SUB(CURDATE(),INTERVAL 5 DAY) > date_entered and DATE_SUB(CURDATE(),INTERVAL 10 DAY) <= date_entered and leads.deleted = 0 ");
    echo number_format(mysql_result($result,0,0));
    ?>
    <br />
    

    <b>Stage 3</b> (Entered 11-20 days ago): <?
    $result = mysql_query("SELECT count(*) FROM leads WHERE DATE_SUB(CURDATE(),INTERVAL 10 DAY) > date_entered and DATE_SUB(CURDATE(),INTERVAL 20 DAY) <= date_entered and leads.deleted = 0 ");
    echo number_format(mysql_result($result,0,0));
    ?>
    <br />
    

    <b>Stage 4</b> (Entered 21-30 days ago): <?
    $result = mysql_query("SELECT count(*) FROM leads WHERE DATE_SUB(CURDATE(),INTERVAL 20 DAY) > date_entered and DATE_SUB(CURDATE(),INTERVAL 30 DAY) <= date_entered and leads.deleted = 0 ");
    echo number_format(mysql_result($result,0,0));
    ?>
    <br />
    

    <b>Stage 5</b> (Entered 31-60 days ago): <?
    $result = mysql_query("SELECT count(*) FROM leads WHERE DATE_SUB(CURDATE(),INTERVAL 30 DAY) > date_entered and DATE_SUB(CURDATE(),INTERVAL 60 DAY) <= date_entered and leads.deleted = 0 ");
    echo number_format(mysql_result($result,0,0));
    ?>
    <br />
    <br />
    
    <b>Old</b> (Entered >60 days ago): <?
    $result = mysql_query("SELECT count(*) FROM leads WHERE DATE_SUB(CURDATE(),INTERVAL 60 DAY) > date_entered and leads.deleted = 0 ");
    echo number_format(mysql_result($result,0,0));
    ?>
    <br />
    
    
    
    
    
    
    <br />
    
    
    
    
    
    
    <b>Leads</b> (Modified Last 24 Hours): <?
    $result = mysql_query("SELECT count(*) FROM leads WHERE DATE_SUB(CURDATE(),INTERVAL 1 DAY) <= date_modified and leads.deleted = 0 ");
    echo number_format(mysql_result($result,0,0));
    ?>
    <br />
    
    
    
    
    <b>Leads</b> (Modified Last Week): <?
    $result = mysql_query("SELECT count(*) FROM leads WHERE DATE_SUB(CURDATE(),INTERVAL 7 DAY) <= date_modified and leads.deleted = 0 ");
    echo number_format(mysql_result($result,0,0));
    ?>
    <br />
    
    
    
    
    <b>Leads</b> (Modified Last Month): <?
    $result = mysql_query("SELECT count(*) FROM leads WHERE DATE_SUB(CURDATE(),INTERVAL 1 MONTH) <= date_modified and leads.deleted = 0 ");
    echo number_format(mysql_result($result,0,0));
    ?>
    <br />
    
    
    
    
    <br />
    
    
    
    <b>Timezones:</b> <br /><?
    $result = mysql_query("select count(*), time_zone_c from leads, leads_cstm where leads.id = leads_cstm.id_c and leads.deleted = 0 group by time_zone_c");
    while ($row = mysql_fetch_assoc($result)) {
        if (strlen($row['time_zone_c']) == 0) {
            $row['time_zone_c'] = "Not Set";
        }
        echo $row['time_zone_c'].": ".$row['count(*)']."<br />";
    }
    ?>
    <br />
    
    
    <br />
    
    
    <b>Statuses:</b> <br /><?
    $result = mysql_query("select count(*), lc_customstatus.name as name from leads, lc_customstatus where leads.status = lc_customstatus.id and leads.deleted = 0 group by leads.status order by count(*) desc");
    while ($row = mysql_fetch_assoc($result)) {
        echo $row['name'].": ".$row['count(*)']."<br />";
    }
    ?>
    <br />
    
    
    <br />    
    

    
    <b>Lead Sources:</b> <br /><?
    $result = mysql_query("select count(*), lead_source from leads where deleted = 0 group by lead_source order by count(*) desc");
    while ($row = mysql_fetch_assoc($result)) {
        echo $row['lead_source'].": ".$row['count(*)']."<br />";
    }
    ?>
    <br />
    <br />
    

    
    
    
    <b>Below 10k:</b> <?
    $result = mysql_query("select count(*) from leads, leads_cstm where leads.id = leads_cstm.id_c and debt_amt_c < 10000 and leads.deleted = 0");
    while ($row = mysql_fetch_assoc($result)) {
        echo $row['count(*)']."<br />";
    }
    ?>
    <br />
    
    <b>Above 10k:</b> <?
    $result = mysql_query("select count(*) from leads, leads_cstm where leads.id = leads_cstm.id_c and debt_amt_c > 10000 and leads.deleted = 0");
    while ($row = mysql_fetch_assoc($result)) {
        echo $row['count(*)']."<br />";
    }
    ?>
    <br />
    
    <b>Above 100k:</b> <?
    $result = mysql_query("select count(*) from leads, leads_cstm where leads.id = leads_cstm.id_c and debt_amt_c > 100000 and leads.deleted = 0");
    while ($row = mysql_fetch_assoc($result)) {
        echo $row['count(*)']."<br />";
    }
    ?>
    <br />
    
    
    
    
    
    
    <br />

    <?
}
echo "</center>";
box_end();
require "footer.php";
?>
