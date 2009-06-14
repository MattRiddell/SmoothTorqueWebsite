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
$level=$_COOKIE[level];
$out=_get_browser();
$total_chans = 0;
if ($level!=sha1("level100")) {
include "header.php";
$ip = $_SERVER['REMOTE_ADDR'];
echo "Attempted break in attempt from $ip ($_COOKIE[user])";
/*================= Log Access ======================================*/
$sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_COOKIE[user]', ' $ip attempted to view the admin page')";
$result=mysql_query($sql, $link);
/*================= Log Access ======================================*/

} else {
require "header.php";
require "header_server.php";

$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);

$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');

$tot =0;
$sql = 'SELECT value FROM SineDialer.config WHERE parameter like \'s_%_calls\'';
$resultx = mysql_query($sql) or die(mysql_error());
if (mysql_num_rows($resultx) > 0) {
	while ($row_x = mysql_fetch_assoc($resultx)) {
		$tot+=$row_x[value];
	}
}


$out=_get_browser();
if ($out[browser]=="MSIE"){
?>
<script type="text/javascript" src="/ajax/jquery.js"></script>
<script type="text/javascript">
        $(function(){ // jquery onload
                window.setInterval(function(){
		        $('#ajaxDiv').loadIfModified('server_total.php');  // jquery ajax load into div
		        $('#ajaxDiv2').loadIfModified('server_details.php');  // jquery ajax load into div
                },5000);
        });

</script>
<?} else {?>
<script type="text/javascript" src="/ajax/jquery.js"></script>
<script type="text/javascript">

        $(function(){ // jquery onload
                window.setInterval(
                function(){
		        $('#ajaxDiv').load('server_total.php');  // jquery ajax load into div
		        $('#ajaxDiv2').load('server_details.php');  // jquery ajax load into div
                }
                ,5000);
        });
</script>
<?}?>


<div id = "ajaxDiv">
<?

box_start();
echo "<center>Total channels across all servers: <b>$tot</b></center>";;
box_end();

?>
</div>
<div id = "ajaxDiv2">

<?/* start of shadow */?>
<table align="center"><tr><td><div class="example" id="v6"><div id="main"><div class="wrap1"><div class="wrap2"><div class="wrap3" align="center">

<table class="" align="center" border="0" cellpadding="2" cellspacing="0">
<TR>
<TD CLASS="thead">
Name
</TD>
<TD CLASS="thead">
Username
</TD>
<TD CLASS="thead">
Address
</TD>
<TD CLASS="thead">
Status
</TD>
<TD CLASS="thead">
</TD>
<TD CLASS="thead">
Current Status
</TD>
</TR>
<?
$sql = 'SELECT * FROM servers order by name';
$result=mysql_query($sql, $link) or die (mysql_error());;
//$campaigngroupid=mysql_result($result,0,'campaigngroupid');
while ($row = mysql_fetch_assoc($result)) {
if ($toggle){
$toggle=false;
$class=" class=\"tborder2\"  onmouseover=\"style.backgroundColor='#84DFC1';\" onmouseout=\"style.backgroundColor='#f8f8f8'\"   ";
} else {
$toggle=true;
$class=" class=\"tborderx\"  onmouseover=\"style.backgroundColor='#84DFC1';\" onmouseout=\"style.backgroundColor='#f0f0f0'\" ";
}
?>
<TR <?echo $class;?>>
<TD>
<?
if (strlen($row[name])<25){
echo "<A HREF=\"editserver.php?id=".$row[id]."\"><img src=\"/images/pencil.png\" border=\"0\" align=\"right\" title=\"Edit\">".$row[name]."</A>";
} else {
echo "<A HREF=\"editserver.php?id=".$row[id]."\"><img src=\"/images/pencil.png\" border=\"0\" align=\"right\" title=\"Edit\">".trim(substr($row[name],0,15))."...</A>";
}
?>
</TD>

<TD>
<?echo $row[username];?>
</TD>
<TD>
<?echo $row[address];?>
</TD>
<TD>
<?
if ($row[status] == 0){
    echo "<img src=\"/images/cross.png\">";
    echo "<a href=\"resetserver.php?id=$row[id]\"><img src=\"/images/control_play_blue.png\" border=\"0\"></a>";
} else if ($row[status] == 1){
    echo "<img src=\"/images/tick.png\">";
    echo "<a href=\"resetserver2.php?id=$row[id]\"><img src=\"/images/control_stop_blue.png\" border=\"0\"></a>";
} else {
    echo "<img src=\"/images/clock.png\">";
    echo "<a href=\"resetserver.php?id=$row[id]\"><img src=\"/images/control_play_blue.png\" border=\"0\"></a>";
}
?>
</TD>
<TD>
<a href="#" onclick="displaySmallMessage('includes/confirmDeleteServer.php?id=<?echo $row[id];?>');return false"><IMG SRC="/images/delete.png" BORDER="0"></a><br>
</TD>
<td>
<?
//$resultx = mysql_query("SELECT value FROM SineDialer.config WHERE parameter = 's_".$row[name]."_connected"'");
$sql = "SELECT value FROM SineDialer.config WHERE parameter = 's_".$row[name]."_connected'";
$resultx = mysql_query($sql) or die(mysql_error());
if (mysql_num_rows($resultx) > 0) {
	switch( mysql_result($resultx,0,0)) {
		case 1:
	//		echo "Active";
			break;
		default:
			echo "Status: ".mysql_result($resultx,0,0);
			break;
	}
} else {
	echo "<font color=\"blue\"><img src=\"/images/sq_progress.gif\"></font>";
}
$sql = "SELECT value FROM SineDialer.config WHERE parameter = 's_".$row[name]."_calls'";
$resultx = mysql_query($sql) or die(mysql_error());
if (mysql_num_rows($resultx) > 0) {
	$num_chans =  mysql_result($resultx,0,0);
	$total_chans += $num_chans;
	if ($num_chans > 0) {
		echo " (<b>".round($num_chans)." channels</b>)";
	} else {
		echo " <font color=\"lightgrey\">(".round($num_chans)." channels)</font>";
	}
} else {
	echo " <font color=\"blue\"><img src=\"/images/sq_progress.gif\"></font>";
}
?>

</td>
</TR>

<?
}
?>

</TABLE>
<?/*end of shadow */?>
</div></div></div></div></div></td></tr></table>
</div>
<?
require "footer.php";
}
?>
