<p align="left">Here is where you specify the different trunks that calls can go out on. This also requires that Asterisk be correctly configured.</p>

<p align="left">To add a trunk, click on the Add Trunk item. You will then have 4 fields to fill out. The first is a trunk name (this is a name that SmoothTorque can use), the next is the Dial String (examples of which are given below), the next options specify the maximum number of calls per second and the maximum number of channels this trunk can support.</p>

<p align="left">The dial strings used here are sent directly to asterisk, so should be in a format that Asterisk understands. Some example dial strings are in the table below.</p>
<table>
	<tr>
		<th bgcolor="#000044"><font color="#ffffff">Dial String</font></th>
		<th bgcolor="#000044"><font color="#ffffff">Description</font></th>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">Local/s@staff/${EXTEN}</td>
		<td  bgcolor="#eeeeee">This sends the call to the Load Test extension, this is already setup as part of the installation</td>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">SIP/${EXTEN}@some_provider</td>
		<td  bgcolor="#eeeeee">This sends the call via SIP to some provider</td>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">IAX2/server/${EXTEN}</td>
		<td  bgcolor="#eeeeee">This sends the call to server via IAX, this is another of your VoIP Servers</td>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">Zap/g1/${EXTEN}</td>
		<td  bgcolor="#eeeeee">This sends the call out via the hardware on the machine</td>
	</tr>
</table>
