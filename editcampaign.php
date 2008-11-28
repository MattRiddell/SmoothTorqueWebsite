<?
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);

$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');

if (isset($_POST[name])){

	//echo "*********".$_POST[name]."**********<br>";
$_POST = array_map(htmlspecialchars,$_POST);
$_GET = array_map(htmlspecialchars,$_GET);
$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);

//echo "*********".$_POST[name]."**********<br>";
	$id=$_POST[id];
    	$name=$_POST[name];
    	$description=$_POST[description];
    	$context=$_POST[context];
        if ($context == 8) {
            $messageid=$_POST[faxid];
        } else {
    	    $messageid=$_POST[messageid];
        }
    	$messageid2=$_POST[messageid2];
    	$messageid3=$_POST[messageid3];
    	$modein=$_POST[mode];
    	if ($modein == "mode_queue"){
        	$mode = 1;
    	} else {
        	$mode = 0;
    	}
    	$astqueuename=$_POST[astqueuename];
    	$maxagents=$_POST[agents];
    	$did=$_POST[did];
    	$clid=$_POST[clid];
    	$trclid=$_POST[trclid];


	$sql = "UPDATE campaign SET"./* groupid='$campaigngroupid',*/" name='$name', description='$description',messageid='$messageid',messageid2='$messageid2',messageid3='$messageid3',mode='$mode',astqueuename='$astqueuename',did='$did',maxagents='$maxagents',clid='$clid',trclid='$trclid',context='$context' WHERE id='$_POST[id]'";
//    echo $sql;
    $result=mysql_query($sql, $link) or die (mysql_error());;
    include("campaigns.php");
    exit;
}else{
	$id = ($_GET[id]);
	$sql = 'SELECT * FROM campaign WHERE id=\''.$id.'\' limit 1';
	$result=mysql_query($sql,$link) or die(mysql_error());
	$row = mysql_fetch_assoc($result);
	$row = array_map(stripslashes,$row);
}
require "header.php";
require "header_campaign.php";

?>

<FORM ACTION="editcampaign.php" METHOD="POST" id="addcampaign">
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2">
<?
?>
<TR title="A short name to give to the campaign"><TD CLASS="thead">Campaign Name
<a href="#" onclick="displaySmallMessage('includes/help.php?section=A short name you would like to give to the campaign - preferrably one word');return false"><img src="/images/help.png" border="0" onload="whatPaySelected(<?echo $row[context];?>)"></a>
</TD><TD>
<INPUT TYPE="HIDDEN" NAME="id" VALUE="<?echo $row[id];?>">
<INPUT TYPE="TEXT" NAME="name" VALUE="<?echo $row[name];?>" size="60">
</TD>
</TR><TR title="A short description of the campagin"><TD CLASS="thead">Campaign Description
<a href="#" onclick="displaySmallMessage('includes/help.php?section=A short description of the campaign in case you are not able to tell from the Campaign Name');return false"><img src="/images/help.png" border="0"></a>
</TD><TD>
<INPUT TYPE="TEXT" NAME="description" VALUE="<?echo $row[description];?>" size="60">
</TD>
</TR>
		<tr id="mode">
			<td class="thead" width=200>Mode
			            <a href="#" onclick="displaySmallMessage('includes/help.php?section=What type of campaign you would like to run. <br /><br />If you are connected to the machine doing the calling then chose Queue Mode.  If you would like to receive any connected calls at a particular phone number, chose DID Mode.  Normally you will use DID Mode unless you have been told to use Queue Mode.');return false"><img src="/images/help.png" border="0"></a>
			</td>
            <td width=*>
			<input type="radio" name="mode" value="didmode" rel="didmode" id="mode_did" <? echo ($row[mode]==0?"CHECKED":"");?> />
			<label for="mode_did" title="Used when you receive calls at a particular number">DID Mode</label>
			<input type="radio" name="mode" value="mode_queue" rel="queue" id="mode_queue"  <? echo ($row[mode]==1?"CHECKED":"");?> />
			<label for="mode_queue" title="used when you are connected to the machine doing the dialing">Queue Mode</label></td>
		</tr>
        <TR><TD CLASS="thead">Type of Campaign
        <a href="#" onclick="displayLargeMessage('includes/help.php?section=<b>Load Simulation</b><br />Simple test campaign.  Does not actually make any phone calls<br /><br /><b>Answer Machine Only</b><br />Human: Hang Up. Answer Machine: Leave Message<br /><br /><b>Immediate Live Only</b><br />Human: Connect immediately to the call center. Answer Machine: hang up.<br /><br /><b>Press 1 Live Only</b><br />Human: Play the person message and then if they press 1, transfer to the call center.  Answer Machine: Hang Up.<br /><br /><b>Immediate Live and Answer Machine</b><br />Human: Connect immediately to the call center. Answer Machine: Leave the answer machine message.<br /><br /><b>Press 1 Live and Answer Machine</b><br />Human: Play the person message and then if they press 1, transfer to the call center.  Answer Machine: Leave the answer machine message.<br /><br /><b>Direct Transfer</b><br />Transfer the call without checking to see if it is a machine or a human.');return false"><img src="/images/help.png" border="0" onload="whatPaySelected('<?echo $row[context];?>')"></a>
        </TD><TD>
        <?
//        print_r($row);
        ?>
<SELECT NAME="context" id="context" onchange="whatPaySelected(this.value)">



