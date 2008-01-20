<?
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);

if (isset($_POST[name])){

$_POST = array_map(mysql_real_escape_string,$_POST);

$description=$_POST[description];
$username=$_POST[username];
$password=sha1($_POST[password]);
$address1=$_POST[address1];
$address2=$_POST[address2];
$city=$_POST[city];
$country=$_POST[country];
$phone=$_POST[phone];
$fax=$_POST[fax];
$email=$_POST[email];
$website=$_POST[website];
$security=$_POST[security];
$company=$_POST[name];
$trunkid=$_POST[trunkid];

    $sql="update campaigngroup set name='$company',description='$description' where id=".$_POST[campaigngroupid];
//    echo $sql;
    $result=mysql_query($sql, $link) or die (mysql_error());;
  //  $insertedID = mysql_insert_id();

    $sql="update customer set username='$username',address1='$address1',address2='$address2',
    city='$city',country='$country',phone='$phone',fax='$fax',email='$email',website='$website',
    security='$security',company='$company', trunkid='$trunkid' WHERE id=".$_POST[id];

    //echo $sql;
    $result=mysql_query($sql, $link) or die (mysql_error());;



    include("customers.php");
    exit;
}
require "header.php";
Require "header_customer.php";

$sql = 'SELECT * FROM customer WHERE id='.$_GET[id];
$result=mysql_query($sql, $link) or die (mysql_error());;
while ($row = mysql_fetch_assoc($result)) {

$sql2 = 'SELECT * FROM campaigngroup WHERE id='.$row[campaigngroupid];
$result2=mysql_query($sql2, $link) or die (mysql_error());;

$row2 = mysql_fetch_assoc($result2);

?>

<FORM ACTION="editcustomer.php" METHOD="POST">
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2">
<?
?>
<TR><TD CLASS="thead">Customer Name</TD><TD>
<INPUT TYPE="HIDDEN" NAME="id" VALUE="<?echo $_GET[id];?>">
<INPUT TYPE="HIDDEN" NAME="campaigngroupid" VALUE="<?echo $row[campaigngroupid];?>">

<INPUT TYPE="TEXT" NAME="name" VALUE="<?echo $row[company];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">Customer Details</TD><TD>
<INPUT TYPE="TEXT" NAME="description" VALUE="<?echo $row2[description];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">Username</TD><TD>
<INPUT TYPE="TEXT" NAME="username" VALUE="<?echo $row[username];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">Address Line 1</TD><TD>
<INPUT TYPE="TEXT" NAME="address1" VALUE="<?echo $row[address1];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">Address Line 2</TD><TD>
<INPUT TYPE="TEXT" NAME="address2" VALUE="<?echo $row[address2];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">City</TD><TD>
<INPUT TYPE="TEXT" NAME="city" VALUE="<?echo $row[city];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">Country</TD><TD>
<INPUT TYPE="TEXT" NAME="country" VALUE="<?echo $row[country];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">Phone</TD><TD>
<INPUT TYPE="TEXT" NAME="phone" VALUE="<?echo $row[phone];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">Fax</TD><TD>
<INPUT TYPE="TEXT" NAME="fax" VALUE="<?echo $row[fax];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">Email</TD><TD>
<INPUT TYPE="TEXT" NAME="email" VALUE="<?echo $row[email];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">Website</TD><TD>
<INPUT TYPE="TEXT" NAME="website" VALUE="<?echo $row[website];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">Customer Type</TD><TD>
<SELECT NAME="security">
<OPTION VALUE="0" <?if ($row[security]==0){echo "SELECTED";}?>>Normal Customer</OPTION>
<OPTION VALUE="100" <?if ($row[security]==100){echo "SELECTED";}?>>Administrator</OPTION>
</SELECT>
</TD>
</TR><TR><TD CLASS="thead">Queue Name</TD><TD>
<SELECT NAME="astqueuename">
<?
$resultss=mysql_query("SELECT name from queue_table",$link);
while ($rowx = mysql_fetch_assoc($resultss)) {
//    echo ."<BR>";
    ?>
<OPTION VALUE="<?echo $rowx[name];?>" <?if ($row[astqueuename]==$rowx[name]){echo "SELECTED";}?>><?echo $rowx[name];?></OPTION>
<?
}
?>
</SELECT>
<a href="queues.php"><IMG SRC="/images/pencil.png" border="0"></a>
</TD>
</TR><TR><TD CLASS="thead">Trunk</TD><TD>
<SELECT NAME="trunkid">
<?
$resultss=mysql_query("SELECT name,id from trunk",$link);
?>
<OPTION VALUE="-1" <?if ($row[trunkid]==-1){echo "SELECTED";}?>>Default</OPTION>
<?
while ($rowx = mysql_fetch_assoc($resultss)) {
//    echo ."<BR>";
    ?>
<OPTION VALUE="<?echo $rowx[id];?>" <?if ($row[trunkid]==$rowx[id]){echo "SELECTED";}?>><?echo $rowx[name];?></OPTION>
<?
}
?>
</SELECT>
<a href="trunk.php"><IMG SRC="/images/pencil.png" border="0"></a>
</TD>
</TR>
</TR><TR><TD COLSPAN=2 ALIGN="RIGHT">
<INPUT TYPE="SUBMIT" VALUE="Save Customer">
</TD>
</TR>
<?
?>

</TABLE>
</FORM>
<?
}
require "footer.php";
?>
