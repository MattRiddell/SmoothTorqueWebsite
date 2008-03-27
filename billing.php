<?
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);

$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);

$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');

if (isset($_POST[name])){
    $name=$_POST[name];
    $dialstring=$_POST[dialstring];
    $maxcps=$_POST[maxcps];
    $maxchans=$_POST[maxchans];
    $sql="update trunk ".
         "set name='$name', dialstring='$dialstring', maxcps='$maxcps', maxchans='$maxchans' where id=".$_POST[id];
    $result=mysql_query($sql, $link) or die (mysql_error());;
/*    $SMDB2->executeUpdate($sql);*/


    header("Location: /trunks.php");
    exit;
}

//require "header_campaign.php";
$pagenum="2";
require "header.php";
//require "header_trunk.php";
$campaigngroupid=$groupid;
$sql = 'SELECT * FROM billing WHERE customerid='.$_GET[id];
$result=mysql_query($sql, $link) or die (mysql_error());;
if (mysql_num_rows($result) == 0) {
    $sql = 'SELECT * FROM customer WHERE id='.$_GET[id];
    $result=mysql_query($sql, $link) or die (mysql_error());;
    $accountcode = "stl-".mysql_result($result,0,"username");
    ?>
    <br />
    There is no billing information for this customer yet<br />
    <br />
    Would you like to create a record?<br />
    <br />
    <a href="addbilling.php?accountcode=<?echo $accountcode."&id=".$_GET[id];?>">Yes please</a><br /><br />
    <a href="customers.php">No thanks</a>
    <?
} else {
while ($row = mysql_fetch_assoc($result)) {
?>

<FORM ACTION="billing.php" METHOD="POST">
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2">
<?
?>
<TR><TD CLASS="thead">AccountCode</TD><TD>
<?echo $row[accountcode];?>
</TD>
</TR>

<TR><TD CLASS="thead">Price Per Minute</TD><TD>
<INPUT TYPE="HIDDEN" NAME="customerid" VALUE="<?echo $groupid;?>">
<INPUT TYPE="HIDDEN" NAME="id" VALUE="<?echo $row[customerid];?>">
<INPUT TYPE="TEXT" NAME="priceperminute" VALUE="<?echo $row[priceperminute];?>" size="60">
</TD>
</TR>

<TR><TD CLASS="thead">First Period</TD><TD>
<INPUT TYPE="TEXT" NAME="firstperiod" VALUE="<?echo $row[firstperiod];?>" size="60">
</TD>
</TR>

<TR><TD CLASS="thead">Increment</TD><TD>
<INPUT TYPE="TEXT" NAME="increment" VALUE="<?echo $row[increment];?>" size="60">
</TD>
</TR>

<TR><TD CLASS="thead">Price Per Call</TD><TD>
<INPUT TYPE="TEXT" NAME="pricepercall" VALUE="<?echo $row[pricepercall];?>" size="60">
</TD>
</TR>

<TR><TD CLASS="thead">Price Per Connected Call</TD><TD>
<INPUT TYPE="TEXT" NAME="priceperconnectedcall" VALUE="<?echo $row[priceperconnectedcall];?>" size="60">
</TD>
</TR>

<TR><TD CLASS="thead">Price Per Press 1</TD><TD>
<INPUT TYPE="TEXT" NAME="priceperpress1" VALUE="<?echo $row[priceperpress1];?>" size="60">
</TD>
</TR>

<TR><TD CLASS="thead">Credit</TD><TD>
<INPUT TYPE="TEXT" NAME="credit" VALUE="<?echo $row[credit];?>" size="60">
</TD>
</TR>

<TR><TD COLSPAN=2 ALIGN="RIGHT">
<INPUT TYPE="SUBMIT" VALUE="Save Billing Information">
</TD>
</TR>
<?
}
}
?>

</TABLE>
</FORM>
<?
require "footer.php";
?>
