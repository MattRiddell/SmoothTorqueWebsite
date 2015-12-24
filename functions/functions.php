<?
require $current_directory."/functions/read_config_file.php";

require $current_directory."/functions/bitmask.php";

require $current_directory."/functions/authentication.php";

require $current_directory."/functions/sanitize.php";

require $current_directory."/functions/urls.php";

require $current_directory."/functions/layout.php";

require $current_directory."/functions/database.php";

if (!function_exists('sec2hms')) {
    function sec2hms($sec, $padHours = FALSE) {

        // holds formatted string
        $hms = "";

        // there are 3600 seconds in an hour, so if we
        // divide total seconds by 3600 and throw away
        // the remainder, we've got the number of hours
        $hours = intval(intval($sec) / 3600);

        // add to $hms, with a leading 0 if asked for
        $hms .= ($padHours)
            ? str_pad($hours, 2, "0", STR_PAD_LEFT).':'
            : $hours.':';

        // dividing the total seconds by 60 will give us
        // the number of minutes, but we're interested in
        // minutes past the hour: to get that, we need to
        // divide by 60 again and keep the remainder
        $minutes = intval(($sec / 60) % 60);

        // then add to $hms (with a leading 0 if needed)
        $hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT).':';

        // seconds are simple - just divide the total
        // seconds by 60 and keep the remainder
        $seconds = intval($sec % 60);

        // add to $hms, again with a leading 0 if needed
        $hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);

        // done!
        return $hms;

    }
}
if (!function_exists('extractXML')) {
    function extractXML($xml) {

        if (!($xml->children())) {
            return (string)$xml;
        }

        foreach ($xml->children() as $child) {
            $name = $child->getName();
            if (count($xml->$name) == 1) {
                $element[$name] = extractXML($child);
            } else {
                $element[][$name] = extractXML($child);
            }
        }

        return $element;
    }
}
if (!function_exists('print_pre')) {
    function print_pre($text) {
        echo "<pre>";
        print_r($text);
        echo "</pre>";
    }
}

if (!function_exists('draw_icons')) {
    function draw_icons($section) {
        if ($handle = opendir('./modules')) {
            while (FALSE !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    $xml = simplexml_load_file("./modules/".$file);
                    if (isset($xml->icon)) {
                        $icon = $xml->icon;
                    } else {
                        $icon = "application";
                    }
                    if ($xml->menu->section == $section) {
                        box_button($xml->name, $icon, $xml->menu->link, $xml->description);
                    }
                }
            }
        }
    }
}


if (!function_exists('check_for_gd_library')) {
    function check_for_gd_library() {
        if (!extension_loaded('gd')) {
            echo "It looks like php-gd is not installed.  Installing it will depend ";
            echo "on the package manager you have installed.  Here are a few examples:<br /><br />";
            echo "Fedora/Centos:<br /><code>yum install -y php-gd</code><br /><br />";
            echo "Debian/Ubuntu:<br /><code>apt-get install php-gd</code><br /><br />";
            echo "Gentoo:<br /><code>emerge php-gd</code><br /><br />";
            echo "Mandriva/Mandrake:<br /><code>urpmi php-gd</code><br /><br />";
            exit(0);
        }

    }
}

if (!function_exists('check_for_upload_settings')) {
    function check_for_upload_settings($current_directory) {
        if (!file_exists($current_directory."/../upload_settings.inc")) {
            if (!file_exists($current_directory."/../../upload_settings.inc")) {
                echo "The file ../upload_settings.inc does not exist.  You will need to ";
                echo "copy it from the $current_directory/cron subdirectory by typing ";
                echo "the following commands<br /><br />";
                echo "<code>cp $current_directory/cron/upload_settings.inc $current_directory/../</code>";
                exit(0);
            }
        }
    }
}

