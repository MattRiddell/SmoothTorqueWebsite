<?php
$pagenum=1;
require "header.php";
if (isset($_POST[description])) {
require_once "PHPTelnet.php";

$telnet = new PHPTelnet();

// if the first argument to Connect is blank,
// PHPTelnet will connect to the local host via 127.0.0.1
$telnet = new PHPTelnet();
$result = $telnet->Connect();
//echo "CONNECTION REQUEST: ".$result."<BR>";
$telnet->DoCommand('selectcg', $result);//echo $result."<BR>";
$telnet->DoCommand($_COOKIE[user], $result);
//echo $result."<BR>";
if (substr(trim($result),0,7)=="GroupID") {
    $groupid=substr(trim($result),8);
}
$telnet->Disconnect();

$result = $telnet->Connect();
//echo "CONNECTION REQUEST: ".$result."<BR>";
$telnet->DoCommand('insertc', $result);//echo $result."<BR>";
$telnet->DoCommand($_POST[name], $result);//echo $result."<BR>";
$telnet->DoCommand($_POST[description], $result);//echo $result."<BR>";
$telnet->DoCommand($groupid, $result);//echo $result."<BR>";
$telnet->Disconnect();
sleep(1);
?>
Campaign Added
<?
}
?>
<meta http-equiv="refresh" content="1;url=menu_editCampaign.php">
