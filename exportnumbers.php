<?

//$_POST = array_map(mysql_real_escape_string,$_POST);
//$_GET = array_map(mysql_real_escape_string,$_GET);
if(isset($_POST[campaignid])){
    $campaignid=$_POST[campaignid];
} else {
    $campaignid=$_GET[campaignid];
}
if(isset($_POST[type])){
    $type=$_POST[type];
} else {
    $type=$_GET[type];
}

if (!isset($_POST[campaignid])&&!isset($_GET[campaignid])){
require "header.php";
$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');
require "header_numbers.php";
    ?>


    <br /><br /><br /><br />
<center>
<table background="/images/sdbox.png" width="300" height="200" class="dragme22">
<tr>
<td>
</td>
<td width="260">
<b>Export Numbers</b><br /><br />
From here you can chose a campaign that you would like to export the numbers from.<br /><br />
<FORM ACTION="exportnumbers.php" METHOD="POST">
    <table class="tborderdd" align="center" border="0" cellpadding="0" cellspacing="2"><TR>
    <TD>Select Campaign:</TD><TD>
        <SELECT NAME="campaignid">
        <?
        $sql = 'SELECT id,name FROM campaign WHERE groupid='.$campaigngroupid;
        $result=mysql_query($sql, $link) or die (mysql_error());;
        //$campaigngroupid=mysql_result($result,0,'campaigngroupid');
        while ($row = mysql_fetch_assoc($result)) {
            echo "<OPTION VALUE=\"".$row[id]."\">".substr($row[name],0,22)."</OPTION>";
        }
        ?>
        </SELECT>

    </TD>
    </TR><TR>
    <TD>Type:</TD><TD>
        <SELECT NAME="type">
        <OPTION VALUE="pressed1">Pressed 1</OPTION>
        <OPTION VALUE="hungup">Did Not Press 1</OPTION>
        <OPTION VALUE="answered">All Answered</OPTION>
        <OPTION VALUE="failed">Failed</OPTION>
        <OPTION VALUE="busy">Busy</OPTION>
        <OPTION VALUE="congested">Congested</OPTION>
        <OPTION VALUE="amd">Answer Machine</OPTION>
        <OPTION VALUE="timeout">No Answer</OPTION>
        <OPTION VALUE="indnc">DNC Numbers</OPTION>
        <OPTION VALUE="unknown">Unknown</OPTION>
        </SELECT>

    </TD>
    </TR><TR>
    <TD COLSPAN=2 ALIGN="CENTER"><br />
    <INPUT TYPE="SUBMIT" VALUE="Chose Types to Export">
    </TD>
    </TR></table>
    </FORM><br />
</td>
<td>
</td></tr>
</table>
</center>







    <?
    require "footer.php";

} else {
include "admin/db_config.php";
mysql_select_db("SineDialer", $link);
if (isset($_GET[campaignid])){
    $_POST[campaignid]=$_GET[campaignid];
}

header("Content-Type: application/octet-stream");
header("Content-Length: $size;");
header("Content-Disposition: attachement; filename=\"".$type." Campaign ID (".$_POST[campaignid].") Dialer Number Export (".date('l dS \of F Y h:i:s A').").csv\"");


?><?
$start=0;
if ($type == "unknown") {
    $sql = 'SELECT *, UNIX_TIMESTAMP(datetime) as newdate FROM number WHERE campaignid='.$_POST[campaignid].' and status like "unknown%"';
} else if ($type == "answered") {
    $sql = 'SELECT *, UNIX_TIMESTAMP(datetime) as newdate FROM number WHERE campaignid='.$_POST[campaignid].' and (status="amd" or status="hungup" or status="pressed1")';
} else {
    $sql = 'SELECT *, UNIX_TIMESTAMP(datetime) as newdate FROM number WHERE campaignid='.$_POST[campaignid].' and status="'.$type.'"';
} 
//echo $sql;
//exit(0);
$result=mysql_query($sql, $link) or die (mysql_error());;

while ($row = mysql_fetch_assoc($result)) {
if ($toggle){
$toggle=false;
$class=" class=\"tborder2\"";
} else {
$toggle=true;
$class=" class=\"tborderx\"";
}
?><?
$newdate = date('l dS \of F Y h:i:s A', $row["newdate"]);

echo $row[phonenumber].",".$newdate.",".$row[status]."\n";?>
<?
}

?>
<?
}
?>
