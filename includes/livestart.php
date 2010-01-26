<form name="myForm" action="startcampaign.php" method="post">
    <input type="hidden" value="<?echo $_GET[id];?>" name="id">
	<table border="0" background="images/bg.gif" width=100% height=100%>
		<tr>
			<td colspan="2">
			<b>Start running a SmoothTorque Predictive Dialing Campaign</b>
            <br /><br />
			</td>
		</tr>

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
			<td></td>
			<td><input type="button" value="Cancel" onclick="closeMessage()">
			<input type="submit" value="Start Campaign" onclick="closeMessage()">
			</td>
		</tr>
	</table>
	<script type="text/javascript">
	document.myForm.agents.focus();
	</script>
</form>
