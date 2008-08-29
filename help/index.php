<?
require "../header.php";
?>
<html>
<head><title><?echo $config_values['TITLE'];?> Help Manual</title></head>


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
echo '<h2 align="left" onClick=hideshow("quick_start")>Quick Start</h2>';
echo '<div id="quick_start" style="display:none">';
include 'src/quickstart.php';
echo '</div>';

echo '<h2 align="left" onClick=hideshow("campaigns")>'.$config_values['MENU_CAMPAIGNS'].'</h2>';
echo '<div id="campaigns" style="display:none">';
include 'src/campaigns.php';
echo '</div>';

echo '<h2 align="left" onClick=hideshow("numbers")>'.$config_values['MENU_NUMBERS'].'</h2>';
echo '<div id="numbers" style="display:none">';
include 'src/all_numbers.php';
echo '</div>';

echo '<h2 align="left" onClick=hideshow("messages")>'.$config_values['MENU_MESSAGES'].'</h2>';
echo '<div id="messages" style="display:none">';
include 'src/messages.php';
echo '</div>';

echo '<h2 align="left" onClick=hideshow("schedules")>'.$config_values['MENU_SCHEDULES'].'</h2>';
echo '<div id="schedules" style="display:none">';
include 'src/schedules.php';
echo '</div>';

if ($level==sha1("level100")){
    //Admin level options
    echo '<h2 align="left" onClick=hideshow("customers")>'.$config_values['MENU_CUSTOMERS'].'</h2>';
    echo '<div id="customers" style="display:none">';
    include 'src/customers.php';
    echo '</div>';

    echo '<h2 align="left" onClick=hideshow("queues")>'.$config_values['MENU_QUEUES'].'</h2>';
    echo '<div id="queues" style="display:none">';
    include 'src/queues.php';
    echo '</div>';
    
    echo '<h2 align="left" onClick=hideshow("agents")>Agents<h2>';
    echo '<div id="agents" style="display:none">';
    include 'src/agents.php';
    echo '</div>';

    echo '<h2 align="left" onClick=hideshow("trunks")>'.$config_values['MENU_TRUNKS'].'</h2>';
    echo '<div id="trunks" style="display:none">';
    include 'src/trunks.php';
    echo '</div>';

    echo '<h2 align="left" onClick=hideshow("servers")>'.$config_values['MENU_SERVERS'].'</h2>';
    echo '<div id="servers" style="display:none">';
    include 'src/servers.php';
    echo '</div>';

    echo '<h2 align="left" onClick=hideshow("admin")>'.$config_values['MENU_ADMIN'].'</h2>';
    echo '<div id="admin" style="display:none">';
    include 'src/admin.php';
    echo '</div>';

}

?>
</td></tr></table>
</body>
</html>
