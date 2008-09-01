<?php
if (! extension_loaded('gd')) {
    echo "It looks like php-gd is not installed.  Installing it will depend ";
    echo "on the package manager you have installed.  Here are a few examples:<br /><br />";
    echo "Fedora/Centos:<br /><code>yum install -y php-gd</code><br /><br />";
    echo "Debian/Ubuntu:<br /><code>apt-get install php-gd</code><br /><br />";
    echo "Gentoo:<br /><code>emerge php-gd</code><br /><br />";
    echo "Mandriva/Mandrake:<br /><code>urpmi php-gd</code><br /><br />";
    exit(0);
}
$current_directory = dirname(__FILE__);
$whoami = exec('whoami');
$config_file = "/stweb.conf";
$default_config = "COLOUR=#323232
TITLE=The SmoothTorque Enterprise Predictive Dialing Platform
LOGO=/images/logo2.png
TEXT=For further information please email sales@venturevoip.com
SOX=/usr/bin/sox
USERID=VentureVoIP
LICENCE=DRFHUJWQIWU
CDR_HOST=localhost
CDR_USER=root
CDR_PASS=
CDR_DB=phoneDB
CDR_TABLE=cdr
MENU_HOME=Home
MENU_CAMPAIGNS=Campaigns
MENU_NUMBERS=Numbers
MENU_DNC=DNC Numbers
MENU_MESSAGES=Messages
MENU_SCHEDULES=Schedules
MENU_CUSTOMERS=Customers
MENU_QUEUES=Queues
MENU_SERVERS=Servers
MENU_TRUNKS=Trunks
MENU_ADMIN=Admin
MENU_LOGOUT=Logout
DATE_COLOUR=#ccccff
MAIN_PAGE_TEXT=To get started, go into your list of campaigns by clicking on the Campaigns tab at the top of this page.
MAIN_PAGE_USERNAME=Username
MAIN_PAGE_PASSWORD=Password
MAIN_PAGE_LOGIN=Login
CURRENCY_SYMBOL=$
PER_MINUTE=Per Minute
USE_BILLING=YES
SPARE1=Spare 1 (unused)
SPARE2=Spare 2 (unused)
SPARE3=Spare 3 (unused)
SPARE4=Spare 4 (unused)
SPARE5=Spare 5 (unused)
ST_MYSQL_HOST=localhost
ST_MYSQL_USER=root
ST_MYSQL_PASS=
ADD_CAMPAIGN=Add Campaign
VIEW_CAMPAIGN=View Campaigns
PER_PAGE=200
NUMBERS_VIEW=View phone numbers
NUMBERS_SYSTEM=Use System Lists
NUMBERS_GENERATE=Generate numbers automatically
NUMBERS_MANUAL=Add number(s) manually
NUMBERS_UPLOAD=Upload numbers from a text file
NUMBERS_EXPORT=Export Phone Numbers
NUMBERS_SEARCH=Search for a phone number
NUMBERS_TITLE=Number List Management
BILLING_TEXT=Billing Logs
CDR_TEXT=Call Details
USE_GENERATE=YES
DNC_NUMBERS_TITLE=Do Not Call List
DNC_VIEW=View existing DNC numbers
DNC_UPLOAD=Upload DNC numbers from a text file
DNC_ADD=Add DNC number(s) manually
PER_LEAD=Price Per Lead
SMTP_HOST=localhost
SMTP_USER=
SMTP_PASS=
USE_SEPARATE_DNC=NO
SMTP_FROM=matt@venturevoip.com";
$comment = "#";
if (!file_exists("../upload_settings.inc")) {
    if (!file_exists("../../upload_settings.inc")) {
        echo "The file ../upload_settings.inc does not exist.  You will need to ";
        echo "copy it from the $current_directory/cron subdirectory by typing ";
        echo "the following commands<br /><br />";
        echo "<code>cp $current_directory/cron/upload_settings.inc $current_directory/../</code>";
        exit(0);
    }
}
if (!file_exists("/tmp/uploads")) {
    echo "The directory /tmp/uploads does not exist.  You will need to create ";
    echo "it by typing the following commands<br /><br />";
    echo "<code>mkdir /tmp/uploads<br />";
    echo "chown $whoami /tmp/uploads<br />";
    echo "cp $current_directory/uploads/* /tmp/uploads</code>";
    exit(0);
}
/*$cmd = "ps aux |grep `cat /SmoothTorque/exampled.lock`";*/
if (file_exists("/SmoothTorque/SmoothTorque.version")) {
	$fp2 = fopen("/SmoothTorque/SmoothTorque.version", "r");
	while (!feof($fp2)) {
		$line = trim(fgets($fp2));
		if (strlen($line)>0){
			$version = substr($line,0,strlen($line)-1);
		}
	}
	fclose ($fp2);
    if($version>0){
        $version/=100;
    }
}
/*
if (file_exists("/SmoothTorque/exampled.lock")) {
	$fp3 = fopen("/SmoothTorque/exampled.lock", "r");
	while (!feof($fp2)) {
		$line = trim(fgets($fp3));
			if (strlen($line)>0){
			$pid = $line;
		}
	}
	fclose ($fp3);

	if (is_file("/proc/$pid/status")) {
		echo "Running";
	} else {
		echo "<font color=\"#ff0000\"><center><b>The server is not running</b></center></font>";
	}
} else {
		echo "<font color=\"#ff0000\"><center><b>The server is not running</b></center></font>";*/
