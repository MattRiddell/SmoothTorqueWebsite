<?
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);

if (isset($_POST[name])){
    $id=$_POST[id];
    $name=$_POST[name];
    $username=$_POST[username];
    $password=$_POST[password];
    $address=$_POST[address];
    $sql="insert  into servers (address,username,password,name) values".
         "('$address', '$username', '$password','$name')";
    $result=mysql_query($sql, $link) or die (mysql_error());;
    header("Location: servers.php");
    exit;
}
//require "header_campaign.php";
$pagenum="7";
require "header.php";
require "header_server.php";

?>

<FORM ACTION="addserver.php" METHOD="POST">
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2">
<?
?>
<TR><TD CLASS="thead">Asterisk Server Name</TD><TD>
<INPUT TYPE="TEXT" NAME="name" VALUE="<?echo $row[name];?>" size="60">
</TD>
</TR>

<TR><TD CLASS="thead">Asterisk Server Address</TD><TD>
<INPUT TYPE="TEXT" NAME="address" VALUE="<?echo $row[address];?>" size="60">
</TD>
</TR>

<TR><TD CLASS="thead">Asterisk Server Username</TD><TD>
<INPUT TYPE="TEXT" NAME="username" VALUE="<?echo $row[username];?>" size="60">
</TD>
</TR>

<TR><TD CLASS="thead">Asterisk Server Password</TD><TD>
<INPUT TYPE="TEXT" NAME="password" VALUE="<?echo $row[password];?>" size="60">
</TD>
</TR>



<TR><TD COLSPAN=2 ALIGN="RIGHT">
<INPUT TYPE="SUBMIT" VALUE="Add Server">
</TD>
</TR>
<?
?>

</TABLE>
</FORM>
<?
require "footer.php";
?>
