<?
$pagenum="3";
$link = mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);

$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');

if (isset($_POST[queuename])){
    //$queueid=$_POST[queueid];
    $campaignid=$_POST[campaignid];
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
    $sql="INSERT INTO queue (campaignid,queuename,status,details,flags,transferclid,
    starttime,endtime,startdate,enddate,did,clid,context,maxcalls,maxchans,maxretries
    ,retrytime,waittime) VALUES
    ('$campaignid','$queuename','$status','$details','$flags','$transferclid',
    '$starttime','$endtime','$startdate','$enddate','$did','$clid','$context','$maxcalls','$maxchans','$maxretries'
    ,'$retrytime','$waittime') ";

//    $sql="INSERT INTO campaign (groupid,name,description,messageid,messageid2,messageid3) VALUES ('$campaigngroupid','$name', '$description', '$messageid','$messageid2','$messageid3')";
    $result=mysql_query($sql, $link) or die (mysql_error());;
    include("schedule.php");
    exit;
}
require "header.php";
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
            echo "<OPTION VALUE=\"".$row[id]."\">".$row[name]."</OPTION>";
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
</TR><TR><TD CLASS="thead">Start Time</TD>
<td><input id='starttime' name="starttime" type='text' value='9:00 am' size=8 maxlength=8 ONBLUR="validateDatePicker(this)">
<IMG SRC="timePickerImages/timepicker.gif" BORDER="0" ALT="Pick a Time!" ONCLICK="selectTime(this,starttime)" STYLE="cursor:hand"></td>
</TD>
</TR><TR><TD CLASS="thead">End Time</TD>
<td><input id='endtime' name="endtime" type='text' value='5:00 pm' size=8 maxlength=8 ONBLUR="validateDatePicker(this)">
<IMG SRC="timePickerImages/timepicker.gif" BORDER="0" ALT="Pick a Time!" ONCLICK="selectTime(this,endtime)" STYLE="cursor:hand"></td>
</TD>
</TR><TR><TD CLASS="thead">Start Date</TD><TD>
<input name="startdate">

<input type=button value="select" onclick="displayDatePicker('startdate', false, 'ymd', '-');">
</TD>
</TR><TR><TD CLASS="thead">End Date</TD><TD>
<input name="enddate">

<input type=button value="select" onclick="displayDatePicker('enddate', false, 'ymd', '-');">
</TD>
</TR><TR><TD CLASS="thead">DID Number (ls3 for load simulation)</TD><TD>
<INPUT TYPE="TEXT" NAME="did" VALUE="ls3" size="60">
</TD>
</TR><TR><TD CLASS="thead">Caller ID</TD><TD>
<INPUT TYPE="TEXT" NAME="clid" VALUE="<?echo $row[clid];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">Transfer Caller ID</TD><TD>
<INPUT TYPE="TEXT" NAME="transferclid" VALUE="<?echo $row[transferclid];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">Type of Campaign</TD><TD>
<SELECT NAME="context">
<OPTION VALUE="0">Load Simulation</OPTION>
<?/*
<OPTION VALUE="1">Answer Machine Only</OPTION>
<OPTION VALUE="2">Live Only</OPTION>
<OPTION VALUE="3">Live and Answer Machine</OPTION>
<OPTION VALUE="4">Spare</OPTION>
<OPTION VALUE="5">Spare 2</OPTION>
<OPTION VALUE="6">Spare 3</OPTION>
<OPTION VALUE="7">Spare 4</OPTION>
<OPTION VALUE="8">Spare 5</OPTION>
<OPTION VALUE="9">Spare 6</OPTION>
<OPTION VALUE="10">Spare 6</OPTION>
*/?>
</SELECT>
</TD>
</TR><TR><TD CLASS="thead">Type of Schedule</TD><TD>
<SELECT NAME="status">
<OPTION VALUE="1">Start at this time</OPTION>
<OPTION VALUE="2">Stop at this time</OPTION>
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
