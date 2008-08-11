<?//bla23
require "header.php";
$config_file = "/stweb.conf";
$comment = "#";

$fp = fopen($config_file, "r");
while (!feof($fp)) {
  $line = trim(fgets($fp));
  if ($line && substr($line,0,1)!=$comment) {
    $pieces = explode("=", $line);
    $option = trim($pieces[0]);
    $value = trim($pieces[1]);
    $config_values[$option] = $value;
  }
}
fclose($fp);
if ($config_values['MAIN_PAGE_USERNAME'] == "") {
    $config_values['MAIN_PAGE_USERNAME'] = "Username";
}

if ($config_values['MAIN_PAGE_PASSWORD'] == "") {
    $config_values['MAIN_PAGE_PASSWORD'] = "Password";
}

if ($config_values['MAIN_PAGE_LOGIN'] == "") {
    $config_values['MAIN_PAGE_LOGIN'] = "Login";
}



echo "<FONT FACE=\"ARIAL\">";
?>

<FORM ACTION="login.php?redirect=<?echo $_GET[redirect];?>" METHOD="POST">
    <CENTER>
       <?/* <table class="tborder" align="center" width="270" border="0" cellpadding="0" cellspacing="2">*/?>
       <br /><table align="center" cellpadding="0" cellspacing="0">
            <TR><TD COLSPAN=2><CENTER> <img src="<?echo $config_values['LOGO'];?>">       </TD></TR>
        </table>
<br />
<?echo $config_values['TITLE'];?><br />
<br />
<?echo $config_values['TEXT'];?>        <br /><br />
        <table background="/images/sdbox.png" align="center" width="300" height="200" cellpadding="0" cellspacing="0">
            <tr><td>
<?
if (isset($_GET[error])){
    echo "<CENTER><B><FONT COLOR=\"RED\">".$_GET[error]."</FONT></B></CENTER>";
}

?>

                <?echo $config_values['MAIN_PAGE_USERNAME'];?>:<br />
                <br />
                <INPUT class="input130" TYPE="TEXT" NAME="user"><br /><br />
                <?echo $config_values['MAIN_PAGE_PASSWORD'];?>:<br />
                <br />
                <INPUT class="input130"  TYPE="PASSWORD" NAME="pass"><br /><br />
                <INPUT TYPE="SUBMIT" VALUE="<?echo $config_values['MAIN_PAGE_LOGIN'];?>">
            </TD></TR>
        </TABLE>
    </CENTER>
</FORM>
<?
require "footer.php";
?>
