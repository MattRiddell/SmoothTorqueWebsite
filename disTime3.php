<?
include "admin/db_config.php";
/* Find out what the base directory name is for two reasons:
 1. So we can include files
 2. So we can explain how to set up things that are missing */
$current_directory = dirname(__FILE__);

/* What page we are currently on - this is used to highlight the menu
 system as well as to not cache certain pages like the graphs */
$self = $_SERVER['PHP_SELF'];

/* Load in the functions we may need - these are the list of available
 custom functions - for more information, read the comments in the
 functions.php file - most functions are in their own file in the
 functions subdirectory */
require "/".$current_directory."/functions/functions.php";

/* If we have no language set, let's use English - this is mainly because
 * header.php is also called from index.php where we couldn't possibly
 * know the language.
 */
if ((!(isset($_COOKIE['language']))) || $_COOKIE['language'] == "--") {
    $_COOKIE['language'] = "en";
}
/* Same goes for the server name */
if ($_COOKIE['url'] == "--") {
    $_COOKIE['url'] = $_SERVER['SERVER_NAME'];
}

/* Set a variable so we don't need to keep reading the cookies */
$url = $_COOKIE['url'];

/* We now have a language and a server name */
$result_config = mysql_query("SELECT * FROM web_config WHERE LANG = ".sanitize($_COOKIE['language'])." AND url = ".sanitize($url)) or die(mysql_error());
if (mysql_num_rows($result_config) == 0) {
    /* No entry found for this url - use the default */
    $sql = "SELECT * FROM web_config WHERE LANG = ".sanitize($_COOKIE['language'])." AND url = 'default'";
    $result_config = mysql_query($sql) or die("Unable to load config information from mysql: ".mysql_error());
}


mysql_select_db("SineDialer", $link) or die("Unable to connect: ".mysql_error());
$totalcost = array();
//echo "Loading config information...\n";
$result_config = mysql_query("SELECT * FROM web_config WHERE LANG = 'en' AND url = '$url'") or die(mysql_error());
if (mysql_num_rows($result_config) == 0) {
    echo "Even though we were sucessful reading the config, it has no values.  Please send an email to smoothtorque@venturevoip.com";
    exit(0);
}
/* Now that we have the config values, put them into the array */
while ($header_row = mysql_fetch_assoc($result_config)) {
    foreach ($header_row as $key => $value) {
        if ($key != "contact_text") {
            $config_values[strtoupper($key)] = $value;
        } else {
            $config_values["TEXT"] = $value;
        }
    }
}

$sql = 'SELECT value FROM config WHERE parameter=\'SHOW_NUMBERS_LEFT\'';
$result = @mysql_query($sql, $link);
if ($result) {
    if (mysql_num_rows($result) > 0) {
        $config_values['SHOW_NUMBERS_LEFT'] = mysql_result($result, 0, 'value');
    }
}

$sql = 'SELECT value FROM config WHERE parameter=\'USE_TIMEZONES\'';
$result = @mysql_query($sql, $link);
if ($result) {
    if (mysql_num_rows($result) > 0) {
        $config_values['USE_TIMEZONES'] = mysql_result($result, 0, 'value');
    }
}

$sql = 'SELECT value FROM config WHERE parameter=\'DELETE_ALL\'';
$result = @mysql_query($sql, $link);
if ($result) {
    if (mysql_num_rows($result) > 0) {
        $config_values['DELETE_ALL'] = mysql_result($result, 0, 'value');
    }
}

$sql = 'SELECT value FROM config WHERE parameter=\'LEAVE_PRESS1\'';
$result = @mysql_query($sql, $link);
if ($result) {
    if (mysql_num_rows($result) > 0) {
        $config_values['LEAVE_PRESS1'] = mysql_result($result, 0, 'value');
    }
}

$sql = 'SELECT value FROM config WHERE parameter=\'test_number\'';
$result = @mysql_query($sql, $link);
if ($result) {
    if (mysql_num_rows($result) > 0) {
        $config_values['test_number'] = mysql_result($result, 0, 'value');
    }
}

$sql = 'SELECT value FROM config WHERE parameter=\'use_new_pie\'';
$result = mysql_query($sql, $link) or die (mysql_error());
$use_new_pie = 0;
if (mysql_num_rows($result) > 0) {
    $use_new_pie = mysql_result($result, 0, 'value');
}


