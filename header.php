<?php
$config_file = "/stweb.conf";
$comment = "#";
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

if ($config_values['PER_PAGE'] == "") {
    $config_values['PER_PAGE'] = "20";
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

    $menu.='<TD class="'.$thead.'" height=27><A HREF="/main.php"><img src="/images/house.png" border="0" align="left">'.$config_values['MENU_HOME'].'</A>&nbsp;</TD>';

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
    $menu.='<TD class="'.$thead.'"><A HREF="/campaigns.php"><img src="/images/folder.png" border="0" align="left">'.$config_values['MENU_CAMPAIGNS'].'</A>&nbsp;</TD>';

    //=======================================================================================================
    // Numbers
    //=======================================================================================================
    if ($self=="/addnumbers.php"||$self=="/serverlist.php"||$self=="/numbers.php"||$self=="/deletenumber.php"||$self=="/viewnumbers.php"||$self == "/gennumbers.php"||$self == "/upload.php"||$self =="//receive.php"||$self=="/resetnumber.php"){
        $thead="thead";
    } else {
        $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
    }
    $menu.='<TD class="'.$thead.'"><A HREF="/numbers.php"><img src="/images/telephone.png" border="0" align="left">'.$config_values['MENU_NUMBERS'].'</A>&nbsp;</TD>';

    //=======================================================================================================
    // DNC Numbers
    //=======================================================================================================
    if ($self=="/adddncnumbers.php"||$self=="/dncnumbers.php"||$self=="/deletedncnumber.php"||$self=="/viewdncnumbers.php"||$self == "/gendncnumbers.php"||$self == "/uploaddnc.php"||$self =="//receivednc.php"){
        $thead="thead";
    } else {
        $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
    }
    $menu.='<TD class="'.$thead.'"><A HREF="/dncnumbers.php"><img src="/images/telephone_error.png" border="0" align="left">'.$config_values['MENU_DNC'].'</A>&nbsp;</TD>';

    //=======================================================================================================
    // Messages
    //=======================================================================================================
    if ($self=="/editmessage.php"||$self=="/addmessage.php"||$self=="/deleteMessage.php"||$self=="/messages.php"||$self=="/uploadmessage.php"){
        $thead="thead";
    } else {
        $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
    }
    $menu.='<TD class="'.$thead.'"><A HREF="/messages.php"><img src="/images/sound.png" border="0" align="left">'.$config_values['MENU_MESSAGES'].'</A>&nbsp;</TD>';
    //=======================================================================================================
    // Schedules
    //=======================================================================================================
    if ($self=="/editschedule.php"||$self=="/addschedule.php"||$self=="/deleteschedule.php"||$self=="/schedule.php"){
        $thead="thead";
    } else {
        $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
    }
    $menu.='<TD class="'.$thead.'"><A HREF="/schedule.php"><img src="/images/clock.png" border="0" align="left">'.$config_values['MENU_SCHEDULES'].'</A>&nbsp;</TD>';
    if ($level==sha1("level100")){

        //=======================================================================================================
        // Customers
        //=======================================================================================================
        if ($self=="/deletecustomer.php"||$self=="/addcustomer.php"||$self=="/customers.php"||$self=="/editcustomer.php"){
            $thead="thead";
        } else {
            $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
        }
        $menu.='<TD class="'.$thead.'"><A HREF="/customers.php"><img src="/images/group.png" border="0" align="left">'.$config_values['MENU_CUSTOMERS'].'</A>&nbsp;</TD>';
        //=======================================================================================================

        //=======================================================================================================
        // Queues
        //=======================================================================================================
        if ($self=="/deletequeue.php"||$self=="/addqueue.php"||$self=="/queues.php"||$self=="/editqueue.php"){
            $thead="thead";
        } else {
            $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
        }
        $menu.='<TD class="'.$thead.'"><A HREF="/queues.php"><img src="/images/database.png" border="0" align="left">'.$config_values['MENU_QUEUES'].'</A>&nbsp;</TD>';
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
        $menu.='<TD class="'.$thead.'"><A HREF="/servers.php"><img src="/images/server.png" border="0" align="left">'.$config_values['MENU_SERVERS'].'</A>&nbsp;</TD>';
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
        $menu.='<TD class="'.$thead.'"><A HREF="/trunks.php"><img src="/images/telephone_link.png" border="0" align="left">'.$config_values['MENU_TRUNKS'].'</A>&nbsp;</TD>';
        //=======================================================================================================

        //=======================================================================================================
        // Admin
        //=======================================================================================================
        if ($self=="/config.php"||$self=="/setparameter.php"){
            $thead="thead";
        } else {
            $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
        }
        $menu.='<TD class="'.$thead.'"><A HREF="/config.php"><img src="/images/cog.png" border="0" align="left">'.$config_values['MENU_ADMIN'].'</A>&nbsp;</TD>';
        //=======================================================================================================

    }
    //    <TD class="thead2"><A HREF="prefs.php">Preferences</A>&nbsp;&nbsp;</TD>
    } else {
        //echo "Billing Administrator Login";
        // This is for people who are logged in as a billing administrator
        //=======================================================================================================
        // Customers
        //=======================================================================================================
        if ($self=="/deletecustomer.php"||$self=="/addcustomer.php"||$self=="/customers.php"||$self=="/editcustomer.php"){
            $thead="thead";
        } else {
            $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
        }
        $menu.='<TD class="'.$thead.'"><A HREF="/customers.php"><img src="/images/group.png" border="0" align="left">'.$config_values['MENU_CUSTOMERS'].'</A>&nbsp;</TD>';
        //=======================================================================================================

    }
    $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";

    $menu.='<TD height="1" class="'.$thead.'"><A HREF="/logout.php"><img src="/images/door_in.png" border="0" align="left">'.$config_values['MENU_LOGOUT'].'</A>&nbsp;</TD><TD CLASS="theadr2" WIDTH=0></TD></TR></table>

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
<TITLE><?echo $config_values['TITLE'];?></TITLE>
<link rel="stylesheet" type="text/css" href="/css/stylelogin.css">
<link rel="stylesheet" type="text/css" href="/css/default.css">
<link rel="stylesheet" href="/css/modal-message.css" type="text/css">
<link rel="shortcut icon" href="/favicon.ico">

