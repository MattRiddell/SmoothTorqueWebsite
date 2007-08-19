<?
$pagenum=1;
require_once "header.php";
require_once "PHPTelnet.php";
$telnet = new PHPTelnet();
$result = $telnet->Connect();
$telnet->DoCommand('selectcg', $result);//echo $result."<BR>";
$telnet->DoCommand($_COOKIE[user], $result);
if (substr(trim($result),0,7)=="GroupID") {
    $groupid=substr(trim($result),8);
}
$telnet->Disconnect();

?>
<form action="campaigns.php" method="post">

<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2"><TR>
    <TD>Select Campaign:</TD><TD>    <select name="id">

<?

$telnet = new PHPTelnet();
$result = $telnet->Connect();
while (substr(trim($result),0,3)!="END") {
    $telnet->DoCommand('getallca', $result);
    if (substr(trim($result),0,3)!="END"){
        $pieces = explode("\n",$result);
        $row[id]= $pieces[0];
        $row[description]= $pieces[1];
        $row[name]= $pieces[2];
        $row[campaigngroupid]= $pieces[3];
        $row[messageid]= $pieces[4];
        $row[messageid2]= $pieces[5];
        $row[messageid3]= $pieces[6];
        echo $result."<BR>";
        if ($groupid==trim($row[campaigngroupid])){
            echo "<option value=\"$row[id]\">$row[description]</option>
";
        } else {
            echo $groupid."!=".$row[campaigngroupid];
        }

    }
}
//exit(0);



?>
</select>
</td></tr><tr><td colspan=2>
<INPUT TYPE="BUTTON" onClick="this.form.action = 'campaigns.php';this.form.submit()" VALUE="Edit Campaign"></td></tr></table>
</form>
