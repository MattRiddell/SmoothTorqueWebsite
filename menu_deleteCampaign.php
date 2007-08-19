<?
$pagenum=1;
require_once "PHPTelnet.php";
require "header.php";?>
<form action="deleteCampaign.php" method="post">
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
            echo "<option value=\"$row[id]\">$row[name]</option>
";
        } else {
//            echo $groupid."!=".$row[campaigngroupid];
        }

    }
}
?>
</select>
</td></tr><tr><td colspan=2>
<INPUT TYPE="Submit" VALUE="Delete Campaign"></td></tr></table>
</form>