/*	echo "<font color=\"#ff0000\"><center>Backend Not running<a      */
/*href=\"startbackend.php\"><b>Start Server</b></a></center></font>";*/
////////}
if (file_exists($config_file)) {
    $fp = fopen($config_file, "r");
    while (!feof($fp)) {
      $line = trim(fgets($fp));
      if ($line && substr($line,0,1)!=$comment) {
        $pieces = explode("=", $line);
        $option = trim($pieces[0]);
        $value = trim($pieces[1]);
        $config_values[$option] = $value;
      }
    }
    fclose($fp);

    // Set Defaults

    if ($config_values['COLOUR'] == "") {
        if (is_writable($config_file)) {

            // In our example we're opening $filename in append mode.
            // The file pointer is at the bottom of the file hence
            // that's where $somecontent will go when we fwrite() it.
            if (!$handle = fopen($config_file, 'w')) {
                 echo "Cannot open file ($filename)";
                 exit;
            }

            // Write $somecontent to our opened file.
            if (fwrite($handle, $default_config) === FALSE) {
                echo "Cannot write to file ($config_file)";
                exit;
            }

            $success = true;
            fclose($handle);

        } else {
            echo "The file $config_file is not writable but it does exist";
            exit(0);
        }
    }
} else {
        if (is_writable($config_file)) {

            // In our example we're opening $filename in append mode.
            // The file pointer is at the bottom of the file hence
            // that's where $somecontent will go when we fwrite() it.
            if (!$handle = fopen($config_file, 'w')) {
                 echo "Cannot open file ($filename)";
                 exit;
            }

            // Write $somecontent to our opened file.
            if (fwrite($handle, $default_config) === FALSE) {
                echo "Cannot write to file ($config_file)";
                exit;
            }

            $success = true;
            fclose($handle);

        } else {
            echo "The file $config_file does not exist and we are unable ";
            echo "to write to that location. Please log in to the system ";
            echo "and type the following commands:<br /><br />";
            echo "<code>touch /stweb.conf</code><br />";
            echo "<code>chown $whoami /stweb.conf</code>";
            exit(0);
        }


}
if ($success) {
    echo "The base config files ($config_file) did not exist, ";
    echo "but were successfully created with default values. ";
    echo "You can either edit that file directly or simply go ";
    echo "to the Admin page in this web interface<br /><br />";
    echo '<a href="main.php">Go to the main page</a>';
    exit(0);
}



if ($config_values['ALLOW_NUMBERS_MANUAL'] == "") {
    $config_values['ALLOW_NUMBERS_MANUAL'] = "NO";
}

if ($config_values['PER_PAGE'] == "") {
    $config_values['PER_PAGE'] = "20";
}

if ($config_values['PER_LEAD'] == "") {
    $config_values['PER_LEAD'] = "Price Per Lead";
}
if ($config_values['DNC_NUMBERS_TITLE'] == "") {
    $config_values['DNC_NUMBERS_TITLE'] = "Do Not Call List";
}
if ($config_values['DNC_VIEW'] == "") {
    $config_values['DNC_VIEW'] = "View existing DNC numbers";
}

if ($config_values['MENU_ADDFUNDS'] == "") {
    $config_values['MENU_ADDFUNDS'] = "Add Funds";
}

if ($config_values['DNC_ADD'] == "") {
    $config_values['DNC_ADD'] = "Add DNC number(s) manually";
}
if ($config_values['USE_SEPARATE_DNC'] == "") {
    $config_values['USE_SEPARATE_DNC'] = "YES";
}


if ($config_values['DNC_UPLOAD'] == "") {
    $config_values['DNC_UPLOAD'] = "Upload DNC numbers from a text file";
}

if ($config_values['NUMBERS_TITLE'] == "") {
    $config_values['NUMBERS_TITLE'] = "Number List Management";
}

if ($config_values['USE_GENERATE'] == "") {
    $config_values['USE_GENERATE'] = "YES";
}

if ($config_values['NUMBERS_VIEW'] == "") {
    $config_values['NUMBERS_VIEW'] = "View phone numbers";
}

if ($config_values['NUMBERS_SYSTEM'] == "") {
    $config_values['NUMBERS_SYSTEM'] = "Use System Lists";
}

if ($config_values['NUMBERS_GENERATE'] == "") {
    $config_values['NUMBERS_GENERATE'] = "Generate numbers automatically";
}

if ($config_values['NUMBERS_MANUAL'] == "") {
    $config_values['NUMBERS_MANUAL'] = "Add number(s) manually";
}

if ($config_values['NUMBERS_UPLOAD'] == "") {
    $config_values['NUMBERS_UPLOAD'] = "Upload numbers from a text file";
}

if ($config_values['NUMBERS_EXPORT'] == "") {
    $config_values['NUMBERS_EXPORT'] = "Export Phone Numbers";
}

if ($config_values['NUMBERS_SEARCH'] == "") {
    $config_values['NUMBERS_SEARCH'] = "Search for a phone number";
}

if ($config_values['SPARE1'] == "") {
    $config_values['SPARE1'] = "Spare 1 (unused)";
}

