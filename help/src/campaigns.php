<h3>Create New Campaign</h3>
<p align="left">Click on the Campaigns tab. When you first login there are no campaigns created for you. We're going to create one now. Click on the Add Campaign button. The next screen has a form that needs to be filled out. Follow the example in the image below.</p>

<img src="images/add_campaign.png" alt="Add campaign">
<h3>Types of Campaign</h3>
<p align="left">In SmoothTorque there are seven types of campaign (with more planned). Their properties are outlined in the table below.</p>

<table align="center">
	<tr>
		<th bgcolor="#000044"><font color="#ffffff"><b>Type</b></font></th>
		<th bgcolor="#000044"><font color="#ffffff"><b>Human</b></font></th>
		<th bgcolor="#000044"><font color="#ffffff"><b>Answer Machine</b></font></th>
		<th bgcolor="#000044"><font color="#ffffff"><b>Comments</b></font></th>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">Load Simulation</td>
		<td  bgcolor="#eeeeee">Nothing.</td>
		<td  bgcolor="#eeeeee">Nothing.</td>
		<td  bgcolor="#eeeeee">Designed as a test of the system.</td>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">Answer Machine Only</td>
		<td  bgcolor="#eeeeee">Hangup.</td>
		<td  bgcolor="#eeeeee">Plays a message.</td>
		<td  bgcolor="#eeeeee">&nbsp;</td>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">Immediate Live</td>
		<td  bgcolor="#eeeeee">Immediately connects to agents.</td>
		<td  bgcolor="#eeeeee">Hang up</td>
		<td  bgcolor="#eeeeee">&nbsp;</td>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">Press 1 Live Only</td>
		<td  bgcolor="#eeeeee">Play a message, if the person presses 1 they will be transferred to the agents.</td>
		<td  bgcolor="#eeeeee">Hang up</td>
		<td  bgcolor="#eeeeee">&nbsp;</td>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">Immediate Live and Answer Machine</td>
		<td  bgcolor="#eeeeee">Immediately connects to agents.</td>
		<td  bgcolor="#eeeeee">Plays a message</td>
		<td  bgcolor="#eeeeee">&nbsp;</td>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">Press 1 Live and Answer Machine</td>
		<td  bgcolor="#eeeeee">Plays a message, if the person presses 1 they will be transferred to the agents.</td>
		<td  bgcolor="#eeeeee">Plays a message</td>
		<td  bgcolor="#eeeeee">&nbsp;</td>
	</tr>
</table>

<p align="left">Once the values are filled in, then click on the Add Campaign button. You'll then be presented with the screen below.</p>

<img src="images/campaign_status.png" alt="The status of a campaign">

<p align="left">The following chart explains what each icon means:</p>

<table align="center">
	<tr>
		<th bgcolor="#000044"><font color="#ffffff"><b>Icon</b></font></th>
		<th bgcolor="#000044"><font color="#ffffff"><b>Description</b></font></th>
	</tr>
   	<tr>
		<td  bgcolor="#eeeeee"><img src="/images/pencil.png" align="left"></td>
		<td  bgcolor="#eeeeee">Edit the information for this campaign</td>
	</tr>
   	<tr>
		<td  bgcolor="#eeeeee"><img src="/images/control_play_blue.png" align="left"></td>
		<td  bgcolor="#eeeeee">Start this campaign</td>
	</tr>
   	<tr>
		<td  bgcolor="#eeeeee"><img src="/images/control_stop_blue.png" align="left"></td>
		<td  bgcolor="#eeeeee">Stop this campaign</td>
	</tr>
   	<tr>
		<td  bgcolor="#eeeeee"><img src="/images/chart_curve.png" align="left"></td>
		<td  bgcolor="#eeeeee">Show the engine status (while running)</td>
	</tr>
       	<tr>
		<td  bgcolor="#eeeeee"><img src="/images/chart_pie.png" align="left"></td>
		<td  bgcolor="#eeeeee">Show the status of the dialed numbers</td>
	</tr>
       	<tr>
		<td  bgcolor="#eeeeee"><img src="/images/arrow_refresh.png" align="left"></td>
		<td  bgcolor="#eeeeee">Recycles numbers (i.e. allows you to reset their status back to new)</td>
	</tr>
       	<tr>
		<td  bgcolor="#eeeeee"><img src="/images/delete.png" align="left"></td>
		<td  bgcolor="#eeeeee">Delete this campaign</td>
	</tr>


</table>



<p align="left">Selecting the different types of campaign will give you different options. Where there is a message that needs to be played, a drop down box listing the available messages in the system will be presented to you. Simply select the relevent message.</p>

<p align="left">There are two types of mode to run the campaign in. The first is DID mode, the second Queue mode. Normally you would uses DID mode almost exclusively, it is uses to send all connected calls to a particular number. If you select queue mode, then you're sending connected calls to the call center queue.</p>

<p align="left">There are slightly different options for the different types of campaign. For a DID Mode campaign, you have the option of sending out Caller ID information, as well as specifying the number of simultaneous calls that the call center can handel.</p>

<p align="left">The Queue Mode specific option is to specify the name of the queue. Note: The queue must exist in Asterisk (as either a RealTime Queue --- as set up by SmoothTorque, or as a static queue in queue.conf) before you can use this feature.</p>
