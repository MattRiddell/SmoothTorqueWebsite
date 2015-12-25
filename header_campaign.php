<?
if ($config_values['ADD_CAMPAIGN'] == "") {
    $config_values['ADD_CAMPAIGN'] = "Add Campaign";
}

if ($config_values['VIEW_CAMPAIGN'] == "") {
    $config_values['VIEW_CAMPAIGN'] = "View Campaigns";
} else {
    //echo $config_values['VIEW_CAMPAIGN'];
}
if ($_COOKIE['level'] == sha1("level100")) {
//box_start(700);
} else {
//box_start(295);
}

box_start();
?>
    <a href="addcampaign.php" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> <? echo $config_values['ADD_CAMPAIGN']; ?>
    </a>
    <a href="campaigns.php" class="btn btn-primary"><i class="glyphicon glyphicon-list"></i> <? echo $config_values['VIEW_CAMPAIGN']; ?>
    </a>


<?
if ($_COOKIE['level'] == sha1("level100")) {
    ?>
    <A HREF="campaigns.php?type=admin" class="btn btn-primary"><i class="glyphicon glyphicon-cog"></i> &nbsp;Admin Campaigns</A>
    <A HREF="selectcustomer.php" class="btn btn-primary"><i class="glyphicon glyphicon-user"></i> &nbsp;Select Customer</A>
    <A HREF="campaigns.php?type=all" class="btn btn-primary"><i class="glyphicon glyphicon-list"></i> &nbsp;All Campaigns</A>
    <?

}
box_end();