if ($config_values['SPARE2'] == "") {
    $config_values['SPARE2'] = "Spare 2 (unused)";
}

if ($config_values['SPARE3'] == "") {
    $config_values['SPARE3'] = "Spare 3 (unused)";
}

if ($config_values['SPARE4'] == "") {
    $config_values['SPARE4'] = "Spare 4 (unused)";
}

if ($config_values['SPARE5'] == "") {
    $config_values['SPARE5'] = "Spare 5 (unused)";
}

if ($config_values['MAIN_PAGE_USERNAME'] == "") {
    $config_values['MAIN_PAGE_USERNAME'] = "Username";
}

if ($config_values['MAIN_PAGE_PASSWORD'] == "") {
    $config_values['MAIN_PAGE_PASSWORD'] = "Password";
}

if ($config_values['MAIN_PAGE_LOGIN'] == "") {
    $config_values['MAIN_PAGE_LOGIN'] = "Login";
}

if ($config_values['ADD_CAMPAIGN'] == "") {
    $config_values['ADD_CAMPAIGN'] = "Add Campaign";
}

if ($config_values['VIEW_CAMPAIGN'] == "") {
    $config_values['VIEW_CAMPAIGN'] = "View Campaigns";
}

if ($config_values['CDR_TEXT'] == "") {
    $config_values['CDR_TEXT'] = "Call Details";
}

if ($config_values['BILLING_TEXT'] == "") {
    $config_values['BILLING_TEXT'] = "Billing Logs";
}

if ($config_values['SMTP_HOST'] == "") {
    $config_values['SMTP_HOST'] = "localhost";
}
if ($config_values['SMTP_FROM'] == "") {
    $config_values['SMTP_FROM'] = "user@mydomain.com";
}

?>
<?
 function _get_browser()
{
  $browser = array ( //reversed array
   "OPERA",
   "MSIE",            // parent
   "NETSCAPE",
   "FIREFOX",
   "SAFARI",
   "KONQUEROR",
   "MOZILLA"        // parent
  );

  $info[browser] = "OTHER";

  foreach ($browser as $parent)
  {
   if ( ($s = strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), $parent)) !== FALSE )
   {
     $f = $s + strlen($parent);
     $version = substr($_SERVER['HTTP_USER_AGENT'], $f, 5);
     $version = preg_replace('/[^0-9,.]/','',$version);

     $info[browser] = $parent;
     $info[version] = $version;
     break; // first match wins
   }
  }

  return $info;
}
$user=$_COOKIE[user];
$level=$_COOKIE[level];
//echo "".$user." - ".$_COOKIE["loggedin"];

