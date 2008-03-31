<h3>Introduction</h3>
<p>The aim of this part is to provide a quick introduction for SmoothTorque. This quick start will show you how to create campaigns. For this we're going to create a simple Load Simulation campaign.</p>

<h3>Create New Campaign</h3>
<p>Click on the Campaigns tab. When you first login there are no campaigns created for you. We're going to create one now. Click on the Add Campaign button. The next screen has a form that needs to be filled out. Follow the example in the image below.</p>

<img src="images/add_campaign.png" alt="Add campaign">

<p>Once the values are filled in, then click on the Add Campaign button. You'll then be presented with the screen below.</p>

<img src="images/campaign_status.png" alt="The status of a campaign">

<p>What each of the different icons do will be explained later on.</p>

<h3>Add Numbers</h3>
<p>The next step is to add numbers to this campaign. To do this click on the Numbers tab on the right of the Campaign tab. You will then be presented with several options.</p>

<img src="images/add_numbers.png" alt="Options for adding numbers to a campaign">
<p>There are multiple uptions for putting numbers in to a campaign. For now, we're simply going to generate numbers automatically. Click on the link at the bottom of the list. You'll then be asked which campaign you want to add them to. At the moment, we've only got one campaign, so add them to the Load Test campaign.</p>

<p>The next step is to determine the format of the numbers that you wish to dial. For example, if we were to generate all the numbers for Dunedin, New Zealand (and we're dialing internationally), then we'd set the start number to 006434600000 and the end number to 006434799999. Now, some of these numbers exist, so be careful not to use them in a real campaign unless you <i>really</i> want to dial the whole of Dunedin.</p>

<img src="images/generate_range.png" alt="Range for numbers to automatically generate">

<h3>Do Not Call (DNC) Numbers</h3>
<p>This is a list of numbers that will <em>never</em> be called. This list is global for all campaigns on the system, unlike the numbers for a particular campaign. For now, we are only going to add three numbers to this list. So, click on the Add DNC Numbers Manually button. You will then be presented with a dialog like the one below.

<img src="images/dnc_numbers.png" alt="Adding 3 numbers to the DNC list"> 

<h3>Messages</h3>
<p>In a normal campaign you would upload the message to be played to people who pickup the phone (or to answer machines, or to fax machines). The file has to be wav format and can be recorded in any recording software. For this campaign we don't need to bother setting a message as it's a load simulation only. For every other type of campaign, you <em>must</em> have a message.

<h3>Starting the Campaign</h3>
<p>We are now ready to start the campaign. Click on the Campaigns tab, this gets you back to the list of campaigns. Press the 'play' button to start the campaign. You should breifly see a message telling you that the campaign is starting, when that message goes away you can see the status of the campaign. If you click on the line graph button, you can see the status of the engine.</p>

<img src="images/running.png" alt="The running campaign">

<p>If you click on the View Number Status button from the above window you'll get information about the status of the numbers dialed. This information is also available from view campaigns page by clicking on the pie graph icon.</p>

<img src="images/pie.png" alt="Number status for a running campaign">

