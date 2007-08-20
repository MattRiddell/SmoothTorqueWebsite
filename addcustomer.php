<?
$pagenum=5;
if (isset($_POST[name])){
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

    $sql="INSERT INTO customer (username,password,campaigngroupid,address1,address2,city,
    country,phone,fax,email,website,security,company)
    VALUES ('$username','$password','0','$address1','$address2','$city',
    '$country','$phone','$fax','$email','$website','$security','$company')";

    require_once "PHPTelnet.php";
    $telnet = new PHPTelnet();
    $result = $telnet->Connect();
    $telnet->DoCommand('sql', $result);
    $telnet->DoCommand($sql, $result);
    $telnet->Disconnect();
    include("customers.php");
    exit;
}
require "header.php";
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
</TR><TR><TD CLASS="thead">Security Level</TD><TD>
<SELECT NAME="security">
<OPTION VALUE="10">User</OPTION>
<OPTION VALUE="100">Admin</OPTION>
</SELECT>
<?
/*<INPUT TYPE="TEXT" NAME="security" VALUE="<?echo $row[security];?>" size="60">*/
?>
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
