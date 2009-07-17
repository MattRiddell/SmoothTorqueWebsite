<?
$override_directory = dirname(__FILE__)."/../../";
require $override_directory."header.php";
?>
<center>
Select List:<br />
<select name="list">
</select>
<br />
What do you want to happen?<br />
<br />
Answer Machine:<br />
<select name="amdchoice">
	<option value = "playmessage">Play a Message</option>
	<option value = "hangup">Hang up the phone</option>	
</select><br />
<br />
Human:<br />
</center>
<?
require $override_directory."footer.php";
?>
