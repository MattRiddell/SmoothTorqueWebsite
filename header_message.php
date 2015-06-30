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
                <?
                if ($config_values['DISABLE_MESSAGE_UPLOAD'] == "NO") {

                    ?>
                    <li>
                        <A HREF="uploadmessage.php?type=audio"><img src="images/sound_add.png" border="0" align="left">Upload New Audio Message</A>
                    </li>
                    <li>
                        <A HREF="uploadmessage.php?type=fax"><img src="images/page_add.png" border="0" align="left">Upload New Fax Message</A>
                    </li>
                <?
                }
                ?>
                <li><A HREF="messages.php"><img src="images/sound.png" border="0" align="left">View All Messages</A>
                </li>
            </ul>
        </div>
    </div>
</nav>