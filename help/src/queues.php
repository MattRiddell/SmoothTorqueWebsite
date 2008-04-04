<p>This page controls the addition of Real-Time Asterisk Queues. To add a queue follow the directions of the add queue wizard.</p>
<p>If you wish to edit the queue settings, then you will have to be aware that there are several different types of parameters for the queue. We'll start off with the Basic Queue options</p>
<h3>Basic Queue Options</h3>
<p>These options control the name of the queue, the strategy of the queue and the timeout value. The name of the queue can be anything that you desire. The timeout value determines how long to call an agent before ginving up. The various ringing strategies are outlined in the table below.</p>

<table>
	<tr>
		<th>Strategy</th>
		<th>Description</th>
	</tr>
	<tr>
		<td>Ring all</td>
		<td>This simply rings all the available agents until one of them picks up.</td>
	</tr>
	<tr>
		<td>Round Robin</td>
		<td>This rings the agents in turn until someone picks up. It will always start at the first agent.</td>
	</tr>
	<tr>
		<td>Lease Recent</td>
		<td>Selects the least recently used agent first.</td>
	</tr>
	<tr>
		<td>Fewest Calls</td>
		<td>Rings the agent with the fewest number of calls.</td>
	</tr>
	<tr>
		<td>Random</td>
		<td>Randomly selects an agent to call from the avaliable agents.</td>
	</tr>
	<tr>
		<td>Round Robin with Memory</td>
		<td>Uses round robin as describe above, but starts from the agent where it was stopped before.</td>
	</tr>
</table>
