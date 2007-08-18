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
if ($_COOKIE["loggedin"]==sha1("LoggedIn".$user)){
    // Logged In
    setcookie("loggedin",sha1("LoggedIn".$user),time()+6000);
    setcookie("user",$user,time()+6000);
    setcookie("level",$level,time()+6000);
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<title>SmoothTorque</title>
<link rel="stylesheet" type="text/css" href="css/stylelogin.css">
<link rel="stylesheet" type="text/css" href="css/default.css">
<script type="text/javascript" src="dropdowntabfiles/dropdowntabs.js">
</script>
<script type="text/javascript" src="ajax/picker.js"></script>
<link rel="stylesheet" type="text/css" href="dropdowntabfiles/halfmoontabs.css" />
</head>
<body>
<div id="moonmenu" class="halfmoon">
<center><img src="logo.png">
<ul>
<li><a href="home.php">Home</a></li>
<li><a href="campaignPage.php" rel="dropmenu1_e">Campaigns</a></li>
<li><a href="numberPage.php" rel="dropmenu2_e">Numbers</a></li>
<li><a href="schedulePage.php" rel="dropmenu3_e">Schedules</a></li>
<li><a href="settingsPage.php">Settings</a></li>
<?if ($level==sha1("level100")) {?>
<li><a href="customers.php" rel="dropmenu5_e">Customers</a></li>
<?}?>
<li><a href="logout.php">Log Out</a></li>
</ul>
</div>


<br style="clear: left;" />


<!--1st drop down menu -->

<div id="dropmenu1_e" class="dropmenudiv_e">
        <a href="menu_addCampaign.php">Add Campaign</a>
        <a href="chart.php">Monitor Campaign</a>
        <a href="menu_editCampaign.php">Edit Campaign</a>
        <a href="menu_deleteCampaign.php">Delete Campaign</a>
</div>

<!--2nd drop down menu -->
<div id="dropmenu2_e" class="dropmenudiv_e" style="width: 150px;">
<a href="gennumbers.php">Generate Numbers</a>
<a href="menu_importNumbers.php">Import Numbers</a>
<a href="menu_exportNumbers.php">Export Numbers</a>
<a href="http://www.javascriptkit.com">Check Usage</a>
</div>

<!--2nd drop down menu -->
<div id="dropmenu3_e" class="dropmenudiv_e" style="width: 150px;">
<a href="schedule.php">View Schedules</a>
<a href="addschedule.php">Add Schedule</a>
</div>

<!--2nd drop down menu -->
<div id="dropmenu5_e" class="dropmenudiv_e" style="width: 150px;">
<a href="addcustomer.php">Add Customer</a>
<a href="customers.php">View Customers</a>
</div>

<script type="text/javascript">
//SYNTAX: tabdropdown.init("menu_id", [integer OR "auto"])
tabdropdown.init("moonmenu", <?echo $pagenum;?>)

</script>





    <?

    require_once "PHPTelnet.php";

$telnet = new PHPTelnet();

// if the first argument to Connect is blank,
// PHPTelnet will connect to the local host via 127.0.0.1
$telnet = new PHPTelnet();
$result = $telnet->Connect();
//echo "CONNECTION REQUEST: ".$result."<BR>";
$telnet->DoCommand('selectcg', $result);//echo $result."<BR>";
$telnet->DoCommand($_COOKIE[user], $result);
//echo $result."<BR>";
if (substr(trim($result),0,7)=="GroupID") {
    $groupid=substr(trim($result),8);
}
$telnet->Disconnect();
} else {
    // Not Logged In
    $myPage=basename($_SERVER['SCRIPT_FILENAME']);
    if ($myPage=="index.php"|$myPage=="login.php"){
        //echo "LOGGING IN ".$user." - ".$_COOKIE["loggedin"];
    } else {
        header("Location: index.php");
        exit;
    }
}


?>




<center>
<font face="arial">