include "admin/db_config.php";
mysql_select_db("SineDialer", $link);
if ($_COOKIE["loggedin"]==sha1("LoggedIn".$user)){
    // Logged In
    $loggedin=true;
    setcookie("loggedin",sha1("LoggedIn".$user),time()+60000);
    setcookie("user",$user,time()+60000);
    setcookie("level",$level,time()+60000);
	echo "<!--Version: $version<br />-->";
$sql = 'SELECT value FROM config WHERE parameter=\'backend\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$backend = mysql_result($result,0,'value');

$self=$_SERVER['PHP_SELF'];
//echo $self;
    $menu='<CENTER>
    <table border="0" cellpadding="3" cellspacing="0"><TR HEIGHT="10">';
        //=======================================================================================================
    // Home
    //=======================================================================================================
    if ($self=="/main.php"){
        $menu.='<td style="background-image: url(/images/clb.gif);"></td>';
        $thead="thead";
    } else {
        $menu.='<TD CLASS="theadl2" WIDTH=0></TD>';
        $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
    }

    $menu.='<TD class="'.$thead.'" height=27><A HREF="/main.php"><img src="/images/house.png" border="0" align="left">'.str_replace(" ","&nbsp;",$config_values['MENU_HOME']).'</A>&nbsp;</TD>';

    if ($level==sha1("level100")||$level==sha1("level0")){
        //=======================================================================================================
    // Campaigns
    //=======================================================================================================
    if ($self=="/campaigns.php"||$self=="/report.php"||$self=="/resetlist.php"||$self=="/list.php"||$self=="/deletecampaign.php"||$self=="/editcampaign.php"||$self=="/addcampaign.php"||$self=="/stopcampaign.php"||$self=="/startcampaign.php"||$self=="/test.php"
    ){
        $thead="thead";
    } else {
        $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
    }
    $menu.='<TD class="'.$thead.'"><A HREF="/campaigns.php"><img src="/images/folder.png" border="0" align="left">'.str_replace(" ","&nbsp;",$config_values['MENU_CAMPAIGNS']).'</A>&nbsp;</TD>';

    //=======================================================================================================
    // Numbers
    //=======================================================================================================
    if ($self=="/addnumbers.php"||$self=="/serverlist.php"||$self=="/numbers.php"||$self=="/deletenumber.php"||$self=="/viewnumbers.php"||$self == "/gennumbers.php"||$self == "/upload.php"||$self =="//receive.php"||$self=="/resetnumber.php"){
        $thead="thead";
    } else {
        $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
    }
    $menu.='<TD class="'.$thead.'"><A HREF="/numbers.php"><img src="/images/telephone.png" border="0" align="left">'.str_replace(" ","&nbsp;",$config_values['MENU_NUMBERS']).'</A>&nbsp;</TD>';

    //=======================================================================================================
    // DNC Numbers
    //=======================================================================================================
    if ($self=="/adddncnumbers.php"||$self=="/dncnumbers.php"||$self=="/deletedncnumber.php"||$self=="/viewdncnumbers.php"||$self == "/gendncnumbers.php"||$self == "/uploaddnc.php"||$self =="//receivednc.php"){
        $thead="thead";
    } else {
        $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
    }
    $menu.='<TD class="'.$thead.'"><A HREF="/dncnumbers.php"><img src="/images/telephone_error.png" border="0" align="left">'.str_replace(" ","&nbsp;",$config_values['MENU_DNC']).'</A>&nbsp;</TD>';

    //=======================================================================================================
    // Messages
    //=======================================================================================================
    if ($self=="/editmessage.php"||$self=="/addmessage.php"||$self=="/deleteMessage.php"||$self=="/messages.php"||$self=="/uploadmessage.php"){
        $thead="thead";
    } else {
        $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
    }
    $menu.='<TD class="'.$thead.'"><A HREF="/messages.php"><img src="/images/sound.png" border="0" align="left">'.str_replace(" ","&nbsp;",$config_values['MENU_MESSAGES']).'</A>&nbsp;</TD>';
    //=======================================================================================================
    // Schedules
    //=======================================================================================================
    if ($self=="/editschedule.php"||$self=="/addschedule.php"||$self=="/deleteschedule.php"||$self=="/schedule.php"){
        $thead="thead";
    } else {
        $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
    }
    $menu.='<TD class="'.$thead.'"><A HREF="/schedule.php"><img src="/images/clock.png" border="0" align="left">'.str_replace(" ","&nbsp;",$config_values['MENU_SCHEDULES']).'</A>&nbsp;</TD>';
    if ($level==sha1("level100")){

        //=======================================================================================================
        // Customers
        //=======================================================================================================
        if ($self=="/deletecustomer.php"||$self=="/addcustomer.php"||$self=="/customers.php"||$self=="/editcustomer.php"){
            $thead="thead";
        } else {
            $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
        }
        $menu.='<TD class="'.$thead.'"><A HREF="/customers.php"><img src="/images/group.png" border="0" align="left">'.str_replace(" ","&nbsp;",$config_values['MENU_CUSTOMERS']).'</A>&nbsp;</TD>';
        //=======================================================================================================

        //=======================================================================================================
        // Queues
        //=======================================================================================================
        if ($self=="/deletequeue.php"||$self=="/addqueue.php"||$self=="/queues.php"||$self=="/editqueue.php"){
            $thead="thead";
        } else {
            $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
        }
        $menu.='<TD class="'.$thead.'"><A HREF="/queues.php"><img src="/images/database.png" border="0" align="left">'.str_replace(" ","&nbsp;",$config_values['MENU_QUEUES']).'</A>&nbsp;</TD>';
        //=======================================================================================================

        //=======================================================================================================
        // Servers
        //=======================================================================================================
        if ($self=="/deleteserver.php"||$self=="/addserver.php"||$self=="/servers.php"||$self=="/editserver.php"){
            $thead="thead";
        } else {
            $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
        }
        if ($backend == 0){
        $menu.='<TD class="'.$thead.'"><A HREF="/servers.php"><img src="/images/server.png" border="0" align="left">'.str_replace(" ","&nbsp;",$config_values['MENU_SERVERS']).'</A>&nbsp;</TD>';
        }
        //=======================================================================================================


        //=======================================================================================================
        // Trunks
        //=======================================================================================================
        if ($self=="/trunks.php"||$self=="/edittrunk.php"||$self=="/addtrunk.php"||$self=="/setdefault.php"||$self=="/deletetrunk.php"){
            $thead="thead";
        } else {
            $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
        }
        $menu.='<TD class="'.$thead.'"><A HREF="/trunks.php"><img src="/images/telephone_link.png" border="0" align="left">'.str_replace(" ","&nbsp;",$config_values['MENU_TRUNKS']).'</A>&nbsp;</TD>';
        //=======================================================================================================

        //=======================================================================================================
        // Admin
        //=======================================================================================================
        if ($self=="/config.php"||$self=="/setparameter.php"){
            $thead="thead";
        } else {
            $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
        }
        $menu.='<TD class="'.$thead.'"><A HREF="/config.php"><img src="/images/cog.png" border="0" align="left">'.str_replace(" ","&nbsp;",$config_values['MENU_ADMIN']).'</A>&nbsp;</TD>';
        //=======================================================================================================

    }
    //    <TD class="thead2"><A HREF="prefs.php">Preferences</A>&nbsp;&nbsp;</TD>
    } else if ($level==sha1("level10")){
        /*//=======================================================================================================
        // Customers
        //=======================================================================================================
        if ($self=="/deletecustomer.php"||$self=="/addcustomer.php"||$self=="/customers.php"||$self=="/editcustomer.php"){
            $thead="thead";
        } else {
            $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
        }
        $menu.='<TD class="'.$thead.'"><A HREF="/customers.php"><img src="/images/group.png" border="0" align="left">'.$config_values['MENU_CUSTOMERS'].'</A>&nbsp;</TD>';
        //=======================================================================================================
        */
        //echo "Billing Administrator Login";
        // This is for people who are logged in as a billing administrator
        //=======================================================================================================
        // Add Funds
        //=======================================================================================================
        if ($self=="/addfunds.php"){
            $thead="thead";
        } else {
            $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
        }
        $menu.='<TD class="'.$thead.'"><A HREF="/addfunds.php"><img src="/images/group.png" border="0" align="left">'.str_replace(" ","&nbsp;",$config_values['MENU_ADDFUNDS']).'</A>&nbsp;</TD>';
        //=======================================================================================================

    }
    $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";

    $menu.='<TD height="1" class="'.$thead.'"><A HREF="/logout.php"><img src="/images/door_in.png" border="0" align="left">'.str_replace(" ","&nbsp;",$config_values['MENU_LOGOUT']).'</A>&nbsp;</TD><TD CLASS="theadr2" WIDTH=0></TD></TR></table>

    ';
    //<TD class="thead2"><A HREF="stats.php">Live Statistics</A>&nbsp;&nbsp;</TD>

} else {
    $loggedin=false;
    // Not Logged In
    $myPage=$_SERVER[PHP_SELF];
    if ($myPage=="/index.php"|$myPage=="/login.php"){
        //echo "LOGGING IN ".$user." - ".$_COOKIE["loggedin"];
    } else {
?>    <META HTTP-EQUIV=REFRESH CONTENT="0; URL=/index.php?redirect=<?echo $myPage;?>">
<?
        exit(0);
    }
}
?>
<HTML>
<HEAD>

