<?
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);

$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');
if (isset($_POST[name])){
   	$_POST = array_map(mysql_real_escape_string,$_POST);
	$id=($_POST[id]);
    $name=($_POST[name]);
    $description=($_POST[description]);
    $messageid=($_POST[messageid]);
    $messageid2=($_POST[messageid2]);
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
    $context=($_POST[context]);
    $sql="INSERT INTO campaign (groupid,name,description,messageid,messageid2,messageid3,mode,astqueuename,did,maxagents,clid,trclid,context) VALUES ('$campaigngroupid','$name', '$description', '$messageid','$messageid2','$messageid3','$mode','$astqueuename','$did','$maxagents','$clid','$trclid','$context')";
//    echo $sql;
    $result=mysql_query($sql, $link) or die (mysql_error());;
    include("campaigns.php");
    exit;
}
require "header.php";
require "header_campaign.php";
?>

<FORM ACTION="addcampaign.php" METHOD="POST">
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2">
<?
?>
<TR><TD CLASS="thead">Campaign Name
<a href="#" onclick="displaySmallMessage('includes/help.php?section=A short name you would like to give to the campaign - preferrably one word');return false"><img src="/images/help.png" border="0"></a>
</TD><TD>
<INPUT TYPE="HIDDEN" NAME="id" VALUE="<?echo $_GET[id];?>">
<INPUT TYPE="TEXT" NAME="name" VALUE="<?echo $row[name];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">Campaign Description
<a href="#" onclick="displaySmallMessage('includes/help.php?section=A short description of the campaign in case you are not able to tell from the Campaign Name');return false"><img src="/images/help.png" border="0"></a>
</TD><TD>
<INPUT TYPE="TEXT" NAME="description" VALUE="<?echo $row[description];?>" size="60">
</TD>
</TR>
<?
$sql="SELECT * from campaignmessage where customer_id=".$campaigngroupid;
$result=mysql_query($sql,$link) or die (mysql_error());
$count=0;
while ($row2[$count] = mysql_fetch_assoc($result)) {
    $count++;
    //echo "<OPTION VALUE=\"".$row[id]."\">".$row[name]."</OPTION>";
}

$sql="SELECT * from queue_table";
$result=mysql_query($sql,$link) or die (mysql_error());
$count2=0;
while ($row_queue[$count2] = mysql_fetch_assoc($result)) {
    $count2++;
    //echo "<OPTION VALUE=\"".$row[id]."\">".$row[name]."</OPTION>";
}


?>

<TR><TD CLASS="thead">Live Message
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
</TR><TR><TD CLASS="thead">Answer Machine Message<a href="#" onclick="displaySmallMessage('includes/help.php?section=If you are leaving automated messages on answer machines then you can set this to a particular message you would like to have played when an answer machine is detected.  Usage of this will depend on your settings in the Type of Campaign section.');return false"><img src="/images/help.png" border="0"></a>
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
</TR><TR><TD CLASS="thead">DNC Confirmation Message
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




		<tr>
			<td class="thead" width=200>Mode
			            <a href="#" onclick="displaySmallMessage('includes/help.php?section=What type of campaign you would like to run. <br /><br />If you are connected to the machine doing the calling then chose Queue Mode.  If you would like to receive any connected calls at a particular phone number, chose DID Mode.  Normally you will use DID Mode unless you have been told to use Queue Mode.');return false"><img src="/images/help.png" border="0"></a>
			</td>
            <td width=*>
			<input type="radio" name="mode" value="didmode" rel="didmode" id="mode_did" />
			<label for="mode_did">DID Mode</label>
			<input type="radio" name="mode" value="mode_queue" rel="queue" id="mode_queue" />
			<label for="mode_queue">Queue Mode</label></td>
		</tr>
        <tr rel="queue">
			<td class="thead" width=200><label for="agents">Queue Name
            <a href="#" onclick="displaySmallMessage('includes/help.php?section=This is the name of a Queue on the telephone system of the provider of this system. Normally this will be assigned to you when you set up an account.');return false"><img src="/images/help.png" border="0"></a>
            </label></td>
			<td width=*>

            <SELECT name="astqueuename">
