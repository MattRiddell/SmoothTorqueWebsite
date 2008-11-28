<?
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);

$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');
if (isset($_POST[name])){
    if ($_POST[context]!="-1"){
       	$_POST = array_map(mysql_real_escape_string,$_POST);
    	$id=($_POST[id]);
        $name=($_POST[name]);
        $description=($_POST[description]);
        $context=$_POST[context];
        if ($context == 8) {
            $messageid=$_POST[faxid];
        } else {
    	    $messageid=$_POST[messageid];
        }        $messageid2=($_POST[messageid2]);
        $messageid3=($_POST[messageid3]);
        $modein=($_POST[mode]);
        if ($modein == "mode_queue"){
            $mode = 1;
        } else {
            $mode = 0;
        }
        $astqueuename=($_POST[astqueuename]);
        $maxagents=($_POST[agents]);
        $did=($_POST[did]);
        $clid=($_POST[clid]);
        $trclid=($_POST[trclid]);
        $sql="INSERT INTO campaign (groupid,name,description,messageid,messageid2,messageid3,mode,astqueuename,did,maxagents,clid,trclid,context) VALUES ('$campaigngroupid','$name', '$description', '$messageid','$messageid2','$messageid3','$mode','$astqueuename','$did','$maxagents','$clid','$trclid','$context')";
    //    echo $sql;
        $result=mysql_query($sql, $link) or die (mysql_error());;
/*================= Log Access ======================================*/
$sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_COOKIE[user]', 'Added a campaign')";
$result=mysql_query($sql, $link);
/*================= Log Access ======================================*/
        include("campaigns.php");
        exit;
    } else {
        $error = "Please select a campaign";
    }
}
require "header.php";
require "header_campaign.php";
?>

<FORM ACTION="addcampaign.php" METHOD="POST" id="addcampaign">
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2">
<?
?>
<TR title="The name for the campaign"><TD CLASS="thead">Campaign Name
<a href="#" onclick="displaySmallMessage('includes/help.php?section=A short name you would like to give to the campaign - preferrably one word');return false"><img src="/images/help.png" border="0"></a>
</TD><TD>
<INPUT TYPE="HIDDEN" NAME="id" VALUE="<?echo $_GET[id];?>">
<INPUT TYPE="TEXT" NAME="name" VALUE="<?echo $row[name];?>" size="60">
</TD>
</TR><TR title="A short description of the campaign"><TD CLASS="thead">Campaign Description
<a href="#" onclick="displaySmallMessage('includes/help.php?section=A short description of the campaign in case you are not able to tell from the Campaign Name');return false"><img src="/images/help.png" border="0"></a>
</TD><TD>
<INPUT TYPE="TEXT" NAME="description" VALUE="<?echo $row[description];?>" size="60">
</TD>
</TR>
		<tr id="mode" style="display:none">
			<td class="thead" width=200>Mode
			            <a href="#" onclick="displaySmallMessage('includes/help.php?section=What type of campaign you would like to run. <br /><br />If you are connected to the machine doing the calling then chose Queue Mode.  If you would like to receive any connected calls at a particular phone number, chose DID Mode.  Normally you will use DID Mode unless you have been told to use Queue Mode.');return false"><img src="/images/help.png" border="0"></a>
			</td>
            <td width=*>
			<input type="radio" name="mode" value="didmode" rel="didmode" id="mode_did" checked />
			<label for="mode_did" title="Which number to receive the calls at">DID Mode</label>
			<input type="radio" name="mode" value="mode_queue" rel="queue" id="mode_queue" />
			<label for="mode_queue" title="Use this is the agents are connected to the machine doing the calling">Queue Mode</label>
		</td>
		</tr>
        <TR><TD CLASS="thead">Type of Campaign
        <a href="#" onclick="displayLargeMessage('includes/help.php?section=<b>Load Simulation</b><br />Simple test campaign.  Does not actually make any phone calls<br /><br /><b>Answer Machine Only</b><br />Human: Hang Up. Answer Machine: Leave Message<br /><br /><b>Immediate Live Only</b><br />Human: Connect immediately to the call center. Answer Machine: hang up.<br /><br /><b>Press 1 Live Only</b><br />Human: Play the person message and then if they press 1, transfer to the call center.  Answer Machine: Hang Up.<br /><br /><b>Immediate Live and Answer Machine</b><br />Human: Connect immediately to the call center. Answer Machine: Leave the answer machine message.<br /><br /><b>Press 1 Live and Answer Machine</b><br />Human: Play the person message and then if they press 1, transfer to the call center.  Answer Machine: Leave the answer machine message.<br /><br /><b>Direct Transfer</b><br />Transfer the call without checking to see if it is a machine or a human.');return false"><img src="/images/help.png" border="0" title="Type Of Campaign"></a>
        </TD><TD>
