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
/* site. Because we may have no cookie set, we should temporarily set it */

require "admin/db_config.php";
$current_directory = dirname(__FILE__);
require "/".$current_directory."/functions/functions.php";
$url = $_SERVER['SERVER_NAME'];
$sql = "SELECT * FROM web_config WHERE LANG=\"en\" AND url = ".sanitize($url);
$result_url = mysql_query($sql);
if (@mysql_num_rows($result_url) == 0) {
    $url = "default";
}
setcookie("url", $url, time() + 6000);
$_COOKIE['url'] = $url;
require "header.php";

$sql = 'SELECT value FROM config WHERE parameter=\'show_front_page_title\'';
$result = mysql_query($sql, $link) or die (mysql_error());
$show_front_page_title = 1;
if (mysql_num_rows($result) > 0) {
    $show_front_page_title = mysql_result($result, 0, 'value');
}

$sql = 'SELECT value FROM config WHERE parameter=\'show_front_page_text\'';
$result = mysql_query($sql, $link) or die (mysql_error());
$show_front_page_text = 1;
if (mysql_num_rows($result) > 0) {
    $show_front_page_text = mysql_result($result, 0, 'value');
}

?>
<div class="container">
    <br/><br/>
    <div class="row">
        <div class='col-md-3'></div>
        <div class="col-md-6">

            <FORM ACTION="login.php<? if (isset($_GET['redirect'])) {
                echo '?redirect='.$_GET['redirect'];
            } ?>" METHOD="POST" class="form">

                <img src="./<? echo $config_values['LOGO']; ?>" class="img-responsive img-rounded"><br/>

                <br/>
                <div class="jumbotron" style="">
                    <?
                    if ($show_front_page_title == "1") {
                        echo "<h3>".stripslashes($config_values['TITLE'])."</h3>";

                    }
                    if ($show_front_page_text == "1") {
                        echo $config_values['TEXT'];
                    }

                    if (isset($_GET['error'])) {
                        echo "<h4><B><FONT COLOR=\"RED\">".$_GET[error]."</FONT></B></h4>";
                    }
                    ?>


                    <div class="form-group">
                        <label for="user"><?= $config_values['MAIN_PAGE_USERNAME']; ?></label>
                        <input type="text" class="form-control" id="user" name="user" placeholder="<?= $config_values['MAIN_PAGE_USERNAME']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="pass"><?= $config_values['MAIN_PAGE_PASSWORD']; ?></label>
                        <input type="password" class="form-control" id="pass" name="pass" placeholder="<?= $config_values['MAIN_PAGE_PASSWORD']; ?>">
                    </div>
                    <?
                    $result = mysql_query("SELECT LANG, language FROM web_config WHERE url = ".sanitize($url));
                    if (mysql_num_rows($result) == 0) {
                        $result = mysql_query("SELECT LANG, language FROM web_config WHERE url = 'default'");
                    }
                    if (mysql_num_rows($result) > 0) {
                    ?>
                    <div class="form-group">
                        <label for="lang">Language:</label>
                        <?
                        echo '<select id="lang" name="language" class="form-control">';
                        //    echo '<option value="en">English</option>';
                        while ($row = mysql_fetch_assoc($result)) {

                            echo '<option value="'.$row[LANG].'">'.$row[language].'</option>';
                        }
                        echo '</select></div>';
                        } ?>
                        <INPUT class="btn btn-primary" TYPE="SUBMIT" VALUE="<? echo $config_values['MAIN_PAGE_LOGIN']; ?>">

            </FORM>

        </div>

    </div>
    <div class='col-md-3'></div>
</div>

<?
require "footer.php";
?>