<?
if ($self == "/test.php" || $self == "/report.php") {
?>
<META HTTP-EQUIV="Pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Expires" CONTENT="-1">
<?
}
?>



<TITLE><?echo $config_values['TITLE'];?></TITLE>
<link rel="stylesheet" type="text/css" href="/css/default.css">
<?/*
<link rel="stylesheet" type="text/css" href="/css/stylelogin.css">
<link rel="stylesheet" href="/css/modal-message.css" type="text/css">
<link rel="stylesheet" href="/upload.css" type="text/css" media="screen" title="Upload" charset="utf-8" />
*/?>
<link rel="shortcut icon" href="/favicon.ico">

<!-- Javascript includes -->
<?/*
<script type="text/javascript" src="/js/ajax-dynamic-content.js"></script>
<script type="text/javascript" src="/js/modal-message.js"></script>
<script type="text/javascript" src="/js/ajax.js"></script>
<script type="text/javascript" src="/usableforms.js"></script>
<script language="javascript" type="text/javascript" src="/upload.js"></script>*/?>
<script type="text/javascript" src="/ajax/picker.js"></script>
<script type="text/javascript" src="/header.js"></script>

<?/*
<script src="prototype.js" type="text/javascript"></script>
*/
?>
<?/*
THIS IS CAUSING AN ERROR!
<script src="js/eep.js" type="text/javascript"></script>
*/?>
<script language="JavaScript" type="text/JavaScript">
function hideshow(name){
	var opened = "./images/open.png";
	var closed = "./images/closed.png";

	var element = document.getElementById(name);
	var img = document.getElementsByName("img_"+name);
	if(element.style.display == "none"){
		img[0].src = opened;
		element.style.display = "";
	}else{
		img[0].src = closed;
		element.style.display = "none";
	}
}
function whatPaySelected(myval){
    if (document.all2) {
        document.all['mode'].style.display = "visible";
    } else {
        document.getElementById('mode').style.display='';
    }

if (myval == '0') {
    if (document.all2) {
        document.all['mode'].style.display = "none";
    } else {
        document.getElementById('mode').style.display='none';
    }
    if (document.all2) {
        document.all['xx1'].style.display = "none";
        document.all['xx2'].style.display = "none";
        document.all['xx3'].style.display = "none";
        document.all['xx4'].style.display = "none";
        document.all['xx5'].style.display = "none";
        document.all['xx6'].style.display = "visible";
    } else {
        document.getElementById('xx1').style.display='none';
        document.getElementById('xx2').style.display='none';
        document.getElementById('xx3').style.display='none';
        document.getElementById('xx4').style.display='none';
        document.getElementById('xx5').style.display='none';
        document.getElementById('xx6').style.display='';
    }
} else if (myval == '10') {
    if (document.all2) {
        document.all['xx1'].style.display = "visible";
        document.all['xx2'].style.display = "visible";
        document.all['xx3'].style.display = "visible";
        document.all['xx4'].style.display = "visible";
        document.all['xx5'].style.display = "visible";
        document.all['xx6'].style.display = "visible";
    } else {
        document.getElementById('xx1').style.display='';
        document.getElementById('xx2').style.display='';
        document.getElementById('xx3').style.display='';
        document.getElementById('xx4').style.display='';
        document.getElementById('xx5').style.display='';
        document.getElementById('xx6').style.display='';
    }
} else if (myval == '11') {
    if (document.all2) {
        document.all['xx1'].style.display = "visible";
        document.all['xx2'].style.display = "visible";
        document.all['xx3'].style.display = "visible";
        document.all['xx4'].style.display = "visible";
        document.all['xx5'].style.display = "visible";
        document.all['xx6'].style.display = "visible";
    } else {
        document.getElementById('xx1').style.display='';
        document.getElementById('xx2').style.display='';
        document.getElementById('xx3').style.display='';
        document.getElementById('xx4').style.display='';
        document.getElementById('xx5').style.display='';
        document.getElementById('xx6').style.display='';
    }
} else if (myval == '12') {
    if (document.all2) {
        document.all['xx1'].style.display = "visible";
        document.all['xx2'].style.display = "visible";
        document.all['xx3'].style.display = "visible";
        document.all['xx4'].style.display = "visible";
        document.all['xx5'].style.display = "visible";
        document.all['xx6'].style.display = "visible";
    } else {
        document.getElementById('xx1').style.display='';
        document.getElementById('xx2').style.display='';
        document.getElementById('xx3').style.display='';
        document.getElementById('xx4').style.display='';
        document.getElementById('xx5').style.display='';
        document.getElementById('xx6').style.display='';
    }
} else if (myval == '13') {
    if (document.all2) {
        document.all['xx1'].style.display = "visible";
        document.all['xx2'].style.display = "visible";
        document.all['xx3'].style.display = "visible";
        document.all['xx4'].style.display = "visible";
        document.all['xx5'].style.display = "visible";
        document.all['xx6'].style.display = "visible";
    } else {
        document.getElementById('xx1').style.display='';
        document.getElementById('xx2').style.display='';
        document.getElementById('xx3').style.display='';
        document.getElementById('xx4').style.display='';
        document.getElementById('xx5').style.display='';
        document.getElementById('xx6').style.display='';
    }
} else if (myval == '14') {
    if (document.all2) {
        document.all['xx1'].style.display = "visible";
        document.all['xx2'].style.display = "visible";
        document.all['xx3'].style.display = "visible";
        document.all['xx4'].style.display = "visible";
        document.all['xx5'].style.display = "visible";
        document.all['xx6'].style.display = "visible";
    } else {
        document.getElementById('xx1').style.display='';
        document.getElementById('xx2').style.display='';
        document.getElementById('xx3').style.display='';
        document.getElementById('xx4').style.display='';
        document.getElementById('xx5').style.display='';
        document.getElementById('xx6').style.display='';
    }
} else if (myval == '15') {
    if (document.all2) {
        document.all['xx1'].style.display = "visible";
        document.all['xx2'].style.display = "visible";
        document.all['xx3'].style.display = "visible";
        document.all['xx4'].style.display = "visible";
        document.all['xx5'].style.display = "visible";
        document.all['xx6'].style.display = "visible";
    } else {
        document.getElementById('xx1').style.display='';
        document.getElementById('xx2').style.display='';
        document.getElementById('xx3').style.display='';
        document.getElementById('xx4').style.display='';
        document.getElementById('xx5').style.display='';
        document.getElementById('xx6').style.display='';
    }
}  else if (myval == '1') {
    if (document.all2) {
        document.all['xx1'].style.display = "none";
        document.all['xx2'].style.display = "none";
        document.all['xx3'].style.display = "visible";
        document.all['xx4'].style.display = "none";
        document.all['xx5'].style.display = "visible";
        document.all['xx6'].style.display = "visible";
    } else {
        document.getElementById('xx1').style.display='none';
        document.getElementById('xx2').style.display='none';
        document.getElementById('xx3').style.display='';
        document.getElementById('xx4').style.display='none';
        document.getElementById('xx5').style.display='';
        document.getElementById('xx6').style.display='';
    }
} else if (myval == '2') {
    if (document.all2) {
        document.all['xx1'].style.display = "visible";
        document.all['xx2'].style.display = "none";
        document.all['xx3'].style.display = "none";
        document.all['xx4'].style.display = "none";
        document.all['xx5'].style.display = "visible";
        document.all['xx6'].style.display = "visible";
    } else {
        document.getElementById('xx1').style.display='';
        document.getElementById('xx2').style.display='none';
        document.getElementById('xx3').style.display='none';
        document.getElementById('xx4').style.display='none';
        document.getElementById('xx5').style.display='';
        document.getElementById('xx6').style.display='';
    }
} else if (myval == '3') {
    if (document.all2) {
        document.all['xx1'].style.display = "visible";
        document.all['xx2'].style.display = "visible";
        document.all['xx3'].style.display = "visible";
        document.all['xx4'].style.display = "visible";
        document.all['xx5'].style.display = "visible";
        document.all['xx6'].style.display = "visible";
    } else {
        document.getElementById('xx1').style.display='';
        document.getElementById('xx2').style.display='';
        document.getElementById('xx3').style.display='';
        document.getElementById('xx4').style.display='';
        document.getElementById('xx5').style.display='';
        document.getElementById('xx6').style.display='';
    }
} else if (myval == '4') {
    if (document.all2) {
        document.all['xx1'].style.display = "visible";
        document.all['xx2'].style.display = "visible";
        document.all['xx3'].style.display = "none";
        document.all['xx4'].style.display = "visible";
        document.all['xx5'].style.display = "visible";
        document.all['xx6'].style.display = "visible";
    } else {
        document.getElementById('xx1').style.display='';
        document.getElementById('xx2').style.display='';
        document.getElementById('xx3').style.display='none';
        document.getElementById('xx4').style.display='';
        document.getElementById('xx5').style.display='';
        document.getElementById('xx6').style.display='';
    }
} else if (myval == '5') {
    if (document.all2) {
        document.all['xx1'].style.display = "visible";
        document.all['xx2'].style.display = "none";
        document.all['xx3'].style.display = "visible";
        document.all['xx4'].style.display = "none";
        document.all['xx5'].style.display = "visible";
        document.all['xx6'].style.display = "visible";
    } else {
        document.getElementById('xx1').style.display='';
        document.getElementById('xx2').style.display='none';
        document.getElementById('xx3').style.display='';
        document.getElementById('xx4').style.display='none';
        document.getElementById('xx5').style.display='';
        document.getElementById('xx6').style.display='';
    }
} else if (myval == '6') {
    if (document.all2) {
        document.all['xx1'].style.display = "visible";
        document.all['xx2'].style.display = "none";
        document.all['xx3'].style.display = "none";
        document.all['xx4'].style.display = "none";
        document.all['xx5'].style.display = "visible";
        document.all['xx6'].style.display = "visible";
    } else {
        document.getElementById('xx1').style.display='';
        document.getElementById('xx2').style.display='none';
        document.getElementById('xx3').style.display='none';
        document.getElementById('xx4').style.display='none';
        document.getElementById('xx5').style.display='';
        document.getElementById('xx6').style.display='';
    }
} else if (myval == '7') {
    if (document.all2) {
        document.all['mode'].style.display = "none";
    } else {
        document.getElementById('mode').style.display='none';
    }
    if (document.all2) {
        document.all['xx1'].style.display = "none";
        document.all['xx2'].style.display = "visible";
        document.all['xx3'].style.display = "none";
        document.all['xx4'].style.display = "none";
        document.all['xx5'].style.display = "none";
        document.all['xx6'].style.display = "visible";
    } else {
        document.getElementById('xx1').style.display='none';
        document.getElementById('xx2').style.display='';
        document.getElementById('xx3').style.display='none';
        document.getElementById('xx4').style.display='none';
        document.getElementById('xx5').style.display='none';
        document.getElementById('xx6').style.display='';
    }
} else if (myval == '8') {
    if (document.all2) {
        document.all['mode'].style.display = "none";
    } else {
        document.getElementById('mode').style.display='none';
    }
    if (document.all2) {
        document.all['fax'].style.display = "visible";
    } else {
        document.getElementById('fax').style.display='';
    }


    if (document.all2) {
        document.all['xx1'].style.display = "none";
        document.all['xx2'].style.display = "none";
        document.all['xx3'].style.display = "none";
        document.all['xx4'].style.display = "none";
        document.all['xx5'].style.display = "visible";
        document.all['xx6'].style.display = "none";
    } else {
        document.getElementById('xx1').style.display='none';
        document.getElementById('xx2').style.display='none';
        document.getElementById('xx3').style.display='none';
        document.getElementById('xx4').style.display='none';
        document.getElementById('xx5').style.display='';
        document.getElementById('xx6').style.display='none';
    }
} else if (myval == '9') {
    if (document.all2) {
        document.all['mode'].style.display = "none";
    } else {
        document.getElementById('mode').style.display='none';
    }
    if (document.all2) {
        document.all['xx1'].style.display = "none";
        document.all['xx2'].style.display = "visible";
        document.all['xx3'].style.display = "none";
        document.all['xx4'].style.display = "none";
        document.all['xx5'].style.display = "visible";
        document.all['xx6'].style.display = "visible";
    } else {
        document.getElementById('xx1').style.display='none';
        document.getElementById('xx2').style.display='';
        document.getElementById('xx3').style.display='none';
        document.getElementById('xx4').style.display='none';
        document.getElementById('xx5').style.display='';
        document.getElementById('xx6').style.display='';
    }
} else if (myval == '054') {
    if (document.all2) {
        document.all['xx1'].style.display = "visible";
        document.all['xx2'].style.display = "visible";
        document.all['xx3'].style.display = "visible";
        document.all['xx4'].style.display = "visible";
        document.all['xx5'].style.display = "visible";
        document.all['xx6'].style.display = "visible";
    } else {
        document.getElementById('xx1').style.display='';
        document.getElementById('xx2').style.display='';
        document.getElementById('xx3').style.display='';
        document.getElementById('xx4').style.display='';
        document.getElementById('xx5').style.display='';
        document.getElementById('xx6').style.display='';
    }
} else if (myval == '-1') {
    if (document.all2) {
        document.all['mode'].style.display = "none";
    } else {
        document.getElementById('mode').style.display='none';
    }
    if (document.all2) {
        document.all['xx1'].style.display = "none";
        document.all['xx2'].style.display = "none";
        document.all['xx3'].style.display = "none";
        document.all['xx4'].style.display = "none";
        document.all['xx5'].style.display = "none";
        document.all['xx6'].style.display = "none";
    } else {
        document.getElementById('xx1').style.display='none';
        document.getElementById('xx2').style.display='none';
        document.getElementById('xx3').style.display='none';
        document.getElementById('xx4').style.display='none';
        document.getElementById('xx5').style.display='none';
        document.getElementById('xx6').style.display='none';
    }
}
}
</script>
<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">
    <!--
    function f_selectAll (s_select) {
var e_select = document.forms['customer'].elements[s_select];
for (var i = 0; i < e_select.options.length; i++)
e_select.options[i].selected = true;
}

    function MoveOption(objSourceElement, objTargetElement)
    {
        var aryTempSourceOptions = new Array();
        var x = 0;

        //looping through source element to find selected options
        for (var i = 0; i < objSourceElement.length; i++) {
            if (objSourceElement.options[i].selected) {
                //need to move this option to target element
                var intTargetLen = objTargetElement.length++;
                objTargetElement.options[intTargetLen].text = objSourceElement.options[i].text;
                objTargetElement.options[intTargetLen].value = objSourceElement.options[i].value;
            }
            else {
                //storing options that stay to recreate select element
                var objTempValues = new Object();
                objTempValues.text = objSourceElement.options[i].text;
                objTempValues.value = objSourceElement.options[i].value;
                aryTempSourceOptions[x] = objTempValues;
                x++;
            }
        }

        //resetting length of source
        objSourceElement.length = aryTempSourceOptions.length;
        //looping through temp array to recreate source select element
        for (var i = 0; i < aryTempSourceOptions.length; i++) {
            objSourceElement.options[i].text = aryTempSourceOptions[i].text;
            objSourceElement.options[i].value = aryTempSourceOptions[i].value;
            objSourceElement.options[i].selected = false;
        }
    }
    //-->
    </SCRIPT>

