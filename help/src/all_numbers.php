<h3>Number Lists</h3>
<p align="left">There are several different ways to get numbers in to a campaign. For each of these you will need to select which campaign you want to add the numbers to. The following table presents these different options.</p>

<ul>
	<li>System Lists</li>
	<li>Upload from a text file</li>
	<li>Add numbers manually</li>
	<li>Generate numbers automatically</li>
</ul>

<h4>System Lists</h4>
<p align="left">These lists are provided by an administrator and may be
along the lines of certain geographic regions, or similar. There may be a
cost associated with using these numbers.  If so you will be informed before
you use them.</p>

<h4>Upload from a text file</h4>
<p align="left">The text file that you use must contain numbers on seperate lines.</p>

<h4>Add numbers manually</h4>
<p align="left">Using this method you will be presented with a box where you put numbers in.</p>

<h4>Generating numbers automatically</h4>
<p align="left"><?echo $config_values['TITLE'];?> generates numbers for you. </p>
<p align="left">The first step is to determine the format of the numbers
that you wish to dial. For example, if we were to generate all the numbers
for Dunedin, New Zealand (and we're dialing internationally), then we'd
set the start number to 006434600000 and the end number to 006434799999.
Now, some of these numbers exist, so be careful not to use them in a real
campaign unless you <i>really</i> want to dial the whole of Dunedin.</p>

<img src="images/generate_range.png" alt="Range for numbers to automatically generate">


<h3>Other options</h3>
<p align="left">You can also view, search, and export number lists. When you view and export you can filter the numbers by their status. The meaning of the different status messages are outlined below.</p>

<table>
	<tr>
		<th bgcolor="#000044"><font color="#ffffff"><b>Message</b></font></th>
		<th bgcolor="#000044"><font color="#ffffff"><b>Meaning</b></font></th>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">Answered</td>
		<td  bgcolor="#eeeeee">The call was answered</td>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">Pressed 1</td>
		<td  bgcolor="#eeeeee">Those called who pressed 1</td>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">Busy</td>
		<td  bgcolor="#eeeeee">Numbers tried that were engaged</td>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">Failed</td>
		<td  bgcolor="#eeeeee">Numbers that failed for some reason, for example if the number doesn't exist</td>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">Answer Machine</td>
		<td  bgcolor="#eeeeee">Calls where an answer machine was detected</td>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">Congested</td>
		<td  bgcolor="#eeeeee">Calls that were tried but the provider was too congested to make the call</td>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">Unknown</td>
		<td  bgcolor="#eeeeee">Anything that doesn't fit in the above categories</td>
	</tr>
</table>

<h3>Do Not Call (DNC) Numbers</h3>
<p align="left">Here similar options for DNC Number exists. You can view and add numbers (via the same text file upload and manually entering mechanisms) as for normal numbers. When you view the numbers you will be able to delete the numbers. Note: The consequences of deleting numbers from the DNC is entirely up to you. This feature is only there to aid testing.</p>
