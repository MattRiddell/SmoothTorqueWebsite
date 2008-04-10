<p>This page controls the addition of Real-Time Asterisk Queues. To add a queue follow the directions of the add queue wizard.</p>
<p>If you wish to edit the queue settings, then you will have to be aware that there are several different types of parameters for the queue. We'll start off with the Basic Queue options</p>
<h3>Basic Queue Options</h3>
<p>These options control the name of the queue, the strategy of the queue and the timeout value. The name of the queue can be anything that you desire. The timeout value determines how long to call an agent before ginving up. The various ringing strategies are outlined in the table below.</p>

<table>
	<tr>
		<th bgcolor="#000044"><font color="#ffffff">Strategy</font></th>
		<th bgcolor="#000044"><font color="#ffffff">Description</font></th>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">Ring all</td>
		<td  bgcolor="#eeeeee">This simply rings all the available agents until one of them picks up.</td>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">Round Robin</td>
		<td  bgcolor="#eeeeee">This rings the agents in turn until someone picks up. It will always start at the first agent.</td>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">Lease Recent</td>
		<td  bgcolor="#eeeeee">Selects the least recently used agent first.</td>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">Fewest Calls</td>
		<td  bgcolor="#eeeeee">Rings the agent with the fewest number of calls.</td>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">Random</td>
		<td  bgcolor="#eeeeee">Randomly selects an agent to call from the avaliable agents.</td>
	</tr>
	<tr>
		<td  bgcolor="#eeeeee">Round Robin with Memory</td>
		<td  bgcolor="#eeeeee">Uses round robin as describe above, but starts from the agent where it was stopped before.</td>
	</tr>
</table>
