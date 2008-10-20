<?

if($_POST[check_submit]){
    include "admin/db_config.php";
    mysql_select_db("SineDialer", $link);

	$query = "INSERT INTO queue_table (name,strategy,reportholdtime) VALUES (\"$_POST[queue_name]\",\"$_POST[strategy]\",".($_POST[reportholdtime]=="no"?"0":"1").")";

	//now we need to run the above query on the DB
	$result=mysql_query($query, $link) or die (mysql_error());;
	$insertedID = mysql_insert_id();
	include("queues.php");
	exit();
}
require "header.php";
require "header_queue.php";

$section=Array();

$section[0] = "This will take you through the creation of an Asterisk Based
Realtime Queue.<br />   <br />
You can create one queue per group of people that will be answering calls.<br />
<br />
These people are called Agents.  Each Agent is a person that may be working
on that Queue.  ";

$section[1] = "You may have some agents which work at different times on a
queue and so use the same physical device. <br /><br />This way you might only have 10
seats in your call center, but 25 staff who work there.<br /><br /><br />";

$section[2] = "First we will create the Queue.  <br />
<br />This can be selected when
you create a campaign, and the system will monitor that queue.<br />
<br />
It will make sure enough calls are made for the people who are logged in to the Queue to
stay busy.";

$section[3] = "<form name=\"myform\" action=\"addqueue.php\" method=\"GET\">
Please type a name for the Queue you are about to create (limited to 12 characters).
<br >
<br >
<center>
<input type=\"text\" name=\"queue_name\" value=\"$_GET[queue_name]\" maxlength=\"12\">
</center>
<br /><br /><br /><br />
";

$section[4] = "The next thing you need to decide on is the ringing strategy.
<br /><br />
<form name=\"myform\" action=\"addqueue.php\" method=\"GET\">

While it may sound complicated, it's actually pretty simple. <br />
<br />
Over the next
few pages I will explain the different strategies and then you can choose
one.<br />";

$section[5] = "<form name=\"myform\" action=\"addqueue.php\" method=\"GET\">
<b>Ring All Strategy</b>
<br /><br />
This type of queue will cause the phone to ring
for all people who are logged into the queue until someone answers it.<br />
<br />
Whoever answers the call first gets it.<br /><br /><br />";
$section[6] = "<form name=\"myform\" action=\"addqueue.php\" method=\"GET\">
<b>Round Robin Strategy</b>
<br /><br />
This strategy takes turns ringing each agent (person logged in to the queue).<br />
<br />
Every time a call comes in, this strategy will start with the first person
in the queue.  <i>See Also: Round Robin with Memory</i>";

$section[7] = "<form name=\"myform\" action=\"addqueue.php\" method=\"GET\">
<b>Least Recent Strategy</b>
<br /><br />
This strategy will call the agent who was least recently called by this queue.<br />
<br />
<br />
<br />
<br />
";

$section[8] = "<form name=\"myform\" action=\"addqueue.php\" method=\"GET\">
<b>Fewest Calls Strategy</b>
<br /><br />
This strategy will ring the agent with fewest completed calls from this queue.<br />
<br />
<br />
<br />
<br />
";

$section[9] = "<form name=\"myform\" action=\"addqueue.php\" method=\"GET\">
<b>Random</b>
<br /><br />
This strategy will ring a random agent. It could be the same agent or it
could be another.
<br />
<br />
<br />
<br />
<br />
";

$section[10] = "<form name=\"myform\" action=\"addqueue.php\" method=\"GET\">
<b>Round Robin with Memory</b>
<br /><br />
This strategy is similar to Round Robin except that it doesn't start from
the beginning every time.  <br />
<br />
It remembers who was the last person to be called
and calls from the next person the following time.
";
$selected_strategy = Array();
$selected_strategy[$_GET[strategy]] = " selected=\"selected\"";
$selected_autofill[$_GET[autofill]] = " selected=\"selected\"";
$selected_reportholdtime[$_GET[reportholdtime]] = " selected=\"selected\"";


$section[11] = "<form name=\"myform\" action=\"addqueue.php\" method=\"GET\">
Ok, so now you can select a strategy.  <br />
<br />
Or you can click back to review
their meanings again.
<br >
<br >
<center>
<select name=\"strategy\" onchange=\"document.myform.submit();return false;\">
<option value=\"ringall\"$selected_strategy[ringall]>Ring All</option>
<option value=\"roundrobin\"$selected_strategy[roundrobin]>Round Robin</option>
<option value=\"leastrecent\"$selected_strategy[leastrecent]>Least Recent</option>
<option value=\"fewestcalls\"$selected_strategy[fewestcalls]>Fewest Calls</option>
<option value=\"random\"$selected_strategy[random]>Random</option>
<option value=\"rrmemory\"$selected_strategy[rrmemory]>Round Robin with Memory</option>
</select>
</center>
<br /><br />
";