<OPTION VALUE="-1" SELECTED>Please chose a type of campaign...</OPTION>
<OPTION VALUE="0" <?echo $row[context]==0?"SELECTED":""?> title="No numbers are dialed">Load Simulation</OPTION>
<OPTION VALUE="1" <?echo $row[context]==1?"SELECTED":""?> title="Only leave a message if an answer machine is detected, hangup otherwise">Answer Machine Only</OPTION>
<OPTION VALUE="2" <?echo $row[context]==2?"SELECTED":""?> title="Connect a person directly to the call center, don't bother with answer machines">Immediate Live</OPTION>
<OPTION VALUE="4" <?echo $row[context]==4?"SELECTED":""?> title="Play a message to a person, and if they press 1 transfer them to the call center, don't bother with answer machines">Press 1 Live Only</OPTION>
<OPTION VALUE="5" <?echo $row[context]==5?"SELECTED":""?> title="Connect a person directly to the call center, and leave a message on the answer machine">Immediate Live and Answer Machine</OPTION>
<OPTION VALUE="3" <?echo $row[context]==3?"SELECTED":""?> title="Play a message to a person, if they press 1 they go to the call center, leave a message on the answer machine">Press 1 Live and Answer Machine</OPTION>
<OPTION VALUE="6" <?echo $row[context]==6?"SELECTED":""?> title="As soon as a number is connected, transfer it to a staff memeber"> Direct Transfer</OPTION>
<OPTION VALUE="7" <?echo $row[context]==7?"SELECTED":""?> title="When a call is answered, play back the message and then hang up"> Immediate Message Playback</OPTION>
<OPTION VALUE="8" <?echo $row[context]==8?"SELECTED":""?> title="Ring a number, when it answers start sending a fax" >Fax Broadcast</OPTION>
<OPTION VALUE="9" <?echo $row[context]==9?"SELECTED":""?> title="Coming Soon">SMS Broadcast (coming soon)</OPTION>
<OPTION VALUE="10" <?echo $row[context]==10?"SELECTED":""?>><?echo $config_values['SPARE1'];?></OPTION>
<OPTION VALUE="11" <?echo $row[context]==11?"SELECTED":""?>><?echo $config_values['SPARE2'];?></OPTION>
<OPTION VALUE="12" <?echo $row[context]==12?"SELECTED":""?>><?echo $config_values['SPARE3'];?></OPTION>
<OPTION VALUE="13" <?echo $row[context]==13?"SELECTED":""?>><?echo $config_values['SPARE4'];?></OPTION>
<OPTION VALUE="14" <?echo $row[context]==14?"SELECTED":""?>><?echo $config_values['SPARE5'];?></OPTION>
<?/*<OPTION VALUE="5" <?if ($row[context]==5){echo "SELECTED";}?>>Spare 2</OPTION>
<OPTION VALUE="6" <?if ($row[context]==6){echo "SELECTED";}?>>Spare 3</OPTION>
<OPTION VALUE="7" <?if ($row[context]==7){echo "SELECTED";}?>>Spare 4</OPTION>
<OPTION VALUE="8" <?if ($row[context]==8){echo "SELECTED";}?>>Spare 5</OPTION>
<OPTION VALUE="9" <?if ($row[context]==9){echo "SELECTED";}?>>Spare 6</OPTION>
<OPTION VALUE="10" <?if ($row[context]==10){echo "SELECTED";}?>>Spare 6</OPTION>*/?>
</SELECT>
</TD>
</TR>
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
    //echo "<OPTION VALUE=\"".$row[id]."\">".$row[name]."</OPTION>";
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
echo $row[messageid];
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




        <tr rel="queue" title="The name of the queue">
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
		<tr rel="didmode" id="xx6" title="The max number of concurrent calls to be connected to the call center">
			<td class="thead" width="216px"><label for="agents">Maximum Connected Calls:
            <a href="#" onclick="displaySmallMessage('includes/help.php?section=This is the number of concurrent calls you would like to receive on the call center number specified.  <br /><br />Normally this will be the number of staff you have.');return false"><img src="/images/help.png" border="0"></a>
            </label></td>
			<td width=*><input type="text" name="agents" id="agents" size="28" value="<?echo ($row[maxagents])?>"></td>
		</tr>
        		<tr id="xx5" title="The caller ID you'd like to use">
			<td class="thead"><label for="did">Caller ID:
			<a href="#" onclick="displaySmallMessage('includes/help.php?section=The CallerID you would like to send on calls to your customers');return false"><img src="/images/help.png" border="0"></a>
			</label></td>
			<td><input type="text" name="clid" id="did" size=28 value="<?echo ($row[clid]);?>"></td>
		</tr>
        <tr rel="didmode" id="xx1" title="The number to have connected calls sent to">
			<td class="thead"><label for="did">Call Center Phone Number:
			<a href="#" onclick="displaySmallMessage('includes/help.php?section=The phone number you would like to have connected calls sent to. Eg: (123) 555-1234. ');return false"><img src="/images/help.png" border="0"></a>
			</label></td>
			<td><input type="text" name="did" id="did" size=28 value="<?echo ($row[did])?>"></td>
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

        </td></tr>*/?>
		<tr>




</TR><TR><TD COLSPAN=2 ALIGN="RIGHT">
<INPUT TYPE="SUBMIT" VALUE="Save Campaign">
</TD>
</TR>
<?
?>

</TABLE>
</FORM>
<?
require "footer.php";
?>
