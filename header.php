<?php
//$cwd = dirname(__FILE__);
$current_directory = dirname(__FILE__);
require "/".$current_directory."/functions/functions.php";

$whoami = exec('whoami');

/* Get Cookies */
$language=$_COOKIE[language];
$user=$_COOKIE[user];
$level=$_COOKIE[level];



if (!extension_loaded('gd')) {
    echo "It looks like php-gd is not installed.  Installing it will depend ";
    echo "on the package manager you have installed.  Here are a few examples:<br /><br />";
    echo "Fedora/Centos:<br /><code>yum install -y php-gd</code><br /><br />";
    echo "Debian/Ubuntu:<br /><code>apt-get install php-gd</code><br /><br />";
    echo "Gentoo:<br /><code>emerge php-gd</code><br /><br />";
    echo "Mandriva/Mandrake:<br /><code>urpmi php-gd</code><br /><br />";
    exit(0);
}
if (!file_exists("../upload_settings.inc")) {
    if (!file_exists("../../upload_settings.inc")) {
        echo "The file ../upload_settings.inc does not exist.  You will need to ";
        echo "copy it from the $current_directory/cron subdirectory by typing ";
        echo "the following commands<br /><br />";
        echo "<code>cp $current_directory/cron/upload_settings.inc $current_directory/../</code>";
        exit(0);
    }
}
if (!file_exists("/var/tmp/uploads")) {
    echo "The directory /var/tmp/uploads does not exist.  You will need to create ";
    echo "it by typing the following commands<br /><br />";
    echo "<code>mkdir /var/tmp/uploads<br />";
    echo "chown $whoami /var/tmp/uploads<br />";
    echo "cp $current_directory/uploads/* /var/tmp/uploads</code>";
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
 * Config File Parsing
 */


require "default_configs.php";

function _get_browser() {
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

    foreach ($browser as $parent) {
        if ( ($s = strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), $parent)) !== FALSE ) {
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



include "admin/db_config.php";
mysql_select_db("SineDialer", $link);


/* Check if the user is logged in */
if (!($_COOKIE["loggedin"]==sha1("LoggedIn".$user))){
    // Not Logged In
    $loggedin=false;
    $myPage=$_SERVER[PHP_SELF];
    if ($myPage=="/index.php"|$myPage=="/login.php"){
        //echo "LOGGING IN ".$user." - ".$_COOKIE["loggedin"];
    } else {
        ?><META HTTP-EQUIV=REFRESH CONTENT="0; URL=/index.php?redirect=<?echo $myPage;?>"><?
        exit(0);
    }

} else {
    // Logged In
    $loggedin=true;
    setcookie("loggedin",sha1("LoggedIn".$user),time()+60000);
    setcookie("user",$user,time()+60000);
    setcookie("level",$level,time()+60000);
    setcookie("language",$language,time()+60000);
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

    $menu.='<TD class="'.$thead.'" height=27><A HREF="/main.php"><img width="16" height="16" src="/images/house.png" border="0" align="left">'.str_replace(" ","&nbsp;",$config_values['MENU_HOME']).'</A>&nbsp;</TD>';

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
    $menu.='<TD class="'.$thead.'"><A HREF="/campaigns.php"><img width="16" height="16"  src="/images/folder.png" border="0" align="left">'.str_replace(" ","&nbsp;",$config_values['MENU_CAMPAIGNS']).'</A>&nbsp;</TD>';

    //=======================================================================================================
    // Numbers
    //=======================================================================================================
    if ($self=="/addnumbers.php"||$self=="/serverlist.php"||$self=="/numbers.php"||$self=="/deletenumber.php"||$self=="/viewnumbers.php"||$self == "/gennumbers.php"||$self == "/upload.php"||$self =="//receive.php"||$self=="/resetnumber.php"){
        $thead="thead";
    } else {
        $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
    }
    $menu.='<TD class="'.$thead.'"><A HREF="/numbers.php"><img width="16" height="16"  src="/images/telephone.png" border="0" align="left">'.str_replace(" ","&nbsp;",$config_values['MENU_NUMBERS']).'</A>&nbsp;</TD>';

    //=======================================================================================================
    // DNC Numbers
    //=======================================================================================================
    if ($self=="/adddncnumbers.php"||$self=="/dncnumbers.php"||$self=="/deletedncnumber.php"||$self=="/viewdncnumbers.php"||$self == "/gendncnumbers.php"||$self == "/uploaddnc.php"||$self =="//receivednc.php"){
        $thead="thead";
    } else {
        $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
    }
    $menu.='<TD class="'.$thead.'"><A HREF="/dncnumbers.php"><img width="16" height="16"  src="/images/telephone_error.png" border="0" align="left">'.str_replace(" ","&nbsp;",$config_values['MENU_DNC']).'</A>&nbsp;</TD>';

    //=======================================================================================================
    // Messages
    //=======================================================================================================
    if ($self=="/editmessage.php"||$self=="/addmessage.php"||$self=="/deleteMessage.php"||$self=="/messages.php"||$self=="/uploadmessage.php"){
        $thead="thead";
    } else {
        $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
    }
    $menu.='<TD class="'.$thead.'"><A HREF="/messages.php"><img width="16" height="16"  src="/images/sound.png" border="0" align="left">'.str_replace(" ","&nbsp;",$config_values['MENU_MESSAGES']).'</A>&nbsp;</TD>';
    //=======================================================================================================
    // Schedules
    //=======================================================================================================
    if ($self=="/editschedule.php"||$self=="/addschedule.php"||$self=="/deleteschedule.php"||$self=="/schedule.php"){
        $thead="thead";
    } else {
        $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
    }
    $menu.='<TD class="'.$thead.'"><A HREF="/schedule.php"><img width="16" height="16"  src="/images/clock.png" border="0" align="left">'.str_replace(" ","&nbsp;",$config_values['MENU_SCHEDULES']).'</A>&nbsp;</TD>';
    if ($level==sha1("level100")){

        //=======================================================================================================
        // Customers
        //=======================================================================================================
        if ($self=="/deletecustomer.php"||$self=="/addcustomer.php"||$self=="/customers.php"||$self=="/editcustomer.php"){
            $thead="thead";
        } else {
            $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
        }
        $menu.='<TD class="'.$thead.'"><A HREF="/customers.php"><img width="16" height="16"  src="/images/group.png" border="0" align="left">'.str_replace(" ","&nbsp;",$config_values['MENU_CUSTOMERS']).'</A>&nbsp;</TD>';
        //=======================================================================================================

        //=======================================================================================================
        // Queues
        //=======================================================================================================
        if ($self=="/deletequeue.php"||$self=="/addqueue.php"||$self=="/queues.php"||$self=="/editqueue.php"){
            $thead="thead";
        } else {
            $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
        }
        $menu.='<TD class="'.$thead.'"><A HREF="/queues.php"><img width="16" height="16"  src="/images/database.png" border="0" align="left">'.str_replace(" ","&nbsp;",$config_values['MENU_QUEUES']).'</A>&nbsp;</TD>';
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
        $menu.='<TD class="'.$thead.'"><A HREF="/servers.php"><img width="16" height="16"  src="/images/server.png" border="0" align="left">'.str_replace(" ","&nbsp;",$config_values['MENU_SERVERS']).'</A>&nbsp;</TD>';
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
        $menu.='<TD class="'.$thead.'"><A HREF="/trunks.php"><img width="16" height="16"  src="/images/telephone_link.png" border="0" align="left">'.str_replace(" ","&nbsp;",$config_values['MENU_TRUNKS']).'</A>&nbsp;</TD>';
        //=======================================================================================================

        //=======================================================================================================
        // Admin
        //=======================================================================================================
        if ($self=="/config.php"||$self=="/setparameter.php"){
            $thead="thead";
        } else {
            $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
        }
        $menu.='<TD class="'.$thead.'"><A HREF="/config.php"><img width="16" height="16"  src="/images/cog.png" border="0" align="left">'.str_replace(" ","&nbsp;",$config_values['MENU_ADMIN']).'</A>&nbsp;</TD>';
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
        $menu.='<TD class="'.$thead.'"><A HREF="/customers.php"><img width="16" height="16"  src="/images/group.png" border="0" align="left">'.$config_values['MENU_CUSTOMERS'].'</A>&nbsp;</TD>';
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
        $menu.='<TD class="'.$thead.'"><A HREF="/addfunds.php"><img width="16" height="16"  src="/images/group.png" border="0" align="left">'.str_replace(" ","&nbsp;",$config_values['MENU_ADDFUNDS']).'</A>&nbsp;</TD>';
        //=======================================================================================================

    }
    $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";

    $menu.='<TD height="1" class="'.$thead.'"><A HREF="/logout.php"><img width="16" height="16"  src="/images/door_in.png" border="0" align="left">'.str_replace(" ","&nbsp;",$config_values['MENU_LOGOUT']).'</A>&nbsp;</TD><TD CLASS="theadr2" WIDTH=0></TD></TR></table>

    ';
    //<TD class="thead2"><A HREF="stats.php">Live Statistics</A>&nbsp;&nbsp;</TD>

}
?>
<HTML>
<HEAD>
<script type="text/javascript">

/* Optional: Temporarily hide the "tabber" class so it does not "flash"
   on the page as plain HTML. After tabber runs, the class is changed
   to "tabberlive" and it will appear. */

document.write('<style type="text/css">.tabber{display:none;}<\/style>');

/*==================================================
  Set the tabber options (must do this before including tabber.js)
  ==================================================*/
var tabberOptions = {

  'cookie':"tabber", /* Name to use for the cookie */

  'onLoad': function(argsObj)
  {
    var t = argsObj.tabber;
    var i;

    /* Optional: Add the id of the tabber to the cookie name to allow
       for multiple tabber interfaces on the site.  If you have
       multiple tabber interfaces (even on different pages) I suggest
       setting a unique id on each one, to avoid having the cookie set
       the wrong tab.
    */
    if (t.id) {
      t.cookie = t.id + t.cookie;
    }

    /* If a cookie was previously set, restore the active tab */
    i = parseInt(getCookie(t.cookie));
    if (isNaN(i)) { return; }
    t.tabShow(i);
    //alert('getCookie(' + t.cookie + ') = ' + i);
  },

  'onClick':function(argsObj)
  {
    var c = argsObj.tabber.cookie;
    var i = argsObj.index;
    //alert('setCookie(' + c + ',' + i + ')');
    setCookie(c, i);
  }
};

/*==================================================
  Cookie functions
  ==================================================*/
function setCookie(name, value, expires, path, domain, secure) {
    document.cookie= name + "=" + escape(value) +
        ((expires) ? "; expires=" + expires.toGMTString() : "") +
        ((path) ? "; path=" + path : "") +
        ((domain) ? "; domain=" + domain : "") +
        ((secure) ? "; secure" : "");
}

function getCookie(name) {
    var dc = document.cookie;
    var prefix = name + "=";
    var begin = dc.indexOf("; " + prefix);
    if (begin == -1) {
        begin = dc.indexOf(prefix);
        if (begin != 0) return null;
    } else {
        begin += 2;
    }
    var end = document.cookie.indexOf(";", begin);
    if (end == -1) {
        end = dc.length;
    }
    return unescape(dc.substring(begin + prefix.length, end));
}
function deleteCookie(name, path, domain) {
    if (getCookie(name)) {
        document.cookie = name + "=" +
            ((path) ? "; path=" + path : "") +
            ((domain) ? "; domain=" + domain : "") +
            "; expires=Thu, 01-Jan-70 00:00:01 GMT";
    }
}

</script>

<script type="text/javascript" src="/tabber.js"></script>
<link rel="stylesheet" type="text/css" href="/css/style.css">
<link rel="stylesheet" href="/example.css" TYPE="text/css" MEDIA="screen">
<link rel="stylesheet" href="/example-print.css" TYPE="text/css" MEDIA="print">





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

<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Simple Tabber Example</title>



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
        document.all['xx1'].style.display = "none";/*the number for the call center*/
        document.all['xx2'].style.display = "none";/*press 1 message*/
        document.all['xx3'].style.display = "visible";/*answer machine message*/
        document.all['xx4'].style.display = "none";/*dnc message*/
        document.all['xx5'].style.display = "visible";/*caller id*/
        document.all['xx6'].style.display = "visible";/*imax connected calls*/
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
/*
    if (document.all2) {
        document.all['mode'].style.display = "none";
    } else {
        document.getElementById('mode').style.display='none';
    }
   */
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





<script type="text/javascript">
var tabberOptions = {'onClick':function(){hideItem('hideShow');}};
</script>



</HEAD>






<BODY BGCOLOR="<?echo $config_values['COLOUR'];?>" >
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
//    echo "Language: $language";
    if ( $config_values['USE_BILLING'] == "YES") {
        if ($postpay == 1) {
            echo "<center><font color=\"".$config_values['DATE_COLOUR']."\"><a href=\"/help/index.php\"><img width=\"16\" height=\"16\"  src=\"/images/help.png\" border=\"0\"><b> Help</b></a> ".ucwords(strftime('%A %d %B %Y %H:%M:%S'))." Credit: $credit Credit Limit: $creditlimit <a href=\"/viewcdr.php\"><img width=\"16\" height=\"16\" src=\"/images/table.png\" border=\"0\"> ".$config_values['CDR_TEXT']."</a> <a href=\"/billinglog_account.php\"><img width=\"16\" height=\"16\" src=\"/images/cart_edit.png\" border=\"0\"> ".$config_values['BILLING_TEXT']."</a></font><br /></center>";
        } else {
            echo "<center><font color=\"".$config_values['DATE_COLOUR']."\"><a href=\"/help/index.php\"><img width=\"16\" height=\"16\"  src=\"/images/help.png\" border=\"0\"><b> Help</b></a> ".ucwords(strftime('%A %d %B %Y %H:%M:%S'))." Credit: $credit <a href=\"/viewcdr.php\"><img width=\"16\" height=\"16\"  src=\"/images/table.png\" border=\"0\"> ".$config_values['CDR_TEXT']."</a> <a href=\"/billinglog_account.php\"><img width=\"16\" height=\"16\"  src=\"/images/cart_edit.png\" border=\"0\"> ".$config_values['BILLING_TEXT']."</a></font><br /></center>";
        }
    } else {
        echo "<center><font color=\"".$config_values['DATE_COLOUR']."\"><a href=\"/help/index.php\"><img width=\"16\" height=\"16\"  src=\"/images/help.png\" border=\"0\"><b> Help</b></a> ".ucwords(strftime('%A %d %B %Y %H:%M:%S'))."</font><br /></center>";
    }
}
?>
