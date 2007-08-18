<?php
if (isset($_POST[description])) {
require_once "PHPTelnet.php";

$telnet = new PHPTelnet();

// if the first argument to Connect is blank,
// PHPTelnet will connect to the local host via 127.0.0.1
$telnet = new PHPTelnet();
$result = $telnet->Connect();
echo "CONNECTION REQUEST: ".$result."<BR>";
$telnet->DoCommand('selectc', $result);echo $result."<BR>";
$telnet->DoCommand($_GET[id], $result);echo $result."<BR>";
$telnet->Disconnect();
sleep(1);
?> 
Campaign Added
<?
} else {
?>
<FORM ACTION="addCampaign.php" METHOD="POST">
Name: <INPUT TYPE="TEXT" NAME="name"><BR>
Description: <INPUT TYPE="TEXT" NAME="description"><BR>
<INPUT TYPE="SUBMIT">
<?
}
?>
