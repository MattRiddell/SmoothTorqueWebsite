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
                    <A HREF="timezones.php?add=1"><img src="images/world_add.png" border="0" align="left">Add Timezone</A>
                </li>
                <li><A HREF="timezones.php?view_timezones=1"><img src="images/world.png" border="0" align="left">Timezones</A>
                </li>
                <? /*<li><A HREF="timezones.php?view_prefixes=1"><img src="images/telephone.png" border="0" align="left">Timezone Prefixes</A></li>*/ ?>
                <li>
                    <A HREF="timezones.php?add_prefixes=1"><img src="images/telephone_add.png" border="0" align="left">Add Single Timezone Prefix</A>
                </li>
                <li>
                    <A HREF="upload_prefixes.php"><img src="images/telephone_add.png" border="0" align="left">Upload Timezone Prefixes</A>
                </li>
                <li>
                    <A HREF="update_numbers.php" target="_blank"><img src="images/arrow_refresh.png" border="0" align="left">Run Timezone Script</A>
                </li>
            </ul>
        </div>
    </div>
</nav>