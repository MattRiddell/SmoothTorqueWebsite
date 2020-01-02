<?
require "header.php";
require "header_campaign.php";
$out=_get_browser();

$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);

?>
<link type="text/css" href="css/cupertino/jquery-ui-1.8.20.custom.css" rel="stylesheet" />
<script src="js/jquery-1.7.2.min.js" type="text/javascript"></script>
<script src="js/jquery-ui-1.8.20.custom.min.js" type="text/javascript"></script>

<br /><br /><br /><br />
<center>
<table background="images/sdbox.png" width="300" height="200" class="dragme22">
<tr>
<td>
</td>
<td width="260">
<?
$sql = "SELECT count(*) FROM number WHERE status='new' and campaignid='$_GET[id]'";
$result = mysql_query($sql);
$num_numbers = mysql_result($result,0,0);
if ($num_numbers <1&& !isset($_GET['debug'])) {
        /* Not enough credit - error and return */
        ?>
        <b>There are no available numbers for this campaign to run - please
        either add some numbers or recycle the current ones.</b>
        </td>
<td>
</td></tr>
</table>
</center>

        <META HTTP-EQUIV=REFRESH CONTENT="10; URL=campaigns.php">
        <?
        exit(0);

}

$sqlx = "SELECT groupid FROM campaign WHERE id=$_GET[id]";
$result_new_groupid=mysql_query($sqlx, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result_new_groupid,0,0);

$result_group_id = mysql_query("SELECT username FROM customer WHERE campaigngroupid = $campaigngroupid");
$new_username = mysql_result($result_group_id,0,0);

