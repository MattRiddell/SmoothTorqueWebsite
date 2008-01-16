<?php
require "header.php";
require "header_queue.php";


$is_int = array( 
 "name" => array(0,128),
 "musiconhold" => array(0,128),
 "announce" => array(0,128),
 "context"=> array(0,128),
 "timeout"=> array(1,11),
 "monitor_join"=> array(1,1),
 "monitor_format"=> array(0,128),
 "queue_youarenext"=> array(0,128),
 "queue_thereare"=> array(0,128),
 "queue_callswaiting"=> array(0,128),
 "queue_holdtime"=> array(0,128),
 "queue_minutes"=> array(0,128),
 "queue_seconds"=> array(0,128),
 "queue_lessthan"=> array(0,128),
 "queue_thankyou"=> array(0,128),
 "queue_reporthold"=> array(0,128),
 "announce_frequency"=> array(1,11),
 "announce_round_seconds"=> array(1,11),
 "announce_holdtime"=> array(0,128),
 "retry"=> array(1,11),
 "wrapuptime"=> array(1,11),
 "maxlen"=> array(1,11),
 "servicelevel"=>array(1,11),
 "strategy"=>array(0,128),
 "joinempty"=> array(0,128),
 "leavewhenempty"=> array(0,128),
 "eventmemberstatus"=> array(1,128),
 "eventwhencalled"=> array(1,128),
 "reportholdtime"=> array(1,11),
 "memberdelay"=> array(1,11),
 "weight"=> array(1,11),
 "timeoutrestart"=> array(1,11),
 "periodic_announce"=> array(0,50),
 "periodic_announce_frequency"=> array(1,11)
);


