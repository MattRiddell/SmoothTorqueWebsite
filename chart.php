<?php
$pagenum="1";

include "charts.php";

require_once "PHPTelnet.php";




if (!isset($_GET[id])){
//require "header.php";

?>
<form action="chart.php" method="get">
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2"><TR>
    <TD>Select Campaign:</TD><TD>    <select name="id">

<?
require_once "PHPTelnet.php";

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
//        echo $result."<BR>";
        if ($groupid==trim($row[campaigngroupid])){
            echo "<option value=\"$row[id]\">substr($row[name],0,22)</option>
";
        } else {
//            echo $groupid."!=".$row[campaigngroupid];
        }

    }
}
?>
</select>
</td></tr><tr><td colspan=2>
<input type="submit" value="Monitor Campaign">
</td></tr></table>
</form>
<?} else {
//require "header.php";
?>
<?
//include charts.php to access the InsertChart function

$id=$_GET[id];
if ($id>0){
echo InsertChart ( "charts.swf", "charts_library", "sample.php?id=".$id, 1000, 548, "ffffff", false );
}


?>

</BODY>
</HTML>
<?}?>
