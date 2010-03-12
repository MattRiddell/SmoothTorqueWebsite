<?

$time_start = microtime(true);

include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);
if (isset($_GET['queueID'])){
	$_GET = array_map(mysql_real_escape_string,$_GET);
    $sql = 'update queue set status='.($_GET['status']).' where queueID='.($_GET['queueID']);
    $result=mysql_query($sql, $link) or die (mysql_error());;
    header("Location: schedule.php?campaignid=".$_GET['campaignid']);
}

require "header.php";
if (!isset($_GET['campaigngroupid'])) {
    $sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE['user'].'\'';
    $result=mysql_query($sql, $link) or die (mysql_error());;
    $campaigngroupid=mysql_result($result,0,'campaigngroupid');
} else {
    $campaigngroupid = $_GET['campaigngroupid'];
}
require "header_campaign.php";


/*================= Log Access ======================================*/
$sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_COOKIE[user]', 'Viewed Campaign Page')";
$result=mysql_query($sql, $link);
/*================= Log Access ======================================*/

if (isset($_GET['campaignid'])){
    $_GET = array_map(mysql_real_escape_string,$_GET);
    $_POST['campaignid']=($_GET['campaignid']);
} else {
    $_GET['campaignid'] = "";
    if (!isset($_POST['campaignid'])) {
        $_POST['campaignid']="";
    }
}
if (!isset($_GET['type'])) {
    $_GET['type'] = "";
}
$out=_get_browser();
if ($out['browser']=="MSIE"){
    ?>
    
    <?
    flush();
    ?>
    <script type="text/javascript" src="<?=$http_dir_name?>ajax/jquery.js"></script>
    <script type="text/javascript">
    $(function(){ // jquery onload
      window.setInterval(function(){
                         $('#ajaxDiv').loadIfModified('disTime3.php?campaigngroupid=<?echo $campaigngroupid;?>&id=<?echo $_POST['campaignid'];?>&type=<?echo $_GET['type'];?>');  // jquery ajax load into div
                         },10000);
      });
    
    </script>
    <?} else {?>
        <script type="text/javascript" src="<?=$http_dir_name?>ajax/jquery.js"></script>
        <script type="text/javascript">
        
        $(function(){ // jquery onload
          window.setInterval(
                             function(){
                             $('#ajaxDiv').load('disTime3.php?campaigngroupid=<?echo $campaigngroupid;?>&id=<?echo $_POST['campaignid'];?>&type=<?echo $_GET['type'];?>');  // jquery ajax load into div
                             }
                             ,10000);
          });
        </script>
        <?}?>
<div id="ajaxDiv">
<?
$id=$_POST['campaignid'];
include "disTime3.php";

?>

</div>
<?

require "footer.php";

// Sleep for a while
usleep(100);

?>