<SELECT NAME="context" id="context" onchange="whatPaySelected(this.value)">
<OPTION VALUE="-1" SELECTED>Please chose a type of campaign...</OPTION>
<OPTION VALUE="0" title="No phone calls are made">Load Simulation</OPTION>
<OPTION VALUE="1" title="Only leave a message for answering machines, hang up when a person answers">Answer Machine Only</OPTION>
<OPTION VALUE="2" title="Automatically send a person straight through to the call center">Immediate Live</OPTION>
<OPTION VALUE="4" title="Play a message to a person, hang up for answering machines">Press 1 Live Only</OPTION>
<OPTION VALUE="5" title="Put a person straight through to the call center, and leave a message for the answer machines">Immediate Live and Answer Machine</OPTION>
<OPTION VALUE="3" title="Play a message to a person, if they press 1, put them through to the call center. Leave a message for answering machines">Press 1 Live and Answer Machine</OPTION>
<OPTION VALUE="6" title="As soon as a number is connected, transfer it to a staff memeber"> Direct Transfer</OPTION>
<OPTION VALUE="7" title="When a call is answered, play back the message and then hang up"> Immediate Message Playback</OPTION>
<OPTION VALUE="8" title="Ring a number, when it answers start sending a fax">Fax Broadcast</OPTION>
<OPTION VALUE="9">SMS Broadcast (coming soon)</OPTION>
<OPTION VALUE="10"><?echo $config_values['SPARE1'];?></OPTION>
<OPTION VALUE="11"><?echo $config_values['SPARE2'];?></OPTION>
<OPTION VALUE="12"><?echo $config_values['SPARE3'];?></OPTION>
<OPTION VALUE="13"><?echo $config_values['SPARE4'];?></OPTION>
<OPTION VALUE="14"><?echo $config_values['SPARE5'];?></OPTION>
<?/*<OPTION VALUE="5" <?if ($row[context]==5){echo "SELECTED";}?>>Spare 2</OPTION>
<OPTION VALUE="6" <?if ($row[context]==6){echo "SELECTED";}?>>Spare 3</OPTION>
<OPTION VALUE="7" <?if ($row[context]==7){echo "SELECTED";}?>>Spare 4</OPTION>
<OPTION VALUE="8" <?if ($row[context]==8){echo "SELECTED";}?>>Spare 5</OPTION>
<OPTION VALUE="9" <?if ($row[context]==9){echo "SELECTED";}?>>Spare 6</OPTION>
<OPTION VALUE="10" <?if ($row[context]==10){echo "SELECTED";}?>>Spare 6</OPTION>*/?>
</SELECT>
</TD>
</TR>
		<tr rel="didmode" id="xx6" style="display:none" >
			<td class="thead" width=200><label for="agents">Maximum Connected Calls:
            <a href="#" onclick="displaySmallMessage('includes/help.php?section=This is the number of concurrent calls you would like to receive on the call center number specified.  <br /><br />Normally this will be the number of staff you have.');return false" title="The number of concurrent calls to be put through to the call center"><img src="/images/help.png" border="0"></a>
            </label></td>
			<td width=*><input type="text" name="agents" id="agents" size="28" value="30"></td>
		</tr>