if (!function_exists('check_for_upload_directory')) {
    function check_for_upload_directory($whoami) {
        if (!file_exists("/var/tmp/uploads")) {
            $current_directory = dirname(__FILE__)."/../";
            echo "The directory /var/tmp/uploads does not exist.  You will need to create ";
            echo "it by typing the following commands<br /><br />";
            echo "<code>mkdir /var/tmp/uploads<br />";
            echo "chown $whoami /var/tmp/uploads<br />";
            echo "cp $current_directory/uploads/* /var/tmp/uploads</code>";
            exit(0);
        }

    }
}
if (!function_exists('get_backend_version')) {
    function get_backend_version() {
        if (file_exists("/SmoothTorque/SmoothTorque.version")) {
            $fp2 = fopen("/SmoothTorque/SmoothTorque.version", "r");
            while (!feof($fp2)) {
                $line = trim(fgets($fp2));
                if (strlen($line) > 0) {
                    $version = substr($line, 0, strlen($line) - 1);
                }
            }
            fclose($fp2);
            if ($version > 0) {
                $version /= 100;
            }
        }
        return $version;
    }
}

if (!function_exists('_get_browser')) {
    function _get_browser() {
        $browser = array( //reversed array
            "OPERA",
            "MSIE",            // parent
            "NETSCAPE",
            "FIREFOX",
            "SAFARI",
            "KONQUEROR",
            "MOZILLA"        // parent
        );

        $info['browser'] = "OTHER";

        foreach ($browser as $parent) {
            if (($s = strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), $parent)) !== FALSE) {
                $f = $s + strlen($parent);
                $version = substr($_SERVER['HTTP_USER_AGENT'], $f, 5);
                $version = preg_replace('/[^0-9,.]/', '', $version);
                $info['browser'] = $parent;
                $info['version'] = $version;
                break; // first match wins
            }
        }
        return $info;
    }
}


if (!function_exists('get_bootstrap_menu')) {
    function get_bootstrap_menu($config_values, $self, $level, $mode = 0) {
        $response = '

        <nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="main.php">'.$config_values['brand'].'</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="glyphicon glyphicon-list"></i> Campaigns <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="new_campaign.php?view=1">View existing campaigns</a></li>
            <li><a href="new_campaign.php?add=1">Add a new campaign</a></li>

          </ul>
        </li>
        <li>
          <a href = "messages.php"  ><i class="glyphicon glyphicon-play-circle"></i> Messages </a >

        </li >
        ';
        if ($level == sha1("level100")) {

            $response .= '
            <li>
          <a href = "config.php"  ><i class="glyphicon glyphicon-cog"></i> Settings </a >

        </li >
        <li>
          <a href = "trunks.php"  ><i class="glyphicon glyphicon-phone-alt"></i> Phone Lines </a >

        </li >
        <li>
          <a href = "customers.php"  ><i class="glyphicon glyphicon-user"></i> Customers </a >

        </li >
        ';
        }
        $response .= '
      </ul>

      <ul class="nav navbar-nav navbar-right">
        <li><a href="logout.php"><i class="glyphicon glyphicon-log-out"></i> Logout</a></li>

      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

        ';
        return $response;
    }
}