$section[12] = "<form name=\"myform\" action=\"addqueue.php\" method=\"GET\">
<b>Autofill</b><br />
<br />
Autofill will follow queue strategy but push multiple calls through
at same time until there are no more waiting callers or no more
available members.<br />
<br />Use Autofill?
<select name=\"autofill\" onchange=\"document.myform.submit();return false;\">
<option value=\"yes\"$selected_autofill[yes]>Yes</option>
<option value=\"no\"$selected_autofill[no]>No</option>
</select>
<br />";

$section[13] = "<form name=\"myform\" action=\"addqueue.php\" method=\"GET\">
<b>Report Hold Time</b><br />
<br />
If you wish to play an announcement to the agent before they accept the call
 telling them how long the customer has been holding, set this to yes.<br />
<br />Use Report Hold Time?
<select name=\"reportholdtime\" onchange=\"document.myform.submit();return false;\">
<option value=\"no\"$selected_reportholdtime[no]>No</option>
<option value=\"yes\"$selected_reportholdtime[yes]>Yes</option>
</select>
<br />";

/*$section[14] = "<form name=\"myform\" action=\"addqueue.php\" method=\"GET\">
<b>Context</b><br />
<br />
This is where you decide what happens if someone in the queue presses a single
digit while they are in the queue. The person will then go to the extension listed in
this context (if it exists). Be aware that the person will lose their place in the queue.<br />
";

$section[15] = "<form name=\"myform\" action=\"addqueue.php\" method=\"GET\">
Please enter the context for the queue.
<br>
<br>
<center>
<input type=\"text\" name=\"context\" value=\"$_GET[context]\">
<br />";
*/
//should have a bit to review the settings, and then accept
//also need to mention that there are more fine tuning options in the edit queues page
//I think that after specifying most of the stuff above the queue is pretty much defined

$section[14] = "<form name=\"myform\" action=\"addqueue.php\" method=\"GET\">
<b>Review</b><br>
On the next page you have a chance to review the settings for the queue created.<br><br>
If you wish to change these options, you can go back and change them. <br><br>
There are more fine control options availiable in the edit queue page";

$section [15] = "<form name=\"myform\" action=\"addqueue.php\" method=\"POST\">
<input type=\"hidden\" name=\"check_submit\" value=\"1\">
<b>Review</b><br>
These are the options you've selected:<br>
Strategy: ".$_GET[strategy]."<br>
Report Hold Time: ".$_GET[reportholdtime]."<br>
Auto Fill: ".$_GET[autofill]."<br><br>
";


$currentsection=$section[1+($_GET[section]-1)];
?>

<br /><br /><br /><br />
<center>
<table background="/images/sdbox.png" width="300" height="200">
<tr>
<td>
</td>
<td width="260">
<DIV class="mypars">
<?
echo $currentsection;
?>
</DIV>
<br />
<?if ($_GET[section]>0) {
?>

<div style="float:left">
<input type="hidden" name="section" value="<?echo $_GET[section]+1;?>">
<?
$cs = $_GET[section];
$url = "section=".($cs-1);
if (isset($_GET[queue_name])){
    $url.="&queue_name=".$_GET[queue_name];
    if ($_GET[section]>3){
        ?><input type="hidden" name="queue_name" value="<?echo $_GET[queue_name];?>"><?
    }
}
if (isset($_GET[strategy])){
    $url.="&strategy=".$_GET[strategy];
    if ($_GET[section]!=11){
        ?><input type="hidden" name="strategy" value="<?echo $_GET[strategy];?>"><?
    }
}

if (isset($_GET[autofill])){
    $url.="&autofill=".$_GET[autofill];
    if ($_GET[section]!=12){
        ?><input type="hidden" name="autofill" value="<?echo $_GET[autofill];?>"><?
    }
}
if (isset($_GET[reportholdtime])){
    $url.="&reportholdtime=".$_GET[reportholdtime];
    if ($_GET[section]!=13){
        ?><input type="hidden" name="reportholdtime" value="<?echo $_GET[reportholdtime];?>"><?
    }
}
?>
<a href="addqueue.php?<?echo $url;?>">
<img src="/images/resultset_previous.png" border = "0">Back</a>
</div>
<?
}
?>
<?if ($_GET[section]>2) {
?>
<div align="right">

<a href="#" onclick="document.myform.submit();return false;"><? echo ($_GET[section] == 15? "Submit": "Next") ?>
<img src="/images/resultset_next.png" border = "0"></a>
</form>
<?
} else {
    $cs = $_GET[section];
    $url = "section=".($cs+1);
    if (isset($_GET[queue_name])){
        $url.="&queue_name=".$_GET[queue_name];
    }
    if (isset($_GET[strategy])){
        $url.="&strategy=".$_GET[strategy];
    }
?>
<div align="right">
<a href="addqueue.php?<?echo $url;?>"><? echo ($_GET[section] == 15? "Submit": "Next") ?>
<img src="/images/resultset_next.png" border = "0"></a>
<?
}
?>

</div>
</td>
<td>
</td></tr>
</table>
</center>
<?
require "footer.php";
?>
