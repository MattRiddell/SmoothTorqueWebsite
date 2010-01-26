<?
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);

$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');

require "header.php";
require "header_schedule.php";
if (isset($_POST[queuename])){
    $campaignid=sanitize($_POST[campaignid],false);
    $name = sanitize($_POST[queuename]);
    $description = sanitize($_POST[details]);
    $regularity = sanitize($_POST[regularity]);
    $username = sanitize($_COOKIE[user]);

    $start_exploded = explode(" ",$_POST[starttime]);
    $temp_time_exploded = explode(":",$start_exploded[0]);
    $start_hour = $temp_time_exploded[0];
    $start_minute = $temp_time_exploded[1];
    if ($start_exploded[1] == "pm") {
        $start_hour += 12;
    }

    $end_exploded = explode(" ",$_POST[endtime]);
    $temp_time_exploded = explode(":",$end_exploded[0]);
    $end_hour = $temp_time_exploded[0];
    $end_minute = $temp_time_exploded[1];
    if ($end_exploded[1] == "pm") {
        $end_hour += 12;
    }

    $sql = "INSERT INTO schedule (name, description, campaignid, start_hour, start_minute, end_hour, end_minute, regularity, username)
               VALUES   ($name, $description, $campaignid, $start_hour, $start_minute, $end_hour, $end_minute, $regularity, $username)";
    //echo $sql;
    $result = mysql_query($sql) or die(mysql_error());
    box_start(330);
    ?>
    Saved your schedule<br />
    <img src="images/tick.png" onLoad="window.location = 'schedule.php'">
    <?
    box_end();
    exit;
}
if (!isset($_POST[campaignid])){
    box_start();
    ?>
    <FORM ACTION="addschedule.php" METHOD="POST">
    <center>In order to create a schedule, you will need to choose which campaign you would like to run.<br />
    <br />
    Please select one from the choices below:
    <table class="tborderxxxx" align="center" border="0" cellpadding="0" cellspacing="2"><TR>
    <TD></TD><TD> <br />
        <SELECT NAME="campaignid">
        <?
        //
        $sql = 'SELECT id,name FROM campaign WHERE groupid='.$campaigngroupid.' ORDER BY name';
        $result=mysql_query($sql, $link) or die (mysql_error());;
        //$campaigngroupid=mysql_result($result,0,'campaigngroupid');
        while ($row = mysql_fetch_assoc($result)) {
            echo "<OPTION VALUE=\"".$row[id]."\">".substr(stripslashes($row[name]),0,22)."</OPTION>";
        }
        ?>
        </SELECT>

    </TD>
    </TR><TR>
    <TD COLSPAN=2 ALIGN="CENTER">
    <br />
    <INPUT TYPE="SUBMIT" VALUE="Add Schedule">
    </TD>
    </TR></table>
    </FORM>
    <?
    box_end();
} else {
?>
<FORM ACTION="addschedule.php" METHOD="POST">
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2">
<?
?>
<TR><TD CLASS="thead">Schedule Name</TD><TD>
<INPUT TYPE="HIDDEN" NAME="campaignid" VALUE="<?echo $_POST[campaignid];?>">
<INPUT TYPE="TEXT" NAME="queuename" VALUE="<?echo $row[queuename];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">Schedule Details</TD><TD>
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
</TR>
<TR>
<TD CLASS="thead">How often to run</TD>
<TD>
<select name="regularity">
<option value="every-day">Every Day</option>
<option value="mon-fri">Monday to Friday</option>
<option value="mon-sat">Monday to Saturday</option>
</select>
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
