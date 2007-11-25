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

$self=$_SERVER['PHP_SELF'];
//echo $self;
    $menu='<CENTER>
    <table border="0" cellpadding="3" cellspacing="0"><TR HEIGHT="19">';

    //=======================================================================================================
    // Home
    //=======================================================================================================
    if ($self=="/main.php"){
        $menu.='<td style="background-image: url(/images/clb.gif);" height=20 width=0></td>';
        $thead="thead";
    } else {
    $menu.='<TD CLASS="theadl2" WIDTH=0 height=20></TD>';
        $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
    }

    $menu.='<TD class="'.$thead.'" height=28><A HREF="/main.php"><img src="/images/house.png" border="0" align="left">Home</A>&nbsp;</TD>';

    //=======================================================================================================
    // Campaigns
    //=======================================================================================================
    if ($self=="/campaigns.php"||$self=="/deletecampaign.php"||$self=="/editcampaign.php"||$self=="/addcampaign.php"||$self=="/stopcampaign.php"||$self=="/startcampaign.php"){
        $thead="thead";
    } else {
        $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
    }
    $menu.='<TD class="'.$thead.'"><A HREF="/campaigns.php"><img src="/images/folder.png" border="0" align="left">Campaigns</A>&nbsp;</TD>';

    //=======================================================================================================
    // Numbers
    //=======================================================================================================
    if ($self=="/addnumbers.php"||$self=="/numbers.php"||$self=="/deletenumber.php"||$self=="/viewnumbers.php"||$self == "/gennumbers.php"||$self == "/upload.php"||$self =="//receive.php"){
        $thead="thead";
    } else {
        $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
    }
    $menu.='<TD class="'.$thead.'"><A HREF="/numbers.php"><img src="/images/telephone.png" border="0" align="left">Numbers</A>&nbsp;</TD>';

    //=======================================================================================================
    // DNC Numbers
    //=======================================================================================================
    if ($self=="/adddncnumbers.php"||$self=="/dncnumbers.php"||$self=="/deletedncnumber.php"||$self=="/viewdncnumbers.php"||$self == "/gendncnumbers.php"||$self == "/uploaddnc.php"||$self =="//receive.php"){
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
        // Servers
        //=======================================================================================================
        if ($self=="/deleteserver.php"||$self=="/addserver.php"||$self=="/servers.php"||$self=="/editserver.php"){
            $thead="thead";
        } else {
            $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
        }
        $menu.='<TD class="'.$thead.'"><A HREF="/servers.php"><img src="/images/server.png" border="0" align="left">Servers</A>&nbsp;</TD>';
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
        if ($self=="/config.php"){
            $thead="thead";
        } else {
            $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";
        }
        $menu.='<TD class="'.$thead.'"><A HREF="/config.php"><img src="/images/cog.png" border="0" align="left">Config</A>&nbsp;</TD>';
        //=======================================================================================================

    }
    //    <TD class="thead2"><A HREF="prefs.php">Preferences</A>&nbsp;&nbsp;</TD>
    $thead="thead2\" onmouseover=\"this.className='thead'\" onmouseout=\"this.className='thead2'\"  \"";

    $menu.='<TD class="'.$thead.'"><A HREF="/logout.php"><img src="/images/door_in.png" border="0" align="left">Logout</A></TD>

    <TD CLASS="theadr2" WIDTH=0></TD>

    </TR></table>

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
<TITLE>SmoothTorque Enterprise</TITLE>
<link rel="stylesheet" type="text/css" href="/css/stylelogin.css">
<link rel="stylesheet" type="text/css" href="/css/default.css">
<link rel="stylesheet" href="/css/modal-message.css" type="text/css">
<link rel="shortcut icon" href="/favicon.ico">
<script type="text/javascript" src="/js/ajax.js"></script>
<script type="text/javascript" src="/js/modal-message.js"></script>
<script type="text/javascript" src="/js/ajax-dynamic-content.js"></script>
<script language="javascript" type="text/javascript" src="/upload.js"></script>
<link rel="stylesheet" href="/upload.css" type="text/css" media="screen" title="Upload" charset="utf-8" />
  <script language="javascript">
    function beginUpload(sid) {
      document.postform.submit();
        var pb = document.getElementById("progress");
        var pb2 = document.getElementById("matt");
        var pb3 = document.getElementById("matt2");
        pb.parentNode.parentNode.style.display='block';
        pb2.style.display='none';
        pb3.style.display='none';
        pb.parentNode.parentNode.style.display='block';
        new ProgressTracker(sid,{
                progressBar: pb,
                onFailure: function(msg) {
                        Element.hide(pb.parentNode);
                        alert(msg);
                }
        });
    }
  </script>
<script type="text/javascript" src="/ajax/picker.js"></script>
<script language=javascript type='text/javascript'>

function hideItem(obj) {
    var el = document.getElementById(obj);
    el.style.display = 'none';

}

</script>
</HEAD>
<BODY BACKGROUND="/images/bg.gif" onload="hideItem('hideShow');">
<?

if (isset($menu)){
   ?>
<CENTER>    <img src="/images/logo2.png">


    <?
    echo $menu;
    flush();
}
?>

<TABLE WIDTH=100% HEIGHT="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0" class="tborder3"><TR VALIGN="TOP" ><TD BGCOLOR="#ffffff">