<!-- Javascript includes -->
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

<script type="text/javascript" src="/js/ajax.js"></script>
<script type="text/javascript" src="/js/modal-message.js"></script>
<script type="text/javascript" src="/js/ajax-dynamic-content.js"></script>
<script type="text/javascript" src="/usableforms.js"></script><?/*
THIS IS CAUSING AN ERROR!
*/?>
<script language="javascript" type="text/javascript" src="/upload.js"></script>
<link rel="stylesheet" href="/upload.css" type="text/css" media="screen" title="Upload" charset="utf-8" />

<style>
<!--
.dragme{position:relative;}
-->
</style>
<STYLE type="text/css">
  DIV.mypars {text-align: left}
</STYLE>
<script type="text/javascript" src="/ajax/picker.js"></script>
<script type="text/javascript" src="/header.js"></script>
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
            echo "<center><font color=\"".$config_values['DATE_COLOUR']."\"><a href=\"/help/index.php\"><img src=\"/images/help.png\" border=\"0\"> Help</a> ".date('l dS \of F Y h:i:sA')." Credit: $credit Credit Limit: $creditlimit <a href=\"viewcdr.php\"><img src=\"/images/cart_edit.png\" border=\"0\"> Billing Information</a></font><br /></center>";
        } else {
            echo "<center><font color=\"".$config_values['DATE_COLOUR']."\"><a href=\"/help/index.php\"><img src=\"/images/help.png\" border=\"0\"> Help</a> ".date('l dS \of F Y h:i:sA')." Credit: $credit <a href=\"viewcdr.php\"><img src=\"/images/cart_edit.png\" border=\"0\"> Billing Information</a></font><br /></center>";
        }
    } else {
        echo "<center><font color=\"".$config_values['DATE_COLOUR']."\"><a href=\"/help/index.php\"><img src=\"/images/help.png\" border=\"0\"> Help</a> ".date('l dS \of F Y h:i:sA')."</font><br /></center>";
    }
}
?>
