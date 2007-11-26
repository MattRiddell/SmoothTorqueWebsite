<?require "header.php";
require "header_numbers.php";
$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');

if (!isset($_POST[campaignid])){
    ?>

    <br /><br /><br /><br />
<center>
<table background="/images/sdbox.png" width="300" height="200">
<tr>
<td>
</td>
<td width="260">
<b>Upload Numbers</b><br /><br />
Which campaign would you like to add the numbers to?<br /><br />
<FORM ACTION="upload.php" METHOD="POST">
    <table class="tborderxxx" align="center" border="0" cellpadding="0" cellspacing="2"><TR>
    <TD>Select Campaign:</TD><TD>
        <SELECT NAME="campaignid">
        <?
        //
        $level=$_COOKIE[level];
        if ($level==sha1("level100")) {
            $sql = 'SELECT id,name FROM campaign';
        } else {
            $sql = 'SELECT id,name FROM campaign WHERE groupid='.$campaigngroupid;
        }
        $result=mysql_query($sql, $link) or die (mysql_error());;
        //$campaigngroupid=mysql_result($result,0,'campaigngroupid');
        while ($row = mysql_fetch_assoc($result)) {
            echo "<OPTION VALUE=\"".$row[id]."\">".$row[name]."</OPTION>";
        }
        ?>
        </SELECT>

    </TD>
    </TR><TR>
    <TD COLSPAN=2 ALIGN="CENTER"><br />
    <INPUT TYPE="SUBMIT" VALUE="Select Campaign">
    </TD>
    </TR></table>
    </FORM>
</td>
<td>
</td></tr>
</table>
</center>










    <?
} else {

require "upload_helper.php";
$sid = md5(uniqid(rand()));
?>

        <br /><br /><br /><br />
<center>
<table background="/images/sdbox.png" width="300" height="200">
<tr>
<td>
</td>
<td width="260">
<div id="matt2">
<b>Upload Numbers</b><br /><br />
Please select a text file with one number per line that you would
like to upload the numbers from and then click Upload.<br /><br />
</div>
<form enctype="multipart/form-data" name="postform" action="/cgi-bin/upload.cgi?sid=<?php echo $sid; ?>&target=<?echo normal_target('receive.php');?>" method="post">
        <center><table><tr><td>
                <div id="matt">
                <input type="file" name="file_1" />
                <input type="hidden" value="<?echo $_POST[campaignid];?>" name="id">
                </form> <br /><br />
                <input type="button" onclick="beginUpload('<?php echo $sid ?>');" value="Upload">
                </div>
                </td></tr>
        <tr><td colspan = 2 width=250>
        <div id="progressbox" style="display: none">
        Please wait while your list is uploaded.<br /><br /><br /><div class="progresscontainer"><div class="progressbar" id="progress"></div></div>
        </div>

        </td></tr>
        </table></center>
        </td>
<td>
</td></tr>
</table>
</center>





        <br />
        <?}?>