if (isset($_GET['campaigngroupid'])) {
    $campaigngroupid = ($_GET['campaigngroupid']);
} else {
    $sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE['user'].'\'';
    $result = mysql_query($sql, $link) or die (mysql_error());;
    $campaigngroupid = mysql_result($result, 0, 'campaigngroupid');
}
if (isset($_POST['id'])) {
    $id = $_POST['id'];
}
mysql_select_db("SineDialer", $link);
$sql = 'SELECT value FROM config WHERE parameter=\'backend\'';
$result = mysql_query($sql, $link) or die (mysql_error());;
$row = mysql_fetch_assoc($result);
$backend = $row['value'];
$level = $_COOKIE['level'];
if ($level == sha1("level100") && $_GET['type'] == "all") {
    $sql = 'SELECT * FROM campaign order by groupid, name';
} else {
    $sql = 'SELECT * FROM campaign WHERE groupid='.$campaigngroupid.' order by groupid, name';
}
$result = mysql_query($sql, $link) or die ("1: ".mysql_error()." FROM ".$sql);;
if (mysql_num_rows($result) == 0) {
    ?>
    <br/><br/>
    <? box_start();
    echo "<br /><center><img src=\"images/icons/gtk-dialog-info.png\" border=\"0\" width=\"64\" height=\"64\"><br /><br />";
    ?>

    <b>You don't have any campaigns created.</b><br/>
    <br/>
    A campaign is a collection of phone <br/>numbers you would like to call.<br/>
    <br/>
    <a href="addcampaign.php">
        <img src="images/icons/gtk-add.png" border="0" width="64" height="64"><br/>
        Click here to create your first campaign</a><br/> or click the Add Campaign button above.
    <br/>
    <br/>
    <?
    //'
    box_end();
    exit(0);
}
?>
<? box_start(580); ?>
<center>
    <b>Key for the icons below:</b><br/>
    <img width="16" height="16" src="images/pencil.png" border="0">&nbsp;Edit Campaign&nbsp;
    <img width="16" height="16" src="images/chart_pie.png" border="0">&nbsp;View Number Statistics&nbsp;
    <img width="16" height="16" src="images/control_stop_blue.png" border="0"> Stop Campaign
    <img width="16" height="16" src="images/control_play_blue.png" border="0"> Start Campaign
    <br/>
    <? if ($config_values['ALLOW_NUMBERS_MANUAL'] == "YES") { ?>
        <img width="16" height="16" src="images/database_lightning.png" border="0"> Initialise Manual Dialing
    <? } ?>
    <img width="16" height="16" src="images/arrow_refresh.png" border="0"> Recycle Numbers
    <img width="16" height="16" src="images/chart_curve.png" border="0"> Realtime Campaign Monitor<br/>
    <img width="16" height="16" src="images/table.png" border="0"> View Numbers
    <img width="16" height="16" src="images/delete.png" border="0"> Delete Campaign
    <? if ($config_values['DELETE_ALL'] == "YES") { ?>
        <img width="16" height="16" src="images/page_white_delete.png" border="0"> Delete All Numbers
    <? } ?>
    <? if (strlen($config_values['test_number']) > 0) { ?>
        <img width="16" height="16" src="images/cog.png" border="0"> Add Test Number to Campaign
    <? } ?>

</center>
<? box_end(); ?><br/>

