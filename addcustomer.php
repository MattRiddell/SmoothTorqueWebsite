<?
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);

if (isset($_POST[name])){
$description=mysql_real_escape_string($_POST[description]);
$username=mysql_real_escape_string($_POST[username]);
$password=sha1($_POST[password]);
$address1=mysql_real_escape_string($_POST[address1]);
$address2=mysql_real_escape_string($_POST[address2]);
$city=mysql_real_escape_string($_POST[city]);
$country=mysql_real_escape_string($_POST[country]);
$phone=mysql_real_escape_string($_POST[phone]);
$fax=mysql_real_escape_string($_POST[fax]);
$email=mysql_real_escape_string($_POST[email]);
$website=mysql_real_escape_string($_POST[website]);
$security=mysql_real_escape_string($_POST[security]);
$company=mysql_real_escape_string($_POST[name]);

    $sql="INSERT INTO campaigngroup (name,description) VALUES ('$company','$description')";
//    echo $sql;
    $result=mysql_query($sql, $link) or die (mysql_error());;
    $insertedID = mysql_insert_id();

    $sql="INSERT INTO customer (username,password,campaigngroupid,address1,address2,city,
    country,phone,fax,email,website,security,company)
    VALUES ('$username','$password','$insertedID','$address1','$address2','$city',
    '$country','$phone','$fax','$email','$website','$security','$company')";

//    echo $sql;
    $result=mysql_query($sql, $link) or die (mysql_error());;



    include("customers.php");
    exit;
}
require "header.php";
require "header_customer.php";
?>

<FORM ACTION="addcustomer.php" METHOD="POST">
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2">
<?
?>
<TR><TD CLASS="thead">Customer Name</TD><TD>
<INPUT TYPE="HIDDEN" NAME="id" VALUE="<?echo $_GET[id];?>">
<INPUT TYPE="TEXT" NAME="name" VALUE="<?echo $row[name];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">Customer Details</TD><TD>
<INPUT TYPE="TEXT" NAME="description" VALUE="<?echo $row[description];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">Username</TD><TD>
<INPUT TYPE="TEXT" NAME="username" VALUE="<?echo $row[username];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">Password</TD><TD>
<INPUT TYPE="PASSWORD" NAME="password" VALUE="<?echo $row[password];?>" size="60">
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
</TR>
</TR><TR><TD COLSPAN=2 ALIGN="RIGHT">
<INPUT TYPE="SUBMIT" VALUE="Add Customer">
</TD>
</TR>
<?
?>

</TABLE>
</FORM>
<?
require "footer.php";
?>
