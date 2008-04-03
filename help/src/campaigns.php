<h3>Types of Campaign</h3>
<p align="left">In SmoothTorque there are seven types of campaign (with more planned). Their properties are outlined in the table below.</p>

<table border="1">
	<tr>
		<th>Type</th>
		<th>Human</th>
		<th>Answer Machine</th>
		<th>Comments</th>
	</tr>
	<tr>
		<td>Load Simulation</td>
		<td>Nothing.</td>
		<td>Nothing.</td>
		<td>Designed as a test of the system.</td>
	</tr>
	<tr>
		<td>Answer Machine Only</td>
		<td>Hangup.</td>
		<td>Plays a message.</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>Immediate Live</td>
		<td>Immediately connects to agents.</td>
		<td>Hang up</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>Press 1 Live Only</td>
		<td>Play a message, if the person presses 1 they will be transferred to the agents.</td>
		<td>Hang up</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>Immediate Live and Answer Machine</td>
		<td>Immediately connects to agents.</td>
		<td>Plays a message</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>Press 1 Live and Answer Machine</td>
		<td>Plays a message, if the person presses 1 they will be transferred to the agents.</td>
		<td>Plays a message</td>
		<td>&nbsp;</td>
	</tr>
</table>


<p align="left">Selecting the different types of campaign will give you different options. Where there is a message that needs to be played, a drop down box listing the available messages in the system will be presented to you. Simply select the relevent message.</p>

<p align="left">There are two types of mode to run the campaign in. The first is DID mode, the second Queue mode. Normally you would uses DID mode almost exclusively, it is uses to send all connected calls to a particular number. If you select queue mode, then you're sending connected calls to the call center queue.</p>

<p align="left">There are slightly different options for the different types of campaign. For a DID Mode campaign, you have the option of sending out Caller ID information, as well as specifying the number of simultaneous calls that the call center can handel.</p>

<p align="left">The Queue Mode specific option is to specify the name of the queue. Note: The queue must exist in Asterisk before you can use this feature, once the queue has been added in Asterisk, you must also tell SmoothTorque about the queue.</p>
