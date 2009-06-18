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
?>

        <script type="text/javascript" src="js/dojo/dojo.js" djConfig="parseOnLoad:false, isDebug:false"></script>                                
        <script type="text/javascript">
		dojo.require("dojo.NodeList-fx");

	</script>


<?
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
		        $('#ajaxDiv2').loadIfModified('server_details.php<?if (isset($_GET[debug]))echo "?debug=".$_GET[debug];?>');  // jquery ajax load into div
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
		        $('#ajaxDiv2').load('server_details.php<?if (isset($_GET[debug]))echo "?debug=".$_GET[debug];?>');  // jquery ajax load into div
                }
                ,5000);
        });
</script>
<?}?>


<div id = "ajaxDiv">
<?
include "server_total.php";
?>
</div>
<div id = "ajaxDiv2">
<?
include "server_details.php";
?>
</div>
<?
require "footer.php";
}
?>
