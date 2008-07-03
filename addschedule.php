<?
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);

$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');

if (isset($_POST[queuename])){
    //$queueid=$_POST[queueid];
    $campaignid=$_POST[campaignid];
    $sql = 'SELECT * FROM campaign WHERE id=\''.$campaignid.'\'';
    $result=mysql_query($sql, $link) or die (mysql_error());;
    $row = mysql_fetch_assoc($result);
    //print_r($row);
    //exit(0);
    //echo "<br><br><br>";
    $trclid=mysql_result($result,0,'trclid');
    $clid=mysql_result($result,0,'clid');
    //echo "CLID: ".$clid;

    if (strlen($trclid)==0) {
        $trclid = "notrclid";
    }
    $did=mysql_result($result,0,'did');
    $mode=mysql_result($result,0,'mode');
    $context=mysql_result($result,0,'context');
    $maxagents=mysql_result($result,0,'maxagents');
    $astqueuename=mysql_result($result,0,'astqueuename');

    $id=$_POST[campaignid];
    $queuename=$_POST[queuename];
    $status=$_POST[status];
    $details=$_POST[details];
    $flags="0";
    //9:00 am
    //5:00 pm
    $starttime=$_POST[starttime];
    $starthour=strtok($starttime,": ");
    $startmin=strtok(": ");
    $startperiod=strtok(": ");
    if ($startperiod=="pm"){
        $starthour+=12;
    } else {
        if ($starthour==12){
            $starthour=0;
        }
    }
    $starttime=$starthour.":".$startmin;
    $endtime=$_POST[endtime];
    $endhour=strtok($endtime,": ");
    $endmin=strtok(": ");
    $endperiod=strtok(": ");
    if ($endperiod=="pm"){
        $endhour+=12;
    } else {
        if ($endhour==12){
            $endhour=0;
        }
    }
    $endtime=$endhour.":".$endmin;

    $startdate=$_POST[startdate];
    $enddate=$_POST[enddate];

$sqlx = 'SELECT campaigngroupid, maxchans, maxcps FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sqlx, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');
$maxchans=mysql_result($result,0,'maxchans');
$maxcps=mysql_result($result,0,'maxcps');
$username=$_COOKIE[user];


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

$sql1="delete from queue where campaignid=".$id;
$did = str_replace("-","",$did);
$did = str_replace("(","",$did);
$did = str_replace(")","",$did);
$did = str_replace(" ","",$did);

$dialstring = str_replace("-","",$dialstring);
$dialstring = str_replace(" ","",$dialstring);
$dialstring = str_replace("(","",$dialstring);
$dialstring = str_replace(")","",$dialstring);
/*
if (strlen($astqueuename)==0){
    $astqueuename = "noqueue";
}*/
$sql2="INSERT INTO queue (campaignid,queuename,status,details,flags,transferclid,
    starttime,endtime,startdate,enddate,did,clid,context,maxcalls,maxchans,maxretries
    ,retrytime,waittime,trunk,astqueuename, accountcode, trunkid, customerID, maxcps) VALUES
    ('$id','$queuename','$status','$details','0','$trclid',
    '$starttime','$endtime','$startdate','$enddate','$did','$clid',
    '$context','$maxagents','$maxchans','0'
    ,'0','30','".$dialstring."','$astqueuename','stl-".$username."','$trunkid','$campaigngroupid','$maxcps') ";
   // echo $sql2."<br />";
//exit(0);
//echo $sql2;
//$resultx=mysql_query($sql1, $link) or die (mysql_error());;
$resultx=mysql_query($sql2, $link) or die (mysql_error());;



    include("schedule.php");
    exit;
}
require "header.php";
require "header_schedule.php";
if (!isset($_POST[campaignid])){
    ?>
    <FORM ACTION="addschedule.php" METHOD="POST">
    <table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2"><TR>
    <TD>Select Campaign:</TD><TD>
        <SELECT NAME="campaignid">
        <?
        //
        $sql = 'SELECT id,name FROM campaign WHERE groupid='.$campaigngroupid;
        $result=mysql_query($sql, $link) or die (mysql_error());;
        //$campaigngroupid=mysql_result($result,0,'campaigngroupid');
        while ($row = mysql_fetch_assoc($result)) {
            echo "<OPTION VALUE=\"".$row[id]."\">".substr($row[name],0,22)."</OPTION>";
        }
        ?>
        </SELECT>

    </TD>
    </TR><TR>
    <TD COLSPAN=2 ALIGN="CENTER">
    <INPUT TYPE="SUBMIT" VALUE="Add Schedule">
    </TD>
    </TR></table>
    </FORM>
    <?
} else {
?>
<FORM ACTION="addschedule.php" METHOD="POST">
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2">
<?
?>
<TR><TD CLASS="thead">Queue Name</TD><TD>
<INPUT TYPE="HIDDEN" NAME="campaignid" VALUE="<?echo $_POST[campaignid];?>">
<INPUT TYPE="TEXT" NAME="queuename" VALUE="<?echo $row[queuename];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">Queue Details</TD><TD>
<INPUT TYPE="TEXT" NAME="details" VALUE="<?echo $row[details];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">Schedule Start Time</TD>
<td><input id='starttime' name="starttime" type='text' value='9:00 am' size=8 maxlength=8 ONBLUR="validateDatePicker(this)">
<IMG SRC="timePickerImages/timepicker.gif" BORDER="0" ALT="Pick a Time!" ONCLICK="selectTime(this,starttime)" STYLE="cursor:hand"></td>
</TD>
</TR><TR><TD CLASS="thead">Schedule End Time</TD>
<td><input id='endtime' name="endtime" type='text' value='5:00 pm' size=8 maxlength=8 ONBLUR="validateDatePicker(this)">
<IMG SRC="timePickerImages/timepicker.gif" BORDER="0" ALT="Pick a Time!" ONCLICK="selectTime(this,endtime)" STYLE="cursor:hand"></td>
</TD>
</TR><TR><TD CLASS="thead">Schedule Start Date</TD><TD>
<input name="startdate">

<input type=button value="select" onclick="displayDatePicker('startdate', false, 'ymd', '-');">
</TD>
</TR><TR><TD CLASS="thead">Schedule End Date</TD><TD>
<input name="enddate">

<input type=button value="select" onclick="displayDatePicker('enddate', false, 'ymd', '-');">
</TD>
</TR>


<TR><TD CLASS="thead">Type of Schedule</TD><TD>
<SELECT NAME="status">
<OPTION VALUE="1">Start at this time</OPTION>
<OPTION VALUE="2">Stop at this time</OPTION>
</SELECT>
</TD>
</TR>
<TR>
<TD COLSPAN=2 ALIGN="RIGHT">
<br />Please note that the start and end dates and times are for when you<br />
would like the dialer to read this information.  For example, a start<br />
time of 8am and an end time of 9am means that if the dialer reads this<br />
entry and the time is between 8am and 9am then it should act on it.
<br /><br />
</TD>
</TR>
<TR><TD COLSPAN=2 ALIGN="RIGHT">
<INPUT TYPE="SUBMIT" VALUE="Add Schedule">
</TD>
</TR>
<?
?>

</TABLE>
</FORM>
<?      }
require "footer.php";
?>
