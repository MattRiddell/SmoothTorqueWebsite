<?
$pagenum=1;
require "header.php";
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

?>
<FORM ACTION="addCampaign.php" METHOD="POST">
Name: <INPUT TYPE="TEXT" NAME="name"><BR>
Description: <INPUT TYPE="TEXT" NAME="description"><BR>
Group ID: <?echo $groupid;?><BR>
<INPUT TYPE="SUBMIT" VALUE="Add New">
</FORM>