<?/*
THIS IS CAUSING AN ERROR!
*/?>

<style>
<!--
.dragme{position:relative;}
-->
</style>
<STYLE type="text/css">
  DIV.mypars {text-align: left}
</STYLE>


<style type="text/css">
<!--

/*core drop shadow rules*/
.wrap1, .wrap2, .wrap3 {
        display:inline-table;
        /* \*/display:block;/**/}
.wrap1 {
        float:left;
        background:url(images/shadow.gif) right bottom no-repeat;}
.wrap2 {background:url(images/corner_bl.gif) left bottom no-repeat;}
.wrap3 {
        padding:0 8px 8px 0;
        background:url(images/corner_tr.gif) right top no-repeat;}
.wrap3 table {
       display:block;
        border:1px solid #ccc;

        border-color:#efefef #ccc #ccc #efefef;}
#v6 .wrap1 {background:url(images/shadow.gif) right bottom no-repeat;}
#v6 .wrap2 {background:url(images/corner_bl.gif) -4px 100% no-repeat;}
#v6 .wrap3 {
        padding:0 16px 16px 0;
        background:url(images/corner_tr.gif) 100% -4px no-repeat;}
.example {clear:both;
}
  table.center {}

-->
</style>


</HEAD>
<BODY BGCOLOR="<?echo $config_values['COLOUR'];?>" onLoad="hideItem('hideShow');">
<?