<?
if ($_COOKIE[level] == sha1("level100")) {
    $sql = 'SELECT * FROM campaignmessage where filename like "x-%"';
    $sql_fax = 'SELECT * FROM campaignmessage where filename like "fax-%"';
} else {
    $sql = 'SELECT * FROM campaignmessage WHERE filename like "x-%" and customer_id='.$campaigngroupid;
    $sql_fax = 'SELECT * FROM campaignmessage WHERE filename like "fax-%" and customer_id='.$campaigngroupid;
}
$result=mysql_query($sql,$link) or die (mysql_error());
$count=0;
while ($row2[$count] = mysql_fetch_assoc($result)) {
    $count++;
}

$result_fax=mysql_query($sql_fax,$link) or die (mysql_error());
$count_fax=0;
while ($row2_fax[$count_fax] = mysql_fetch_assoc($result_fax)) {
    $count_fax++;
}

$sql="SELECT * from queue_table";
$result=mysql_query($sql,$link) or die (mysql_error());
$count2=0;
while ($row_queue[$count2] = mysql_fetch_assoc($result)) {
    $count2++;
}
$sql="SELECT astqueuename from customer where campaigngroupid=$campaigngroupid";
$result=mysql_query($sql,$link) or die (mysql_error());
if (mysql_num_rows($result) > 0) {
    $row_queue[$count2][name] = mysql_result($result,0,0);
    $count2++;
}


?>

<?/*
===================================================================================================
                                This is for the fax message
===================================================================================================
*/?>


<TR id="fax" style="display:none" title="The fax you would like to send"><TD CLASS="thead">Fax Message
<a href="#" onclick="displaySmallMessage('includes/help.php?section=If you are running a campaign which sends a fax to the user then this is the fax that will be used.');return false"><img src="/images/help.png" border="0"></a>
</TD><TD>
<SELECT name="faxid">
<?
for ($i=0;$i<$count_fax;$i++){
$selected="";
if ($row[messageid]==$row2_fax[$i][id]){
    $selected=" SELECTED";
}
echo "<OPTION VALUE=\"".$row2_fax[$i][id]."\"$selected>".$row2_fax[$i][description]."</OPTION>";
}
?>
</SELECT>
</TD>
</TR>

<?/*
===================================================================================================
                                This is for the live message
===================================================================================================
*/?>

<TR id="xx2" style="display:none" title="The message to play to the person who answers the phone"><TD CLASS="thead">Live Message
<a href="#" onclick="displaySmallMessage('includes/help.php?section=If you are running a campaign which plays a message to the user while waiting for them to press 1 then this is the message that will be used.');return false"><img src="/images/help.png" border="0"></a>
</TD><TD>
<SELECT name="messageid">
<?
for ($count2=0;$count2<$count;$count2++){
$selected="";
if ($row[messageid]==$row2[$count2][id]){
    $selected=" SELECTED";
}
echo "<OPTION VALUE=\"".$row2[$count2][id]."\"$selected>".$row2[$count2][description]."</OPTION>";
}
?>
</SELECT>
</TD>
</TR>

<?/*
===================================================================================================
                                This is for the answer machine message
===================================================================================================
*/?>


<TR id="xx3"  style="display:none" title="The message to leave to the answer machine"><TD CLASS="thead">Answer Machine Message<a href="#" onclick="displaySmallMessage('includes/help.php?section=If you are leaving automated messages on answer machines then you can set this to a particular message you would like to have played when an answer machine is detected.  Usage of this will depend on your settings in the Type of Campaign section.');return false"><img src="/images/help.png" border="0"></a>
</TD><TD>
<SELECT name="messageid2">
<?
for ($count2=0;$count2<$count;$count2++){
$selected="";
if ($row[messageid2]==$row2[$count2][id]){
    $selected=" SELECTED";
}
echo "<OPTION VALUE=\"".$row2[$count2][id]."\"$selected>".$row2[$count2][description]."</OPTION>";
}
?>
</SELECT>
</TD>
</TR>

