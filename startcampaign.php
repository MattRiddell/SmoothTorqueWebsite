<?
require "header.php";
require "header_campaign.php";
$out=_get_browser();

$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);

//print_r($_POST);
?>
<br /><br /><br /><br />
<center>
<table background="/images/sdbox.png" width="300" height="200" class="dragme22">
<tr>
<td>
</td>
<td width="260">
<?
/*Maximum Concurrent Calls:<b> <?echo $_POST[agents];?></b><br />
Call Center Phone Number:<b> <?echo $_POST[did];?></b><br />
Type of campaign:<b>

if ($_POST[context]==0) {
    echo "Load Simulation";
} else if ($_POST[context]==1) {
    echo "Answer Machine Only";
} else if ($_POST[context]==2) {
    echo "Immediate Live";
} else if ($_POST[context]==3) {
    echo "Press 1 Live";
} else if ($_POST[context]==4) {
    echo "Immediate Live and Answer Machine";
} else if ($_POST[context]==5) {
    echo "Press 1 Live and Answer Machine";
}
*/

$sqlx = 'SELECT campaigngroupid, maxchans, maxcps FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sqlx, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');
$maxchans=mysql_result($result,0,'maxchans');
$maxcps=mysql_result($result,0,'maxcps');
$username=$_COOKIE[user];

if ( $config_values['USE_BILLING'] == "YES") {
    $sql = "Select credit, credit_limit from billing where accountcode = 'stl-$username'";
    $result_credit = mysql_query($sql, $link);
    if (mysql_num_rows($result_credit) > 0) {
        $credit = mysql_result($result_credit,0,"credit");
        $credit_limit = mysql_result($result_credit,0,"credit_limit");
    } else {
        $credit = 0;
        $credit_limit = 0;
    }
    if ($credit <= 0) {
        if ($credit > 0-$credit_limit) {
            $allowed_to_start = true;
        } else {
            $allowed_to_start = false;
        }
    } else {
        $allowed_to_start = true;
    }
    if (!$allowed_to_start) {
/*================= Log Access ======================================*/
$sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_COOKIE[user]', 'Does not have credit to start campaign')";
$result=mysql_query($sql, $link);
/*================= Log Access ======================================*/

        /* Not enough credit - error and return */
        ?>
        <b>Sorry, you do not have enough credit to start a campaign. Please add some
        credit to your account and try again.</b>
        </td>
<td>
</td></tr>
</table>
</center>

        <META HTTP-EQUIV=REFRESH CONTENT="10; URL=/campaigns.php">
        <?
        exit(0);
    }
}

$sql4="select trunkid from customer where campaigngroupid = ".$campaigngroupid;
$resultx=mysql_query($sql4, $link) or die (mysql_error());;
$trunkid=mysql_result($resultx,0,'trunkid');

if ($trunkid==-1){
    $sql3="select dialstring, id from trunk where current = 1";
    $resultx=mysql_query($sql3, $link) or die (mysql_error());;
    $dialstring=mysql_result($resultx,0,'dialstring');
    $trunkid = mysql_result($resultx,0,'id');
} else {
    $sql3="select dialstring from trunk where id = ".$trunkid;
    $resultx=mysql_query($sql3, $link) or die (mysql_error());;
    $dialstring=mysql_result($resultx,0,'dialstring');
}

$dncsql = "SELECT number.phonenumber FROM number LEFT JOIN dncnumber ON number.phonenumber=dncnumber.phonenumber WHERE dncnumber.phonenumber IS NOT NULL AND number.campaignid='$_GET[id]'";
$resultdnc=mysql_query($dncsql, $link) or die (mysql_error());;
//echo $dncsql."<br />";
while ($row = mysql_fetch_assoc($resultdnc)) {
//    echo $row[phonenumber]." is in dnc<br />";
    echo "<!-- . -->";
    $removedncsql = "UPDATE number set status = 'indnc' where phonenumber='$row[phonenumber]'";
    $resultremovednc=mysql_query($removedncsql, $link) or die (mysql_error());;
}
//exit(0);

$sql1="delete from queue where campaignid=".$_GET[id];
$did = str_replace("-","",$_GET[did]);
$did = str_replace("(","",$did);
$did = str_replace(")","",$did);
$did = str_replace(" ","",$did);

//$dialstring = str_replace("-","",$dialstring);
$dialstring = str_replace(" ","",$dialstring);
$dialstring = str_replace("(","",$dialstring);
$dialstring = str_replace(")","",$dialstring);

/*================= Log Access ======================================*/
$sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_COOKIE[user]', 'Starting campaign')";
$result=mysql_query($sql, $link);
/*================= Log Access ======================================*/


$sql2="INSERT INTO queue (campaignid,queuename,status,details,flags,transferclid,
    starttime,endtime,startdate,enddate,did,clid,context,maxcalls,maxchans,maxretries
    ,retrytime,waittime,trunk,astqueuename, accountcode, trunkid, customerID, maxcps) VALUES
    ('$_GET[id]','autostart-$_GET[id]','1','No details','0','$_GET[trclid]',
    '00:00','23:59','2005-01-01','2020-01-01','$did','$_GET[clid]',
    '$_GET[context]','$_GET[agents]','$maxchans','0'
    ,'0','30','".$dialstring."','$_GET[astqueuename]','stl-".$username."','$trunkid','$campaigngroupid','$maxcps') ";
//    echo $sql2;
//exit(0);
//echo $sql2;
$resultx=mysql_query($sql1, $link) or die (mysql_error());;
$resultx=mysql_query($sql2, $link) or die (mysql_error());;


?>
</b>
<br />
<br />
<?
if ($out[browser]=="MSIE"){
?>
<script type="text/javascript" src="/ajax/jquery.js"></script>
        <script type="text/javascript">
        $(function(){ // jquery onload
                window.setInterval(function(){ // setInterval code
                        $('#ajaxDiv').loadIfModified('campaignstatus.php?id=<?echo $_GET[id];?>');
                },2000);
        });

        </script>
 <?} else {?>
<script type="text/javascript" src="/ajax/jquery.js"></script>
        <script type="text/javascript">
        $(function(){ // jquery onload
                window.setInterval(function(){ // setInterval code
                        $('#ajaxDiv').load('campaignstatus.php?id=<?echo $_GET[id];?>');
                },2000);
        });

        </script>

<?}?>
<div id="ajaxDiv">
<?
$id=$_GET[id];include "campaignstatus.php";?>
</div>

<br />
</td>
<td>
</td></tr>
</table>
</center>
<?
require "footer.php";
?>
