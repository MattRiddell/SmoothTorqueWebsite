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
	echo "<!--Version: $version<br />-->";
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
//		echo "Running";
	} else {
		echo "<font color=\"#ff0000\"><center><b>The server is not running</b></center></font>";
	}
} else {
		echo "<font color=\"#ff0000\"><center><b>The server is not running</b></center></font>";
/*	echo "<font color=\"#ff0000\"><center>Backend Not running<a      */
/*href=\"startbackend.php\"><b>Start Server</b></a></center></font>";*/
/*} */

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

include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);
if ($_COOKIE["loggedin"]==sha1("LoggedIn".$user)){
    // Logged In
    setcookie("loggedin",sha1("LoggedIn".$user),time()+60000);
    setcookie("user",$user,time()+60000);
    setcookie("level",$level,time()+60000);
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

    $menu.='<TD class="'.$thead.'" height=27><A HREF="/main.php"><img src="/images/house.png" border="0" align="left">Home</A>&nbsp;</TD>';

    //=======================================================================================================
    // Campaigns
    //=======================================================================================================
    if ($self=="/campaigns.php"||$self=="/report.php"||$self=="/resetlist.php"||$self=="/list.php"||$self=="/deletecampaign.php"||$self=="/editcampaign.php"||$self=="/addcampaign.php"||$self=="/stopcampaign.php"||$self=="/startcampaign.php"||$self=="/test.php"
    ){
        $thead="thead";
    } else {
        $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
    }
    $menu.='<TD class="'.$thead.'"><A HREF="/campaigns.php"><img src="/images/folder.png" border="0" align="left">Campaigns</A>&nbsp;</TD>';

    //=======================================================================================================
    // Numbers
    //=======================================================================================================
    if ($self=="/addnumbers.php"||$self=="/numbers.php"||$self=="/deletenumber.php"||$self=="/viewnumbers.php"||$self == "/gennumbers.php"||$self == "/upload.php"||$self =="//receive.php"||$self=="/resetnumber.php"){
        $thead="thead";
    } else {
        $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
    }
    $menu.='<TD class="'.$thead.'"><A HREF="/numbers.php"><img src="/images/telephone.png" border="0" align="left">Numbers</A>&nbsp;</TD>';

    //=======================================================================================================
    // DNC Numbers
    //=======================================================================================================
    if ($self=="/adddncnumbers.php"||$self=="/dncnumbers.php"||$self=="/deletedncnumber.php"||$self=="/viewdncnumbers.php"||$self == "/gendncnumbers.php"||$self == "/uploaddnc.php"||$self =="//receivednc.php"){
        $thead="thead";
    } else {
        $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
    }
    $menu.='<TD class="'.$thead.'"><A HREF="/dncnumbers.php"><img src="/images/telephone_error.png" border="0" align="left">DNC Numbers</A>&nbsp;</TD>';

    //=======================================================================================================
    // Messages
    //=======================================================================================================
    if ($self=="/editmessage.php"||$self=="/addmessage.php"||$self=="/deleteMessage.php"||$self=="/messages.php"){
        $thead="thead";
    } else {
        $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
    }
    $menu.='<TD class="'.$thead.'"><A HREF="/messages.php"><img src="/images/sound.png" border="0" align="left">Messages</A>&nbsp;</TD>';
    if ($level==sha1("level100")){

        //=======================================================================================================
        // Customers
        //=======================================================================================================
        if ($self=="/deletecustomer.php"||$self=="/addcustomer.php"||$self=="/customers.php"||$self=="/editcustomer.php"){
            $thead="thead";
        } else {
            $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
        }
        $menu.='<TD class="'.$thead.'"><A HREF="/customers.php"><img src="/images/group.png" border="0" align="left">Customers</A>&nbsp;</TD>';
        //=======================================================================================================

        //=======================================================================================================
        // Queues
        //=======================================================================================================
        if ($self=="/deletequeue.php"||$self=="/addqueue.php"||$self=="/queues.php"||$self=="/editqueue.php"){
            $thead="thead";
        } else {
            $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
        }
        $menu.='<TD class="'.$thead.'"><A HREF="/queues.php"><img src="/images/database.png" border="0" align="left">Queues</A>&nbsp;</TD>';
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
        $menu.='<TD class="'.$thead.'"><A HREF="/servers.php"><img src="/images/server.png" border="0" align="left">Servers</A>&nbsp;</TD>';
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
        $menu.='<TD class="'.$thead.'"><A HREF="/trunks.php"><img src="/images/telephone_link.png" border="0" align="left">Trunks</A>&nbsp;</TD>';
        //=======================================================================================================

        //=======================================================================================================
        // Config
        //=======================================================================================================
        if ($self=="/config.php"||$self=="/setparameter.php"){
            $thead="thead";
        } else {
            $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
        }
        $menu.='<TD class="'.$thead.'"><A HREF="/config.php"><img src="/images/cog.png" border="0" align="left">Config</A>&nbsp;</TD>';
        //=======================================================================================================

    }
    //    <TD class="thead2"><A HREF="prefs.php">Preferences</A>&nbsp;&nbsp;</TD>
    $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";

    $menu.='<TD height="1" class="'.$thead.'"><A HREF="/logout.php"><img src="/images/door_in.png" border="0" align="left">Logout</A>&nbsp;</TD><TD CLASS="theadr2" WIDTH=0></TD></TR></table>

    ';
    //<TD class="thead2"><A HREF="stats.php">Live Statistics</A>&nbsp;&nbsp;</TD>
} else {
    // Not Logged In
    $myPage=$_SERVER[PHP_SELF];
    if ($myPage=="/index.php"|$myPage=="/login.php"){
        //echo "LOGGING IN ".$user." - ".$_COOKIE["loggedin"];
    } else {
        header("Location: /");
        exit;
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
<script src="prototype.js" type="text/javascript"></script>
<?/*
THIS IS CAUSING AN ERROR!
<script src="js/eep.js" type="text/javascript"></script>
*/?>
<script type="text/javascript" src="/js/ajax.js"></script>
<script type="text/javascript" src="/js/modal-message.js"></script>
<script type="text/javascript" src="/js/ajax-dynamic-content.js"></script>
<?/*
THIS IS CAUSING AN ERROR!
<script type="text/javascript" src="/usableforms.js"></script>*/?>
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
<BODY BGCOLOR="<?echo $config_values['COLOUR'];?>" onload="hideItem('hideShow');">
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
<?/*echo "<center><br /><font color=\"#dddddd\">".date('l dS \of F Y h:i:s A')."</font><br /><br /></center>";*/echo "<br />";?>