<?
$user = $_COOKIE['user'];
?>
<table class="table table-striped" align="center" border="0" cellpadding="2" cellspacing="0">
    <thead>
    <TR>

        <th></th>

        <th CLASS="">
            <? if ($level == sha1("level100") && $_GET['type'] == "all") { ?>
                Name<br/>(Account)
            <? } else { ?>
                Name
            <? } ?>
        </th>
        <th CLASS="">
            Description
        </th>
        <th CLASS="">
        </th>
        <th CLASS="">

        </th>
        <th CLASS="">

        </th>
        <th CLASS="">

        </th>
        <? if ($config_values['USE_BILLING'] == "YES") { ?>
            <th CLASS="">
                Cost
            </th>
        <? } ?>


        <th CLASS="">
            Percentage Busy
        </th>
        <th></th>
    </thead>
    </TR>
    <?
    $toggle = FALSE;
    while ($row = mysql_fetch_assoc($result)) {

        $sql = 'SELECT status, flags, maxcalls, progress from queue where campaignid='.$row['id'];
        $resultx = mysql_query($sql, $link) or die (mysql_error());;
        $rowx = mysql_fetch_assoc($resultx);

        $status = $rowx['status'];
        $flags = $rowx['flags'];
        $maxcalls = $rowx['maxcalls'];
        $progress = $rowx['progress'];


        flush();
        $row = @array_map(stripslashes, $row);
        if ($status == 101) {
            //$class = " class=\"tborder_active\"  onmouseover=\"style.backgroundColor='#84DFC1';\" onmouseout=\"style.backgroundColor='#88f888'\"   ";
        } else if ($toggle) {
            $toggle = FALSE;
            //$class=" class=\"tborder2\"  onmouseover=\"style.backgroundColor='#84DFC1';\" onmouseout=\"style.backgroundColor='#f8f8f8'\"   ";
        } else {
            $toggle = TRUE;
            //$class=" class=\"tborderx\"  onmouseover=\"style.backgroundColor='#84DFC1';\" onmouseout=\"style.backgroundColor='#f0f0f0'\" ";
        }

        ?>
        <TR <? echo $class; ?>>
            <td></td>
            <TD>
                <?
                if (strlen($row['name']) < 22) {
                    echo "<A class=\"btn btn-default\" title=\"Edit this campaign\" HREF=\"editcampaign.php?id=".$row['id']."\"><img width=\"16\" height=\"16\" src=\"images/pencil.png\" border=\"0\" align=\"right\" title=\"Edit This Campaign\">".$row['name']."</A>";
                } else {
                    echo "<A class=\"btn btn-default\" title=\"Edit this campaign\" HREF=\"editcampaign.php?id=".$row['id']."\"><img width=\"16\" height=\"16\" src=\"images/pencil.png\" border=\"0\" align=\"right\" title=\"Edit This Campaign\">".trim(substr($row['name'], 0, 18))."...</A>";
                }
                if ($level == sha1("level100") && $_GET['type'] == "all") {
                    $result_acct = mysql_query("SELECT username FROM customer WHERE campaigngroupid = ".$row['groupid']);
                    if (mysql_num_rows($result_acct) == 0) {
                        $acct = "Unknown";
                    } else {
                        $acct = mysql_result($result_acct, 0, 0);
                    }
                    echo "<br />($acct)";
                }
                ?>
            </TD>
            <TD>
                <?
                $max_str_len = 25;

                if (strlen($row['description']) < $max_str_len) {
                    echo $row['description'];
                } else {
                    echo trim(substr($row['description'], 0, $max_str_len))."...";
                }
                ?>
            </TD>
            <?
            //if ($config_values['USE_TIMEZONES'] == 'YES') {
            //    $extra_sql = " AND NOW() between start_time and end_time ";
            //} else {
            $extra_sql = "";
            //}
            if ($config_values['SHOW_NUMBERS_LEFT'] == 'YES' && $_GET['show_numbers'] == 1) {
                $sql = 'SELECT count(*) from number where campaignid='.$row['id'].' and (status="manual_dial" or status="new" or status="new_nodial" or status="no-credit") '.$extra_sql;
                $result2 = mysql_query($sql, $link) or die (mysql_error());;
                $new_numbers = mysql_result($result2, 0, 'count(*)');

                $sql = 'SELECT count(*) from number where campaignid='.$row['id'].' and (status="manual_dial" or status="no-credit") '.$extra_sql;
                $result2 = mysql_query($sql, $link) or die (mysql_error());;
                $manual_numbers = mysql_result($result2, 0, 'count(*)');

                $sql = 'SELECT count(*) from number where campaignid='.$row['id'];
                $result2 = mysql_query($sql, $link) or die (mysql_error());;
                $total_numbers = mysql_result($result2, 0, 'count(*)');

                $sql = 'SELECT count(*) from number where campaignid='.$row['id']." AND status = 'new' and NOT (TIME(NOW()) between start_time and end_time)";
                $result2 = mysql_query($sql, $link) or die (mysql_error());;
                $out_of_tz = mysql_result($result2, 0, 'count(*)');

                $sql = 'Select count(*) from number where status=\'new\' and campaignid = '.$row['id']." and TIME(NOW()) between start_time and end_time";
                $result2 = mysql_query($sql, $link) or die (mysql_error());;
                $in_tz = mysql_result($result2, 0, 'count(*)');

                // ;

                if ($config_values['USE_TIMEZONES'] == 'YES') {
                    $tz = " ($out_of_tz out of Time Zone - $in_tz in TZ) ";
                } else {
                    $tz = "";
                }

                //$tz = "";
            }


            ?>
            <TD width="100">
                <?

                if ($config_values['SHOW_NUMBERS_LEFT'] == 'YES'&& $_GET['show_numbers'] == 1) {
                    if ($isApple) {
                        echo "Remaining: $new_numbers/$total_numbers $tz";
                    }
                    if ($total_numbers == 0) {
                        $perc = 0;
                    } else {

                        $perc = round((((($new_numbers / $total_numbers) * 100)) * 1) - 1, 0);
                    }


                    ?>

                    </div>
                    <div class="progress">
                        <div data-toggle="tooltip" data-placement="left" title="(<? echo "Remaining: $new_numbers/$total_numbers $tz"; ?>)" class="progress-bar <? if ($perc == 0) { ?>progress-bar-danger<? } ?>" role="progressbar" aria-valuenow="<?= $perc ?>" aria-valuemin="0" aria-valuemax="100" style="min-width: 4em;width: <?= $perc ?>%;">
                            <?= $perc ?>%
                        </div>
                    </div>
                    <?

                } else if ($config_values['SHOW_NUMBERS_LEFT'] == 'YES') {
                    function url_origin($s, $use_forwarded_host = FALSE) {
                        $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on');
                        $sp = strtolower($s['SERVER_PROTOCOL']);
                        $protocol = substr($sp, 0, strpos($sp, '/')).(($ssl) ? 's' : '');
                        $port = $s['SERVER_PORT'];
                        $port = ((!$ssl && $port == '80') || ($ssl && $port == '443')) ? '' : ':'.$port;
                        $host = ($use_forwarded_host && isset($s['HTTP_X_FORWARDED_HOST'])) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : NULL);
                        $host = isset($host) ? $host : $s['SERVER_NAME'].$port;
                        return $protocol.'://'.$host;
                    }

                    function full_url($s, $use_forwarded_host = FALSE) {
                        return url_origin($s, $use_forwarded_host).$s['REQUEST_URI'];
                    }

                    $absolute_url = full_url($_SERVER);
                    if (strpos($absolute_url, "?") ===  FALSE) {
                        $absolute_url .= "?x=1";
                    }
                    //echo ;
                    //echo "x";
                    ?>
                    <a href="<?=$absolute_url?>&show_numbers=1" class="btn btn-primary">Show Numbers</a>
                    <?
                }
                ?>
            </TD>


            <TD>

                <?
                if ($maxcalls > 0) {
                    $perc = round(($flags / $maxcalls) * 100);
                } else {
                    $perc = 0;
                }
                if ($perc > 100) {
                    $perc = 100;
                }
                if ($status == 101){
                ?>
                <a class="btn btn-default disabled" href="#" title="Start campaign (Already started)"><IMG width="16" height="16" SRC="images/control_play_blue.png" BORDER="0"></a>
            </TD>
        <td>
        <? if ($user != "demo") {
            ?>
            <a class="btn btn-danger" title="Stop running this campaign" href="stopcampaign.php?id=<? echo $row['id']; ?>"><img width="16" height="16" src="images/control_stop_blue.png" border="0"></a>
        <? } else { ?>
            <a href="#" class="btn btn-default disabled" title="Stop campaign (Not running)"><img width="16" height="16" src="images/control_stop_blue.png" border="0"></a>
            <?
        }
        } else {

        ?>
        <? if ($user != "demo") { ?>
            <a class="btn btn-success" title="Start running this campaign" href="startcampaign.php?id=<? echo $row['id']; ?>&astqueuename=<? echo $row['astqueuename']; ?>&clid=<? echo $row['clid']; ?>&trclid=<? echo $row['trclid']; ?>&agents=<? echo $row['maxagents']; ?>&did=<? echo $row['did']; ?>&context=<? echo $row['context']; ?>&drive_min=<?= $row['drive_min'] ?>&drive_max=<?= $row['drive_max'] ?>">
                <IMG width="16" height="16" SRC="images/control_play_blue.png" BORDER="0"></a><br>
        <? } else { ?>
            <a class="btn btn-default disabled" href="#" title="Start campaign (Already started)"><IMG width="16" height="16" SRC="images/control_play_blue.png" BORDER="0"></a>
            <br>
            <?
        } ?>
        </TD>
            <td>
                <a href="#" class="btn btn-default disabled"><img width="16" height="16" src="images/control_stop.png" border="0" title="Stop running campaign"></a>
                <?
                }

                if (strlen($config_values['test_number']) > 0) { ?>
                    <a class="btn btn-default" href="add_test_number.php?id=<?= $row['id'] ?>&type=<?= $_GET['type'] ?>" title="Add test number to this campaign"><img width="16" height="16" src="images/cog.png" border="0"></a>
                    <?
                }

                if ($config_values['ALLOW_NUMBERS_MANUAL'] == "YES") {
                    ?>
                    <a class="btn btn-default" href="manual_init.php?campaignid=<?= $row['id'] ?>">
                        <img width="16" height="16" src="images/database_lightning.png" border="0" title="Initialise campaign for manual dialing">
                    </a>
                    <?
                }

                if ($config_values['DELETE_ALL'] == "YES") { ?>
                    <a class="btn btn-default" href="recycle.php?type=deleteall&id=<?= $row['id'] ?>">
                        <img width="16" height="16" src="images/page_white_delete.png" border="0" title="Delete all numbers">
                    </a>
                    <?
                }
                ?>
            </td>
            <TD>
                <? if ($backend == 0) { ?>
                    <a class="btn btn-default" title="View the report for this campaign" href="report<? if ($use_new_pie == 1) {
                        echo "2";
                    } ?>.php?id=<? echo $row['id']; ?>" class="abcd"><img width="16" height="16" src="images/chart_pie.png" border="0"></a>
                    <a class="btn btn-default" title="View the graph for this campaign" href="test.php?id=<? echo $row['id']; ?>" class="abcd"><img width="16" height="16" src="images/chart_curve.png" border="0"></a>&nbsp;
                    <?
                } ?>
                <a class="btn btn-default" title="Recycle Numbers" href="recycle.php?id=<? echo $row['id']; ?>&type_input=<? echo $_GET['type']; ?>" class="abcd"><img width="16" height="16" src="images/arrow_refresh.png" border="0"></a>&nbsp;
                <a class="btn btn-default" title="List Numbers" href="viewnumbers.php?campaignid=<? echo $row['id']; ?>" class="abcd"><img width="16" height="16" src="images/table.png" border="0"></a>&nbsp;
                <?
                if ($user != "demo") {
                    echo "<A class=\"btn btn-default\" title=\"Delete this campaign\" HREF=\"deletecampaign.php?id=".$row['id']."\"><IMG width=\"16\" height=\"16\" SRC=\"images/delete.png\" BORDER=\"0\"></A>";
                } else {
                    echo "<A class=\"btn btn-default\" title=\"Delete this campaign\" HREF=\"#\"><IMG SRC=\"images/delete.png\" BORDER=\"0\" width=\"16\" height=\"16\" ></A>";
                }
                ?>
            </TD>
            <?
            if ($config_values['USE_BILLING'] == "YES") { ?>
                <TD>
                    <?
                    if ($row['cost'] > 0) {
                        echo '<A class="btn btn-default" HREF="viewcdr_campaign.php?campaignid='.$row['id'].'">';
                        echo $config_values['CURRENCY_SYMBOL']." ".number_format($row['cost'], 2)."</a>";
                    } else {
                        echo "-";
                    }
                    ?>
                </TD>
                <?
            } ?>

            <td>


                <div class="progress">
                    <div class="progress-bar <? if ($perc == 0) { ?>progress-bar-danger<?
                    } ?>" role="progressbar" aria-valuenow="<?= $perc ?>" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em;width: <?= $perc ?>%;">
                        <?= $perc ?>%
                    </div>
                </div>


            </td>

            <td></td>
        </TR>

        <?
    }
    ?>

</TABLE>
<?

?>
