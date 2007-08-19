<?
$pagenum=1;
require "header.php";?>
<?if (isset($_POST[id])) {
require_once "PHPTelnet.php";

$telnet = new PHPTelnet();
$result = $telnet->Connect();
//echo "CONNECTION REQUEST: ".$result."<BR>";
$telnet->DoCommand('selectc', $result);
//echo "XX".$result."<BR>";
$telnet->DoCommand($_POST[id], $result);
//echo "YY:".$result."<BR>";
$pieces = explode("\n",$result);
?>
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2">
<?
foreach ($pieces as $line_num => $line) {
	if (substr(trim($line),0,5)=="Name:") {
		echo "<TR><TD CLASS=thead>Name:</TD><TD><input type=\"text\" name=\"name\" value=\"".substr(trim($line),6)."\"></TD></TR>";
	} else if (substr(trim($line),0,8)=="groupid:") {
		//echo "Group ID:<input type=\"text\" name=\"groupid\" value=\"".substr(trim($line),9)."\"><BR>";
	} else if (substr(trim($line),0,11)=="messageid1:") {
		echo "<TR><TD CLASS=thead>Message ID1:</TD><TD><input type=\"text\" name=\"messageid1\" value=\"".substr(trim($line),12)."\"></TD></TR>";
	} else if (substr(trim($line),0,11)=="messageid2:") {
		echo "<TR><TD CLASS=thead>Message ID2:</TD><TD><input type=\"text\" name=\"messageid2\" value=\"".substr(trim($line),12)."\"></TD></TR>";
	} else if (substr(trim($line),0,11)=="messageid3:") {
		echo "<TR><TD CLASS=thead>Message ID3:</TD><TD><input type=\"text\" name=\"messageid3\" value=\"".substr(trim($line),12)."\"></TD></TR>";
	} else if (substr(trim($line),0,17)=="campaignconfigid:") {
		echo "<TR><TD CLASS=thead>Campaign Config ID:</TD><TD><input type=\"text\" name=\"campaignconfigid\" value=\"".substr(trim($line),18)."\"></TD></TR>";
	} else if (substr(trim($line),0,12)=="Description:") {
		echo "<TR><TD CLASS=thead COLSPAN=2>Description:</TD></TR>" ;
		echo "<TR><TD COLSPAN=2><textarea COLS=36 name=\"description\">".substr(trim($line),13)."</textarea></TD></TR>";
	} else {
//		echo $line."<BR>";
	}
}
$telnet->Disconnect();
sleep(1);
}
?>
</table>
<a href="menu_editCampaign.php">Main Menu</a>
