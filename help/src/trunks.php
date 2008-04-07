<p>Here is where you specify the different trunks that calls can go out on. This also requires that Asterisk be correctly configured.</p>

<p>To add a trunk, click on the Add Trunk item. You will then have 4 fields to fill out. The first is a trunk name (this is a name that SmoothTorque can use), the next is the Dial String (examples of which are given below), the next options specify the maximum number of calls per second and the maximum number of channels this trunk can support.</p>

<p>The dial strings used here are sent directly to asterisk, so should be in a format that Asterisk understands. Some example dial strings are in the table below.</p>
<table>
	<tr>
		<th>Dial String</th>
		<th>Description</th>
	</tr>
	<tr>
		<td>Local/s@staff/${EXTEN}</td>
		<td>This sends the call to the Load Test extension, this is already setup as part of the installation</td>
	</tr>
	<tr>
		<td>SIP/${EXTEN}@some_provider</td>
		<td>This sends the call via SIP to some provider</td>
	</tr>
	<tr>
		<td>IAX2/server/${EXTEN}</td>
		<td>This sends the call to server via IAX, this is another of your VoIP Servers</td>
	</tr>
	<tr>
		<td>Zap/g1/${EXTEN}</td>
		<td>This sends the call out via the hardware on the machine</td>
	</tr>
</table>
