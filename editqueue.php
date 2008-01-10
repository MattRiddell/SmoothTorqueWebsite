<?php
require "header.php";
require "header_queue.php";


/* this function builds a drop down box for the strategy
   The existing strategy is passed in as an parameter
   The name for the resulting select is also passed in.
*/
function make_strategy_selector($strategy, $name){
	$result = "<select name=\"".$name."\">\n
		<option value=\"ringall\"".($strategy=="ringall"?" selected":"").">Ring all</option>\n
		<option value=\"roundrobin\"".($strategy=="roundrobin"?" selected":"").">Round Robin</option>\n
		<option value=\"leastrecent\"".($strategy=="leastrecent"?" selected":"").">Least Recent</option>\n
		<option value=\"fewestcalls\"".($strategy=="fewestcalls"?" selected":"").">Fewest Calls</option>\n
		<option value=\"random\"".($strategy=="random"?" selected":"").">Random</option>\n
		<option value=\"rrmemory\"".($strategy=="rrmemory"?" selected":"").">Round Robin with Memory</option>\n
		</select>"; 
	return $result;
}


$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';

$result = mysql_query($sql,$link) or die(mysql_error());
$campaigngroupid = mysql_result($result,0,'campaigngroupid');

$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);
$name = $_GET[name];

$sql = 'SELECT * FROM queue_table WHERE name=\''.$name.'\' limit 1';
$result = mysql_query($sql,$link) or die(mysql_error());
$row = mysql_fetch_assoc($result);
?>
<p>Basic Configuration Options</p>
<table class="" align="center" border="0" cellpadding="2" cellspacing="0">
<tr class="tborder2"><td class="thead">Name</td><td><?echo $row[name];?></td></tr>
<tr class="tborderx"><td class="thead">Strategy</td><td><?echo make_strategy_selector($row[strategy],"strategy")?></td></tr>
<tr class="tborder2"><td class="thead">Timeout</td><td><?echo $row[timeout]?></td></tr>
</table>

<p>Intermediate Configuration Options</p>
<table class="" align="center" border="0" cellpadding="2" cellspacing="0">
<tr class="tborder2"><td class="thead">Music on Hold</td><td><?echo $row[musiconhold];?></td><td>default</td></tr>
<tr class="tborderx"><td class="thead">Announce</td><td><?echo $row[announce];?></td><td>default</td></tr>
<tr class="tborder2"><td class="thead">Context</td><td><?echo $row[context];?></td><td>default</td></tr>
<tr class="tborder2"><td class="thead">Retry</td><td><?echo $row[retry];?></td><td>default</td></tr>
<tr class="tborderx"><td class="thead">Service Level</td><td><?echo $row[servicelevel];?></td><td>default</td></tr>
<tr class="tborder2"><td class="thead">Announce Frequency</td><td><?echo $row[announce_frequency];?></td><td>default</tr>
<tr class="tborderx"><td class="thead">Announce Holdtime</td><td><?echo $row[announce_holdtime];?></td><td>default</tr>
<tr class="tborder2"><td class="thead">Max Queue length</td><td><?echo $row[maxlen];?></td><td>default</tr>
</table>

<p>Advanced Configuration Options</p>
<p>Sound Files</p>
<table class="" align="center" border="0" cellpadding="2" cellspacing"0">
<tr class="tborderx"><td class="thead">You are next</td><td><?echo $row[queue_youarenext];?></td><td>default</td></tr>
<tr class="tborder2"><td class="thead">There are...</td><td><?echo $row[queue_thereare];?></td><td>default</td></tr>
<tr class="tborderx"><td class="thead">Calls waiting</td><td><?echo $row[queue_callswaiting];?></td><td>default</td></tr>
<tr class="tborder2"><td class="thead">Hold time</td><td><?echo $row[queue_holdtime];?></td><td>default</td></tr>
<tr class="tborderx"><td class="thead">Minutes</td><td><echo $row[queue_minutes];?></td><td>default</td></tr>
<tr class="tborder2"><td class="thead">Seconds</td><td><echo $row[queue_seconds];?></td><td>default</td></tr>
<tr class="tborderx"><td class="thead">Less than...</td><td><?echo $row[queue_lessthan];?></td><td>default</td></tr>
<tr class="tborder2"><td class="thead">Thank you</td><td><?echo $row[queue_thankyou];?></td><td>default</td></tr>
</table>
<p>Recording Options</p>
<table class="" align="center" border="0" cellpadding="2" cellspacing"0">
<tr class="tborderx"><td class="thead">Monitor Join</td><td><?echo "hello world";?></td><td>default</td></tr>
<tr class="tborder2"><td class="thead">Monitor Format</td><td><?echo "hello world";?></td><td>default</tr>
<tr class="tborderx"><td class="thead">Event Member Status</td><td><?echo "hello world";?></td><td>default</td></tr>
<tr class="tborder2"><td class="thead">Event When Called</td><td><?echo "hello world";?></td><td>default</tr>
</table>
<p>Queue Performance</p>
<table class="" align="center" border="0" cellpadding="2" cellspacing"0">
<tr class="tborderx"><td class="thead">Join when Empty</td><td><?echo "hello world";?></td><td>default</td></tr>
<tr class="tborder2"><td class="thead">Leave When Empty</td><td><?echo "hello world";?></td><td>default</tr>
<tr class="tborderx"><td class="thead">Report Hold Time</td><td><?echo "hello world";?></td><td>default</td></tr>
<tr class="tborder2"><td class="thead">Weight</td><td><?echo "hello world";?></td><td>default</tr>
<tr class="tborderx"><td class="thead">Report hold time</td><td><?echo "hello world";?></td><td>default</td></tr>
<tr class="tborder2"><td class="thead">Member Delay</td><td><?echo "hello world";?></td><td>default</tr>
</table>

<?php
require "footer.php";
?>
