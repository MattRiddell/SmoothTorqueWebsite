<?
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
$level = $_COOKIE[level];
$language = $_COOKIE[language];
$url = $_COOKIE[url];
include "admin/db_config.php";

$current_directory = dirname(__FILE__);
require "/".$current_directory."/functions/functions.php";

if ($level != sha1("level100")) {
    include "header.php";
    $ip = $_SERVER['REMOTE_ADDR'];
    echo "Attempted break in attempt from $ip ($_COOKIE[user])";
    /*================= Log Access ======================================*/
    $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_COOKIE[user]', ' $ip attempted to view the admin page')";
    $result = mysql_query($sql, $link);
    /*================= Log Access ======================================*/

} else {
    if (isset($_GET[delete_server])) {
        $server = sanitize($_GET[delete_server]);
        $lang = sanitize($_GET[LANG]);
        $sql = "DELETE FROM web_config WHERE url = $server AND LANG = $lang";
        mysql_query($sql);
        unset($_POST[colour]);
    }
    if (strlen($_POST[url_to_add]) > 0) {
        $copy_from = sanitize($_POST[copy_from]);
        $url_to_write = sanitize($_POST[url_to_add]);
        $result = mysql_query("SELECT * FROM web_config WHERE url = $copy_from");
        while ($row = mysql_fetch_assoc($result)) {
            $sql1 = "INSERT INTO web_config (url, ";
            $sql2 = ") VALUES ($url_to_write, ";
            foreach ($row as $field => $value) {
                if ($field != "url") {
                    $sql1 .= $field.",";
                    $sql2 .= sanitize($value).",";
                }
            }
            $sql = substr($sql1, 0, strlen($sql1) - 1).substr($sql2, 0, strlen($sql2) - 1).")";
            $resultx = mysql_query($sql);
        }
        unset($_POST[colour]);
    }

    //echo $_POST[sox];
    //print_r($_POST);
    //exit(0);
    if (isset($_POST[colour])) {
        mysql_query("UPDATE web_config SET language=".sanitize($_POST[LANGUAGE])." WHERE url = ".sanitize($url)." and LANG = ".sanitize($language));
        mysql_query("UPDATE web_config SET colour=".sanitize($_POST[colour])." WHERE url = ".sanitize($url));
        mysql_query("UPDATE web_config SET title=".sanitize($_POST[title])." WHERE url = ".sanitize($url)." and LANG = ".sanitize($language));
        mysql_query("UPDATE web_config SET logo=".sanitize($_POST[logo])." WHERE url = ".sanitize($url));
        mysql_query("UPDATE web_config SET contact_text=".sanitize($_POST[text])." WHERE url = ".sanitize($url)." and LANG = ".sanitize($language));
        mysql_query("UPDATE web_config SET sox=".sanitize($_POST[sox])." WHERE url = ".sanitize($url));
        mysql_query("UPDATE web_config SET userid=".sanitize($_POST[userid])." WHERE url = ".sanitize($url));
        mysql_query("UPDATE web_config SET licence=".sanitize($_POST[licence])." WHERE url = ".sanitize($url));
        mysql_query("UPDATE web_config SET cdr_host=".sanitize($_POST[CDR_HOST])." WHERE url = ".sanitize($url));
        mysql_query("UPDATE web_config SET cdr_user=".sanitize($_POST[CDR_USER])." WHERE url = ".sanitize($url));
        mysql_query("UPDATE web_config SET cdr_pass=".sanitize($_POST[CDR_PASS])." WHERE url = ".sanitize($url));
        mysql_query("UPDATE web_config SET cdr_db=".sanitize($_POST[CDR_DB])." WHERE url = ".sanitize($url));
        mysql_query("UPDATE web_config SET cdr_table=".sanitize($_POST[CDR_TABLE])." WHERE url = ".sanitize($url));
        mysql_query("UPDATE web_config SET menu_home=".sanitize($_POST[MENU_HOME])." WHERE url = ".sanitize($url)." and LANG = ".sanitize($language));
        mysql_query("UPDATE web_config SET menu_campaigns=".sanitize($_POST[MENU_CAMPAIGNS])." WHERE url = ".sanitize($url)." and LANG = ".sanitize($language));
        mysql_query("UPDATE web_config SET menu_numbers=".sanitize($_POST[MENU_NUMBERS])." WHERE url = ".sanitize($url)." and LANG = ".sanitize($language));
        mysql_query("UPDATE web_config SET menu_dnc=".sanitize($_POST[MENU_DNC])." WHERE url = ".sanitize($url)." and LANG = ".sanitize($language));
        mysql_query("UPDATE web_config SET menu_messages=".sanitize($_POST[MENU_MESSAGES])." WHERE url = ".sanitize($url)." and LANG = ".sanitize($language));
        mysql_query("UPDATE web_config SET menu_schedules=".sanitize($_POST[MENU_SCHEDULES])." WHERE url = ".sanitize($url)." and LANG = ".sanitize($language));
        mysql_query("UPDATE web_config SET menu_customers=".sanitize($_POST[MENU_CUSTOMERS])." WHERE url = ".sanitize($url)." and LANG = ".sanitize($language));
        mysql_query("UPDATE web_config SET menu_cdr=".sanitize($_POST[MENU_CDR])." WHERE url = ".sanitize($url)." and LANG = ".sanitize($language));
        mysql_query("UPDATE web_config SET menu_surveys=".sanitize($_POST[MENU_SURVEYS])." WHERE url = ".sanitize($url)." and LANG = ".sanitize($language));
        mysql_query("UPDATE web_config SET menu_queues=".sanitize($_POST[MENU_QUEUES])." WHERE url = ".sanitize($url)." and LANG = ".sanitize($language));
        mysql_query("UPDATE web_config SET menu_servers=".sanitize($_POST[MENU_SERVERS])." WHERE url = ".sanitize($url)." and LANG = ".sanitize($language));
        mysql_query("UPDATE web_config SET menu_trunks=".sanitize($_POST[MENU_TRUNKS])." WHERE url = ".sanitize($url)." and LANG = ".sanitize($language));
        mysql_query("UPDATE web_config SET menu_admin=".sanitize($_POST[MENU_ADMIN])." WHERE url = ".sanitize($url)." and LANG = ".sanitize($language));
        mysql_query("UPDATE web_config SET menu_logout=".sanitize($_POST[MENU_LOGOUT])." WHERE url = ".sanitize($url)." and LANG = ".sanitize($language));
        mysql_query("UPDATE web_config SET date_colour=".sanitize($_POST[DATE_COLOUR])." WHERE url = ".sanitize($url));
        mysql_query("UPDATE web_config SET main_page_text=".sanitize($_POST[MAIN_PAGE_TEXT])." WHERE url = ".sanitize($url)." and LANG = ".sanitize($language));
        mysql_query("UPDATE web_config SET main_page_username=".sanitize($_POST[MAIN_PAGE_USERNAME])." WHERE url = ".sanitize($url)." and LANG = ".sanitize($language));
        mysql_query("UPDATE web_config SET main_page_password=".sanitize($_POST[MAIN_PAGE_PASSWORD])." WHERE url = ".sanitize($url)." and LANG = ".sanitize($language));
        mysql_query("UPDATE web_config SET main_page_login=".sanitize($_POST[MAIN_PAGE_LOGIN])." WHERE url = ".sanitize($url)." and LANG = ".sanitize($language));
        mysql_query("UPDATE web_config SET currency_symbol=".sanitize($_POST[CURRENCY_SYMBOL])." WHERE url = ".sanitize($url)." and LANG = ".sanitize($language));
        mysql_query("UPDATE web_config SET per_minute=".sanitize($_POST[PER_MINUTE])." WHERE url = ".sanitize($url)." and LANG = ".sanitize($language));
        mysql_query("UPDATE web_config SET use_billing=".sanitize($_POST[USE_BILLING])." WHERE url = ".sanitize($url));
        mysql_query("UPDATE web_config SET front_page_billing=".sanitize($_POST[FRONT_PAGE_BILLING])." WHERE url = ".sanitize($url));
        mysql_query("UPDATE web_config SET spare1=".sanitize($_POST[SPARE1])." WHERE url = ".sanitize($url));
        mysql_query("UPDATE web_config SET spare2=".sanitize($_POST[SPARE2])." WHERE url = ".sanitize($url));
        mysql_query("UPDATE web_config SET spare3=".sanitize($_POST[SPARE3])." WHERE url = ".sanitize($url));
        mysql_query("UPDATE web_config SET spare4=".sanitize($_POST[SPARE4])." WHERE url = ".sanitize($url));
        mysql_query("UPDATE web_config SET spare5=".sanitize($_POST[SPARE5])." WHERE url = ".sanitize($url));
        mysql_query("UPDATE web_config SET add_campaign=".sanitize($_POST[ADD_CAMPAIGN])." WHERE url = ".sanitize($url)." and LANG = ".sanitize($language));
        mysql_query("UPDATE web_config SET view_campaign=".sanitize($_POST[VIEW_CAMPAIGN])." WHERE url = ".sanitize($url)." and LANG = ".sanitize($language));
        mysql_query("UPDATE web_config SET per_page=".sanitize($_POST[PER_PAGE])." WHERE url = ".sanitize($url));
        mysql_query("UPDATE web_config SET numbers_view=".sanitize($_POST[NUMBERS_VIEW])." WHERE url = ".sanitize($url)." and LANG = ".sanitize($language));
        mysql_query("UPDATE web_config SET numbers_system=".sanitize($_POST[NUMBERS_SYSTEM])." WHERE url = ".sanitize($url)." and LANG = ".sanitize($language));
        mysql_query("UPDATE web_config SET numbers_generate=".sanitize($_POST[NUMBERS_GENERATE])." WHERE url = ".sanitize($url)." and LANG = ".sanitize($language));
        mysql_query("UPDATE web_config SET numbers_manual=".sanitize($_POST[NUMBERS_MANUAL])." WHERE url = ".sanitize($url)." and LANG = ".sanitize($language));
        mysql_query("UPDATE web_config SET numbers_upload=".sanitize($_POST[NUMBERS_UPLOAD])." WHERE url = ".sanitize($url)." and LANG = ".sanitize($language));
        mysql_query("UPDATE web_config SET numbers_export=".sanitize($_POST[NUMBERS_EXPORT])." WHERE url = ".sanitize($url)." and LANG = ".sanitize($language));
        mysql_query("UPDATE web_config SET numbers_title=".sanitize($_POST[NUMBERS_TITLE])." WHERE url = ".sanitize($url)." and LANG = ".sanitize($language));
        mysql_query("UPDATE web_config SET billing_text=".sanitize($_POST[BILLING_TEXT])." WHERE url = ".sanitize($url)." and LANG = ".sanitize($language));
        mysql_query("UPDATE web_config SET cdr_text=".sanitize($_POST[CDR_TEXT])." WHERE url = ".sanitize($url)." and LANG = ".sanitize($language));
        mysql_query("UPDATE web_config SET use_generate=".sanitize($_POST[USE_GENERATE])." WHERE url = ".sanitize($url));
        mysql_query("UPDATE web_config SET dnc_numbers_title=".sanitize($_POST[DNC_NUMBERS_TITLE])." WHERE url = ".sanitize($url)." and LANG = ".sanitize($language));
        mysql_query("UPDATE web_config SET dnc_view=".sanitize($_POST[DNC_VIEW])." WHERE url = ".sanitize($url)." and LANG = ".sanitize($language));
        mysql_query("UPDATE web_config SET dnc_search=".sanitize($_POST[DNC_SEARCH])." WHERE url = ".sanitize($url)." and LANG = ".sanitize($language));
        mysql_query("UPDATE web_config SET dnc_upload=".sanitize($_POST[DNC_UPLOAD])." WHERE url = ".sanitize($url)." and LANG = ".sanitize($language));
        mysql_query("UPDATE web_config SET dnc_add=".sanitize($_POST[DNC_ADD])." WHERE url = ".sanitize($url)." and LANG = ".sanitize($language));
        mysql_query("UPDATE web_config SET per_lead=".sanitize($_POST[PER_LEAD])." WHERE url = ".sanitize($url)." and LANG = ".sanitize($language));
        mysql_query("UPDATE web_config SET smtp_host=".sanitize($_POST[SMTP_HOST])." WHERE url = ".sanitize($url));
        mysql_query("UPDATE web_config SET smtp_user=".sanitize($_POST[SMTP_USER])." WHERE url = ".sanitize($url));
        mysql_query("UPDATE web_config SET smtp_pass=".sanitize($_POST[SMTP_PASS])." WHERE url = ".sanitize($url));
        mysql_query("UPDATE web_config SET smtp_from=".sanitize($_POST[SMTP_FROM])." WHERE url = ".sanitize($url));
        mysql_query("UPDATE web_config SET use_separate_dnc=".sanitize($_POST[USE_SEPARATE_DNC])." WHERE url = ".sanitize($url));
        mysql_query("UPDATE web_config SET allow_numbers_manual=".sanitize($_POST[ALLOW_NUMBERS_MANUAL])." WHERE url = ".sanitize($url));

        $sql = "REPLACE INTO config (parameter, value) VALUES ('expected_rate',".sanitize($_POST['expected_rate']).")";
        mysql_query($sql) or die(mysql_error());

        $sql = "REPLACE INTO config (parameter, value) VALUES ('brand',".sanitize($_POST['brand']).")";
        mysql_query($sql) or die(mysql_error());

        $sql = "REPLACE INTO config (parameter, value) VALUES ('use_new_pie',".sanitize($_POST['use_new_pie']).")";
        mysql_query($sql) or die(mysql_error());

        $sql = "REPLACE INTO config (parameter, value) VALUES ('show_front_page_title',".sanitize($_POST['show_front_page_title']).")";
        mysql_query($sql) or die(mysql_error());

        $sql = "REPLACE INTO config (parameter, value) VALUES ('show_front_page_text',".sanitize($_POST['show_front_page_text']).")";
        mysql_query($sql) or die(mysql_error());


        $sql = "REPLACE INTO config (parameter, value) VALUES ('logo_width',".sanitize($_POST['logo_width']).")";
        mysql_query($sql) or die(mysql_error());


        $sql = "REPLACE INTO config (parameter, value) VALUES ('logo_height',".sanitize($_POST['logo_height']).")";
        mysql_query($sql) or die(mysql_error());

        $sql = "REPLACE INTO config (parameter, value) VALUES ('strict_credit_limit',".sanitize($_POST['strict_credit_limit']).")";
        mysql_query($sql) or die(mysql_error());

        $sql = "REPLACE INTO config (parameter, value) VALUES ('disable_all_types',".sanitize($_POST['disable_all_types']).")";
        mysql_query($sql) or die(mysql_error());

        $sql = "REPLACE INTO config (parameter, value) VALUES ('SHOW_NUMBERS_LEFT',".sanitize($_POST['SHOW_NUMBERS_LEFT']).")";
        mysql_query($sql) or die(mysql_error());

        $sql = "REPLACE INTO config (parameter, value) VALUES ('EVERGREEN',".sanitize($_POST['EVERGREEN']).")";
        mysql_query($sql) or die(mysql_error());

        $sql = "REPLACE INTO config (parameter, value) VALUES ('USE_TIMEZONES',".sanitize($_POST['USE_TIMEZONES']).")";
        mysql_query($sql) or die(mysql_error());

        $sql = "REPLACE INTO config (parameter, value) VALUES ('CDR_USE_STATE',".sanitize($_POST['CDR_USE_STATE']).")";
        mysql_query($sql) or die(mysql_error());

        $sql = "REPLACE INTO config (parameter, value) VALUES ('NUMBER_EXHAUSTION',".sanitize($_POST['NUMBER_EXHAUSTION']).")";
        mysql_query($sql) or die(mysql_error());

        $sql = "REPLACE INTO config (parameter, value) VALUES ('DISABLE_RECYCLE_ALL',".sanitize($_POST['DISABLE_RECYCLE_ALL']).")";
        mysql_query($sql) or die(mysql_error());

        $sql = "REPLACE INTO config (parameter, value) VALUES ('DISABLE_MESSAGE_UPLOAD',".sanitize($_POST['DISABLE_MESSAGE_UPLOAD']).")";
        mysql_query($sql) or die(mysql_error());

        $sql = "REPLACE INTO config (parameter, value) VALUES ('DISABLE_SURVEYS',".sanitize($_POST['DISABLE_SURVEYS']).")";
        mysql_query($sql) or die(mysql_error());

        $sql = "REPLACE INTO config (parameter, value) VALUES ('MENU_TIMEZONES',".sanitize($_POST['MENU_TIMEZONES']).")";
        mysql_query($sql) or die(mysql_error());

        $sql = "REPLACE INTO config (parameter, value) VALUES ('DELETE_ALL',".sanitize($_POST['DELETE_ALL']).")";
        mysql_query($sql) or die(mysql_error());

        $sql = "REPLACE INTO config (parameter, value) VALUES ('LEAVE_PRESS1',".sanitize($_POST['LEAVE_PRESS1']).")";
        mysql_query($sql) or die(mysql_error());

        $sql = "REPLACE INTO config (parameter, value) VALUES ('use_names',".sanitize($_POST['use_names']).")";
        mysql_query($sql) or die(mysql_error());

        $sql = "REPLACE INTO config (parameter, value) VALUES ('sugar_user',".sanitize($_POST['sugar_user']).")";
        mysql_query($sql) or die(mysql_error());

        $sql = "REPLACE INTO config (parameter, value) VALUES ('sugar_host',".sanitize($_POST['sugar_host']).")";
        mysql_query($sql) or die(mysql_error());

        $sql = "REPLACE INTO config (parameter, value) VALUES ('sugar_pass',".sanitize($_POST['sugar_pass']).")";
        mysql_query($sql) or die(mysql_error());

        $sql = "REPLACE INTO config (parameter, value) VALUES ('sugar_db',".sanitize($_POST['sugar_db']).")";
        mysql_query($sql) or die(mysql_error());

        $sql = "REPLACE INTO config (parameter, value) VALUES ('configurable_drive',".sanitize($_POST['configurable_drive']).")";
        mysql_query($sql) or die(mysql_error());

        $sql = "REPLACE INTO config (parameter, value) VALUES ('configurable_target',".sanitize($_POST['configurable_target']).")";
        mysql_query($sql) or die(mysql_error());

        $sql = "REPLACE INTO config (parameter, value) VALUES ('cdr_workaround',".sanitize($_POST['cdr_workaround']).")";
        mysql_query($sql) or die(mysql_error());

        $sql = "REPLACE INTO config (parameter, value) VALUES ('test_number',".sanitize($_POST['test_number']).")";
        mysql_query($sql) or die(mysql_error());


        /*
         
         *  *  * $add = @fopen("./admin/db_config.php",'w');
         $script = '\<\?
         $db_host="localhost";
         $db_user="root";
         $db_pass="";
         $link = mysql_connect($db_host, $db_user, $db_pass) OR die(mysql_error());
         \?\>
         ';
         fwrite($add,$script);
         fclose($add);*/


    }
    include "header.php";
    /*================= Log Access ======================================*/
    $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_COOKIE[user]', 'Viewed the admin page')";
    $result = mysql_query($sql, $link);
    /*================= Log Access ======================================*/

    if (isset($_POST[userid])) {
        /*================= Log Access ======================================*/
        $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_COOKIE[user]', 'Config Updated')";
        $result = mysql_query($sql, $link);
        /*================= Log Access ======================================*/

        $sql = "UPDATE config SET value='$_POST[userid]' WHERE parameter='userid'";
        $result = mysql_query($sql, $link) or die (mysql_error());

        $sql = "UPDATE config SET value='$_POST[licencekey]' WHERE parameter='licencekey'";
        $result = mysql_query($sql, $link) or die (mysql_error());
    }

    $sql = 'SELECT value FROM config WHERE parameter=\'backend\'';
    $result = mysql_query($sql, $link) or die (mysql_error());;
    $backend = mysql_result($result, 0, 'value');

    $sql = 'SELECT value FROM config WHERE parameter=\'userid\'';
    $result = mysql_query($sql, $link) or die (mysql_error());;
    $userid = mysql_result($result, 0, 'value');

    $sql = 'SELECT value FROM config WHERE parameter=\'licencekey\'';
    $result = mysql_query($sql, $link) or die (mysql_error());;
    $licencekey = mysql_result($result, 0, 'value');

    $sql = 'SELECT value FROM config WHERE parameter=\'expected_rate\'';
    $result = mysql_query($sql, $link) or die (mysql_error());
    if (mysql_num_rows($result) > 0) {
        $expected_rate = mysql_result($result, 0, 'value');
    } else {
        $expected_rate = 100;
    }

    $sql = 'SELECT value FROM config WHERE parameter=\'use_new_pie\'';
    $result = mysql_query($sql, $link) or die (mysql_error());
    $use_new_pie = 0;
    if (mysql_num_rows($result) > 0) {
        $use_new_pie = mysql_result($result, 0, 'value');
    }

    $sql = 'SELECT value FROM config WHERE parameter=\'show_front_page_title\'';
    $result = mysql_query($sql, $link) or die (mysql_error());
    $show_front_page_title = 1;
    if (mysql_num_rows($result) > 0) {
        $show_front_page_title = mysql_result($result, 0, 'value');
    }

    $sql = 'SELECT value FROM config WHERE parameter=\'show_front_page_text\'';
    $result = mysql_query($sql, $link) or die (mysql_error());
    $show_front_page_text = 1;
    if (mysql_num_rows($result) > 0) {
        $show_front_page_text = mysql_result($result, 0, 'value');
    }

    $sql = 'SELECT value FROM config WHERE parameter=\'logo_width\'';
    $result = mysql_query($sql, $link) or die (mysql_error());
    if (mysql_num_rows($result) > 0) {
        $logo_width = mysql_result($result, 0, 'value');
    }

    $sql = 'SELECT value FROM config WHERE parameter=\'logo_height\'';
    $result = mysql_query($sql, $link) or die (mysql_error());
    if (mysql_num_rows($result) > 0) {
        $logo_height = mysql_result($result, 0, 'value');
    }

    $sql = 'SELECT value FROM config WHERE parameter=\'use_names\'';
    $result = mysql_query($sql, $link) or die (mysql_error());
    if (mysql_num_rows($result) > 0) {
        $use_names = mysql_result($result, 0, 'value');
    }

    $sql = 'SELECT value FROM config WHERE parameter=\'SHOW_NUMBERS_LEFT\'';
    $result = mysql_query($sql, $link) or die (mysql_error());
    if (mysql_num_rows($result) > 0) {
        $config_values['SHOW_NUMBERS_LEFT'] = mysql_result($result, 0, 'value');
    }


    $sql = 'SELECT value FROM config WHERE parameter=\'sugar_user\'';
    $result = mysql_query($sql, $link) or die (mysql_error());
    if (mysql_num_rows($result) > 0) {
        $config_values['SUGAR_USER'] = mysql_result($result, 0, 'value');
    }

    $sql = 'SELECT value FROM config WHERE parameter=\'sugar_host\'';
    $result = mysql_query($sql, $link) or die (mysql_error());
    if (mysql_num_rows($result) > 0) {
        $config_values['SUGAR_HOST'] = mysql_result($result, 0, 'value');
    }

    $sql = 'SELECT value FROM config WHERE parameter=\'sugar_pass\'';
    $result = mysql_query($sql, $link) or die (mysql_error());
    if (mysql_num_rows($result) > 0) {
        $config_values['SUGAR_PASS'] = mysql_result($result, 0, 'value');
    }

    $sql = 'SELECT value FROM config WHERE parameter=\'sugar_db\'';
    $result = mysql_query($sql, $link) or die (mysql_error());
    if (mysql_num_rows($result) > 0) {
        $config_values['SUGAR_DB'] = mysql_result($result, 0, 'value');
    }


    $sql = 'SELECT value FROM config WHERE parameter=\'EVERGREEN\'';
    $result = mysql_query($sql, $link) or die (mysql_error());
    if (mysql_num_rows($result) > 0) {
        $config_values['EVERGREEN'] = mysql_result($result, 0, 'value');
    } else {
        $config_values['EVERGREEN'] = "NO";
    }

    $sql = 'SELECT value FROM config WHERE parameter=\'CDR_USE_STATE\'';
    $result = mysql_query($sql, $link) or die (mysql_error());
    if (mysql_num_rows($result) > 0) {
        $config_values['CDR_USE_STATE'] = mysql_result($result, 0, 'value');
    } else {
        $config_values['CDR_USE_STATE'] = "NO";
    }

    $sql = 'SELECT value FROM config WHERE parameter=\'NUMBER_EXHAUSTION\'';
    $result = mysql_query($sql, $link) or die (mysql_error());
    if (mysql_num_rows($result) > 0) {
        $config_values['NUMBER_EXHAUSTION'] = mysql_result($result, 0, 'value');
    } else {
        $config_values['NUMBER_EXHAUSTION'] = "NO";
    }

    $sql = 'SELECT value FROM config WHERE parameter=\'DISABLE_RECYCLE_ALL\'';
    $result = mysql_query($sql, $link) or die (mysql_error());
    if (mysql_num_rows($result) > 0) {
        $config_values['DISABLE_RECYCLE_ALL'] = mysql_result($result, 0, 'value');
    } else {
        $config_values['DISABLE_RECYCLE_ALL'] = "NO";
    }


    $sql = 'SELECT value FROM config WHERE parameter=\'USE_TIMEZONES\'';
    $result = mysql_query($sql, $link) or die (mysql_error());
    if (mysql_num_rows($result) > 0) {
        $config_values['USE_TIMEZONES'] = mysql_result($result, 0, 'value');
    } else {
        $config_values['USE_TIMEZONES'] = "NO";
    }

    $sql = 'SELECT value FROM config WHERE parameter=\'MENU_TIMEZONES\'';
    $result = mysql_query($sql, $link) or die (mysql_error());
    if (mysql_num_rows($result) > 0) {
        $config_values['MENU_TIMEZONES'] = mysql_result($result, 0, 'value');
    } else {
        $config_values['MENU_TIMEZONES'] = "Timezones";
    }

    $sql = 'SELECT value FROM config WHERE parameter=\'disable_all_types\'';
    $result = mysql_query($sql, $link) or die (mysql_error());
    if (mysql_num_rows($result) > 0) {
        $config_values['disable_all_types'] = mysql_result($result, 0, 'value');
    } else {
        $config_values['disable_all_types'] = "NO";
    }


    $sql = 'SELECT value FROM config WHERE parameter=\'brand\'';
    $result = mysql_query($sql, $link) or die (mysql_error());
    if (mysql_num_rows($result) > 0) {
        $config_values['brand'] = mysql_result($result, 0, 'value');
    }






    ?>
    
    <link type="text/css" href="css/cupertino/jquery-ui-1.8.20.custom.css" rel="stylesheet" />
    <script src="js/jquery-1.7.2.min.js" type="text/javascript"></script>
    <script src="js/jquery-ui-1.8.20.custom.min.js" type="text/javascript"></script>
    
   
    



    <form action="config.php" name="config" method="post">
    




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
            <ul class="nav nav-default"  id="tabs" data-tabs="tabs">



    <li><a href="#tools" class="btn btn-default navbar-btn "  data-toggle="tab">Tools</a></li>
    <li><a href="#system" class="btn btn-default navbar-btn" data-toggle="tab">System</a></li>
    <li><a href="#email" class="btn btn-default navbar-btn" data-toggle="tab">Email</a></li>
    <li><a href="#mysql" class="btn btn-default navbar-btn" data-toggle="tab">Database</a></li>
    <li><a href="#multi" class="btn btn-default navbar-btn" data-toggle="tab">Multi Tennant Hosting</a></li>
    <li><a href="#numbers" class="btn btn-default navbar-btn" data-toggle="tab">Numbers</a></li>
    <li><a href="#dnc" class="btn btn-default navbar-btn" data-toggle="tab">Do Not Call (DNC)</a></li>
    <li><a href="#licensing" class="btn btn-default navbar-btn" data-toggle="tab">Licensing</a></li>
	<li><a href="#look" class="btn btn-default navbar-btn" data-toggle="tab">Look and Feel</a></li>
	<li><a href="#menutext" class="btn btn-default navbar-btn" data-toggle="tab">Menu Text</a></li>
	<li><a href="#other" class="btn btn-default navbar-btn" data-toggle="tab">Other Text</a></li>
	<li><a href="#billing" class="btn btn-default navbar-btn" data-toggle="tab">Billing</a></li>
	<li><a href="#advanced" class="btn btn-default navbar-btn" data-toggle="tab">Advanced</a></li>
	<li><a href="#credits" class="btn btn-default navbar-btn" data-toggle="tab">Credits</a></li>
    </ul>
    </div></div></nav>


<div id="my-tab-content" class="tab-content">
    <? /************************** SETTINGS TAB *************************/ ?>
    
    <div id="tools" title="Tools" class="tab-pane" >
    <br />
    
        <a href="mailto:matt@venturevoip.com" class="btn btn-default"><img src="<?= $http_dir_name ?>images/ftp.png" border="0" width="32"><br />Submit Support Request</a>
        <a href="log.php" class="btn btn-default"><img src="<?= $http_dir_name ?>images/document.png" border="0" width="32"><br />View System Logs</a>
        <a href="billinglog.php" class="btn btn-default"><img src="<?= $http_dir_name ?>images/kcalc.png" border="0" width="32"><br />View <? echo $config_values['BILLING_TEXT']; ?></a>
    <a href="view_system_bill.php" class="btn btn-default"><img src="<?= $http_dir_name ?>images/log.png" border="0" width="32"><br />View Billing Graphs</a>
    </div>
    
    
    <? /************************** SYSTEM TAB *************************/ ?>
    
    
    <div id="system" title="System" class="tab-pane">
    
    <table class="table table-striped">
    <thead>
    <tr>
    <th colspan="2">Settings</th>
    </tr>
    </thead>
    <tr>
    <td>
    <? if ($backend == 0) { ?>
        <IMG SRC="<?= $http_dir_name ?>images/tick.png" BORDER="1" WIDTH="16" HEIGHT="16" class="abcd">
    <? } else { ?>
        <a href="setparameter.php?parameter=backend&value=0"><IMG SRC="<?= $http_dir_name ?>images/ch.gif" BORDER="1" WIDTH="16" HEIGHT="16"></a>
    <? } ?>
    </td>
    <td>Linux Backend (<b>Version <? echo $version; ?></b>)</td>
    </tr>
    <tr  ><td><? if ($backend == 1) { ?>
        <IMG SRC="<?= $http_dir_name ?>images/tick.png" BORDER="1" WIDTH="16" HEIGHT="16" class="abcd">
    <? } else { ?>
        <a href="setparameter.php?parameter=backend&value=1"><IMG SRC="<?= $http_dir_name ?>images/ch.gif" BORDER="1" WIDTH="16" HEIGHT="16"></a>
    <? } ?>
    </td>
    <td>Windows Backend</td></tr>
    <tr  >
    <td>
    Sox Path:
    </td>
    <td>
    <input class="form-control" type="Text" name="sox" value="<? echo $config_values['SOX']; ?>">
    </td>
    </tr>

    <tr  >
    <td>
    Expected Percent of Press 1 Calls (0.01-100):
    </td>
    <td>
    <input class="form-control" type="Text" name="expected_rate" value="<?
    if ($expected_rate > 0) {
        echo $expected_rate;
    } else {
        echo 100;
    }
    ?>">
    </td>
    </tr>

    <tr  ><td colspan="2"><input class="btn btn-primary" type="submit" value="Save Config Information"></td></tr>
    </table>
    </div>


    <? /************************** EMAIL TAB *************************/ ?>
    <div id="email" title="Email"class="tab-pane">

    <table class="table table-striped">

    <tr><th colspan="2">Email Settings</th>

    <tr  >
    <td>
    SMTP Host Name
    </td>
    <td>
    <input class="form-control" type="Text" name="SMTP_HOST" value="<? echo $config_values['SMTP_HOST']; ?>">
    </td>
    </tr>

    <tr  >
    <td>
    SMTP User Name
    </td>
    <td>
    <input class="form-control" type="Text" name="SMTP_USER" value="<? echo $config_values['SMTP_USER']; ?>">
    </td>
    </tr>

    <tr  >
    <td>
    SMTP Password
    </td>
    <td>
    <input class="form-control" type="Text" name="SMTP_PASS" value="<? echo $config_values['SMTP_PASS']; ?>">
    </td>
    </tr>

    <tr  >
    <td>
    Email from address
    </td>
    <td>
    <input class="form-control" type="Text" name="SMTP_FROM" value="<? echo $config_values['SMTP_FROM']; ?>">
    </td>
    </tr>


    <tr  ><td colspan="2"><input class="btn btn-primary" type="submit" value="Save Config Information"></td></tr>
    </table>
    </div>

    <? /************************** Mysql TAB *************************/ ?>
    <div id="mysql" title="MySQL" class="tab-pane">

    <table class="table table-striped">

    <tr><th colspan="2">MySQL Settings</th>

    <tr  >
    <td>
    SmoothTorque MySQL Host Name
    </td>
    <td>
    <input class="form-control" type="Text" name="ST_MYSQL_HOST" value="<? echo $db_host; ?>">
    </td>
    </tr>

    <tr  >
    <td>
    SmoothTorque MySQL User Name
    </td>
    <td>
    <input class="form-control" type="Text" name="ST_MYSQL_USER" value="<? echo $db_user; ?>">
    </td>
    </tr>

    <tr  >
    <td>
    SmoothTorque MySQL Password
    </td>
    <td>
    <input class="form-control" type="password" name="ST_MYSQL_PASS" value="<? echo $db_pass; ?>">
    </td>
    </tr>

    <tr  >
    <td>
    Asterisk MySQL CDR Host:
    </td>
    <td>
    <input class="form-control" type="Text" name="CDR_HOST" value="<? echo $config_values['CDR_HOST']; ?>">
    </td>
    </tr>

    <tr  >
    <td>
    Asterisk MySQL CDR Username:
    </td>
    <td>
    <input class="form-control" type="Text" name="CDR_USER" value="<? echo $config_values['CDR_USER']; ?>">
    </td>
    </tr>

    <tr  >
    <td>
    Asterisk MySQL CDR Pass:
    </td>
    <td>
    <input class="form-control" type="password" name="CDR_PASS" value="<? echo $config_values['CDR_PASS']; ?>">
    </td>
    </tr>

    <tr  >
    <td>
    Asterisk MySQL CDR Database:
    </td>
    <td>
    <input class="form-control" type="Text" name="CDR_DB" value="<? echo $config_values['CDR_DB']; ?>">
    </td>
    </tr>

    <tr  >
    <td>
    Asterisk MySQL CDR Table:
    </td>
    <td>
    <input class="form-control" type="Text" name="CDR_TABLE" value="<? echo $config_values['CDR_TABLE']; ?>">
    </td>
    </tr>

    <tr  >
    <td>
    SugarCRM MySQL Hostname:
    </td>
    <td>
    <input class="form-control" type="Text" name="sugar_host" value="<? echo $config_values['SUGAR_HOST']; ?>">
    </td>
    </tr>

    <tr  >
    <td>
    SugarCRM MySQL Username:
    </td>
    <td>
    <input class="form-control" type="Text" name="sugar_user" value="<? echo $config_values['SUGAR_USER']; ?>">
    </td>
    </tr>

    <tr  >
    <td>
    SugarCRM MySQL Password:
    </td>
    <td>
    <input class="form-control" type="Text" name="sugar_pass" value="<? echo $config_values['SUGAR_PASS']; ?>">
    </td>
    </tr>

    <tr  >
    <td>
    SugarCRM MySQL Database:
    </td>
    <td>
    <input class="form-control" type="Text" name="sugar_db" value="<? echo $config_values['SUGAR_DB']; ?>">
    </td>
    </tr>

    <tr  ><td colspan="2"><input class="btn btn-primary" type="submit" value="Save Config Information"></td></tr>
    </table>
    </div>

    <? /************************** Multi Tenant TAB *************************/ ?>

    <div id="multi" title="Hosting"class="tab-pane">

    <table class="table table-striped">
    <tr>
    <th colspan="2">Multitenant Hosting</th>
    </tr>
    <tr >
    <td colspan="2">
    <?
    $result = mysql_query("SELECT url, language, LANG FROM web_config WHERE url != 'default' ORDER BY url");
    if (mysql_num_rows($result) > 0) {
        echo "Currently defined servers:</td></tr>";
        while ($row = mysql_fetch_assoc($result)) {
            echo "<tr class=\"tborder2\"><td><b>".$row[url]."</b></td><td>Language: ".$row[language]."&nbsp;<a href=\"config.php?delete_server=$row[url]&LANG=$row[LANG]\"><img src=\"images/cross.png\" border=\"0\"></td></tr>";
            $servers[$row[url]] = 1;
        }
        ?>
        <tr ><th colspan="2">Add new server</th>
        </tr><tr ><td>
        <form action="config.php" method="POST">
        New URL:</td><td> <input class="form-control" type="Text" name="url_to_add" value=""></td></tr>
        <tr ><td>Copy From: </td><td><select  class="form-control" name="copy_from">
        <option value="default">Default Configs</option>
        <?
        foreach ($servers as $url => $bla) {
            echo "<option value=\"$url\">$url</option>";
        }
        ?>
        </select></td></tr>
        <tr><td colspan="2">
        <input class="btn btn-primary" type="submit" value="Add URL">
        </form>
        <?
    } else {
        ?>
        You are not currently set up for multitenant hosting<br/>
        <br/>
        Please enter a URL you would like to set up. (i.e. call.venturevoip.com)<br/>
        <br/>
        Once you have added a url, you can log in from that URL and configure the system.<br/>
        <br/>
        <form action="config.php?add_url=1" method="POST">
            <input class="form-control" type="Text" name="url_to_add" value="">
            <input type="hidden" name="copy_from" value="default">
            <input class="btn btn-primary" type="submit" value="Add URL">
        </form>
    <?
    }
    ?>
    </td>
    </tr>

    </table>
    </div>
    <? /********************************** Licensing TAB ***********************/ ?>

        <div id="licensing" title="Licensing"class="tab-pane">

        <table class="table table-striped">

        <tr>
        <th colspan="2">Licence Details</th>
        </tr>
        <tr  >
        <td>
        User ID:
        </td>
        <td>
        <input class="form-control" type="Text" name="userid" value="<? echo $userid; ?>">
        </td>
        </tr>
        <tr  >
        <td>
        Licence Key:
        </td>
        <td>
        <input class="form-control" type="Text" name="licencekey" value="<? echo $licencekey; ?>">
        </td>
        </tr>
        <tr  >
        <td>
        Licence Details:
        </td>
        <td>
        <?
    $handle = fopen("http://license.venturevoip.com/licencest.php?userid=$userid&licence=$licencekey", "rb");
    $contents2 = '';
    while (!feof($handle)) {
        $contents2 .= fread($handle, 8192);
    }
    $contents3 = explode("\n", $contents2);
    //print_r($contents3);
    fclose($handle);
    $contents = $contents3[0];
    if ($contents < 1000) {
        if ($contents == 0) {
            echo "Unlicensed demo";
        } else if ($contents == 1) {
            echo "Calls Per Second";
        } else if ($contents == 2) {
            echo "Calls Per Second";
        } else if ($contents == 3) {
            echo "1 Server (Unlimited Channels)";
        } else {
            $servers = $contents - 2;
            echo $servers." Servers (Unlimited Channels)";
        }
    } else {
        // Licenced on a per channel basis
        $test = $contents / 10000;
        //    echo round($test)." - ";
        //    echo $contents." - ";
        //    echo (10000*round($test))." - ";
        $maxchans = $contents - (10000 * round($test));
        echo round($test - 2)." Servers (Max. ".$maxchans." Channels)";
    }

    ?>




        </td>
        </tr>

    <tr  ><td colspan="2"><input class="btn btn-primary" type="submit" value="Save Config Information"></td></tr>
    </table>
    </div>


    <? /************************** Look and Feel TAB *************************/ ?>
    <div id="look" title="Theme"class="tab-pane">

    <table class="table table-striped">

    <tr>
    <th colspan="2">Look and Feel</th>
    </tr>


    <tr  >
    <td>
    Background Colour:
    </td>
    <td>
    <script language=JavaScript src="js/picker.js"></script>
    <input class="form-control" type="Text" name="colour" value="<? echo $config_values['COLOUR']; ?>">
    <a href="javascript:TCP.popup(document.forms['config'].elements['colour'], 1)"><img width="15" height="13" border="0" alt="Click Here to Pick up the color" src="img/sel.gif"></a>

    </td>
    </tr>

    <tr  >
    <td>
    Date/Time Colour:
    </td>
    <td>
    <input class="form-control" type="Text" name="DATE_COLOUR" value="<? echo $config_values['DATE_COLOUR']; ?>">
    <a href="javascript:TCP.popup(document.forms['config'].elements['DATE_COLOUR'], 1)"><img width="15" height="13" border="0" alt="Click Here to Pick the color" src="img/sel.gif"></a>

    </td>
    </tr>



    <tr  >
    <td>
    Site Name:
    </td>
    <td>
    <input class="form-control" type="Text" name="title" value="<? echo stripslashes($config_values['TITLE']); ?>">
    </td>
    </tr>

    <tr  >
    <td>
    Short Brand Name:
    </td>
    <td>
    <input class="form-control" type="Text" name="brand" value="<? echo stripslashes($config_values['brand']); ?>">
    </td>
    </tr>

    </td>
    </tr>
    <tr  >
    <td>
    Logo Filename:
    </td>
    <td>
    <input class="form-control" type="Text" name="logo" value="<? echo $config_values['LOGO']; ?>">
    </td>
    </tr>

    </td>
    </tr>
    <tr  >
    <td>
    Logo Width:
    </td>
    <td>
    <input class="form-control" type="Text" name="logo_width" value="<? echo $logo_width; ?>">
    </td>
    </tr>

    </td>
    </tr>
    <tr  >
    <td>
    Logo Height:
    </td>
    <td>
    <input class="form-control" type="Text" name="logo_height" value="<? echo $logo_height; ?>">
    </td>
    </tr>

    <tr  >
    <td>
    Opening Text:
    </td>
    <td>
    <input class="form-control" type="Text" name="text" value="<? echo $config_values['TEXT']; ?>">
    </td>
    </tr>

    <tr  >
    <td>
    Use Flash-based Pie Chart:
    </td>
    <td>
    <select  class="form-control" name="use_new_pie">
    <option value="1" <? if ($use_new_pie == 1) {
        echo "selected";
    } ?>>Yes</option>
    <option value="0" <? if ($use_new_pie != 1) {
        echo "selected";
    } ?>>No</option>
    </select>
    </td>
    </tr>

    <tr  >
    <td>
    Show the title on the front page:
    </td>
    <td>
    <select  class="form-control" name="show_front_page_title">
    <option value="1" <? if ($show_front_page_title == 1) {
        echo "selected";
    } ?>>Yes</option>
    <option value="0" <? if ($show_front_page_title != 1) {
        echo "selected";
    } ?>>No</option>
    </select>
    </td>
    </tr>

    <tr  >
    <td>
    Show the text on the front page:
    </td>
    <td>
    <select  class="form-control" name="show_front_page_text">
    <option value="1" <? if ($show_front_page_text == 1) {
        echo "selected";
    } ?>>Yes</option>
    <option value="0" <? if ($show_front_page_text != 1) {
        echo "selected";
    } ?>>No</option>
    </select>
    </td>
    </tr>



    <tr  ><td colspan="2"><input class="btn btn-primary" type="submit" value="Save Config Information"></td></tr>
    </table>
    </div>
    <? /************************** Menu Text TAB *************************/ ?>
    <div id="menutext" title="Menu Text"class="tab-pane">

    <table class="table table-striped">

    <? /*******************************************************************/ ?>
    <? /*                           Menu Text                             */ ?>
    <? /*******************************************************************/ ?>
    <tr><th colspan="2">Menu Text</th>

    <tr  >
    <td>
Language:
    </td>
    <td>
    <input class="form-control" type="Text" name="LANGUAGE" value="<? echo $config_values['LANGUAGE']; ?>">
    </td>
    </tr>
    <tr  >
    <td>
    Home Menu Text:
    </td>
    <td>
    <input class="form-control" type="Text" name="MENU_HOME" value="<? echo $config_values['MENU_HOME']; ?>">
    </td>
    </tr>
    <tr  >
    <td>
    Campaigns Menu Text:
    </td>
    <td>
    <input class="form-control" type="Text" name="MENU_CAMPAIGNS" value="<? echo $config_values['MENU_CAMPAIGNS']; ?>">
    </td>
    </tr>
    <tr  >
    <td>
    Numbers Menu Text:
    </td>
    <td>
    <input class="form-control" type="Text" name="MENU_NUMBERS" value="<? echo $config_values['MENU_NUMBERS']; ?>">
    </td>
    </tr>
    <tr  >
    <td>
    DNC Numbers Menu Text:
    </td>
    <td>
    <input class="form-control" type="Text" name="MENU_DNC" value="<? echo $config_values['MENU_DNC']; ?>">
    </td>
    </tr>
    <tr  >
    <td>
    Messages Menu Text:
    </td>
    <td>
    <input class="form-control" type="Text" name="MENU_MESSAGES" value="<? echo $config_values['MENU_MESSAGES']; ?>">
    </td>
    </tr>
    <tr  >
    <td>
    Schedules Menu Text:
    </td>
    <td>
    <input class="form-control" type="Text" name="MENU_SCHEDULES" value="<? echo $config_values['MENU_SCHEDULES']; ?>">
    </td>
    </tr>
    <tr  >
    <td>
    Customers Menu Text:
    </td>
    <td>
    <input class="form-control" type="Text" name="MENU_CUSTOMERS" value="<? echo $config_values['MENU_CUSTOMERS']; ?>">
    </td>
    </tr>
    <tr  >
    <td>
    Surveys Menu Text:
    </td>
    <td>
    <input class="form-control" type="Text" name="MENU_SURVEYS" value="<? echo $config_values['MENU_SURVEYS']; ?>">
    </td>
    </tr>
    <tr  >
    <td>
    CDR Menu Text:
    </td>
    <td>
    <input class="form-control" type="Text" name="MENU_CDR" value="<? echo $config_values['MENU_CDR']; ?>">
    </td>
    </tr>
    <tr  >
    <td>
    Queues Menu Text:
    </td>
    <td>
    <input class="form-control" type="Text" name="MENU_QUEUES" value="<? echo $config_values['MENU_QUEUES']; ?>">
    </td>
    </tr>
    <tr  >
    <td>
    Servers Menu Text:
    </td>
    <td>
    <input class="form-control" type="Text" name="MENU_SERVERS" value="<? echo $config_values['MENU_SERVERS']; ?>">
    </td>
    </tr>
    <tr  >
    <td>
    Trunks Menu Text:
    </td>
    <td>
    <input class="form-control" type="Text" name="MENU_TRUNKS" value="<? echo $config_values['MENU_TRUNKS']; ?>">
    </td>
    </tr>
    <tr  >
    <td>
    Timezone Menu Text:
    </td>
    <td>
    <input class="form-control" type="Text" name="MENU_TIMEZONES" value="<? echo $config_values['MENU_TIMEZONES']; ?>">
    </td>
    </tr>
    <tr  >
    <td>
    Admin Menu Text:
    </td>
    <td>
    <input class="form-control" type="Text" name="MENU_ADMIN" value="<? echo $config_values['MENU_ADMIN']; ?>">
    </td>
    </tr>
    <tr  >
    <td>
    Logout Menu Text:
    </td>
    <td>
    <input class="form-control" type="Text" name="MENU_LOGOUT" value="<? echo $config_values['MENU_LOGOUT']; ?>">
    </td>
    </tr>
    <tr  >
    <td colspan="2">
    <input class="btn btn-primary" type="submit" value="Save Config Information">
    </td>
    </tr>
    </table>
    </div>


    <? /************************** Misc Text TAB *************************/ ?>
    <div id="other" title="Misc Text"class="tab-pane">

    <table class="table table-striped">


    <tr><th colspan="2">Other Text</th>



    <tr  >
    <td>
    Main Page Text:
    </td>
    <td>
    <input class="form-control" type="Text" name="MAIN_PAGE_TEXT" size="60" value="<? echo $config_values['MAIN_PAGE_TEXT']; ?>">
    </td>
    </tr>

    <tr  >
    <td>
    Add Campaign Text:
    </td>
    <td>
    <input class="form-control" type="Text" name="ADD_CAMPAIGN" size="60" value="<? echo $config_values['ADD_CAMPAIGN']; ?>">
    </td>
    </tr>

    <tr  >
    <td>
    View Campaigns Text:
    </td>
    <td>
    <input class="form-control" type="Text" name="VIEW_CAMPAIGN" size="60" value="<? echo $config_values['VIEW_CAMPAIGN']; ?>">
    </td>
    </tr>

    <tr  >
    <td>
    Main Page Username Text:
    </td>
    <td>
    <input class="form-control" type="Text" name="MAIN_PAGE_USERNAME" size="60" value="<? echo $config_values['MAIN_PAGE_USERNAME']; ?>">
    </td>
    </tr>

    <tr  >
    <td>
    Main Page Password Text:
    </td>
    <td>
    <input class="form-control" type="Text" name="MAIN_PAGE_PASSWORD" size="60" value="<? echo $config_values['MAIN_PAGE_PASSWORD']; ?>">
    </td>
    </tr>

    <tr  >
    <td>
    Main Page Login Text:
    </td>
    <td>
    <input class="form-control" type="Text" name="MAIN_PAGE_LOGIN" size="60" value="<? echo $config_values['MAIN_PAGE_LOGIN']; ?>">
    </td>
    </tr>


    <tr  >
    <td colspan="2">
    <input class="btn btn-primary" type="submit" value="Save Config Information">
    </td>
    </tr>
    </table>
    </div>


    <? /************************** DNC TAB *************************/ ?>
    <div id="dnc" title="DNC"class="tab-pane">

    <table class="table table-striped">

    <? /*******************************************************************/ ?>
    <? /*                        DNC Numbers Section                      */ ?>
    <? /*******************************************************************/ ?>

    <tr><th colspan="2">DNC Numbers Section</th>

    <tr  >
    <td>
    Number List Management Text (Title):
    </td>
    <td>
    <input class="form-control" type="Text" name="DNC_NUMBERS_TITLE" size="60" value="<? echo $config_values['DNC_NUMBERS_TITLE']; ?>">
    </td>
    </tr>

    <tr  >
    <td>
    View existing DNC numbers Text (Title):
    </td>
    <td>
    <input class="form-control" type="Text" name="DNC_VIEW" size="60" value="<? echo $config_values['DNC_VIEW']; ?>">
    </td>
    </tr>

    <tr  >
    <td>
    Search DNC numbers Text (Title):
    </td>
    <td>
    <input class="form-control" type="Text" name="DNC_SEARCH" size="60" value="<? echo $config_values['DNC_SEARCH']; ?>">
    </td>
    </tr>
    <tr  >
    <td>
    Upload DNC numbers Text (Title):
    </td>
    <td>
    <input class="form-control" type="Text" name="DNC_UPLOAD" size="60" value="<? echo $config_values['DNC_UPLOAD']; ?>">
    </td>
    </tr>

    <tr  >
    <td>
    Add DNC numbers Text (Title):
    </td>
    <td>
    <input class="form-control" type="Text" name="DNC_ADD" size="60" value="<? echo $config_values['DNC_ADD']; ?>">
    </td>
    </tr>

    <tr  >
    <td>
    Use separate DNC entries for separate customers
        </td>
        <td>
        <input type="radio" name="USE_SEPARATE_DNC" value="YES" <? if ($config_values['USE_SEPARATE_DNC'] == "YES") {
        echo "checked";
    } ?>> Yes
    <input type="radio" name="USE_SEPARATE_DNC" value="NO" <? if ($config_values['USE_SEPARATE_DNC'] != "YES") {
        echo "checked";
    } ?>> No
    </td>
    </tr>
    <tr  >
    <td colspan="2">
    <input class="btn btn-primary" type="submit" value="Save Config Information">
    </td>
    </tr>
    </table>
    </div>
    <? /************************** Numbers TAB *************************/ ?>
    <div id="numbers" title="Numbers"class="tab-pane">

    <table class="table table-striped">



    <? /*******************************************************************/ ?>
    <? /*                        Numbers Section                           */ ?>
    <? /*******************************************************************/ ?>

    <tr><th colspan="2">Numbers Section</th>


    <tr  >
    <td>
    Show Number of Numbers remaining:
    </td>
    <td>
    <input type="radio" name="SHOW_NUMBERS_LEFT" value="YES" <? if ($config_values['SHOW_NUMBERS_LEFT'] == "YES") {
        echo "checked";
    } ?>> Yes
    <input type="radio" name="SHOW_NUMBERS_LEFT" value="NO" <? if ($config_values['SHOW_NUMBERS_LEFT'] != "YES") {
        echo "checked";
    } ?>> No
    </td>
    </tr>

    <tr  >
    <td>
    Allow campaigns to run continuously:
    </td>
    <td>
    <input type="radio" name="EVERGREEN" value="YES" <? if ($config_values['EVERGREEN'] == "YES") {
        echo "checked";
    } ?>> Yes
    <input type="radio" name="EVERGREEN" value="NO" <? if ($config_values['EVERGREEN'] != "YES") {
        echo "checked";
    } ?>> No
    </td>
    </tr>

    <tr  >
    <td>
    Use timezone based updating (requires cron job):
    </td>
    <td>
    <input type="radio" name="USE_TIMEZONES" value="YES" <? if ($config_values['USE_TIMEZONES'] == "YES") {
        echo "checked";
    } ?>> Yes
    <input type="radio" name="USE_TIMEZONES" value="NO" <? if ($config_values['USE_TIMEZONES'] != "YES") {
        echo "checked";
    } ?>> No
    </td>
    </tr>


    <tr  >
    <td>
    Look up number to state:
    </td>
    <td>
    <input type="radio" name="CDR_USE_STATE" value="YES" <? if ($config_values['CDR_USE_STATE'] == "YES") {
        echo "checked";
    } ?>> Yes
    <input type="radio" name="CDR_USE_STATE" value="NO" <? if ($config_values['CDR_USE_STATE'] != "YES") {
        echo "checked";
    } ?>> No
    </td>
    </tr>

    <tr  >
    <td>
    Provide number exhaustion warning emails:
    </td>
    <td>
    <input type="radio" name="NUMBER_EXHAUSTION" value="YES" <? if ($config_values['NUMBER_EXHAUSTION'] == "YES") {
        echo "checked";
    } ?>> Yes
    <input type="radio" name="NUMBER_EXHAUSTION" value="NO" <? if ($config_values['NUMBER_EXHAUSTION'] != "YES") {
        echo "checked";
    } ?>> No
    </td>
    </tr>

    <tr  >
    <td>
    Disable "Recycle All Numbers" Option:
    </td>
    <td>
    <input type="radio" name="DISABLE_RECYCLE_ALL" value="YES" <? if ($config_values['DISABLE_RECYCLE_ALL'] == "YES") {
        echo "checked";
    } ?>> Yes
    <input type="radio" name="DISABLE_RECYCLE_ALL" value="NO" <? if ($config_values['DISABLE_RECYCLE_ALL'] != "YES") {
        echo "checked";
    } ?>> No
    </td>
    </tr>





    <tr  >
    <td>
    Provide Delete All Option:
    </td>
    <td>
    <input type="radio" name="DELETE_ALL" value="YES" <? if ($config_values['DELETE_ALL'] == "YES") {
        echo "checked";
    } ?>> Yes
    <input type="radio" name="DELETE_ALL" value="NO" <? if ($config_values['DELETE_ALL'] != "YES") {
        echo "checked";
    } ?>> No
    </td>
    </tr>

    <tr  >
    <td>
    Leave Press 1 with Delete All Option:
    </td>
    <td>
    <input type="radio" name="LEAVE_PRESS1" value="YES" <? if ($config_values['LEAVE_PRESS1'] == "YES") {
        echo "checked";
    } ?>> Yes
    <input type="radio" name="LEAVE_PRESS1" value="NO" <? if ($config_values['LEAVE_PRESS1'] != "YES") {
        echo "checked";
    } ?>> No
    </td>
    </tr>



    <tr  >
    <td>
    Number of entries to show per page:
    </td>
    <td>
    <input class="form-control" type="Text" name="PER_PAGE" size="60" value="<? echo $config_values['PER_PAGE']; ?>">
    </td>
    </tr>

    <tr  >
    <td>
    Allow manual dialling:
    </td>
    <td>
    <input type="radio" name="ALLOW_NUMBERS_MANUAL" value="YES" <? if ($config_values['ALLOW_NUMBERS_MANUAL'] == "YES") {
        echo "checked";
    } ?>> Yes
    <input type="radio" name="ALLOW_NUMBERS_MANUAL" value="NO" <? if ($config_values['ALLOW_NUMBERS_MANUAL'] != "YES") {
        echo "checked";
    } ?>> No
    </td>
    </tr>

    <tr  >
    <td>
    Number List Management Text (Title):
    </td>
    <td>
    <input class="form-control" type="Text" name="NUMBERS_TITLE" size="60" value="<? echo $config_values['NUMBERS_TITLE']; ?>">
    </td>
    </tr>


    <tr  >
    <td>
    View phone numbers text:
    </td>
    <td>
    <input class="form-control" type="Text" name="NUMBERS_VIEW" size="60" value="<? echo $config_values['NUMBERS_VIEW']; ?>">
    </td>
    </tr>

    <tr  >
    <td>
    Use System Lists Text:
    </td>
    <td>
    <input class="form-control" type="Text" name="NUMBERS_SYSTEM" size="60" value="<? echo $config_values['NUMBERS_SYSTEM']; ?>">
    </td>
    </tr>

    <tr  >
    <td>
    Search for a phone number Text:
        </td>
        <td>
        <input class="form-control" type="Text" name="NUMBERS_SEARCH" size="60" value="<? echo $config_values['NUMBERS_SEARCH']; ?>">
        </td>
        </tr>

        <tr  >
        <td>
        Export Phone Numbers Text:
        </td>
        <td>
        <input class="form-control" type="Text" name="NUMBERS_EXPORT" size="60" value="<? echo $config_values['NUMBERS_EXPORT']; ?>">
        </td>
        </tr>

        <tr  >
        <td>
        Upload numbers from a text file Text:
        </td>
        <td>
        <input class="form-control" type="Text" name="NUMBERS_UPLOAD" size="60" value="<? echo $config_values['NUMBERS_UPLOAD']; ?>">
        </td>
        </tr>

        <tr  >
        <td>
        Add number(s) manually Text:
        </td>
        <td>
        <input class="form-control" type="Text" name="NUMBERS_MANUAL" size="60" value="<? echo $config_values['NUMBERS_MANUAL']; ?>">
        </td>
        </tr>

        <tr  >
        <td>
        Generate numbers automatically Text:
        </td>
        <td>
        <input class="form-control" type="Text" name="NUMBERS_GENERATE" size="60" value="<? echo $config_values['NUMBERS_GENERATE']; ?>">
        </td>
        </tr>

        <tr  >
        <td>
        Use the Generate numbers automatically option
        </td>
        <td>
        <input type="radio" name="USE_GENERATE" value="YES" <? if ($config_values['USE_GENERATE'] == "YES") {
        echo "checked";
    } ?>> Yes
    <input type="radio" name="USE_GENERATE" value="NO" <? if ($config_values['USE_GENERATE'] != "YES") {
        echo "checked";
    } ?>> No
    </td>
    </tr>

    <tr  >
    <td>
    Allow importing of names to use with numbers:
    </td>
    <td>
    <input type="radio" name="use_names" value="YES" <? if ($config_values['use_names'] == "YES") {
        echo "checked";
    } ?>> Yes
    <input type="radio" name="use_names" value="NO" <? if ($config_values['use_names'] != "YES") {
        echo "checked";
    } ?>> No
    </td>
    </tr>


    <tr  >
    <td colspan="2">
    <input class="btn btn-primary" type="submit" value="Save Config Information">
    </td>
    </tr>
    </table>
    </div>
    <? /************************** Billing TAB *************************/ ?>
    <div id="billing" title="Billing"class="tab-pane">

    <table class="table table-striped">


    <tr><th colspan="2">Billing Information</th>

    <tr  >
    <td>
    Call Details Text (in header):
    </td>
    <td>
    <input class="form-control" type="Text" name="CDR_TEXT" value="<? echo $config_values['CDR_TEXT']; ?>">
    </td>
    </tr>

    <tr  >
    <td>
    Billing Text (in header):
    </td>
    <td>
    <input class="form-control" type="Text" name="BILLING_TEXT" value="<? echo $config_values['BILLING_TEXT']; ?>">
    </td>
    </tr>

    <tr  >
    <td>
    Currency Symbol (i.e. $):
    </td>
    <td>
    <input class="form-control" type="Text" name="CURRENCY_SYMBOL" value="<? echo $config_values['CURRENCY_SYMBOL']; ?>">
    </td>
    </tr>

    <tr  >
    <td>
    Per Minute Wording in CDR
    </td>
    <td>
    <input class="form-control" type="Text" name="PER_MINUTE" value="<? echo $config_values['PER_MINUTE']; ?>">
    </td>
    </tr>

    <tr  >
    <td>
    Price per lead wording
    </td>
    <td>
    <input class="form-control" type="Text" name="PER_LEAD" value="<? echo $config_values['PER_LEAD']; ?>">
    </td>
    </tr>


    <tr  >
    <td>
    Display billing on front page for admin
        </td>
        <td>
        <input type="radio" name="FRONT_PAGE_BILLING" value="YES" <? if ($config_values['FRONT_PAGE_BILLING'] == "YES") {
        echo "checked";
    } ?>> Yes
    <input type="radio" name="FRONT_PAGE_BILLING" value="NO" <? if ($config_values['FRONT_PAGE_BILLING'] != "YES") {
        echo "checked";
    } ?>> No
    </td>
    </tr>

    <tr  >
    <td>
    Use the SmoothTorque Billing System
    </td>
    <td>
    <input type="radio" name="USE_BILLING" value="YES" <? if ($config_values['USE_BILLING'] == "YES") {
        echo "checked";
    } ?>> Yes
    <input type="radio" name="USE_BILLING" value="NO" <? if ($config_values['USE_BILLING'] != "YES") {
        echo "checked";
    } ?>> No
    </td>
    </tr>

    <tr  >
    <td>
    Use predictive strict billing (extra MySQL load)
    </td>
    <td>
    <input type="radio" name="strict_credit_limit" value="YES" <? if ($config_values['strict_credit_limit'] == "YES") {
        echo "checked";
    } ?>> Yes
    <input type="radio" name="strict_credit_limit" value="NO" <? if ($config_values['strict_credit_limit'] != "YES") {
        echo "checked";
    } ?>> No
    </td>
    </tr>



    <tr  >
    <td colspan="2">
    <input class="btn btn-primary" type="submit" value="Save Config Information">
    </td>
    </tr>
    </table>
    </div>
    <? /************************** Advanced TAB *************************/ ?>
    <div id="advanced" title="Advanced"class="tab-pane">

    <table class="table table-striped">


    <tr><th colspan="2">Custom contexts</th>

    <tr >
    <td>
    Disable Everything Except Spare1
    </td>
    <td>

    <input type="radio" name="disable_all_types" value="YES" <? if ($config_values['disable_all_types'] == "YES") {
        echo "checked";
    } ?>> Yes
    <input type="radio" name="disable_all_types" value="NO" <? if ($config_values['disable_all_types'] != "YES") {
        echo "checked";
    } ?>> No
    </td>
    </tr>


    <tr  >
    <td>
    Description of spare1 context (optional)
    </td>
    <td>
    <input class="form-control" type="Text" name="SPARE1" value="<? echo $config_values['SPARE1']; ?>">
    </td>
    </tr>

    <tr  >
    <td>
    Description of spare2 context (optional)
    </td>
    <td>
    <input class="form-control" type="Text" name="SPARE2" value="<? echo $config_values['SPARE2']; ?>">
    </td>
    </tr>

    <tr  >
    <td>
    Description of spare3 context (optional)
    </td>
    <td>
    <input class="form-control" type="Text" name="SPARE3" value="<? echo $config_values['SPARE3']; ?>">
    </td>
    </tr>

    <tr  >
    <td>
    Description of spare4 context (optional)
    </td>
    <td>
    <input class="form-control" type="Text" name="SPARE4" value="<? echo $config_values['SPARE4']; ?>">
    </td>
    </tr>

    <tr  >
    <td>
    Description of spare5 context (optional)
    </td>
    <td>
    <input class="form-control" type="Text" name="SPARE5" value="<? echo $config_values['SPARE5']; ?>">
    </td>
    </tr>


    <tr  >
    <td>
    Provide Configurable Drive:<br />
    <i>(<font color="red">WARNING:</font> configurable drive is an incredibly<br> powerful feature and should be used carefully)</i>
    </td>
    <td>
    <select  class="form-control" name="configurable_drive">
    <option value="1" <? if ($config_values['configurable_drive'] == 1) {
        echo "selected";
    } ?>>Yes</option>
    <option value="0" <? if ($config_values['configurable_drive'] != 1) {
        echo "selected";
    } ?>>No</option>
    </select>
    </td>
    </tr>


    <tr  >
    <td>
    Provide Configurable Target Percentage:<br />
    <i>(<font color="red">WARNING:</font> configurable target percentage can override<br> predictive capabilities and should be used carefully)</i>
    </td>
    <td>
    <select  class="form-control" name="configurable_target">
    <option value="1" <? if ($config_values['configurable_target'] == 1) {
        echo "selected";
    } ?>>Yes</option>
    <option value="0" <? if ($config_values['configurable_target'] != 1) {
        echo "selected";
    } ?>>No</option>
    </select>
    </td>
    </tr>

    <tr  >
    <td>
    Use CDR Count InnoDB Workaround:
    </td>
    <td>
    <select  class="form-control" name="cdr_workaround">
    <option value="1" <? if ($config_values['cdr_workaround'] == 1) {
        echo "selected";
    } ?>>Yes</option>
    <option value="0" <? if ($config_values['cdr_workaround'] != 1) {
        echo "selected";
    } ?>>No</option>
    </select>
    </td>
    </tr>

    <tr  >
    <td>
    Test Phone Number:
    </td>
    <td>
    <input class="form-control" type="Text" name="test_number" value="<? echo $config_values['test_number']; ?>">
    </td>
    </tr>
    
    
    <tr  >
    <td>
    Disable "Message Upload" Option:
    </td>
    <td>
    <input type="radio" name="DISABLE_MESSAGE_UPLOAD" value="YES" <? if ($config_values['DISABLE_MESSAGE_UPLOAD'] == "YES") {
        echo "checked";
    } ?>> Yes
    <input type="radio" name="DISABLE_MESSAGE_UPLOAD" value="NO" <? if ($config_values['DISABLE_MESSAGE_UPLOAD'] != "YES") {
        echo "checked";
    } ?>> No
    </td>
    </tr>
    
    
    <tr  >
    <td>
    Disable "Surveys" Option:
    </td>
    <td>
    <input type="radio" name="DISABLE_SURVEYS" value="YES" <? if ($config_values['DISABLE_SURVEYS'] == "YES") {
        echo "checked";
    } ?>> Yes
    <input type="radio" name="DISABLE_SURVEYS" value="NO" <? if ($config_values['DISABLE_SURVEYS'] != "YES") {
        echo "checked";
    } ?>> No
    </td>
    </tr>
    
    
    <tr  >
    <td colspan="2">
    <input class="btn btn-primary" type="submit" value="Save Config Information">
    </td>
    </tr>
    </table>
    
    </form>
    </div>
    <? /************************** SETTINGS TAB *************************/ ?>
    
    <div id="credits" title="Credits"class="tab-pane">
    <br />
    <img src="images/00_logo.jpg" border="0"><br /><br />
    SmoothTorque was written by the following staff from <a href="http://www.venturevoip.com/about.php">VentureVoIP</a>:<br />
    <br />
    <b>Matt Riddell</b>: Development Lead<br />
    <b>Forbes Williams</b>: Backend Algorithms<br />
    <b>Paul Crane</b>: Documentation<br />
    <b>Chris MacGregor</b>: Translations<br />
    <b>Chris Latta</b>: Backend Optimizations<br />
    <br />
    This SmoothTorque website uses the following components:<br />
    <br />
    <a href="http://www.famfamfam.com/lab/icons/silk/" target="_blank">Silk icon set 1.3 from Mark James</a><br />
    <a href="http://www.aditus.nu/jpgraph/" target="_blank">JPGraph from Aditus Consulting</a><br />
    <a href="http://www.dhtmlgoodies.com/scripts/modal-message/demo-modal-message.html" target="_blank">DHTML modal dialog box</a><br />
    <a href="http://www.dhtmlgoodies.com" target="_blank">Ajax dynamic content</a><br />
    <a href="http://www.twilightuniverse.com" target="_blank">Simple AJAX Code-Kit</a><br />
    <a href="http://www.barelyfitz.com/projects/tabber/" target="_blank">JavaScript tabifier</a><br />
    <a href="http://www.softcomplex.com/products/tigra_color_picker/" target="_blank">Tigra Color Picker</a><br />
    <a href="http://jquery.com/" target="_blank">jQuery</a><br />
    <br />
    <br />
    </div>
    </td></tr></table>
    </div>
    </div>
    <?
} ?>
