<link rel="stylesheet" type="text/css" href="css/stylelogin.css">
<link rel="stylesheet" type="text/css" href="css/default.css">
<script type="text/javascript" src="/ajax/picker.js"></script>

<?
echo "<FONT FACE=\"ARIAL\"><BR><BR><BR>";
?>

<FORM ACTION="login.php" METHOD="POST">
    <CENTER>
        <table align="center" width="300" border="0" cellpadding="0" cellspacing="2">
            <TR><TD COLSPAN=2><CENTER><IMG SRC="logo.png"></TD></TR>
        </table>
        <BR><BR>
        <table class="tborder" align="center" width="300" border="0" cellpadding="0" cellspacing="2">
            <TR><TD COLSPAN=2><CENTER>Please enter your username and password<BR><BR></TD></TR>
<?
if (isset($_GET[error])){
    echo "<TR><TD COLSPAN=2><CENTER><B><FONT COLOR=\"RED\">".$_GET[error]."</FONT></B></CENTER></TD></TR> ";
}

?>

            <TR><TD>UserName:</TD><TD><INPUT class="input30" TYPE="TEXT" NAME="user"></TD></TR>
            <TR><TD>Password:</TD><TD><INPUT class="input30"  TYPE="PASSWORD" NAME="pass"></TD></TR>
            <TR><TD></TD><TD><INPUT TYPE="SUBMIT" VALUE="Login"></TD></TR>
        </TABLE>
    </CENTER>
</FORM>
<?
require "footer.php";
?>
