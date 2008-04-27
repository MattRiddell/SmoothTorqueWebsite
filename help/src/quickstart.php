<h3>Introduction</h3>
<p align="left">The aim of this part is to provide a quick introduction for <?echo $config_values['TITLE'];?>. This quick start will show you how to create campaigns. For this we're going to create a simple Load Simulation campaign.</p>

<h3>Create New Campaign</h3>
<p align="left">Click on the <?echo $config_values['MENU_CAMPAIGNS'];?> tab. When you first login there are no campaigns created for you. We're going to create one now. Click on the Add Campaign button. The next screen has a form that needs to be filled out. Follow the example in the image below.</p>

<img src="images/add_campaign.png" alt="Add campaign">
<h3>Types of Campaign</h3>
<p align="left">In <?echo $config_values['TITLE'];?> there are seven types of campaign (with
more planned). Their properties are outlined in the table below.</p>

<table border="0">
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
		<td  bgcolor="#eeeeee">Plays a message, if the person presses 1
		they will be transferred to the agents.</td>
		<td  bgcolor="#eeeeee">Plays a message</td>
		<td  bgcolor="#eeeeee">&nbsp;</td>
	</tr>
</table>

<p align="left">Once the values are filled in, then click on the Add
Campaign button. You'll then be presented with the screen below.</p>

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



<h3>Add Numbers</h3>
<p align="left">The next step is to add numbers to this campaign. To do this click on the Numbers tab on the right of the Campaign tab. You will then be presented with several options.</p>

<img src="images/add_numbers.png" alt="Options for adding numbers to a campaign">
<p align="left">There are multiple uptions for putting numbers in to a campaign. For now, we're simply going to generate numbers automatically. Click on the link at the bottom of the list. You'll then be asked which campaign you want to add them to. At the moment, we've only got one campaign, so add them to the Load Test campaign.</p>

<p align="left">The next step is to determine the format of the numbers that you wish to dial. For example, if we were to generate all the numbers for Dunedin, New Zealand (and we're dialing internationally), then we'd set the start number to 006434600000 and the end number to 006434799999. Now, some of these numbers exist, so be careful not to use them in a real campaign unless you <i>really</i> want to dial the whole of Dunedin.</p>

<img src="images/generate_range.png" alt="Range for numbers to automatically generate">

<h3>Do Not Call (DNC) Numbers</h3>
<p align="left">This is a list of numbers that will <em>never</em>
be called. This list is global for all campaigns on the system, unlike
the numbers for a particular campaign. For now, we are only going to add
three numbers to this list. So, click on the Add DNC Numbers Manually
button. You will then be presented with a dialog like the one below.</p>

<img src="images/dnc_numbers.png" alt="Adding 3 numbers to the DNC list">

<h3>Messages</h3>
<p align="left">In a normal campaign you would upload the message to be played to people who pickup the phone (or to answer machines, or to fax machines). The file has to be wav format and can be recorded in any recording software. For this campaign we don't need to bother setting a message as it's a load simulation only. For every other type of campaign, you <em>must</em> have a message.

<h3>Starting the Campaign</h3>
<p align="left">We are now ready to start the campaign. Click on the <?echo $config_values['MENU_CAMPAIGNS'];?> tab, this gets you back to the list of campaigns. Press the 'play' button to start the campaign. You should breifly see a message telling you that the campaign is starting, when that message goes away you can see the status of the campaign. If you click on the line graph button, you can see the status of the engine.</p>

<img src="images/running.png" alt="The running campaign">

<p align="left">If you click on the View Number Status button from the above window you'll get information about the status of the numbers dialed. This information is also available from view campaigns page by clicking on the pie graph icon.</p>

<img src="images/pie.png" alt="Number status for a running campaign">
