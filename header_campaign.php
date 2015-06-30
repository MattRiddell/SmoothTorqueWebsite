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
?>
<nav class="navbar navbar-default center">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">

                <li>
                    <A HREF="addcampaign.php" class="btn btn-default navbar-btn"><img src="images/folder_add.png" border="0" align="left"><? echo $config_values['ADD_CAMPAIGN']; ?>
                    </A>
                </li>
                <li>
                    <A HREF="campaigns.php" class="btn btn-default navbar-btn"><img src="images/folder_explore.png" border="0" align="left"><? echo $config_values['VIEW_CAMPAIGN']; ?>
                        </a>
                </li>


                <?
                if ($_COOKIE['level'] == sha1("level100")) {

                    //if (isset($_GET['type']) && $_GET['type'] == "all") {
                        ?>
                        <li>
                            <A HREF="campaigns.php?type=admin" class="btn btn-default navbar-btn"><img src="images/cog.png" border="0">&nbsp;Admin Campaigns</A>
                        </li>
                        <li>
                            <A HREF="selectcustomer.php" class="btn btn-default navbar-btn"><img src="images/user.png" border="0">&nbsp;Select Customer</A>
                        </li>
                    <li>
                        <A HREF="campaigns.php?type=all" class="btn btn-default navbar-btn"><img src="images/folder.png" border="0">&nbsp;All Campaigns</A>
                    </li>
                    <?

                }
                ?>

            </ul>
        </div>
    </div>
</nav>


