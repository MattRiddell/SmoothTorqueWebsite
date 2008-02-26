<?
require "header.php";
require "header_numbers.php";

include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);

$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');

if (!isset($_POST[campaignid])){
    ?>

    <br /><br /><br /><br />
<center>
<table background="/images/sdbox.png" width="300" height="200" class="dragme22">
<tr>
<td>
</td>
<td width="260">
Which campaign would you like to add numbers to?<br /><br />
<FORM ACTION="serverlist.php" METHOD="POST">
    <table class="tborderxxx2" align="center" border="0" cellpadding="0" cellspacing="2"><TR>
    <TD>Select Campaign:</TD><TD>
        <SELECT NAME="campaignid">
        <?
        //
        $sql = 'SELECT id,name FROM campaign WHERE groupid='.$campaigngroupid;
        $result=mysql_query($sql, $link) or die (mysql_error());;
        //$campaigngroupid=mysql_result($result,0,'campaigngroupid');
        while ($row = mysql_fetch_assoc($result)) {
            echo "<OPTION VALUE=\"".$row[id]."\">".substr($row[name],0,22)."</OPTION>";
        }
        ?>
        </SELECT>

    </TD>
    </TR><TR>
    <TD COLSPAN=2 ALIGN="CENTER">
    <br />
    <INPUT TYPE="SUBMIT" VALUE="Select Campaign">
    </TD>
    </TR></table>
    </FORM></td>
<td>
</td></tr>
</table>
</center>





    <?
} else {
 if (isset($_POST[importfrom])){?>
    <br /><br /><br /><br /><br />
 <table background="/images/sdbox.png" align="center" width="300" height="200" cellpadding="0" cellspacing="0" class="dragme22">
            <tr><td>
<div id="hideShow">
    Please Wait, saving Phone Numbers<br />
    <br />
    <img src="/images/ajax-loader.gif"><br />
    <br />
    This may take some time...
</div>                  <?/*for ($i=$_POST[start];$i<=$_POST[end];$i++){       */

/*
Import the numbers from campaign: $_POST[
*/
$sql = "SELECT phonenumber FROM number WHERE campaignid=-".$_POST[importfrom];
$result = mysql_query($sql, $link) or die(mysql_error());
for ($i = 0; $i<mysql_num_rows($result);$i++) {
    $number = mysql_result($result, $i, 'phonenumber');
//    echo "Inserting ".$number."<br />";
    $sql="INSERT IGNORE INTO number (campaignid,phonenumber,status,type) VALUES ($_POST[campaignid],'$number','new',0)";
    $result2 = mysql_query($sql, $link) or die (mysql_error());;
}
//exit(0);
//$sql="INSERT IGNORE INTO number (campaignid,phonenumber,status,type) VALUES ($_POST[campaignid],'$number','new',0)";
//        echo $sql;
//        $result=mysql_query($sql, $link) or die (mysql_error());;
        echo "<!-- . -->";
        flush();

     echo "</div><img src=\"/images/tick.gif\">Completed Saving";
     ?>
    <script language="javascript">
    function delayer(){
    window.location = "numbers.php"
    }
    setTimeout('delayer()', 3000);
    </script>
    <?
    echo "<BR></TD></TR>
        </TABLE>";
 } else {
?>


    <br /><br /><br /><br />
<center>
<table background="/images/sdbox.png" width="300" height="200" class="dragme22">
<tr>
<td>
</td>
<td width="260">
Please choose a list to use:<br /><br />


<FORM ACTION="serverlist.php" METHOD="POST">
<table class="tborderbb" align="center" border="0" cellpadding="0" cellspacing="2">
<?
?>
<TR><TD>
<select name="importfrom">
<?
$resultx=mysql_query("SELECT adminlists FROM customer WHERE username='".$_COOKIE[user]."'",$link) or die(mysql_error());
$row2=explode(",",mysql_result($resultx,0,0));

$resultss2=mysql_query("SELECT distinct(campaignid) from number where campaignid<0",$link);
while ($rowx2 = mysql_fetch_assoc($resultss2)) {
    $resultss3=mysql_query("SELECT name from campaign where id=".(0-$rowx2[campaignid]),$link);
    $found = 0;
    echo "x";
    foreach ($row2 as $abc){
        echo "Checking $abc against ".(0-$rowx2[campaignid])."
        ";
        if ($abc == (0-$rowx2[campaignid])) {
                    echo "Found";
                    $found = 1;

        }
    }
    if ($found == 1) {
        echo '<option value="'.(0-$rowx2[campaignid]).'">'.mysql_result($resultss3,0,0).'</option>   ';
    }


}
?>
</select>
<br />
<br />

<INPUT TYPE="HIDDEN" NAME="campaignid" VALUE="<?echo $_POST[campaignid];?>">
</TD>
</TR>
<TR><TD COLSPAN=2 ALIGN="RIGHT">
<INPUT TYPE="SUBMIT" VALUE="Add Numbers">
</TD>
</TR>
<?
?>

</TABLE>
</FORM>


</td>
<td>
</td></tr>
</table>
</center>



<?
}}
require "footer.php";
?>