/* this function builds a drop down box for the strategy
   The existing strategy is passed in as an parameter
   The name for the resulting select is also passed in.
*/
function make_strategy_selector($strategy, $name){
	$result = "<select name=\"".$name."\">\n
		<option value=\"ringall\"".($strategy=="ringall"?" selected":"")." title=\"Ring all channels until one answers.\">Ring all</option>\n
		<option value=\"roundrobin\"".($strategy=="roundrobin"?" selected":"")." title=\"Take turns ringing each available interface.\">Round Robin</option>\n
		<option value=\"leastrecent\"".($strategy=="leastrecent"?" selected":"")." title=\"Use the interface which was least recently called by this queue.\">Least Recent</option>\n
		<option value=\"fewestcalls\"".($strategy=="fewestcalls"?" selected":"")." title=\"Ring the one with the fewest completed calls from this queue.\">Fewest Calls</option>\n
		<option value=\"random\"".($strategy=="random"?" selected":"")." title=\"Ring a random interface.\">Random</option>\n
		<option value=\"rrmemory\"".($strategy=="rrmemory"?" selected":"")." title=\"Round robin with memory, remember where we left off last ring pass.\">Round Robin with Memory</option>\n
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
	$_POST[monitor_join] = $_POST[monitor_join]=="on"?1:0;
	$_POST[eventmemberstatus] = $_POST[evenmemberstatus]=="on"?1:0;
	$_POST[eventwhencalled] = $_POST[eventwhencalled]=="on"?1:0;
	$_POST[reportholdtime] = $_POST[reportholdtime]=="on"?1:0;


	//insert stuff in to the database
	$names = array_keys($_POST);
	$result = "";
	for($i = 1; $i <count($_POST);$i++){
		$result .= $names[$i]."=";
		if($_POST[$names[$i]] != ""){
			if($is_int[$names[$i]][0]){
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
<tr class="tborder2"><td class="thead">Name<a href="#" onClick="displaySmallMessage('/includes/help.php?section=The name of the queue');return false;"><img src="./images/help.png" border="0"></a></td><td><input type="text" value="<?echo $row[name];?>" name="name" maxlength="<?echo $is_int[name][1]?>"></td></tr>
<tr class="tborderx"><td class="thead">Strategy<a href="#" onClick="displaySmallMessage('/includes/help.php?section=Calls are distributed among members handling a queue with one of several strategies');return false;"><img src="./images/help.png" border="0"></a></td><td><?echo make_strategy_selector($row[strategy],"strategy")?></td></tr>
<tr class="tborder2"><td class="thead">Timeout<a href="#" onClick="displaySmallMessage('/includes/help.php?section=Time out, in seconds, when calling an agent, e.g. 15');return false;"><img src="./images/help.png" border="0"></a></td><td><input type="text" value="<?echo $row[timeout]?>" name="timeout" maxlength="<?echo $is_int[timeout][1]?>"></td></tr>
</table>

<div onClick="toggle('misc')"><p>Miscellaneous Configuration Options <img src="./images/closed.png" name="img_misc"></p></div>
<table class="" align="center" border="0" cellpadding="2" cellspacing="0" id="misc" style="display:none">
<tr class="tborder2"><td class="thead">Music on Hold<a href="#" onClick="displaySmallMessage('/includes/help.php?section=The music played while someone is waiting in the queue');return false;"><img src="./images/help.png" border="0"></a></td><td><input type="text" value="<?echo $row[musiconhold];?>" name="musiconhold" maxlength="<?echo $is_int[musiconhold][1]?>"></td><td><p onClick="resetToDefault('musiconhold','');">default</p></td></tr>
<tr class="tborderx"><td class="thead">Announce<a href="#" onClick="displaySmallMessage('/includes/help.php?section=Play this message when an agent picks up the phone to service someone in the queue');return false;"><img src="./images/help.png" border="0"></a></td><td><input type="text" value="<?echo $row[announce];?>" name="announce" maxlength="<?echo $is_int[announce][1]?>"></td><td><p
onClick="resetToDefault('announce','');">default</p></td></tr>
<tr class="tborder2"><td class="thead">Context<a href="#" onClick="displaySmallMessage('/includes/help.php?section=What happens to the call if the person presses a key, for example if they press 1');return false;"><img src="./images/help.png" border="0"></a></td><td><input type="text" value="<?echo $row[context];?>" name="context"  maxlength="<?echo $is_int[context][1]?>"></td><td><p
onClick="resetToDefault('context','');">default</p></td></tr>
<tr class="tborder2"><td class="thead">Retry<a href="#" onClick="displaySmallMessage('/includes/help.php?section=If a timeout occourse this value determins how long to wait before presenting the call to another free agent');return false;"><img src="./images/help.png" border="0"></a></td><td><input type="text" value="<?echo $row[retry];?>" name="retry"  maxlength="<?echo $is_int[retry][1]?>"></td><td><p onClick="resetToDefault('retry','');">default</p></td></tr>
<tr class="tborderx"><td class="thead">Service Level<a href="#" onClick="displaySmallMessage('/includes/help.php?section=This value represents, ideally how long a call has to wait before being presented to an agent');return false;"><img src="./images/help.png" border="0"></a></td><td><input type="text" value="<?echo $row[servicelevel];?>" name="servicelevel"  maxlength="<?echo $is_int[servicelevel][1]?>"></td><td><p onClick="resetToDefault('servicelevel','');">default</p></td></tr>
<tr class="tborder2"><td class="thead">Announce Frequency<a href="#" onClick="displaySmallMessage('/includes/help.php?section=How often (in seconds) to announce to a person in the queue their position and estimated wait time');return false;"><img src="./images/help.png" border="0"></a></td><td><input type="text" value="<?echo $row[announce_frequency];?>" name="announce_frequency"  maxlength="<?echo $is_int[accounce_frequency][1]?>"></td><td><p onClick="resetToDefault('announce_frequency','');">default</p></tr>
<tr class="tborderx"><td class="thead">Announce Holdtime<a href="#" onClick="displaySmallMessage('/includes/help.php?section=Determines if the announcement should include the estimated hold time. Possible values:<br>Once --- only play the message once<br>Yes --- play the message as often as specified<br>No --- don&#96;t play the message at all');return false;"><img src="./images/help.png" border="0"></a></td><td><input type="text" value="<?echo $row[announce_holdtime];?>" name="announce_holdtime"  maxlength="<?echo $is_int[announce_holdtime][1]?>"></td><td><p onClick="resetToDefault('announce_holdtime','');">default</p></tr>
<tr class="tborder2"><td class="thead">Max Queue Length<a href="#" onClick="displaySmallMessage('/includes/help.php?section=The maximum number of calls in the queue before going on to the next priority of the current extension. Essentially, if there are too many people in the queue, then the next step of the dial plan will be executed.');return false;"><img src="./images/help.png" border="0"></a></td><td><input type="text" value="<?echo $row[maxlen];?>" name="maxlen"  maxlength="<?echo $is_int[maxlen][1]?>"></td><td><p onClick="resetToDefault('maxlen','');">default</p></tr>
</table>

<p onClick="toggle('sound');">Sound Files <img src="./images/closed.png" name="img_sound"></p>
<table class="" align="center" border="0" cellpadding="2" cellspacing"0" id="sound" style="display:none">
<tr class="tborderx"><td class="thead">You are next<a href="#" onClick="displaySmallMessage('/includes/help.php?section=The file that is used for the message: You are next');return false;"><img src="./images/help.png" border="0"></a></td><td><input type="text" value="<?echo $row[queue_youarenext];?>" name="queue_youarenext"  maxlength="<?echo $is_int[queue_youarenext][1]?>"></td><td><p onClick="resetToDefault('queue_youarenext','');">default</p></td></tr>
<tr class="tborder2"><td class="thead">There are...<a href="#" onClick="displaySmallMessage('/includes/help.php?section=The path to the file that is used for the message: There are');return false;"><img src="./images/help.png" border="0"></a></td><td><input type="text" value="<?echo $row[queue_thereare];?>" name="queue_thereare" maxlength="<?echo $is_int[queue_thereare][1]?>"></td><td><p onClick="resetToDefault('queue_thereare','');">default</p></td></tr>
<tr class="tborderx"><td class="thead">Calls waiting<a href="#" onClick="displaySmallMessage('/includes/help.php?section=File for the message: calls waiting.');return false;"><img src="./images/help.png" border="0"></a></td><td><input type="text" value="<?echo $row[queue_callswaiting];?>" name="queue_callswaiting" maxlength="<?echo $is_int[queue_callswaiting][1]?>"></td><td><p onClick="resetToDefault('queue_callswaiting','');">default</p></td></tr>
<tr class="tbordeR2"><td class="thead">Hold time<a href="#" onClick="displaySmallMessage('/includes/help.php?section=File for the message: The current estimated hold time is');return false;"><img src="./images/help.png" border="0"></a></td><td><input type="text" value="<?echo $row[queue_holdtime];?>" name="queue_holdtime" maxlength="<?echo $is_int[queue_holdtime][1]?>"></td><td><p onClick="resetToDefault('queue_holdtime','');">default</p></td></tr>
<tr class="tborderx"><td class="thead">Minutes<a href="#" onClick="displaySmallMessage('/includes/help.php?section=File for the message: minutes.');return false;"><img src="./images/help.png" border="0"></a></td><td><input type="text" value="<?echo $row[queue_minutes];?>" name="queue_minutes" maxlength="<?echo $is_int[queue_minutes][1]?>"></td><td><p onClick="resetToDefault('queue_minutes','');">default</p></td></tr>
<tr class="tborder2"><td class="thead">Seconds<a href="#" onClick="displaySmallMessage('/includes/help.php?section=File for the message: seconds.');return false;"><img src="./images/help.png" border="0"></a></td><td><input type="text" value="<?echo $row[queue_seconds];?>" name="queue_seconds" maxlength="<?echo $is_int[queue_seconds][1]?>"></td><td><p onClick="resetToDefault('queue_seconds','');">default</p></td></tr>
<tr class="tborderx"><td class="thead">Less than...<a href="#" onClick="displaySmallMessage('/includes/help.php?section=File for the message: less than');return false;"><img src="./images/help.png" border="0"></a></td><td><input type="text" value="<?echo $row[queue_lessthan];?>" name="queue_lessthan" maxlength="<?echo $is_int[queue_lessthan][1]?>"></td><td><p onClick="resetToDefault('queue_lessthan','');">default</p></td></tr>
<tr class="tborder2"><td class="thead">Thank you<a href="#" onClick="displaySmallMessage('/includes/help.php?section=File for the message: Thank you for your patience.');return false;"><img src="./images/help.png" border="0"></a></td><td><input type="text" value="<?echo $row[queue_thankyou];?>" name="queue_thankyou" maxlength="<?echo $is_int[queue_thankyou][1]?>"></td><td><p onClick="resetToDefault('queue_thankyou','');">default</p></td></tr>
</table>

<p onClick="toggle('recording');">Recording Options <img src="./images/closed.png" name="img_recording"></p>
<table class="" align="center" border="0" cellpadding="2" cellspacing"0" id="recording" style="display:none">
<tr class="tborderx"><td class="thead">Monitor Join<a href="#" onClick="displaySmallMessage('/includes/help.php?section=Normaly when asterisk records a conversation it will record the different sdes in different files, this option tells asterisk to join the files together at the end of the call.');return false;"><img src="./images/help.png" border="0"></a></td><td><input type="checkbox" <?echo ($row[monitor_join]?"checked":"");?> name="monitor_join" maxlength="<?echo $is_int[monitor_join][1]?>"></td><td><p onClick="resetToDefault('monitor_join','');">default</p></td></tr>
<tr class="tborder2"><td class="thead">Monitor Format<a href="#" onClick="displaySmallMessage('/includes/help.php?section=The format for recording the calls, possible values are wav, gsm or wav49. If this option is not specified then no calls will be recorded.');return false;"><img src="./images/help.png" border="0"></a></td><td><input type="text" value="<?echo $row[monitor_format];?>" name="monitor_format" maxlength="<?echo $is_int[monitor_format][1]?>"></td><td><p onClick="resetToDefault('monitor_format','');">default</p></tr>
<tr class="tborderx"><td class="thead">Event Member Status<a href="#" onClick="displaySmallMessage('/includes/help.php?section=If selected then the manager will generate a lot of information about the members of the queue (specifically the eveng QueueMemberStatus). WARNING This can be a lot more information.');return false;"><img src="./images/help.png" border="0"></a></td><td><input type="checkbox" <?echo ($row[eventmemberstatus]?"checked":"");?> name="eventmemberstatus maxlength="<?echo $is_int[eventmemberstatus][1]?>""></td><td><p onClick="resetToDefault('eventmemberstatus','');">default</p></td></tr>
<tr class="tborder2"><td class="thead">Event When Called<a href="#" onClick="displaySmallMessage('/includes/help.php?section=If this option is ticked, then the following manager events will be generated: AgentCalled, AgentDump, AgentConnect and AgentComplete');return false;"><img src="./images/help.png" border="0"></a></td><td><input type="checkbox" <?echo ($row[eventwhencalled]?"checked":"");?> name="eventwhencalled" maxlength="<?echo $is_int[eventwhencalled][1]?>"></td><td><p onClick="resetToDefault('eventwhencalled','');">default</p></tr>
</table>

<p onClick="toggle('queue');">Queue Performance <img src="./images/closed.png" name="img_queue"></p>
<table class="" align="center" border="0" cellpadding="2" cellspacing"0" id="queue" style="display:none">
<tr class="tborderx"><td class="thead">Join when Empty<a href="#" onClick="displaySmallMessage('/includes/help.php?section=This determins if a caller is allowed to join a queue with no agents in it. Possible options: <br>Yes --- allow the callers to join queues with no members of unavailable members<br>NO --- disallow the callers to join a queue with no members<br>Strict --- the callers cannot join a queue with no members, but are allowed to join one with unavailable members');return false;"><img src="./images/help.png" border="0"></a></td><td><input type="text" value="<?echo $row[joinempty];?>" name="joinempty" maxlength="<?echo $is_int[joinempty][1]?>"></td><td><p onClick="resetToDefault('joinempty','');">default</p></td></tr>
<tr class="tborder2"><td class="thead">Leave When Empty<a href="#" onClick="displaySmallMessage('/includes/help.php?section=If this option is checked then you will allow the removing of callers from the queue, if there are new callers which cannot join. The possibly values are the same as for joinempty.');return false;"><img src="./images/help.png" border="0"></a></td><td><input type="textbox" <?echo ($row[leavewhenempty]?"checked":"");?> name="leavewhenempty" maxlength="<?echo $is_int[leavewhenempty][1]?>"></td><td><p onClick="resetToDefault('leavewhenempty','');">default</p></tr>
<tr class="tborderx"><td class="thead">Report Hold Time<a href="#" onClick="displaySmallMessage('/includes/help.php?section=Report the hold time to the agent before they talk to the person in the queue.');return false;"><img src="./images/help.png" border="0"></a></td><td><input type="checkbox" <?echo ($row[reportholdtime]?"checked":"");?>" name="reportholdtime" maxlength="<?echo $is_int[reportholdtime][1]?>"></td><td><p onClick="resetToDefault('reportholdtime','');">default</p></td></tr>
<tr class="tborder2"><td class="thead">Weight<a href="#" onClick="displaySmallMessage('/includes/help.php?section=When a channel is included in more than one queue, the higher weighted queue will be answered first.');return false;"><img src="./images/help.png" border="0"></a></td><td><input type="text" value="<?echo $row[weight];?>" name="weight" maxlength="<?echo $is_int[weight][1]?>">
</td><td><p onClick="resetToDefault('weight','');">default</p></tr>
<tr class="tborderx"><td class="thead">Member Delay<a href="#" onClick="displaySmallMessage('/includes/help.php?section=A number of seconds of silence before the member of the queue is connected to the agent');return false;"><img src="./images/help.png" border="0"></a></td><td><input type="text" value="<?echo $row[memberdelay];?>" name="memberdelay" maxlength="<?echo $is_int[memberdelay][1]?>"></td><td><p onClick="resetToDefault('memberdelay','');">default</p></tr>
</table>
<input type="submit" value="Save Queue">
</form>
<?php
require "footer.php";
?>
