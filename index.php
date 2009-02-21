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
/* site. */
require "header.php";

// Read the main config file
$config_file = "/stweb.conf";

// A comment in the above file is defined here
$comment = "#";

// Open the config file
$fp = fopen($config_file, "r");

// Loop through the file
while (!feof($fp)) {
  // Get a line from the file
  $line = trim(fgets($fp));

  /* if line exists and is not started by the comment defined above
   * By default a comment is defined as a line starting with #
   */
  if ($line && substr($line,0,strlen($comment))!=$comment) {
    // Get out the pieces of the line separated by the = sign
    $pieces = explode("=", $line);

    // The option is the piece before the =
    $option = trim($pieces[0]);

    // The value is the piece after the =
    $value = trim($pieces[1]);

    // Now set the value in the overall array
    $config_values[$option] = $value;
  }
}

// Close the file
fclose($fp);

// Set some defaults that will be required for the front page
if ($config_values['MAIN_PAGE_USERNAME'] == "") {
    $config_values['MAIN_PAGE_USERNAME'] = "Username";
}

if ($config_values['MAIN_PAGE_PASSWORD'] == "") {
    $config_values['MAIN_PAGE_PASSWORD'] = "Password";
}

if ($config_values['MAIN_PAGE_LOGIN'] == "") {
    $config_values['MAIN_PAGE_LOGIN'] = "Login";
}

if ($config_values['TITLE'] == "") {
    $config_values['TITLE'] = "SmoothTorque Predictive Dialing Platform";
}

if ($config_values['TEXT'] == "") {
    $config_values['TEXT'] = "For further information please email sales@venturevoip.com";
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
                <INPUT class="input130" TYPE="TEXT" NAME="user"><br />
                <?echo $config_values['MAIN_PAGE_PASSWORD'];?>:<br />
                <INPUT class="input130"  TYPE="PASSWORD" NAME="pass"><br /><br />
<?if (sizeof($config_values_array) > 0) {
    echo 'Language:<br />';
    //echo "Translations Available";
    echo '<select name="language">';
//    echo '<option value="en">English</option>';
    foreach ($config_values_array as $lang=>$translations) {

        echo '<option value="'.$lang.'">'.$config_values_array[$lang][LANGUAGE].'</option>';
    }
    echo '</select><br /><br />';
}?>
                <INPUT TYPE="SUBMIT" VALUE="<?echo $config_values['MAIN_PAGE_LOGIN'];?>">
            </TD></TR>
        </TABLE>
    </CENTER>
</FORM>
<?
require "footer.php";
?>
