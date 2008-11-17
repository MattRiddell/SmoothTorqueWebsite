<?
if ($config_values['ADD_CAMPAIGN'] == "") {
    $config_values['ADD_CAMPAIGN'] = "Add Campaign";
}

if ($config_values['VIEW_CAMPAIGN'] == "") {
    $config_values['VIEW_CAMPAIGN'] = "View Campaigns";
} else {
    //echo $config_values['VIEW_CAMPAIGN'];
}

?>
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2"><TR>
    <TD class="subheader"><A HREF="addcampaign.php"><img src="/images/folder_add.png" border="0" align="left"><?echo $config_values['ADD_CAMPAIGN'];?></A>&nbsp;&nbsp;</TD>
    <TD class="subheader"><A HREF="campaigns.php"><img src="/images/folder_explore.png" border="0" align="left"><?echo $config_values['VIEW_CAMPAIGN'];?></A>&nbsp;&nbsp;</TD>
    </TR></table>
    <BR>
<?flush();?>
