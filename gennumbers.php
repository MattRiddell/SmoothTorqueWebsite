<?

//ini_set('error_reporting', E_ALL);
//xxxsaasasasas(sdsd);
require "header.php";
require "header_numbers.php";


$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);

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
<TR>
<td>
</td>
<td width="260">
Please select a campaign to add numbers to<br /><br />
<FORM ACTION="gennumbers.php" METHOD="POST">
    <table class="tborderxsxx" align="center" border="0" cellpadding="0" cellspacing="2"><TR>
    <TD>
        <SELECT NAME="campaignid">
        <?
        if ($_COOKIE[level] == sha1("level100")) {
            echo "<OPTION VALUE=\"-1\">Shared List</OPTION>";
        }
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
<td></td></TR>
</table>
</center>
    <?
} else {
 if (isset($_POST[start])){
 $count=0;
 $count2=0;
 ?><br /><br /><br /><br /><br />
 <table background="/images/sdbox.png" align="center" width="300" height="200" cellpadding="0" cellspacing="0" class="dragme22">
 <tr><td width=250><center>
 <table><tr><td>
<div id="hideShow" >
    Please Wait, saving Phone Numbers<br /><br />
    <div id="progressbox">
        <div class="progresscontainer">
            <div class="progressbar" id="progress"></div>
        </div>
    </div>
    <br />
    This may take some time...
</div>
</td></tr></table>
<?

 /* Problem here - for loops treat the numbers as ints - so strip leading
    zeros - read the zeros first to apply them later */

unset($starter);
if (substr($_POST[start],0,3) == "000") {
    $starter = "000";
} else if (substr($_POST[start],0,2) == "00") {
    $starter = "00";
} else if (substr($_POST[start],0,1) == "00") {
    $starter = "0";
}
 for ($i=$_POST[start];$i<=$_POST[end];$i++){
    $count++;
    $myarray[$count]=$starter.$i;
    echo "$count is ".$myarray[$count]."<br />";
 }


echo "done";
exit(0);
shuffle($myarray);

 $count = 0;
 for ($i=$_POST[start];$i<=$_POST[end];$i++){
    $sql="INSERT IGNORE INTO number (campaignid,phonenumber,status,type) VALUES ($_POST[campaignid],$myarray[$count],'new',0)";
    $result=mysql_query($sql, $link) or die (mysql_error());;

    $count++;
    $count2++;
    $count6++;
    if ($count6>100){
    echo "<!-- . -->";
    $count6=0;
    }
    if ($count2>($_POST[end]-$_POST[start])/100){
        if ($count3>10){
            //echo "<BR>";
            $count3=0;
        }
        $count3++;

        $percent = round($count/($_POST[end]-$_POST[start])*100);
        ?>
        <script language="javascript">
         e=document.getElementById("progress");
         e.style.width = <?echo ($percent*2.5)?> + 'px';
        </script>
        <?
        flush();
        $count2=0;
    }
 }
 echo "Phone Numbers Saved<img src=\"/images/tick.gif\">";

?>
               </TD></TR>
        </TABLE>
 <?

// print_r($myarray);
 } else {
 if ($_POST[campaignid] == -1 && !isset($_POST[name])) {
    /*
    Create a campaign
    Create a new negative campaign id
    Use this campaign id for the generation
    */
    ?>
        <br /><br /><br /><br />
<center>
<table background="/images/sdbox.png" width="300" height="200" class="dragme22">
    <TR>
        <td>
        </td>
        <td width="260">
            Create Administrator Number List<br />
            <br />
            <FORM ACTION="gennumbers.php" METHOD="POST">
            <table class="tborderxsxx" align="center" border="0" cellpadding="0" cellspacing="2">
                <TR>
                    <TD CLASS="thead">Name</TD>
                    <TD>
                        <INPUT TYPE="HIDDEN" NAME="campaignid" VALUE="<?echo $_POST[campaignid];?>">
                        <INPUT TYPE="TEXT" NAME="name" VALUE="" size="20">
                    </TD>
                </TR>
                <TR>
                    <TD CLASS="thead">Description</TD>
                    <TD>
                        <INPUT TYPE="TEXT" NAME="description" VALUE="" size="20">
                    </TD>
                </TR>
                <TR>
                    <TD COLSPAN=2 ALIGN="RIGHT">
                        <br />
                        <INPUT TYPE="SUBMIT" VALUE="Create Campaign">
                    </TD>
                </TR>
            </TABLE>
        </TD>
        <td></td>
    </TR>
    </table>
</FORM>
    <?
 } else {
 if (isset($_POST[name])){
// echo $_POST[name];
$sql = "INSERT INTO campaign (name, description, groupid, messageid, messageid2, messageid3)  VALUES ('$_POST[name]', '$_POST[description]', -1, 0,0,0)";
 $result=mysql_query($sql, $link) or die (mysql_error());;

 ?>
 <br /><br /><br /><br />
<center>
<table background="/images/sdbox.png" width="300" height="200" class="dragme22">
    <TR>
        <td>
        </td>
        <td width="260">
            Please type the range you would like for the phone numbers.<br />
            <br />
            <FORM ACTION="gennumbers.php" METHOD="POST">
            <table class="tborderxsxx" align="center" border="0" cellpadding="0" cellspacing="2">
                <TR>
                    <TD CLASS="thead">Start Number</TD>
                    <TD>
                        <INPUT TYPE="HIDDEN" NAME="campaignid" VALUE="-<?echo mysql_insert_id();?>">
                        <INPUT TYPE="TEXT" NAME="start" VALUE="16035550000" size="20">
                    </TD>
                </TR>
                <TR>
                    <TD CLASS="thead">End Number</TD>
                    <TD>
                        <INPUT TYPE="TEXT" NAME="end" VALUE="16035559999" size="20">
                    </TD>
                </TR>
                <TR>
                    <TD COLSPAN=2 ALIGN="RIGHT">
                        <br />
                        <INPUT TYPE="SUBMIT" VALUE="Generate Numbers">
                    </TD>
                </TR>
            </TABLE>
        </TD>
        <td></td>
    </TR>
    </table>
</FORM>
 <?
 /*
 Create campaign, get campaign id
 */

 ?>                                          <?
 } else {
?>
    <br /><br /><br /><br />
<center>
<table background="/images/sdbox.png" width="300" height="200" class="dragme22">
    <TR>
        <td>
        </td>
        <td width="260">
            Please type the range you would like for the phone numbers.<br />
            <br />
            <FORM ACTION="gennumbers.php" METHOD="POST">
            <table class="tborderxsxx" align="center" border="0" cellpadding="0" cellspacing="2">
                <TR>
                    <TD CLASS="thead">Start Number</TD>
                    <TD>
                        <INPUT TYPE="HIDDEN" NAME="campaignid" VALUE="<?echo $_POST[campaignid];?>">
                        <INPUT TYPE="TEXT" NAME="start" VALUE="16035550000" size="20">
                    </TD>
                </TR>
                <TR>
                    <TD CLASS="thead">End Number</TD>
                    <TD>
                        <INPUT TYPE="TEXT" NAME="end" VALUE="16035559999" size="20">
                    </TD>
                </TR>
                <TR>
                    <TD COLSPAN=2 ALIGN="RIGHT">
                        <br />
                        <INPUT TYPE="SUBMIT" VALUE="Generate Numbers">
                    </TD>
                </TR>
            </TABLE>
        </TD>
        <td></td>
    </TR>
    </table>
</FORM>
    <?
}}}}
require "footer.php";
?>