$sqlx = 'SELECT campaigngroupid, maxchans, maxcps FROM customer WHERE username=\''.$new_username.'\'';
$result=mysql_query($sqlx, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');
$maxchans=mysql_result($result,0,'maxchans');
$maxcps=mysql_result($result,0,'maxcps');
$username=$new_username;

$sqlx = "SELECT messageid FROM campaign WHERE id=$_GET[id]";
$result=mysql_query($sqlx, $link) or die (mysql_error());;
$messageid=mysql_result($result,0,'messageid');


$sqlx = "SELECT length FROM campaignmessage WHERE id=$messageid";
$result=mysql_query($sqlx, $link) or die (mysql_error());;
unset($length);
if (mysql_num_rows($result) > 0) {
	$length=mysql_result($result,0,'length');
}




if ( $config_values['USE_BILLING'] == "YES") {
    $sql = "Select credit, creditlimit, priceperminute, pricepercall, firstperiod from billing where accountcode = 'stl-$username'";

    //echo $sql;
    $result_credit = mysql_query($sql, $link) or die("a:".mysql_error());
    //echo "numrows: ".mysql_num_rows($result_credit);
    if (mysql_num_rows($result_credit) > 0) {
        $credit = mysql_result($result_credit,0,"credit");
        $credit_limit = mysql_result($result_credit,0,"creditlimit");
        $priceperminute = mysql_result($result_credit,0,"priceperminute");
        $pricepercall = mysql_result($result_credit,0,"pricepercall");
        $firstperiod = mysql_result($result_credit,0,"firstperiod");
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
    if ($allowed_to_start) {
        //echo "Credit: ".$credit."<br />";
        //echo "Credit Limit: ".$credit_limit."<br />";
        //echo "Price Per Minute: ".$priceperminute."<br />";
        //echo "Price Per Call: ".$pricepercall."<br />";
        //echo "Message Length: ".$length."<br />";
        // Each call will take the price per minute * length/60
        if ($length < $firstperiod) {
            $length = $firstperiod;
        }
        $onecall = $priceperminute * ($length/60);
        $onecall += $pricepercall;
        //echo "One Call Should Cost: ".$onecall."<br />";
        $real_credit = $credit + $credit_limit;
        //echo "Real Credit: ".$real_credit."<br />";
	if ($onecall > 0) {
            $call = $real_credit/$onecall;
	} else {
            $call = 999999999;
	}
        //echo "Max Calls: ".floor($call)."<br />";
        $maxcalls = floor($call);
        if ($maxcalls < 1) {
            $allowed_to_start = false;
        }
        /*================= Log Access ======================================*/
        $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_COOKIE[user]', ' is only allowed to make $maxcalls calls because their credit is ".($credit + $credit_limit)." and the total cost per call is $onecall based on an audio length of ".$length.", a per minute cost of $priceperminute and a lead cost of $pricepercall')";
        $result=mysql_query($sql, $link);
        /*================= Log Access ======================================*/
    }
    if (!$allowed_to_start) {
        /*================= Log Access ======================================*/
        $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_COOKIE[user]', 'Does not have credit to start campaign')";
        $result=mysql_query($sql, $link);
        /*================= Log Access ======================================*/

        /* Not enough credit - error and return */
        ?>
        <b>Sorry, you do not have enough credit to start a campaign. Please add some
        credit to your account and try again. <?echo "Credit: $credit Credit Limit: $credit_limit";?></b>
        </td>
        <td>
        </td></tr>
        </table>
        </center>

        <META HTTP-EQUIV=REFRESH CONTENT="10; URL=campaigns.php">
        <?
        exit(0);
    } else {
        /*================= Log Access ======================================*/
        $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_COOKIE[user]', 'Does have credit to start campaign (Credit: $credit Credit Limit: $credit_limit)')";
        $result=mysql_query($sql, $link);
        /*================= Log Access ======================================*/

    }
}

$sql4="select trunkid from customer where campaigngroupid = ".$campaigngroupid;
$resultx=mysql_query($sql4, $link) or die ("b:".mysql_error());;
$trunkid=mysql_result($resultx,0,'trunkid');

if ($trunkid==-1){
    $sql3="select dialstring, id from trunk where current = 1";
    $resultx=mysql_query($sql3, $link) or die ("c:".mysql_error());;
    $dialstring=mysql_result($resultx,0,'dialstring');
    $trunkid = mysql_result($resultx,0,'id');
} else {
    $sql3="select dialstring from trunk where id = ".$trunkid;
    $resultx=mysql_query($sql3, $link) or die ("d:".mysql_error());;
    $dialstring=mysql_result($resultx,0,'dialstring');
}
?>
<div id="progressdiv" style="display:none" title="Starting your campaign..."><center>
<span id="status"></span>
<div id="progress" style="height: 20px"></div>
</center>
</div>
<script>
$("#progressdiv").dialog({modal: true});
</script>
<?
//exit(0);
$dncsql = "SELECT number.phonenumber FROM number LEFT JOIN dncnumber ON number.phonenumber=dncnumber.phonenumber WHERE dncnumber.phonenumber IS NOT NULL AND number.campaignid='$_GET[id]' and number.status = 'new'";
?><script>
$("#status").text("Scrubbing against Do Not Call list");
$("#progress").progressbar({value: 0});
</script><?

$resultdnc=mysql_query($dncsql, $link) or die ("e:".mysql_error());;
?><script>
$("#status").text("Query run against database - about to start scrubbing");
</script><?

$num_rows_dnc = mysql_num_rows($resultdnc);
$count_dnc_rows_so_far = 0;
?><script>
$("#status").text("Scrubbing against DNC");
</script><?
//echo $dncsql."<br />";
while ($row = mysql_fetch_assoc($resultdnc)) {
    $count_dnc_rows_so_far++;

    if ($count_dnc_rows_so_far % 100) {
        ?><script>
        $("#status").text("Scrubbing <?=$row['phonenumber']?> against DNC");
        $("#progress").progressbar({value: <?=round($count_dnc_rows_so_far/$num_rows_dnc*100)?>});
        </script><?
        flush();
    }
//    echo $row[phonenumber]." is in dnc<br />";
    echo "<!-- . -->";
    $removedncsql = "UPDATE number set status = 'indnc' where phonenumber='$row[phonenumber]'";
    $resultremovednc=mysql_query($removedncsql, $link) or die ("f:".mysql_error());;
}
?>
<script>
$("#progressdiv").dialog("close");
</script>
<?
if ( $config_values['USE_BILLING'] == "YES") {
    if ($config_values['strict_credit_limit'] == "YES") {
        if ($_GET[context] != 0) {
            $credit_limit_sql = "UPDATE number SET status='no-credit' WHERE status='new' and campaignid='$_GET[id]'";
            $result_credit_limit_sql=mysql_query($credit_limit_sql, $link) or die ("g:".mysql_error());;

            $credit_limit_sql2 = "UPDATE number SET status='new' WHERE status='no-credit' and campaignid='$_GET[id]' limit $maxcalls";
            $result_credit_limit_sql2=mysql_query($credit_limit_sql2, $link) or die (mysql_error()." from ".$credit_limit_sql2);;
        } else {
            $credit_limit_sql2 = "UPDATE number SET status='new' WHERE status='no-credit' and campaignid='$_GET[id]'";
            $result_credit_limit_sql2=mysql_query($credit_limit_sql2, $link) or die ("i:".mysql_error());;

        }
    }
}
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


if (strlen($_GET[astqueuename])> 0) {
    $mode = 1;
} else {
    $mode = 0;
}
$sql = 'SELECT value FROM config WHERE parameter=\'expected_rate\'';
$result=mysql_query($sql, $link) or die (mysql_error());
if (mysql_num_rows($result) > 0) {
    $expected_rate = mysql_result($result,0,'value');
} else {
    $expected_rate = 100;
}
if (isset($_GET['drive_min'])) {
    $drive_min = $_GET['drive_min'];
    $drive_max = $_GET['drive_max'];
} else {
    $drive_min = "43.0";
    $drive_max = "61.0";
}


$sql2="INSERT INTO queue (campaignid,queuename,status,details,flags,transferclid,
    starttime,endtime,startdate,enddate,did,clid,context,maxcalls,maxchans,maxretries
    ,retrytime,waittime,trunk,astqueuename, accountcode, trunkid, customerID, maxcps, mode, expectedRate, drive_min, drive_max) VALUES
    ('$_GET[id]','autostart-$_GET[id]','1','No details','0','$_GET[trclid]',
    '00:00','23:59','2005-01-01','2090-01-01','$did','$_GET[clid]',
    '$_GET[context]','$_GET[agents]','$maxchans','0'
    ,'0','30','".$dialstring."','$_GET[astqueuename]','stl-".$new_username."','$trunkid','$campaigngroupid','$maxcps','$mode', '$expected_rate','$drive_min','$drive_max') ";
//    echo $sql2;
//exit(0);
//echo $sql2;
$resultx=mysql_query($sql1, $link) or die ("j:".mysql_error());;
$resultx=mysql_query($sql2, $link) or die ("k:".mysql_error());;


?>
</b>
<br />
<br />
<?
if ($out[browser]=="MSIE"){
?>
<script type="text/javascript" src="ajax/jquery.js"></script>
        <script type="text/javascript">
        $(function(){ // jquery onload
                window.setInterval(function(){ // setInterval code
                        $('#ajaxDiv').loadIfModified('campaignstatus.php?id=<?echo $_GET[id];?>');
                },2000);
        });

        </script>
 <?} else {?>
<script type="text/javascript" src="ajax/jquery.js"></script>
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
