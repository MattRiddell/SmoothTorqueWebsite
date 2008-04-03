<?
require "../header.php";
?>
<html>
<head><title>SmoothTorque Linux Help Manual</title></head>


<script language="JavaScript" type="text/JavaScript">
function hideshow(name){
	var element = document.getElementById(name);
	if(element.style.display == "none")
		element.style.display="";
	else
		element.style.display = "none";
}

</script>
<body>
<center>
<table width="50%">
<tr>
<td>
<font face="arial" align="left">
<br />
<br />
<p align="left">
Welcome to <?echo $config_values['TITLE'];?> help file.  Please select a category from below by clicking
on one of the titles.
<br />
<br />
This help file is tailored to your personal installation of <?echo $config_values['TITLE'];?>.

</p>
<?php
echo '<h1>Normal User</h1>';
echo '<H2 onClick=hideshow("quick_start")>Quick Start</h2>';
echo '<div id="quick_start" style="display:none">';
include 'src/quickstart.php';
echo '</div>';

echo '<h2 onClick=hideshow("campaigns")>'.$config_values['MENU_CAMPAIGNS'].'</h2>';
echo '<div id="campaigns" style="display:none">';
include 'src/campaigns.php';
echo '</div>';

echo '<h2 onClick=hideshow("numbers")>Numbers</h2>';
echo '<div id="numbers" style="display:none">';
include 'src/all_numbers.php';
echo '</div>';

echo '<h2 onClick=hideshow("messages")>Messages</h2>';
echo '<div id="messages" style="display:none">';
include 'src/messages.php';
echo '</div>';

echo '<h2 onClick=hideshow("schedules")>Schedules</h2>';
echo '<div id="schedules" style="display:none">';
include 'src/schedules.php';
echo '</div>';

?>
</td></tr></table>
</body>
</html>
