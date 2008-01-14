<?php
require "header.php";
require "header_queue.php";


$is_int = array( 
 "name" => 0,
 "musiconhold" => 0,
 "announce" =>0,
 "context"=>0,
 "timeout"=>1,
 "monitor_join"=>1,
 "monitor_format"=>0,
 "queue_youarenext"=>0,
 "queue_thereare"=>0,
 "queue_callswaiting"=>0,
 "queue_holdtime"=>0,
 "queue_minutes"=>0,
 "queue_seconds"=>0,
 "queue_lessthan"=>0,
 "queue_thankyou"=>0,
 "queue_reporthold"=>0,
 "announce_frequency"=>1,
 "announce_round_seconds"=>1,
 "announce_holdtime"=>0,
 "retry"=>1,
 "wrapuptime"=>1,
 "maxlen"=>1,
 "servicelevel"=>1,
 "strategy"=>0,
 "joinempty"=>0,
 "leavewhenempty"=>0,
 "eventmemberstatus"=>1,
 "eventwhencalled"=>1,
 "reportholdtime"=>1,
 "memberdelay"=>1,
 "weight"=>1,
 "timeoutrestart"=>1,
 "periodic_announce"=>0,
 "periodic_announce_frequency"=>1
);


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
?>

<script type="text/javascript">
<!--
function resetToDefault(name, default_value){
	var textbox = document.getElementsByName(name);
	textbox[0].value = default_value;
}

function toggle(name){
	var opened = "./images/open.png";
	var closed = "./images/closed.png";

	var element = document.getElementById(name);
	var img = document.getElementsByName("img_"+name);
	if(element.style.display == "none"){
		img[0].src = opened;
		element.style.display = "";
	}else{
		img[0].src = closed;
		element.style.display = "none";
	}
}
//-->
</script>

<?php
$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';

$result = mysql_query($sql,$link) or die(mysql_error());
$campaigngroupid = mysql_result($result,0,'campaigngroupid');

$_GET = array_map(mysql_real_escape_string,$_GET);
$_POST = array_map(mysql_real_escape_string,$_POST);
if(array_key_exists('_submit_check', $_POST)){
	//insert stuff in to the database
	$names = array_keys($_POST);
	$result = "";
	for($i = 1; $i <count($_POST);$i++){
		$result .= $names[$i]."=";
		if($_POST[$names[$i]] != ""){
			if($is_int[$names[$i]]){
				$result .= $_POST[$names[$i]].", ";
			}else{
				$result .= "'".$_POST[$names[$i]]."', ";
			}
		}else
			$result .= "NULL, ";
	}
	$result = substr($result,0,strlen($resut)-2);	
	$sql = "UPDATE queue_table SET ".$result." WHERE name='".$_GET[name]."'";
	$result = mysql_query($sql,$link) or die(mysql_error());
	exit;
}

$name = $_GET[name];

$sql = 'SELECT * FROM queue_table WHERE name=\''.$name.'\' limit 1';
$result = mysql_query($sql,$link) or die(mysql_error());
$row = mysql_fetch_assoc($result);
?>

<form method="POST" action="<?php echo $_SERVER['PHP_SELF']."?name=".$_GET[name]; ?>">
<input type="hidden" name="_submit_check" value="1">
<p>Basic Configuration Options</p>
<table class="" align="center" border="0" cellpadding="2" cellspacing="0">
<tr class="tborder2"><td class="thead">Name</td><td><input type="text" value="<?echo $row[name];?>" name="name"></td></tr>
<tr class="tborderx"><td class="thead">Strategy</td><td><?echo make_strategy_selector($row[strategy],"strategy")?></td></tr>
<tr class="tborder2"><td class="thead">Timeout</td><td><input type="text" value="<?echo $row[timeout]?>" name="timeout"></td></tr>
</table>

<div onClick="toggle('intermediate')"><p>Intermediate Configuration Options <img src="./images/closed.png" name="img_intermediate"></p></div>
<table class="" align="center" border="0" cellpadding="2" cellspacing="0" id="intermediate" style="display:none">
<tr class="tborder2"><td class="thead">Music on Hold</td><td><input type="text" value="<?echo $row[musiconhold];?>" name="musiconhold"></td><td><p onClick="resetToDefault('musiconhold','');">default</p></td></tr>
<tr class="tborderx"><td class="thead">Announce</td><td><input type="text" value="<?echo $row[announce];?>" name="announce"></td><td><p
onClick="resetToDefault('announce','');">default</p></td></tr>
<tr class="tborder2"><td class="thead">Context</td><td><input type="text" value="<?echo $row[context];?>" name="context"></td><td><p
onClick="resetToDefault('context','');">default</p></td></tr>
<tr class="tborder2"><td class="thead">Retry</td><td><input type="text" value="<?echo $row[retry];?>" name="retry"></td><td><p onClick="resetToDefault('retry','');">default</p></td></tr>
<tr class="tborderx"><td class="thead">Service Level</td><td><input type="text" value="<?echo $row[servicelevel];?>" name="servicelevel"></td><td><p onClick="resetToDefault('servicelevel','');">default</p></td></tr>
<tr class="tborder2"><td class="thead">Announce Frequency</td><td><input type="text" value="<?echo $row[announce_frequency];?>" name="announce_frequency"></td><td><p onClick="resetToDefault('announce_frequency','');">default</p></tr>
<tr class="tborderx"><td class="thead">Announce Holdtime</td><td><input type="text" value="<?echo $row[announce_holdtime];?>" name="announce_holdtime"></td><td><p onClick="resetToDefault('announce_holdtime','');">default</p></tr>
<tr class="tborder2"><td class="thead">Max Queue length</td><td><input type="text" value="<?echo $row[maxlen];?>" name="maxlen"></td><td><p onClick="resetToDefault('maxlen','');">default</p></tr>
</table>

