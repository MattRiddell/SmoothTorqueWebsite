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
box_start(700);
} else {
box_start(295);
}
?>
<table  align="center" width="100%" border="0" cellpadding="0" cellspacing="3"><TR>
    <TD class="subheader"><A HREF="addcampaign.php"><img src="images/folder_add.png" border="0" align="left"><?echo $config_values['ADD_CAMPAIGN'];?></A>&nbsp;&nbsp;</TD>
    <TD class="subheader"><A HREF="campaigns.php"><img src="images/folder_explore.png" border="0" align="left"><?echo $config_values['VIEW_CAMPAIGN'];?></A>&nbsp;&nbsp;</TD>


    <?
    if ($_COOKIE[level] == sha1("level100")) {

        if ($_GET[type]=="all") {
            echo "<TD class=\"subheader\"><A HREF=\"campaigns.php?type=admin\"><img src=\"images/cog.png\" border=\"0\">&nbsp;Admin Campaigns</A></TD>";
            echo "<TD class=\"subheader\"><A HREF=\"selectcustomer.php\"><img src=\"images/user.png\" border=\"0\">&nbsp;Select Customer</A></TD>";
            echo "<TD class=\"subheader\"><b><img src=\"images/folder.png\" border=\"0\">&nbsp;All Campaigns</b></TD>";
        } else {
            echo "<TD class=\"subheader\"><b><img src=\"images/cog.png\" border=\"0\">&nbsp;Admin Campaigns</b></TD>";
            echo "<TD class=\"subheader\"><A HREF=\"selectcustomer.php\"><img src=\"images/user.png\" border=\"0\">&nbsp;Select Customer</A></TD>";
            echo "<TD class=\"subheader\"><A HREF=\"campaigns.php?type=all\"><img src=\"images/folder.png\" border=\"0\">&nbsp;All Campaigns</A></TD>";
        }
    }
    ?>


    </TR></table><?box_end();?>
<?flush();?>
