<?
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);

if (isset($_POST[name])){
    $id=$_POST[id];
    $name=$_POST[name];
    $username=$_POST[username];
    $password=$_POST[password];
    $address=$_POST[address];
    $sql="update servers  set address='$address',username='$username',password='$password',name='$name' where id=$id";
    $result=mysql_query($sql, $link) or die (mysql_error());;
    header("Location: servers.php");
    exit;
}
//require "header_campaign.php";
$pagenum="7";
require "header.php";
require "header_server.php";

$sql = "select * from servers where id=".$_GET[id];
$result=mysql_query($sql,$link);
$row=mysql_fetch_assoc($result);
?>

<FORM ACTION="editserver.php" METHOD="POST">
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2">
<?
?>
<TR><TD CLASS="thead">Asterisk Server Name</TD><TD>
<INPUT TYPE="TEXT" NAME="name" VALUE="<?echo $row[name];?>" size="60">
<INPUT TYPE="HIDDEN" NAME="id" VALUE="<?echo $_GET[id];?>">
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
<INPUT TYPE="password" NAME="password" VALUE="xxxxxxxxxxxx" size="60">
</TD>
</TR>



<TR><TD COLSPAN=2 ALIGN="RIGHT">
<INPUT TYPE="SUBMIT" VALUE="Save Server">
</TD>
</TR>
<?
?>

</TABLE>
</FORM>
<?
require "footer.php";
?>
