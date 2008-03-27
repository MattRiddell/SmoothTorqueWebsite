<?
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);

$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);

$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');

if (isset($_POST[firstperiod])){
    $firstperiod=$_POST[firstperiod];
    $customerid = $_POST[id];
    $priceperminute = $_POST[priceperminute];
    $increment = $_POST[increment];
    $pricepercall = $_POST[pricepercall];
    $priceperconnectedcall = $_POST[priceperconnectedcall];
    $priceperpress1 = $_POST[priceperpress1];
    $credit = $_POST[credit];


    $sql="update billing ".
         "set firstperiod='$firstperiod', increment='$increment', priceperminute='$priceperminute'
         pricepercall = '$pricepercall', priceperconnectedcall='$priceperconnectedcall', priceperpress1='$priceperpress1',
         credit='$credit' where customerid=".$customerid;
    $result=mysql_query($sql, $link) or die (mysql_error());;
/*    $SMDB2->executeUpdate($sql);*/


    header("Location: /customers.php");
    exit;
}

//require "header_campaign.php";
$pagenum="2";
require "header.php";
//require "header_trunk.php";
$campaigngroupid=$groupid;
$sql = 'SELECT * FROM billing WHERE customerid='.$_GET[id];
$result=mysql_query($sql, $link);
if (mysql_error()=="Table 'SineDialer.billing' doesn't exist") {
    $sql = "CREATE TABLE `billing` (
  `customerid` int(11) unsigned NOT NULL default '0',
  `accountcode` varchar(250) NOT NULL default '',
  `priceperminute` double(10,5) default '0.00000',
  `firstperiod` int(10) unsigned default '1',
  `increment` int(10) unsigned default '1',
  `credit` double(100,10) default '0.0000000000',
  `pricepercall` double(10,5) default '0.00000',
  `priceperconnectedcall` double(10,5) default '0.00000',
  `priceperpress1` double(10,5) default '0.00000',
  PRIMARY KEY  (`customerid`,`accountcode`)
)";
$result=mysql_query($sql, $link);


}
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