<div onClick="toggle('advanced')"><p>Advanced Configuration Options <img src="./images/closed.png" name="img_advanced"></p></div>
<div id="advanced" style="display:none">
<p onClick="toggle('sound');">Sound Files <img src="./images/closed.png" name="img_sound"></p>
<table class="" align="center" border="0" cellpadding="2" cellspacing"0" id="sound" style="display:none">
<tr class="tborderx"><td class="thead">You are next</td><td><input type="text" value="<?echo $row[queue_youarenext];?>" name="queue_youarenext"></td><td><p onClick="resetToDefault('queue_youarenext','');">default</p></td></tr>
<tr class="tborder2"><td class="thead">There are...</td><td><input type="text" value="<?echo $row[queue_thereare];?>" name="queue_thereare"></td><td><p onClick="resetToDefault('queue_thereare','');">default</p></td></tr>
<tr class="tborderx"><td class="thead">Calls waiting</td><td><input type="text" value="<?echo $row[queue_callswaiting];?>" name="queue_callswaiting"</td><td><p onClick="resetToDefault('queue_callswaiting','');">default</p></td></tr>
<tr class="tbordeR2"><td class="thead">Hold time</td><td><input type="text" value="<?echo $row[queue_holdtime];?>" name="queue_holdtime"></td><td><p onClick="resetToDefault('queue_holdtime','');">default</p></td></tr>
<tr class="tborderx"><td class="thead">Minutes</td><td><input type="text" value="<?echo $row[queue_minutes];?>" name="queue_minutes"></td><td><p onClick="resetToDefault('queue_minutes','');">default</p></td></tr>
<tr class="tborder2"><td class="thead">Seconds</td><td><input type="text" value="<?echo $row[queue_seconds];?>" name="queue_seconds"></td><td><p onClick="resetToDefault('queue_seconds','');">default</p></td></tr>
<tr class="tborderx"><td class="thead">Less than...</td><td><input type="text" value="<?echo $row[queue_lessthan];?>" name="queue_lessthan"></td><td><p onClick="resetToDefault('queue_lessthan','');">default</p></td></tr>
<tr class="tborder2"><td class="thead">Thank you</td><td><input type="text" value="<?echo $row[queue_thankyou];?>" name="queue_thankyou"></td><td><p onClick="resetToDefault('queue_thankyou','');">default</p></td></tr>
</table>

<p onClick="toggle('recording');">Recording Options <img src="./images/closed.png" name="img_recording"></p>
<table class="" align="center" border="0" cellpadding="2" cellspacing"0" id="recording" style="display:none">
<tr class="tborderx"><td class="thead">Monitor Join</td><td><input type="text" value="<?echo $row[monitor_join];?>" name="monitor_join"></td><td><p onClick="resetToDefault('monitor_join','');">default</p></td></tr>
<tr class="tborder2"><td class="thead">Monitor Format</td><td><input type="text" value="<?echo $row[monitor_format];?>" name="monitor_format"></td><td><p onClick="resetToDefault('monitor_format','');">default</p></tr>
<tr class="tborderx"><td class="thead">Event Member Status</td><td><input type="text" value="<?echo $row[eventmemberstatus];?>" name="eventmemberstatus"></td><td><p onClick="resetToDefault('eventmemberstatus','');">default</p></td></tr>
<tr class="tborder2"><td class="thead">Event When Called</td><td><input type="text" value="<?echo $row[eventwhencalled];?>" name="eventwhencalled"></td><td><p onClick="resetToDefault('eventwhencalled','');">default</p></tr>
</table>

<p onClick="toggle('queue');">Queue Performance <img src="./images/closed.png" name="img_queue"></p>
<table class="" align="center" border="0" cellpadding="2" cellspacing"0" id="queue" style="display:none">
<tr class="tborderx"><td class="thead">Join when Empty</td><td><input type="text" value="<?echo $row[joinempty];?>" name="joinempty"></td><td><p onClick="resetToDefault('joinempty','');">default</p></td></tr>
<tr class="tborder2"><td class="thead">Leave When Empty</td><td><input type="text" value="<?echo $row[leavewhenempty];?>" name="leavewhenempty"></td><td><p onClick="resetToDefault('leavewhenempty','');">default</p></tr>
<tr class="tborderx"><td class="thead">Report Hold Time</td><td><input type="text" value="<?echo $row[reportholdtime];?>" name="reportholdtime"></td><td><p onClick="resetToDefault('reportholdtime','');">default</p></td></tr>
<tr class="tborder2"><td class="thead">Weight</td><td><input type="text" value="<?echo $row[weight];?>" name="weight"></td><td><p onClick="resetToDefault('weight','');">default</p></tr>
<tr class="tborderx"><td class="thead">Member Delay</td><td><input type="text" value="<?echo $row[memberdelay];?>" name="memberdelay"></td><td><p onClick="resetToDefault('memberdelay','');">default</p></tr>
</table>
</div>
<input type="submit">
</form>
<?php
require "footer.php";
?>
