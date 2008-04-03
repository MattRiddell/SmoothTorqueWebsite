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
<font face="arial">
<?php
echo '<h1>Normal User</h1>';
echo '<H2 onClick=hideshow("quick_start")>Quick Start</h2>';
echo '<div id="quick_start" style="display:none">';
include 'src/quickstart.php';
echo '</div>';

echo '<h2 onClick=hideshow("campaigns")>Campaigns</h2><img src="../images/closed.png" name="img_misc" align="right">';
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

</body>
</html>