<?
for ($count2=0;$count2<$count;$count2++){
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
		<tr rel="didmode">
			<td class="thead" width=200><label for="agents">Maximum Connected Calls:
            <a href="#" onclick="displaySmallMessage('includes/help.php?section=This is the number of concurrent calls you would like to receive on the call center number specified.  <br /><br />Normally this will be the number of staff you have.');return false"><img src="/images/help.png" border="0"></a>
            </label></td>
			<td width=*><input type="text" name="agents" id="agents" size="28" value="30"></td>
		</tr>
        		<tr>
			<td class="thead"><label for="did">Caller ID:
			<a href="#" onclick="displaySmallMessage('includes/help.php?section=The CallerID you would like to send on calls to your customers');return false"><img src="/images/help.png" border="0"></a>
			</label></td>
			<td><input type="text" name="clid" id="did" size=28 value="ls3"></td>
		</tr>
        <tr rel="didmode">
			<td class="thead"><label for="did">Call Center Phone Number:
			<a href="#" onclick="displaySmallMessage('includes/help.php?section=The phone number you would like to have connected calls sent to. Eg: (123) 555-1234. ');return false"><img src="/images/help.png" border="0"></a>
			</label></td>
			<td><input type="text" name="did" id="did" size=28 value="ls3"></td>
		</tr>
        <TR><TD CLASS="thead">Type of Campaign
        <a href="#" onclick="displayLargeMessage('includes/help.php?section=<b>Load Simulation</b><br />Simple test campaign.  Does not actually make any phone calls<br /><br /><b>Answer Machine Only</b><br />Human: Hang Up. Answer Machine: Leave Message<br /><br /><b>Immediate Live Only</b><br />Human: Connect immediately to the call center. Answer Machine: hang up.<br /><br /><b>Press 1 Live Only</b><br />Human: Play the person message and then if they press 1, transfer to the call center.  Answer Machine: Hang Up.<br /><br /><b>Immediate Live and Answer Machine</b><br />Human: Connect immediately to the call center. Answer Machine: Leave the answer machine message.<br /><br /><b>Press 1 Live and Answer Machine</b><br />Human: Play the person message and then if they press 1, transfer to the call center.  Answer Machine: Leave the answer machine message.');return false"><img src="/images/help.png" border="0"></a>
        </TD><TD>
<SELECT NAME="context">
<OPTION VALUE="0" SELECTED>Load Simulation</OPTION>
<OPTION VALUE="1">Answer Machine Only</OPTION>
<OPTION VALUE="2">Immediate Live</OPTION>
<OPTION VALUE="4">Press 1 Live</OPTION>
<OPTION VALUE="5">Immediate Live and Answer Machine</OPTION>
<OPTION VALUE="3">Press 1 Live and Answer Machine</OPTION>
<?/*<OPTION VALUE="5" <?if ($row[context]==5){echo "SELECTED";}?>>Spare 2</OPTION>
<OPTION VALUE="6" <?if ($row[context]==6){echo "SELECTED";}?>>Spare 3</OPTION>
<OPTION VALUE="7" <?if ($row[context]==7){echo "SELECTED";}?>>Spare 4</OPTION>
<OPTION VALUE="8" <?if ($row[context]==8){echo "SELECTED";}?>>Spare 5</OPTION>
<OPTION VALUE="9" <?if ($row[context]==9){echo "SELECTED";}?>>Spare 6</OPTION>
<OPTION VALUE="10" <?if ($row[context]==10){echo "SELECTED";}?>>Spare 6</OPTION>*/?>
</SELECT>
</TD>
</TR>
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

        </td></tr>*/?>
		<tr>




</TR><TR><TD COLSPAN=2 ALIGN="RIGHT">
<INPUT TYPE="SUBMIT" VALUE="Add Campaign">
</TD>
</TR>
<?
?>

</TABLE>
</FORM>
<?
require "footer.php";
?>
