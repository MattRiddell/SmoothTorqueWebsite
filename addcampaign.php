<?
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);

$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');
if (isset($_POST[name])){
    $id=$_POST[id];
    $name=$_POST[name];
    $description=$_POST[description];
    $messageid=$_POST[messageid];
    $messageid2=$_POST[messageid2];
    $messageid3=$_POST[messageid3];
    $sql="INSERT INTO campaign (groupid,name,description,messageid,messageid2,messageid3) VALUES ('$campaigngroupid','$name', '$description', '$messageid','$messageid2','$messageid3')";
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
<TR><TD CLASS="thead">Campaign Name</TD><TD>
<INPUT TYPE="HIDDEN" NAME="id" VALUE="<?echo $_GET[id];?>">
<INPUT TYPE="TEXT" NAME="name" VALUE="<?echo $row[name];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">Campaign Description</TD><TD>
<INPUT TYPE="TEXT" NAME="description" VALUE="<?echo $row[description];?>" size="60">
</TD>
</TR>
<?
$sql="SELECT * from campaignmessage";
$result=mysql_query($sql,$link) or die (mysql_error());
$count=0;
while ($row2[$count] = mysql_fetch_assoc($result)) {
    $count++;
    //echo "<OPTION VALUE=\"".$row[id]."\">".$row[name]."</OPTION>";
}
?>

<TR><TD CLASS="thead">Live Message</TD><TD>
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
</TR><TR><TD CLASS="thead">Answer Machine Message</TD><TD>
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
</TR><TR><TD CLASS="thead">Transfer Message</TD><TD>
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
			<td class="thead" width=200><label for="agents">Maximum Connected Calls:</label></td>
			<td width=*><input type="text" name="agents" id="agents" size="28" value="30"></td>
		</tr>
        <tr  class=tborder2>
        <td colspan="2">
        This is the number of concurrent calls you would like to receive
        at the number below.
        </td></tr>
				<tr>
			<td class="thead"><label for="did">Caller ID:</label></td>
			<td><input type="text" name="clid" id="did" size=28 value="ls3"></td>
		</tr>
		<tr class=tborder2>
			<td colspan=2>The CallerID you would like to send.</td>
		</tr>
        <tr>
			<td class="thead"><label for="did">Call Center Phone Number:</label></td>
			<td><input type="text" name="did" id="did" size=28 value="ls3"></td>
		</tr>
        <tr class=tborder2>
        <td colspan="2">
        The phone number you would like to have connected calls sent to. Eg: (123) 555-1234.
        </td></tr>
        <TR><TD CLASS="thead">Type of Campaign</TD><TD>
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
        <tr class=tborder2>
        <td colspan="2">
<b>Load Simulation</b><br />
Simple test campaign.  Does not actually make any phone calls<br />
<b>Answer Machine Only</b><br />
Human: Hang Up Answer Machine: Leave Message<br />
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

        </td></tr>
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
