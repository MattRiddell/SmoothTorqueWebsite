<?php
$pagenum="1";

include "charts.php";

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


if (!isset($_GET[id])){
require "header.php";

?>
<form action="chart.php" method="get">
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2"><TR>
    <TD>Select Campaign:</TD><TD>    <select name="id">

<?
$lines2=file("/tmp/Sm1.allCampaigns");
$name="";
foreach ($lines2 as $line_num => $line) {
//              echo $line;
        if (substr($line,0,4)=="id =") {
                $id=trim(substr($line,5));
        } else if (substr($line,0,4)=="grou") {
                   if (trim(substr($line,10))==$groupid){
                    echo "".$name;
                } else {
                    echo "[".trim(substr($line,10))."]";
                }
        } else if (substr($line,0,4)=="name") {
                $name= "<option value=\"$id\">".trim(substr($line,6))."</option>
";
        }
}
?>
</select>
</td></tr><tr><td colspan=2>
<input type="submit" value="Monitor Campaign">
</td></tr></table>
</form>
<?} else {
require "header.php";
?>
<br \><br \><br \><br \>
<?
//include charts.php to access the InsertChart function

$id=$_GET[id];
if ($id>0){
echo InsertChart ( "charts.swf", "charts_library", "sample.php?id=".$id, 1000, 548, "eeeeee", false );
}


?>

</BODY>
</HTML>
<?}?>
