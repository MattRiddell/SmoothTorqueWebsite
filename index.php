<?
require "header.php";
echo "<FONT FACE=\"ARIAL\">";
?>

<FORM ACTION="login.php" METHOD="POST">
    <CENTER>
       <?/* <table class="tborder" align="center" width="270" border="0" cellpadding="0" cellspacing="2">*/?>
       <br /><table align="center" cellpadding="0" cellspacing="0">
            <TR><TD COLSPAN=2><CENTER><IMG SRC="/images/logo2.png"></TD></TR>
        </table>
<br />	
Welcome to the SmoothTorque Hosted Predictive Dialing Platform.<br />
<br />
If you would like to see a demonstration of the capabilities <br />of this platform, please contact Ian Lamb by emailing
<a href="mailto:sales@venturevoip.com">Sales</a>.
        <br /><br />
        <table background="/images/sdbox.png" align="center" width="300" height="200" cellpadding="0" cellspacing="0">
            <tr><td>
<?
if (isset($_GET[error])){
    echo "<CENTER><B><FONT COLOR=\"RED\">".$_GET[error]."</FONT></B></CENTER>";
}

?>

                UserName:<br />
                <br />
                <INPUT class="input130" TYPE="TEXT" NAME="user"><br /><br />
                Password:<br />
                <br />
                <INPUT class="input130"  TYPE="PASSWORD" NAME="pass"><br /><br />
                <INPUT TYPE="SUBMIT" VALUE="Login">
            </TD></TR>
        </TABLE>
    </CENTER>
</FORM>
<?
require "footer.php";
?>
