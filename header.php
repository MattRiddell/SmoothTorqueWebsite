<?php

/* Find out what the base directory name is for two reasons:
    1. So we can include files
    2. So we can explain how to set up things that are missing */
$current_directory = dirname(__FILE__);

/* What page we are currently on - this is used to highlight the menu
   system as well as to not cache certain pages like the graphs */
$self=$_SERVER['PHP_SELF'];

/* Load in the functions we may need - these are the list of available
   custom functions - for more information, read the comments in the
   functions.php file - most functions are in their own file in the
   functions subdirectory */
require "/".$current_directory."/functions/functions.php";

/* Find out what user the webserver is running as */
$whoami = exec('whoami');

/* Get Cookies */
$language=$_COOKIE[language];
$user=$_COOKIE[user];
$level=$_COOKIE[level];

/* Make sure we have the environment set up correctly - if not give the
   user some information about how to remedy the situation - these
   functions are mainly for an installer */
check_for_gd_library();
check_for_upload_settings();
check_for_upload_directory($whoami);

/* This was temporarily used to check for the running of the backend.
   Not currently used because of permission problems, but may be
   resurrected in the future */
/*$cmd = "ps aux |grep `cat /SmoothTorque/exampled.lock`";*/

/* See if we can find out the version of the SmoothTorque backend that is
   currently installed - we use this so we can inform about updates etc */
$version = get_backend_version();

/* Config File Parsing */
require "default_configs.php";

/* Load in the database connection values and chose the database name */
include "admin/db_config.php";
mysql_select_db("SineDialer", $link);

/* Check if the user is logged in */
if (!($_COOKIE["loggedin"]==sha1("LoggedIn".$user))){
    /* The user is not logged in */
    $loggedin=false;
    $myPage=$_SERVER[PHP_SELF];
    if (!($myPage=="/index.php"|$myPage=="/login.php")){
        /* Because header is included in login and the main page we don't
           want to redirect them constantly while they are trying to log
           in.  If they are not on these pages and they are not logged in
           they should be sent to the main page - but we remember via the
           redirect variable the page they were trying to get to. */
        ?><META HTTP-EQUIV=REFRESH CONTENT="0; URL=/index.php?redirect=<?echo $myPage;?>"><?
        exit(0);
    } else {
        exit(0);
    }
}

/* If we have made it this far then the user is logged in - all future
   code assumes this. */
$loggedin=true;

/* Set all the cookies again to extend login time - they're not inactive */
setcookie("loggedin",sha1("LoggedIn".$user),time()+60000);
setcookie("user",$user,time()+60000);
setcookie("level",$level,time()+60000);
setcookie("language",$language,time()+60000);

/* Get the menu structure based on the config values, the current
   page, and the security level of the person viewing the page */
$menu = get_menu_html($config_values, $self, $level);

/* Start printing out the HTML page */
?>
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<TITLE><?echo $config_values['TITLE'];?></TITLE>
<?
/* If we are on one of the realtime graph pages we don't want it to be cached */
if ($self == "/test.php" || $self == "/report.php") {?>
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="-1">
<?}?>
<script type="text/javascript" src="/tabber.js"></script>
<link rel="stylesheet" type="text/css" href="/css/style.css">
<link rel="stylesheet" href="/example.css" TYPE="text/css" MEDIA="screen">
<link rel="stylesheet" href="/example-print.css" TYPE="text/css" MEDIA="print">
<link rel="stylesheet" type="text/css" href="/css/default.css">
<link rel="shortcut icon" href="/favicon.ico">
<!-- Javascript includes -->
<script type="text/javascript" src="/ajax/picker.js"></script>
<script type="text/javascript" src="/header.js"></script>
</head>

<body bgcolor="<?echo $config_values['COLOUR'];?>" >

<?if (isset($menu)){?>
<center><img src="<?echo $config_values['LOGO'];?>">
<?echo $menu;flush();}?>

<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0" class="tborder3">
<tr valign="TOP" >
<td bgcolor="#ffffff">
<?
if (!($config_values['USE_BILLING'] == "YES")) {
    /* The billing system is not enabled so don't bother printing links
       related to credit etc */
    echo "<center><font color=\"".$config_values['DATE_COLOUR']."\"><a href=\"/help/index.php\"><img width=\"16\" height=\"16\"  src=\"/images/help.png\" border=\"0\"><b> Help</b></a> ".ucwords(strftime('%A %d %B %Y %H:%M:%S'))."</font><br /></center>";
} else {
    /* Find out how much credit and what the credit limit is for this
       customer */
    $sql = "SELECT credit, creditlimit from billing where accountcode = 'stl-$_COOKIE[user]'";
    $result = mysql_query($sql,$link);
    if (mysql_num_rows($result)==0){
        /* They have no billing account - set to defaults */
        $credit = $config_values['CURRENCY_SYMBOL']." 0.00";
        $creditlimit = 0;
        $postpay = 0;
    } else {
        /* They have a billing account - set up the variables */
        $credit = $config_values['CURRENCY_SYMBOL']." ".number_format(mysql_result($result,0,'credit'),2);
        $creditlimit = $config_values['CURRENCY_SYMBOL']." ".number_format(mysql_result($result,0,'creditlimit'),2);
        $postpay = 1;
    }
    if ($postpay == 1) {
        echo "<center><font color=\"".$config_values['DATE_COLOUR']."\"><a href=\"/help/index.php\"><img width=\"16\" height=\"16\"  src=\"/images/help.png\" border=\"0\"><b> Help</b></a> ".ucwords(strftime('%A %d %B %Y %H:%M:%S'))." Credit: $credit Credit Limit: $creditlimit <a href=\"/viewcdr.php\"><img width=\"16\" height=\"16\" src=\"/images/table.png\" border=\"0\"> ".$config_values['CDR_TEXT']."</a> <a href=\"/billinglog_account.php\"><img width=\"16\" height=\"16\" src=\"/images/cart_edit.png\" border=\"0\"> ".$config_values['BILLING_TEXT']."</a></font><br /></center>";
    } else {
        echo "<center><font color=\"".$config_values['DATE_COLOUR']."\"><a href=\"/help/index.php\"><img width=\"16\" height=\"16\"  src=\"/images/help.png\" border=\"0\"><b> Help</b></a> ".ucwords(strftime('%A %d %B %Y %H:%M:%S'))." Credit: $credit <a href=\"/viewcdr.php\"><img width=\"16\" height=\"16\"  src=\"/images/table.png\" border=\"0\"> ".$config_values['CDR_TEXT']."</a> <a href=\"/billinglog_account.php\"><img width=\"16\" height=\"16\"  src=\"/images/cart_edit.png\" border=\"0\"> ".$config_values['BILLING_TEXT']."</a></font><br /></center>";
    }
}
?>
