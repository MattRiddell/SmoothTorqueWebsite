<?
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
Please type a name for the Queue you are about to create.
<br >
<br >
<center>
<input type=\"text\" name=\"queue_name\" value=\"$_GET[queue_name]\">
</center>
<br /><br /><br /><br />
";

$section[4] = "The next thing you need to decide on is the ringing strategy.
<br /><br />
<form name=\"myform\" action=\"addqueue.php\" method=\"GET\">
<input type=\"hidden\" name=\"queue_name\" value=\"$_GET[queue_name]\">

While it may sound complicated, it's actually pretty simple. <br />
<br />
Over the next
few pages I will explain the different strategies and then you can choose
one.<br />";

$section[5] = "<form name=\"myform\" action=\"addqueue.php\" method=\"GET\">
<input type=\"hidden\" name=\"queue_name\" value=\"$_GET[queue_name]\">
<b>Ring All Strategy</b>
<br /><br />
This type of queue will cause the phone to ring
for all people who are logged into the queue until someone answers it.<br />
<br />
Whoever answers the call first gets it.<br /><br /><br />";
$section[6] = "<form name=\"myform\" action=\"addqueue.php\" method=\"GET\">
<input type=\"hidden\" name=\"queue_name\" value=\"$_GET[queue_name]\">
<b>Round Robin Strategy</b>
<br /><br />
This strategy takes turns ringing each agent (person logged in to the queue).<br />
<br />
Every time a call comes in, this strategy will start with the first person
in the queue.  <i>See Also: Round Robin with Memory</i>";

$section[7] = "<form name=\"myform\" action=\"addqueue.php\" method=\"GET\">
<input type=\"hidden\" name=\"queue_name\" value=\"$_GET[queue_name]\">
<b>Least Recent Strategy</b>
<br /><br />
This strategy will call the agent who was least recently called by this queue.<br />
<br />
<br />
<br />
<br />
<br />
";

$section[8] = "<form name=\"myform\" action=\"addqueue.php\" method=\"GET\">
<input type=\"hidden\" name=\"queue_name\" value=\"$_GET[queue_name]\">
<b>Fewest Calls Strategy</b>
<br /><br />
This strategy will ring the agent with fewest completed calls from this queue.<br />
<br />
<br />
<br />
<br />
<br />
";

$section[9] = "<form name=\"myform\" action=\"addqueue.php\" method=\"GET\">
<input type=\"hidden\" name=\"queue_name\" value=\"$_GET[queue_name]\">
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
<input type=\"hidden\" name=\"queue_name\" value=\"$_GET[queue_name]\">
<b>Round Robin with Memory</b>
<br /><br />
This strategy is similar to Round Robin except that it doesn't start from
the beginning every time.  <br />
<br />
It remembers who was the last person to be called
and calls from the next person the following time.

";

$section[11] = "<form name=\"myform\" action=\"addqueue.php\" method=\"GET\">
<input type=\"hidden\" name=\"queue_name\" value=\"$_GET[queue_name]\">
Ok, so now you can select a strategy.  <br />
<br />
Or you can click back to review
their meanings again.
<br >
<br >
<center>
<select name=\"strategy\" onchange=\"document.myform.submit();return false;\">
<option value=\"ringall\">Ring All</option>
<option value=\"roundrobin\">Round Robin</option>
<option value=\"leastrecent\">Least Recent</option>
<option value=\"fewestcalls\">Fewest Calls</option>
<option value=\"random\">Random</option>
<option value=\"rrmemory\">Round Robin with Memory</option>
</select>
</center>
<br /><br />
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
}
if (isset($_GET[strategy])){
    $url.="&strategy=".$_GET[strategy];
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

<a href="#" onclick="document.myform.submit();return false;">Next
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

<a href="addqueue.php?<?echo $url;?>">Next
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
