<?
if ($config_values['ADD_CAMPAIGN'] == "") {
    $config_values['ADD_CAMPAIGN'] = "Add Campaign";
}

if ($config_values['VIEW_CAMPAIGN'] == "") {
    $config_values['VIEW_CAMPAIGN'] = "View Campaigns";
} else {
    //echo $config_values['VIEW_CAMPAIGN'];
}
if ($_COOKIE[level] == sha1("level100")) {
box_start(680);
} else {
box_start(295);
}
?>
<table  align="center" width="100%" border="0" cellpadding="0" cellspacing="3"><TR>
    <TD class="subheader"><A HREF="addcampaign.php"><img src="/images/folder_add.png" border="0" align="left"><?echo $config_values['ADD_CAMPAIGN'];?></A>&nbsp;&nbsp;</TD>
    <TD class="subheader"><A HREF="campaigns.php"><img src="/images/folder_explore.png" border="0" align="left"><?echo $config_values['VIEW_CAMPAIGN'];?></A>&nbsp;&nbsp;</TD>


    <?
    if ($_COOKIE[level] == sha1("level100")) {

        if ($_GET[type]=="all") {
            echo "<TD class=\"subheader\"><A HREF=\"campaigns.php?type=admin\">Admin Campaigns</A></TD>";
            echo "<TD class=\"subheader\"><A HREF=\"selectcustomer.php\">Select Customer</A></TD>";
            echo "<TD class=\"subheader\"><b>All Campaigns</b></TD>";
        } else {
            echo "<TD class=\"subheader\"><b>Admin Campaigns</b></TD>";
            echo "<TD class=\"subheader\"><A HREF=\"selectcustomer.php\">Select Customer</A></TD>";
            echo "<TD class=\"subheader\"><A HREF=\"campaigns.php?type=all\">All Campaigns</A></TD>";
        }
    }
    ?>


    </TR></table><?box_end();?>
    <?box_start(720);?>
    <center>
    <img width="16" height="16" src="/images/pencil.png" border="0">&nbsp;Edit Campaign&nbsp;
    <img width="16" height="16" src="/images/chart_pie.png" border="0">&nbsp;View Number Stats&nbsp;
    <img width="16" height="16" src="/images/control_stop_blue.png" border="0"> Stop Campaign
    <img width="16" height="16" src="/images/control_play_blue.png" border="0"> Start Campaign
    <br />
<?    if ($config_values['ALLOW_NUMBERS_MANUAL'] == "YES") {?>
    <img width="16" height="16" src="/images/database_lightning.png" border="0"> Initialise Manual Dialing
    <?}?>
    <img width="16" height="16" src="/images/chart_curve.png" border="0"> Realtime stats
    <img width="16" height="16" src="/images/table.png" border="0"> View Numbers
    <img width="16" height="16" src="/images/delete.png" border="0"> Delete Campaign
    </center>
    <?box_end();?><br />
<?flush();?>
