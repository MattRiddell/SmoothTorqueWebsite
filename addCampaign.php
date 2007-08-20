<?
if (isset($_POST[name])){
    require_once "PHPTelnet.php";

$telnet = new PHPTelnet();
$result = $telnet->Connect();
$telnet->DoCommand('selectcg', $result);
$telnet->DoCommand($_COOKIE[user], $result);
if (substr(trim($result),0,7)=="GroupID") {
    $campaigngroupid=substr(trim($result),8);
}
$telnet->Disconnect();

    $id=$_POST[id];
    $name=$_POST[name];
    $description=$_POST[description];
    $messageid=$_POST[messageid];
    $messageid2=$_POST[messageid2];
    $messageid3=$_POST[messageid3];
    $sql="INSERT INTO campaign (groupid,name,description,messageid,messageid2,messageid3) VALUES ('$campaigngroupid','$name', '$description', '$messageid','$messageid2','$messageid3')";
//    echo $sql;

        require_once "PHPTelnet.php";
    $telnet = new PHPTelnet();
$result = $telnet->Connect();
$telnet->DoCommand('sql', $result);
//flush();
$telnet->DoCommand($sql, $result);
//echo "".$result."<BR>";
//flush();
$telnet->Disconnect();


    header("Location: campaigns.php");
    exit;
}
//require "header_campaign.php";
$pagenum="1";
require "header.php";
$campaigngroupid=$groupid;

?>

<FORM ACTION="addCampaign.php" METHOD="POST">
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2">
<?
?>
<TR><TD CLASS="thead">Campaign Name</TD><TD>
<INPUT TYPE="HIDDEN" NAME="id" VALUE="<?echo $_GET[id];?>">
<INPUT TYPE="TEXT" NAME="name" VALUE="<?echo $row[name];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">Campaign Description</TD><TD>
<INPUT TYPE="TEXT" NAME="description" VALUE="<?echo $row[description];?>" size="60">
</TD>
</TR>
<?

/*$sql="insert  into campaignmessage (filename,name,description) values".
//     "('/var/lib/asterisk/sounds/beep.gsm', 'beep', 'a beep sound'),".
//     "('/var/lib/asterisk/sounds/demo-echotest.gsm', 'echotest', 'the echo test sound')";
//     "('/var/lib/asterisk/sounds/intro2.ulaw', 'intro2', 'BigEars Live Introduction')";
//     "('/var/lib/asterisk/sounds/answermachine.ulaw', 'answermachine', 'BigEars Answer Machine Message')";
//     "('/var/lib/asterisk/sounds/transfer.ulaw', 'transfer', 'BigEars Transfer Message')";

require_once "PHPTelnet.php";
    $telnet = new PHPTelnet();
$result = $telnet->Connect();
$telnet->DoCommand('sql', $result);
flush();
$telnet->DoCommand($sql, $result);
echo "".$result."<BR>";
flush();
$telnet->Disconnect();*/

require_once "PHPTelnet.php";

$telnet = new PHPTelnet();

// if the first argument to Connect is blank,
// PHPTelnet will connect to the local host via 127.0.0.1
$row2=$SMDB->executeQuery("SELECT * FROM campaignmessage where customer_id=".$groupid);
$count=sizeof($row2);

//$count--;


/*$count=0;
while ($row2[$count] = mysql_fetch_assoc($result)) {
    $count++;
    //echo "<OPTION VALUE=\"".$row[id]."\">".$row[name]."</OPTION>";
} */
?>

<TR><TD CLASS="thead">Live Message</TD><TD>
<SELECT name="messageid">
<?
for ($count2=0;$count2<$count;$count2++){
$selected="";
if ($row[messageid]==$row2[$count2][id]){
    $selected=" SELECTED";
}
echo "<OPTION VALUE=\"".$row2[$count2][id]."\"$selected>".$row2[$count2][description]."</OPTION>";
}
?>
</SELECT>
</TD>
</TR><TR><TD CLASS="thead">Answer Machine Message</TD><TD>
<SELECT name="messageid2">
<?
for ($count2=0;$count2<$count;$count2++){
$selected="";
if ($row[messageid2]==$row2[$count2][id]){
    $selected=" SELECTED";
}
echo "<OPTION VALUE=\"".$row2[$count2][id]."\"$selected>".$row2[$count2][description]."</OPTION>";
}
?>
</SELECT>
</TD>
</TR><TR><TD CLASS="thead">Transfer Message</TD><TD>
<SELECT name="messageid3">
<?
for ($count2=0;$count2<$count;$count2++){
$selected="";
if ($row[messageid3]==$row2[$count2][id]){
    $selected=" SELECTED";
}
echo "<OPTION VALUE=\"".$row2[$count2][id]."\"$selected>".$row2[$count2][description]."</OPTION>";
}
?>
</SELECT>
</TD>
</TR>
</TR><TR><TD COLSPAN=2 ALIGN="RIGHT">
<INPUT TYPE="SUBMIT" VALUE="Add Campaign">
</TD>
</TR>
<?
?>

</TABLE>
</FORM>
<?
require "footer.php";
?>
