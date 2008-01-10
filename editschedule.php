<?
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);

$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);

$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');

if (isset($_POST[queuename])){
    //$queueid=$_POST[queueid];
    $campaignid=$_POST[campaignID];
    $queuename=$_POST[queuename];
    $status=$_POST[status];
    $details=$_POST[details];
    $flags="0";
    $transferclid=$_POST[transferclid];
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
    $did=$_POST[did];
    $clid=$_POST[clid];
    $context=$_POST[context];
    $maxcalls=$_POST[maxcalls];
    $maxchans=$_POST[maxchans];
    $maxretries=$_POST[maxretries];
    $retrytime=$_POST[retrytime];
    $waittime=$_POST[waittime];
    $queueid=$_POST[queueid];
    $sql="UPDATE queue SET campaignid='$campaignid',queuename='$queuename'
    ,details='$details',transferclid='$transferclid',
    starttime='$starttime',endtime='$endtime',startdate='$startdate',enddate='$enddate',did='$did'
    ,clid='$clid',context='$context',maxcalls='$maxcalls',maxchans='$maxchans',maxretries='$maxretries'
    ,retrytime='$retrytime',waittime='$waittime' WHERE queueid=$queueid";
    // echo $sql;
//    $sql="INSERT INTO campaign (groupid,name,description,messageid,messageid2,messageid3) VALUES ('$campaigngroupid','$name', '$description', '$messageid','$messageid2','$messageid3')";
    $result=mysql_query($sql, $link) or die (mysql_error());;
    include("schedule.php");
    exit;
}
require "header.php";
require "header_schedule.php";
if (!isset($_GET[id])){
} else {
$sql = 'SELECT * FROM queue WHERE queueid='.$_GET[id];
        $result=mysql_query($sql, $link) or die (mysql_error());;
        //$campaigngroupid=mysql_result($result,0,'campaigngroupid');
        $row = mysql_fetch_assoc($result);
?>
<FORM ACTION="editschedule.php" METHOD="POST">
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2">
<?
?>
<TR><TD CLASS="thead">Queue Name</TD><TD>
<INPUT TYPE="HIDDEN" NAME="campaignID" VALUE="<?echo $row[campaignID];?>">
<INPUT TYPE="HIDDEN" NAME="queueid" VALUE="<?echo $_GET[id];?>">
<INPUT TYPE="TEXT" NAME="queuename" VALUE="<?echo $row[queuename];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">Queue Details</TD><TD>
<INPUT TYPE="TEXT" NAME="details" VALUE="<?echo $row[details];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">Start Time</TD>
<?
    $starttime=$row[starttime];
    $starthour=strtok($starttime,": ");
    $startmin=strtok(": ");
    if ($starthour>12){
        $starthour-=12;
        $startampm="pm";
    } else {
        $startampm="am";
    }
    $start="$starthour:$startmin $startampm";
    
    $endtime=$row[endtime];
    $endhour=strtok($endtime,": ");
    $endmin=strtok(": ");
    if ($endhour>12){
        $endhour-=12;
        $endampm="pm";
    } else {
        $endampm="am";
    }
    $end="$endhour:$endmin $endampm";

    //echo "[$start - $end]";
    
?>
<td><input id='starttime' name="starttime" type='text' value='<?echo $start;?>' size=8 maxlength=8 ONBLUR="validateDatePicker(this)">
<IMG SRC="timePickerImages/timepicker.gif" BORDER="0" ALT="Pick a Time!" ONCLICK="selectTime(this,starttime)" STYLE="cursor:hand"></td>
</TD>
</TR><TR><TD CLASS="thead">End Time</TD>
<td><input id='endtime' name="endtime" type='text' value='<?echo $end;?>' size=8 maxlength=8 ONBLUR="validateDatePicker(this)">
<IMG SRC="timePickerImages/timepicker.gif" BORDER="0" ALT="Pick a Time!" ONCLICK="selectTime(this,endtime)" STYLE="cursor:hand"></td>
</TD>
</TR><TR><TD CLASS="thead">Start Date</TD><TD>
<input name="startdate" value="<?echo $row[startdate];?>"> 

<input type=button value="select" onclick="displayDatePicker('startdate', false, 'ymd', '-');">
</TD>
</TR><TR><TD CLASS="thead">End Date</TD><TD>
<input name="enddate" value="<?echo $row[enddate];?>"> 

<input type=button value="select" onclick="displayDatePicker('enddate', false, 'ymd', '-');">
</TD>
</TR><TR><TD CLASS="thead">DID Number</TD><TD>
<INPUT TYPE="TEXT" NAME="did" VALUE="<?echo $row[did];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">Caller ID</TD><TD>
<INPUT TYPE="TEXT" NAME="clid" VALUE="<?echo $row[clid];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">Transfer Caller ID</TD><TD>
<INPUT TYPE="TEXT" NAME="transferclid" VALUE="<?echo $row[transferclid];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">Type of Campaign</TD><TD>
<SELECT NAME="context">
<OPTION VALUE="0" <?if ($row[context]==0){echo "SELECTED";}?>>Load Simulation</OPTION>
<OPTION VALUE="1" <?if ($row[context]==1){echo "SELECTED";}?>>Answer Machine Only</OPTION>
<OPTION VALUE="2" <?if ($row[context]==2){echo "SELECTED";}?>>Live Only</OPTION>
<OPTION VALUE="3" <?if ($row[context]==3){echo "SELECTED";}?>>Live and Answer Machine</OPTION>
<OPTION VALUE="4" <?if ($row[context]==4){echo "SELECTED";}?>>Spare</OPTION>
<OPTION VALUE="5" <?if ($row[context]==5){echo "SELECTED";}?>>Spare 2</OPTION>
<OPTION VALUE="6" <?if ($row[context]==6){echo "SELECTED";}?>>Spare 3</OPTION>
<OPTION VALUE="7" <?if ($row[context]==7){echo "SELECTED";}?>>Spare 4</OPTION>
<OPTION VALUE="8" <?if ($row[context]==8){echo "SELECTED";}?>>Spare 5</OPTION>
<OPTION VALUE="9" <?if ($row[context]==9){echo "SELECTED";}?>>Spare 6</OPTION>
<OPTION VALUE="10" <?if ($row[context]==10){echo "SELECTED";}?>>Spare 6</OPTION>
</SELECT>
</TD>
</TR><TR><TD CLASS="thead">Type of Schedule</TD><TD>
<SELECT NAME="status">
<OPTION VALUE="1" <?if ($row[status]==1){echo "SELECTED";}?><?if ($row[status]==101){echo "SELECTED";}?>>Start at this time</OPTION>
<OPTION VALUE="2" <?if ($row[status]==2){echo "SELECTED";}?><?if ($row[status]==102){echo "SELECTED";}?>>Stop at this time</OPTION>
</SELECT>
</TD>
</TR><TR><TD CLASS="thead">Max Number of Calls</TD><TD>
<INPUT TYPE="TEXT" NAME="maxcalls" VALUE="<?echo $row[maxcalls];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">Max Number of Channels</TD><TD>
<INPUT TYPE="TEXT" NAME="maxchans" VALUE="<?echo $row[maxchans];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">Max Retries</TD><TD>
<INPUT TYPE="TEXT" NAME="maxretries" VALUE="<?echo $row[maxretries];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">Retry Time</TD><TD>
<INPUT TYPE="TEXT" NAME="retrytime" VALUE="<?echo $row[retrytime];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">Wait Time</TD><TD>
<INPUT TYPE="TEXT" NAME="waittime" VALUE="<?echo $row[waittime];?>" size="60">
</TD>

</TR><TR><TD COLSPAN=2 ALIGN="RIGHT">
<INPUT TYPE="SUBMIT" VALUE="Save Changes">
</TD>
</TR>
<?
?>

</TABLE>
</FORM>
<?      }
require "footer.php";
?>