if (!function_exists('get_menu_html')) {
    function get_menu_html($config_values, $self, $level, $mode = 0) {
        global $http_dir_name;
        if ($mode == 1) {
            $menu = '<CENTER>
<nav class="navbar navbar-default ">
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


<a class="navbar-brand" href="main.php">'.$config_values['TITLE'].'</a>


     ';
            //=======================================================================================================
            // Home
            //=======================================================================================================
            if ($self == "/main.php") {
                $menu .= '<li class="active">';

            } else {
                $menu .= '<li>';

            }

            $menu .= '<A HREF="'.$http_dir_name.'main.php"><i class="glyphicon glyphicon-home"></i> '.str_replace(" ", "&nbsp;", $config_values['MENU_HOME']).'</A></li>';

            //=======================================================================================================
            // Campaigns
            //=======================================================================================================
            if ($level == sha1("level100") || $level == sha1("level0")) {
                if ($self == "/campaigns.php" || $self == "/report.php" || $self == "/resetlist.php" || $self == "/list.php" || $self == "/deletecampaign.php" || $self == "/editcampaign.php" || $self == "/addcampaign.php" || $self == "/stopcampaign.php" || $self == "/startcampaign.php" || $self == "/test.php"
                ) {
                    $menu .= '<li class="active">';
                } else {
                    $menu .= '<li>';
                }
                $menu .= '<A HREF="'.$http_dir_name.'campaigns.php"><i class="glyphicon glyphicon-file"></i> '.str_replace(" ", "&nbsp;", $config_values['MENU_CAMPAIGNS']).'</A></li>';

                //=======================================================================================================
                // Numbers
                //=======================================================================================================
                if ($self == "/addnumbers.php" || $self == "/notifications.php" || $self == "/serverlist.php" || $self == "/numbers.php" || $self == "/deletenumber.php" || $self == "/viewnumbers.php" || $self == "/gennumbers.php" || $self == "/upload.php" || $self == "//receive.php" || $self == "/resetnumber.php") {
                    $menu .= '<li class="active">';
                } else {
                    $menu .= '<li>';
                }
                $menu .= '<A HREF="'.$http_dir_name.'numbers.php"><i class="glyphicon glyphicon-list"></i> '.str_replace(" ", "&nbsp;", $config_values['MENU_NUMBERS']).'</A></li>';

                //=======================================================================================================
                // DNC Numbers
                //=======================================================================================================
                if ($self == "/adddncnumbers.php" || $self == "/dncnumbers.php" || $self == "/deletedncnumber.php" || $self == "/viewdncnumbers.php" || $self == "/gendncnumbers.php" || $self == "/uploaddnc.php" || $self == "//receivednc.php") {
                    $menu .= '<li class="active">';
                } else {
                    $menu .= '<li>';
                }
                $menu .= '<A HREF="'.$http_dir_name.'dncnumbers.php"><i class="glyphicon glyphicon-remove-circle"></i> '.str_replace(" ", "&nbsp;", $config_values['MENU_DNC']).'</A></li>';

                //=======================================================================================================
                // Messages
                //=======================================================================================================
                if ($self == "/editmessage.php" || $self == "/addmessage.php" || $self == "/deleteMessage.php" || $self == "/messages.php" || $self == "/uploadmessage.php") {
                    $menu .= '<li class="active">';
                } else {
                    $menu .= '<li>';
                }
                $menu .= '<A HREF="'.$http_dir_name.'messages.php"><i class="glyphicon glyphicon-volume-up"></i> '.str_replace(" ", "&nbsp;", $config_values['MENU_MESSAGES']).'</A></li>';

                //=======================================================================================================
                // Schedules
                //=======================================================================================================
                if ($self == "/editschedule.php" || $self == "/addschedule.php" || $self == "/deleteschedule.php" || $self == "/schedule.php") {
                    $menu .= '<li class="active">';
                } else {
                    $menu .= '<li>';
                }
                $menu .= '<A HREF="'.$http_dir_name.'schedule.php"><i class="glyphicon glyphicon-calendar"></i> '.str_replace(" ", "&nbsp;", $config_values['MENU_SCHEDULES']).'</A></li>';


                if ($level == sha1("level100")) {

                    //=======================================================================================================
                    // Surveys
                    //=======================================================================================================
                    if ($config_values['DISABLE_SURVEYS'] != "YES") {
                        if ($self == "/surveys.php" || $self == "/survey_responses.php" || $self == "/transfer_report.php") {
                            $menu .= '<li class="active">';
                        } else {
                            $menu .= '<li>';
                        }
                        $menu .= '<A HREF="'.$http_dir_name.'surveys.php"><i class="glyphicon glyphicon-earphone"></i> '.str_replace(" ", "&nbsp;", $config_values['MENU_SURVEYS']).'</A></li>';
                    }
                    //=======================================================================================================
                    // CDRs
                    //=======================================================================================================
                    if ($self == "/viewcdr.php") {
                        $menu .= '<li class="active">';
                    } else {
                        $menu .= '<li>';
                    }
                    $menu .= '<A HREF="'.$http_dir_name.'viewcdr.php?all=1"><i class="glyphicon glyphicon-list-alt"></i> '.str_replace(" ", "&nbsp;", $config_values['MENU_CDR']).'</A></li>';
                    //=======================================================================================================

                    //=======================================================================================================
                    // Customers
                    //=======================================================================================================
                    if ($self == "/deletecustomer.php" || $self == "/addcustomer.php" || $self == "/customers.php" || $self == "/editcustomer.php") {
                        $menu .= '<li class="active">';
                    } else {
                        $menu .= '<li>';
                    }
                    $menu .= '<A HREF="'.$http_dir_name.'customers.php"><i class="glyphicon glyphicon-user"></i> '.str_replace(" ", "&nbsp;", $config_values['MENU_CUSTOMERS']).'</A></li>';
                    //=======================================================================================================

                    //=======================================================================================================
                    // Queues
                    //=======================================================================================================
                    if ($self == "/deletequeue.php" || $self == "/addqueue.php" || $self == "/queues.php" || $self == "/editqueue.php") {
                        $menu .= '<li class="active">';
                    } else {
                        $menu .= '<li>';
                    }
                    $menu .= '<A HREF="'.$http_dir_name.'queues.php"><i class="glyphicon glyphicon-align-justify"></i> '.str_replace(" ", "&nbsp;", $config_values['MENU_QUEUES']).'</A></li>';
                    //=======================================================================================================

                    //=======================================================================================================
                    // Servers
                    //=======================================================================================================
                    if ($self == "/deleteserver.php" || $self == "/addserver.php" || $self == "/servers.php" || $self == "/editserver.php") {
                        $menu .= '<li class="active">';
                    } else {
                        $menu .= '<li>';
                    }

                    $menu .= '<A HREF="'.$http_dir_name.'servers.php"><i class="glyphicon glyphicon-hdd"></i> '.str_replace(" ", "&nbsp;", $config_values['MENU_SERVERS']).'</A></li>';
                    //=======================================================================================================


                    //=======================================================================================================
                    // Trunks
                    //=======================================================================================================
                    if ($self == "/trunks.php" || $self == "/edittrunk.php" || $self == "/addtrunk.php" || $self == "/setdefault.php" || $self == "/deletetrunk.php") {
                        $menu .= '<li class="active">';
                    } else {
                        $menu .= '<li>';
                    }
                    $menu .= '<A HREF="'.$http_dir_name.'trunks.php"><i class="glyphicon glyphicon-phone-alt"></i> '.str_replace(" ", "&nbsp;", $config_values['MENU_TRUNKS']).'</A></li>';
                    //=======================================================================================================

                    //=======================================================================================================
                    // Timezones
                    //=======================================================================================================
                    if ($config_values['USE_TIMEZONES'] == "YES") {
                        if ($self == "/timezones.php") {
                            $menu .= '<li class="active">';
                        } else {
                            $menu .= '<li>';
                        }
                        $menu .= '<A HREF="'.$http_dir_name.'timezones.php?view_timezones=1"><i class="glyphicon glyphicon-time"></i> '.str_replace(" ", "&nbsp;", $config_values['MENU_TIMEZONES']).'</A></li>';
                    }
                    //=======================================================================================================

                    //=======================================================================================================
                    // Admin
                    //=======================================================================================================
                    if ($self == "/config.php" || $self == "/setparameter.php") {
                        $menu .= '<li class="active">';
                    } else {
                        $menu .= '<li>';
                    }
                    $menu .= '<A HREF="'.$http_dir_name.'config.php"><i class="glyphicon glyphicon-cog"></i> '.str_replace(" ", "&nbsp;", $config_values['MENU_ADMIN']).'</A></li>';
                    //=======================================================================================================

                }
                //    <TD class="thead2"><A HREF="prefs.php">Preferences</A>&nbsp;</li>
            } else if ($level == sha1("level10")) {
                /*//=======================================================================================================
                 // Customers
                 //=======================================================================================================
                 if ($self=="/deletecustomer.php"||$self=="/addcustomer.php"||$self=="/customers.php"||$self=="/editcustomer.php"){
                 $thead="thead";
                 } else {
                 $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
                 }
                 $menu.='<TD class="'.$thead.'"><A HREF="/customers.php"><img width="16" height="16"  src="images/group.png" border="0" ><br />'.$config_values['MENU_CUSTOMERS'].'</A></li>';
                 //=======================================================================================================
                 */

                //echo "Billing Administrator Login";
                // This is for people who are logged in as a billing administrator
                //=======================================================================================================
                // Add Funds
                //=======================================================================================================
                if ($self == "/addfunds.php") {
                    $menu .= '<li class="active">';
                } else {
                    $menu .= '<li>';
                }
                $menu .= '<TD class="'.$thead.'"><A HREF="'.$http_dir_name.'addfunds.php"><i class="glyphicon glyphicon-credit-card"></i> '.str_replace(" ", "&nbsp;", $config_values['MENU_ADDFUNDS']).'</A></li>';
                //=======================================================================================================

            }
            $thead = "thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";

            $menu .= '</ul><ul class="nav navbar-nav navbar-right"><li><A HREF="'.$http_dir_name.'logout.php"><i class="glyphicon glyphicon-log-out"></i> '.str_replace(" ", "&nbsp;", $config_values['MENU_LOGOUT']).'</A></li><TD CLASS="theadr2" WIDTH=0></li>
 </ul>

    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
        ';
        } else {


            $menu = '<CENTER>
        <table border="0" cellpadding="3" cellspacing="0"><TR HEIGHT="10">';
            //=======================================================================================================
            // Home
            //=======================================================================================================
            if ($self == "/main.php") {
                $menu .= '<td style="background-image: url(images/clb.gif);"></td>';
                $thead = "thead";
            } else {
                $menu .= '<TD CLASS="theadl2" WIDTH=0></TD>';
                $thead = "thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
            }

            $menu .= '<TD class="'.$thead.'" height=27><A HREF="'.$http_dir_name.'main.php"><img width="16" height="16" src="'.$http_dir_name.'images/house.png" border="0"><br />'.str_replace(" ", "&nbsp;", $config_values['MENU_HOME']).'</A>&nbsp;</TD>';

            //=======================================================================================================
            // Campaigns
            //=======================================================================================================
            if ($level == sha1("level100") || $level == sha1("level0")) {
                if ($self == "/campaigns.php" || $self == "/report.php" || $self == "/resetlist.php" || $self == "/list.php" || $self == "/deletecampaign.php" || $self == "/editcampaign.php" || $self == "/addcampaign.php" || $self == "/stopcampaign.php" || $self == "/startcampaign.php" || $self == "/test.php"
                ) {
                    $thead = "thead";
                } else {
                    $thead = "thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
                }
                $menu .= '<TD class="'.$thead.'"><A HREF="'.$http_dir_name.'campaigns.php"><img width="16" height="16"  src="'.$http_dir_name.'images/folder.png" border="0" ><br />'.str_replace(" ", "&nbsp;", $config_values['MENU_CAMPAIGNS']).'</A>&nbsp;</TD>';

                //=======================================================================================================
                // Numbers
                //=======================================================================================================
                if ($self == "/addnumbers.php" || $self == "/notifications.php" || $self == "/serverlist.php" || $self == "/numbers.php" || $self == "/deletenumber.php" || $self == "/viewnumbers.php" || $self == "/gennumbers.php" || $self == "/upload.php" || $self == "//receive.php" || $self == "/resetnumber.php") {
                    $thead = "thead";
                } else {
                    $thead = "thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
                }
                $menu .= '<TD class="'.$thead.'"><A HREF="'.$http_dir_name.'numbers.php"><img width="16" height="16"  src="'.$http_dir_name.'images/telephone.png" border="0" ><br />'.str_replace(" ", "&nbsp;", $config_values['MENU_NUMBERS']).'</A>&nbsp;</TD>';

                //=======================================================================================================
                // DNC Numbers
                //=======================================================================================================
                if ($self == "/adddncnumbers.php" || $self == "/dncnumbers.php" || $self == "/deletedncnumber.php" || $self == "/viewdncnumbers.php" || $self == "/gendncnumbers.php" || $self == "/uploaddnc.php" || $self == "//receivednc.php") {
                    $thead = "thead";
                } else {
                    $thead = "thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
                }
                $menu .= '<TD class="'.$thead.'"><A HREF="'.$http_dir_name.'dncnumbers.php"><img width="16" height="16"  src="'.$http_dir_name.'images/telephone_error.png" border="0" ><br />'.str_replace(" ", "&nbsp;", $config_values['MENU_DNC']).'</A>&nbsp;</TD>';

                //=======================================================================================================
                // Messages
                //=======================================================================================================
                if ($self == "/editmessage.php" || $self == "/addmessage.php" || $self == "/deleteMessage.php" || $self == "/messages.php" || $self == "/uploadmessage.php") {
                    $thead = "thead";
                } else {
                    $thead = "thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
                }
                $menu .= '<TD class="'.$thead.'"><A HREF="'.$http_dir_name.'messages.php"><img width="16" height="16"  src="'.$http_dir_name.'images/sound.png" border="0" ><br />'.str_replace(" ", "&nbsp;", $config_values['MENU_MESSAGES']).'</A>&nbsp;</TD>';

                //=======================================================================================================
                // Schedules
                //=======================================================================================================
                if ($self == "/editschedule.php" || $self == "/addschedule.php" || $self == "/deleteschedule.php" || $self == "/schedule.php") {
                    $thead = "thead";
                } else {
                    $thead = "thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
                }
                $menu .= '<TD class="'.$thead.'"><A HREF="'.$http_dir_name.'schedule.php"><img width="16" height="16"  src="'.$http_dir_name.'images/clock.png" border="0" ><br />'.str_replace(" ", "&nbsp;", $config_values['MENU_SCHEDULES']).'</A>&nbsp;</TD>';


                if ($level == sha1("level100")) {

                    //=======================================================================================================
                    // Surveys
                    //=======================================================================================================
                    if ($config_values['DISABLE_SURVEYS'] != "YES") {
                        if ($self == "/surveys.php" || $self == "/survey_responses.php" || $self == "/transfer_report.php") {
                            $thead = "thead";
                        } else {
                            $thead = "thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
                        }
                        $menu .= '<TD class="'.$thead.'"><A HREF="'.$http_dir_name.'surveys.php"><img width="16" height="16"  src="'.$http_dir_name.'images/table.png" border="0" ><br />'.str_replace(" ", "&nbsp;", $config_values['MENU_SURVEYS']).'</A>&nbsp;</TD>';
                    }
                    //=======================================================================================================
                    // CDRs
                    //=======================================================================================================
                    if ($self == "/viewcdr.php") {
                        $thead = "thead";
                    } else {
                        $thead = "thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
                    }
                    $menu .= '<TD class="'.$thead.'"><A HREF="'.$http_dir_name.'viewcdr.php?all=1"><img width="16" height="16"  src="'.$http_dir_name.'images/page_green.png" border="0" ><br />'.str_replace(" ", "&nbsp;", $config_values['MENU_CDR']).'</A>&nbsp;</TD>';
                    //=======================================================================================================

                    //=======================================================================================================
                    // Customers
                    //=======================================================================================================
                    if ($self == "/deletecustomer.php" || $self == "/addcustomer.php" || $self == "/customers.php" || $self == "/editcustomer.php") {
                        $thead = "thead";
                    } else {
                        $thead = "thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
                    }
                    $menu .= '<TD class="'.$thead.'"><A HREF="'.$http_dir_name.'customers.php"><img width="16" height="16"  src="'.$http_dir_name.'images/group.png" border="0" ><br />'.str_replace(" ", "&nbsp;", $config_values['MENU_CUSTOMERS']).'</A>&nbsp;</TD>';
                    //=======================================================================================================

                    //=======================================================================================================
                    // Queues
                    //=======================================================================================================
                    if ($self == "/deletequeue.php" || $self == "/addqueue.php" || $self == "/queues.php" || $self == "/editqueue.php") {
                        $thead = "thead";
                    } else {
                        $thead = "thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
                    }
                    $menu .= '<TD class="'.$thead.'"><A HREF="'.$http_dir_name.'queues.php"><img width="16" height="16"  src="'.$http_dir_name.'images/database.png" border="0" ><br />'.str_replace(" ", "&nbsp;", $config_values['MENU_QUEUES']).'</A>&nbsp;</TD>';
                    //=======================================================================================================

                    //=======================================================================================================
                    // Servers
                    //=======================================================================================================
                    if ($self == "/deleteserver.php" || $self == "/addserver.php" || $self == "/servers.php" || $self == "/editserver.php") {
                        $thead = "thead";
                    } else {
                        $thead = "thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
                    }

                    $menu .= '<TD class="'.$thead.'"><A HREF="'.$http_dir_name.'servers.php"><img width="16" height="16"  src="'.$http_dir_name.'images/server.png" border="0" ><br />'.str_replace(" ", "&nbsp;", $config_values['MENU_SERVERS']).'</A>&nbsp;</TD>';
                    //=======================================================================================================


                    //=======================================================================================================
                    // Trunks
                    //=======================================================================================================
                    if ($self == "/trunks.php" || $self == "/edittrunk.php" || $self == "/addtrunk.php" || $self == "/setdefault.php" || $self == "/deletetrunk.php") {
                        $thead = "thead";
                    } else {
                        $thead = "thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
                    }
                    $menu .= '<TD class="'.$thead.'"><A HREF="'.$http_dir_name.'trunks.php"><img width="16" height="16"  src="'.$http_dir_name.'images/telephone_link.png" border="0" ><br />'.str_replace(" ", "&nbsp;", $config_values['MENU_TRUNKS']).'</A>&nbsp;</TD>';
                    //=======================================================================================================

                    //=======================================================================================================
                    // Timezones
                    //=======================================================================================================
                    if ($config_values['USE_TIMEZONES'] == "YES") {
                        if ($self == "/timezones.php") {
                            $thead = "thead";
                        } else {
                            $thead = "thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
                        }
                        $menu .= '<TD class="'.$thead.'"><A HREF="'.$http_dir_name.'timezones.php?view_timezones=1"><img width="16" height="16"  src="'.$http_dir_name.'images/world.png" border="0" ><br />'.str_replace(" ", "&nbsp;", $config_values['MENU_TIMEZONES']).'</A>&nbsp;</TD>';
                    }
                    //=======================================================================================================

                    //=======================================================================================================
                    // Admin
                    //=======================================================================================================
                    if ($self == "/config.php" || $self == "/setparameter.php") {
                        $thead = "thead";
                    } else {
                        $thead = "thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
                    }
                    $menu .= '<TD class="'.$thead.'"><A HREF="'.$http_dir_name.'config.php"><img width="16" height="16"  src="'.$http_dir_name.'images/cog.png" border="0" ><br />'.str_replace(" ", "&nbsp;", $config_values['MENU_ADMIN']).'</A>&nbsp;</TD>';
                    //=======================================================================================================

                }
                //    <TD class="thead2"><A HREF="prefs.php">Preferences</A>&nbsp;&nbsp;</TD>
            } else if ($level == sha1("level10")) {
                /*//=======================================================================================================
                 // Customers
                 //=======================================================================================================
                 if ($self=="/deletecustomer.php"||$self=="/addcustomer.php"||$self=="/customers.php"||$self=="/editcustomer.php"){
                 $thead="thead";
                 } else {
                 $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
                 }
                 $menu.='<TD class="'.$thead.'"><A HREF="/customers.php"><img width="16" height="16"  src="images/group.png" border="0" ><br />'.$config_values['MENU_CUSTOMERS'].'</A>&nbsp;</TD>';
                 //=======================================================================================================
                 */

                //echo "Billing Administrator Login";
                // This is for people who are logged in as a billing administrator
                //=======================================================================================================
                // Add Funds
                //=======================================================================================================
                if ($self == "/addfunds.php") {
                    $thead = "thead";
                } else {
                    $thead = "thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
                }
                $menu .= '<TD class="'.$thead.'"><A HREF="'.$http_dir_name.'addfunds.php"><img width="16" height="16"  src="'.$http_dir_name.'images/group.png" border="0" ><br />'.str_replace(" ", "&nbsp;", $config_values['MENU_ADDFUNDS']).'</A>&nbsp;</TD>';
                //=======================================================================================================

            }
            $thead = "thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";

            $menu .= '<TD height="1" class="'.$thead.'"><A HREF="'.$http_dir_name.'logout.php"><img width="16" height="16"  src="'.$http_dir_name.'images/door_in.png" border="0" ><br />'.str_replace(" ", "&nbsp;", $config_values['MENU_LOGOUT']).'</A>&nbsp;</TD><TD CLASS="theadr2" WIDTH=0></TD></TR></table>
        
        ';
        }
        return $menu;
    }
}
?>