<?/*
===================================================================================================
                                This is for the DNC List Message
===================================================================================================
*/?>


<TR  id="xx4" style="display:none" title="The message played to someone who wants to be put on the DNC list"><TD CLASS="thead">DNC Confirmation Message
<a href="#" onclick="displaySmallMessage('includes/help.php?section=This message is played to a customer who presses 2 to be added to DNC.');return false"><img src="/images/help.png" border="0"></a>
</TD><TD>
<SELECT name="messageid3">
<?
for ($count2=0;$count2<$count;$count2++){
$selected="";
if ($row[messageid3]==$row2[$count2][id]){
    $selected=" SELECTED";
}
echo "<OPTION VALUE=\"".$row2[$count2][id]."\"$selected>".$row2[$count2][description]."</OPTION>";
}
?>
</SELECT>
</TD>
</TR>




        <tr rel="queue" title="The name of the queue used for agents">
			<td class="thead" width=200><label for="agents">Queue Name
            <a href="#" onclick="displaySmallMessage('includes/help.php?section=This is the name of a Queue on the telephone system of the provider of this system. Normally this will be assigned to you when you set up an account.');return false"><img src="/images/help.png" border="0"></a>
            </label></td>
			<td width=*>

            <SELECT name="astqueuename">
<?
for ($count2=0;$count2<sizeof($row_queue);$count2++){
$selected="";
if ($row[astqueuename]==$row_queue[$count2][name]){
    $selected=" SELECTED";
}
echo "<OPTION VALUE=\"".$row_queue[$count2][name]."\"$selected>".$row_queue[$count2][name]."</OPTION>";
}
?>
</SELECT>

			</td>
		</tr>
        		<tr id="xx5" style="display:none" title="The caller id you would like to send out">
			<td class="thead"><label for="did">Caller ID:
			<a href="#" onclick="displaySmallMessage('includes/help.php?section=The CallerID you would like to send on calls to your customers');return false"><img src="/images/help.png" border="0"></a>
			</label></td>
			<td><input type="text" name="clid" id="did" size=28 value="ls3"></td>
		</tr>
        <tr rel="didmode" id="xx1" style="display:none" title="The number for the call center">
			<td class="thead"><label for="did">Call Center Phone Number:
			<a href="#" onclick="displaySmallMessage('includes/help.php?section=The phone number you would like to have connected calls sent to. Eg: (123) 555-1234. ');return false"><img src="/images/help.png" border="0" id="x"  ></a>
			</label></td>
			<td><input type="text" name="did" id="did" size=28 value="ls3"></td>
		</tr>
<?/*        <tr class=tborder2>
        <td colspan="2">
<b>Load Simulation</b><br />
Simple test campaign.  Does not actually make any phone calls<br />
<b>Answer Machine Only</b><br />
Human: Hang Up. Answer Machine: Leave Message<br />
<b>Immediate Live Only</b><br />
Human: Connect immediately to the call center. Answer Machine: hang up.<br />
<b>Press 1 Live Only</b><br />
Human: Play the person message and then if they press
1, transfer to the call center.  Answer Machine: Hang Up.<br />
<b>Immediate Live and Answer Machine</b><br />
Human: Connect immediately to the call center. Answer Machine: Leave the answer machine message.<br />
<b>Press 1 Live and Answer Machine</b><br />
Human: Play the person message and then if they press
1, transfer to the call center.  Answer Machine: Leave the answer machine message.<br />
<b>Direct Transfer</b><br />
Transfer the call to the queue or did regardless of answer machine or human.<br />
        </td></tr>*/?>
		<tr>




</TR><TR><TD COLSPAN=2 ALIGN="RIGHT">
<INPUT TYPE="SUBMIT" VALUE="<?echo $config_values['ADD_CAMPAIGN'];?>">
</TD>
</TR>
<?
?>

</TABLE>
</FORM>
<?
require "footer.php";
?>
