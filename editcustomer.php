<?
$pagenum=6;

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

//    $sql="update campaigngroup set name='$company',description='$description' where id=".$_POST[campaigngroupid];
//    echo $sql;
//    $result=mysql_query($sql, $link) or die (mysql_error());;
  //  $insertedID = mysql_insert_id();

    $sql="update customer set username='$username',address1='$address1',address2='$address2',
    city='$city',country='$country',phone='$phone',fax='$fax',email='$email',website='$website',
    security='$security',company='$company' WHERE id=".$_POST[id];

    //echo $sql;
    //$result=mysql_query($sql, $link) or die (mysql_error());;
    require_once "PHPTelnet.php";

$telnet = new PHPTelnet();
$result = $telnet->Connect();
$telnet->DoCommand('sql', $result);
flush();
$telnet->DoCommand($sql, $result);
//echo "".$result."<BR>";
flush();
$telnet->Disconnect();


    include("customers.php");
    exit;
}
require "header.php";
//require "header_customer.php";

require_once "PHPTelnet.php";

$telnet = new PHPTelnet();

// if the first argument to Connect is blank,
// PHPTelnet will connect to the local host via 127.0.0.1
$telnet = new PHPTelnet();
$result = $telnet->Connect();
//echo "CONNECTION REQUEST: ".$result."<BR>";
$x=10;
while (substr(trim($result),0,3)!="END") {
    $telnet->DoCommand('getallc', $result);
    if (substr(trim($result),0,3)!="END"){
        $pieces = explode("\n",$result);
        $row[username]= $pieces[0];
        $row[address1]= $pieces[2];
        $row[address2]= $pieces[3];
        $row[city]= $pieces[4];
        $row[country]= $pieces[5];
        $row[phone]= $pieces[6];
        $row[email]= $pieces[7];
        $row[fax]= $pieces[8];
        $row[website]= $pieces[9];
        $row[security]= $pieces[10];
        $row[company]= $pieces[11];
        $row[id]= $pieces[12];

        /*for ($i=0;$i<sizeof($pieces);$i++){
            echo "[$i]:$pieces[$i]<BR>";
        }*/
        if ($row[id]==$_GET[id]){



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
</TR><TR><TD CLASS="thead">Security Level (Either 0 or 100)</TD><TD>
<INPUT TYPE="TEXT" NAME="security" VALUE="<?echo $row[security];?>" size="60">
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
}
}
require "footer.php";
?>
