<?
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);

$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');

if (isset($_POST[new1])){
	$_POST = array_map(mysql_real_escape_string,$_POST);
    $id=$_POST[id];
    $new1=$_POST[new1];
    $new2=$_POST[new2];
    if ($new1 == $new2) {
    $sql = "UPDATE customer set password='".sha1($new1)."' where id=$id";
    $result=mysql_query($sql, $link) or die (mysql_error());;


    header("Location: customers.php");
    exit;
    } else {
    $error = "Passwords do not match!";
    }
}
//require "header_campaign.php";
$pagenum="2";
require "header.php";
require "header_customer.php";

?>

<FORM ACTION="changepassword.php" METHOD="POST">
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2">
<?
if (isset($error)){
?>
<TR><TD CLASS="thead" COLSPAN="2"><b><font color="white"><?echo $error;?></font></b></TD></TR>
<?
}
?>
<TR><TD CLASS="thead">New Password</TD><TD>
<INPUT TYPE="HIDDEN" NAME="id" VALUE="<?echo $_GET[id];?>">
<INPUT TYPE="PASSWORD" NAME="new1" size="60">
</TD>
</TR>

<TR><TD CLASS="thead">Repeat</TD><TD>
<INPUT TYPE="PASSWORD" NAME="new2" size="60">
</TD>
</TR>



<TR><TD COLSPAN=2 ALIGN="RIGHT">
<INPUT TYPE="SUBMIT" VALUE="Save Password Change">
</TD>
</TR>
<?
?>

</TABLE>
</FORM>
<?
require "footer.php";
?>
