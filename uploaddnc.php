<?require "header.php";
require "header_numbers.php";
$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');

if (1) {

require "upload_helper.php";
$sid = md5(uniqid(rand()));
?>

        <br /><br /><br /><br />
<center>
<table background="/images/sdbox.png" width="300" height="200" class="dragme">
<tr>
<td>
</td>
<td width="260">
<div id="matt2">
<b>Upload DNC Numbers</b><br /><br />
Please select a text file with one DNC number per line that you would
like to upload the DNC numbers from and then click Upload.<br /><br />
</div>
<form enctype="multipart/form-data" name="postform" action="/cgi-bin/upload.cgi?sid=<?php echo $sid; ?>&target=<?echo normal_target('receivednc.php');?>" method="post">
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
        Please wait while your DNC list is uploaded.<br /><br /><br /><div class="progresscontainer"><div class="progressbar" id="progress"></div></div>
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