if (isset($menu)){
   ?>
<CENTER>    <img src="<?echo $config_values['LOGO'];?>">


    <?
    echo $menu;
    flush();
}
?>

<TABLE WIDTH=100% HEIGHT="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0" class="tborder3">
<TR VALIGN="TOP" >
<TD BGCOLOR="#ffffff">
<?
$sql = "SELECT credit, creditlimit from billing where accountcode = 'stl-$_COOKIE[user]'";
$result = mysql_query($sql,$link);
if (mysql_num_rows($result)==0){
    $credit = $config_values['CURRENCY_SYMBOL']." 0.00";
    $creditlimit = 0;
    $postpay = 0;
} else {
    $credit = $config_values['CURRENCY_SYMBOL']." ".number_format(mysql_result($result,0,'credit'),2);
    $creditlimit = $config_values['CURRENCY_SYMBOL']." ".number_format(mysql_result($result,0,'creditlimit'),2);
    $postpay = 1;
}
if ($loggedin){
    if ( $config_values['USE_BILLING'] == "YES") {
        if ($postpay == 1) {
            echo "<center><font color=\"".$config_values['DATE_COLOUR']."\"><a href=\"/help/index.php\"><img src=\"/images/help.png\" border=\"0\"><b> Help</b></a> ".date('l dS \of F Y h:i:sA')." Credit: $credit Credit Limit: $creditlimit <a href=\"/viewcdr.php\"><img src=\"/images/table.png\" border=\"0\"> ".$config_values['CDR_TEXT']."</a> <a href=\"/billinglog_account.php\"><img src=\"/images/cart_edit.png\" border=\"0\"> ".$config_values['BILLING_TEXT']."</a></font><br /></center>";
        } else {
            echo "<center><font color=\"".$config_values['DATE_COLOUR']."\"><a href=\"/help/index.php\"><img src=\"/images/help.png\" border=\"0\"><b> Help</b></a> ".date('l dS \of F Y h:i:sA')." Credit: $credit <a href=\"/viewcdr.php\"><img src=\"/images/table.png\" border=\"0\"> ".$config_values['CDR_TEXT']."</a> <a href=\"/billinglog_account.php\"><img src=\"/images/cart_edit.png\" border=\"0\"> ".$config_values['BILLING_TEXT']."</a></font><br /></center>";
        }
    } else {
        echo "<center><font color=\"".$config_values['DATE_COLOUR']."\"><a href=\"/help/index.php\"><img src=\"/images/help.png\" border=\"0\"><b> Help</b></a> ".date('l dS \of F Y h:i:sA')."</font><br /></center>";
    }
}
?>
