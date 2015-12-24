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
                    <A HREF="addcampaign.php" class="btn btn-default navbar-btn"><img src="images/folder_add.png" border="0" align="left"><? echo $config_values['ADD_CAMPAIGN']; ?>
                    </A>
                
                    <A HREF="campaigns.php" class="btn btn-default navbar-btn"><img src="images/folder_explore.png" border="0" align="left"><? echo $config_values['VIEW_CAMPAIGN']; ?>
                        </a>
                


                <?
                if ($_COOKIE['level'] == sha1("level100")) {

                    //if (isset($_GET['type']) && $_GET['type'] == "all") {
                        ?>
                        
                            <A HREF="campaigns.php?type=admin" class="btn btn-default navbar-btn"><img src="images/cog.png" border="0">&nbsp;Admin Campaigns</A>
                        
                        
                            <A HREF="selectcustomer.php" class="btn btn-default navbar-btn"><img src="images/user.png" border="0">&nbsp;Select Customer</A>
                        
                    
                        <A HREF="campaigns.php?type=all" class="btn btn-default navbar-btn"><img src="images/folder.png" border="0">&nbsp;All Campaigns</A>
                    
                    <?

                }
box_end();
