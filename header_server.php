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
                    <A HREF="addserver.php" class="btn btn-default navbar-btn"><img src="images/server_add.png" border="0"><br/>Add Asterisk Server</A>
                </li>
                <li>
                    <A HREF="servers.php" class="btn btn-default navbar-btn"><img src="images/server.png" border="0"><br/>Asterisk Servers</A>
                </li>
                <?
                if (isset($_GET['debug'])) {
                    ?>
                    <li>
                        <A HREF="freeswitch_servers.php" class="btn btn-default navbar-btn"><img src="images/server.png" border="0"><br/>FreeSwitch Servers</A>
                    </li>
                <?
                }
                ?>
                <?
                if (strlen($config_values['SUGAR_HOST']) > 0) {
                    ?>
                    <li>
                        <A HREF="sugar_servers.php" class="btn btn-default navbar-btn"><img src="images/database.png" border="0"><br/>SugarCRM Servers</A>
                    </li>
                <?
                }
                ?>
                <li>
                    <A HREF="mysql_stats.php" class="btn btn-default navbar-btn"><img src="images/database.png" border="0"><br/>MySQL Status</A>
                </li>

            </ul>
        </div>
    </div>
</nav>