<?
require "header.php";
require "header_numbers.php";

include "admin/db_config.php";
mysql_select_db("SineDialer", $link);

$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result = mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid = mysql_result($result, 0, 'campaigngroupid');

if (1) {
if (isset($_POST[start])){
?>
<br/><br/><br/><br/><br/>
<table background="images/sdbox.png" align="center" width="300" height="200" cellpadding="0" cellspacing="0" class="dragme22">
    <tr>
        <td>
            <div id="hideShow">
                Please Wait, saving Phone Numbers<br/>
                <br/>
                <img src="images/ajax-loader.gif"><br/>
                <br/>
                This may take some time...
            </div>                  <? /*for ($i=$_POST[start];$i<=$_POST[end];$i++){       */
            $split = explode("\n", $_POST[start]);
            foreach ($split as $number) {
                //$myarray[$count]=$i;
                if (strlen($number > 0)) {
                    $number = str_replace("\r", "", $number);
                    $number = str_replace(" ", "", $number);
                    $number = str_replace("-", "", $number);
                    $number = str_replace("(", "", $number);
                    $number = str_replace(")", "", $number);
                    $sql = "INSERT IGNORE INTO dncnumber (campaignid,phonenumber,status,type) VALUES ($campaigngroupid,'$number','new',0)";
//        echo $sql;
                    $result = mysql_query($sql, $link) or die (mysql_error());;
                    echo "<!-- . -->";
                    flush();
                }
            }
            echo "</div><img src=\"images/tick.gif\">Completed Saving";
            ?>
            <script language="javascript">
                function delayer() {
                    window.location = "dncnumbers.php"
                }
                setTimeout('delayer()', 1000);
            </script>
            <?
            echo "<BR></TD></TR>
        </TABLE>";
            } else {
                box_start();
                ?>

                <FORM ACTION="adddncnumbers.php" METHOD="POST">
                    <h3>Enter DNC Numbers (One Per Line)</h3>

                    <TEXTAREA class="form-control" NAME="start" COLS="20" ROWS="20"></TEXTAREA><br/>

                    <INPUT class="btn btn-primary" TYPE="SUBMIT" VALUE="Add DNC Numbers">

                </FORM><?
                box_end();
            }
            }
            require "footer.php";
            ?>
