<?
$pagenum=1;require "header.php";
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
<form action="campaigns.php" method="post">

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
<INPUT TYPE="BUTTON" onClick="this.form.action = 'campaigns.php';this.form.submit()" VALUE="Edit Campaign"></td></tr></table>
</form>
