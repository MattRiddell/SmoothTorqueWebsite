<?php

/* SmoothTorque Website
 * ====================
 *
 * Feel free to modify anything in here you need to.
 *
 * In order to update the website to the latest version type:
 *
 * svn up
 *
 * This will download the latest version.  When you login after updating
 * the website, any changes required to the database will be performed.
 */

/* Read the header file.  This is done on pretty much every page of the
/* site. Because we may have no cookie set, we should temporarily set it*/

require "admin/db_config.php";
$current_directory = dirname(__FILE__);
require "/".$current_directory."/functions/functions.php";
$url = $_SERVER[SERVER_NAME];
$sql = "SELECT * FROM web_config WHERE LANG=\"en\" AND url = ".sanitize($url);
$result_url = mysql_query($sql);
if (mysql_num_rows($result_url) == 0) {
    $url = "default";
}
setcookie("url",$url,time()+6000);
require "header.php";

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
<?box_start();?>
            <table align="center" width="300" height="200" cellpadding="0" cellspacing="0">
            <tr><td>
<?
if (isset($_GET[error])){
    echo "<CENTER><B><FONT COLOR=\"RED\">".$_GET[error]."</FONT></B></CENTER>";
}

?>

                <?echo $config_values['MAIN_PAGE_USERNAME'];?>:<br />
                <INPUT class="input130" TYPE="TEXT" NAME="user"><br />
                <?echo $config_values['MAIN_PAGE_PASSWORD'];?>:<br />
                <INPUT class="input130"  TYPE="PASSWORD" NAME="pass"><br /><br />
<?
$result = mysql_query("SELECT LANG, language FROM web_config WHERE url = ".sanitize($url));
if (mysql_num_rows($result) == 0) {
    $result = mysql_query("SELECT LANG, language FROM web_config WHERE url = 'default'");
}
if (mysql_num_rows($result) > 0) {
    echo 'Language:<br />';
    //echo "Translations Available";
    echo '<select name="language">';
//    echo '<option value="en">English</option>';
    while ($row = mysql_fetch_assoc($result)) {

        echo '<option value="'.$row[LANG].'">'.$row[language].'</option>';
    }
    echo '</select><br /><br />';
}?>
                <INPUT TYPE="SUBMIT" VALUE="<?echo $config_values['MAIN_PAGE_LOGIN'];?>">
            </TD></TR>
        </TABLE>
    </CENTER>
</FORM>
<?box_end();?>

<?
require "footer.php";
?>
