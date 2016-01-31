<? require "header.php";
require "header_numbers.php";
$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result = mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid = mysql_result($result, 0, 'campaigngroupid');

$_POST = array_map(mysql_real_escape_string, $_POST);
$_GET = array_map(mysql_real_escape_string, $_GET);

require "upload_helper.php";
$sid = md5(uniqid(rand()));
?>

<br/><br/><br/><br/>
<center>
    <?box_start()?>
                <? if ($_GET[type] == "audio") { ?>
                <div id="matt2">
                    <b>Upload Message</b><br/><br/>
                    <?if ($config_values['disable_all_types'] == "YES") {?>
                        To record an audio message/voice mail click on the vocaroo link below:<br />
                        <br />
                        <a href="http://www.vocaroo.com">www.vocaroo.com</a>
                    <?} else {?>
                    Please select a wave file from your computer that you would like to use
                    as one of your messages and then click Upload.<br/><br/>
                    <?}?>
                </div>
                <form enctype="multipart/form-data" name="postform" action="/cgi-bin/upload.cgi?sid=<?php echo $sid; ?>&target=<? echo normal_target('receivemessage.php'); ?>" method="post">
                    <? } else if ($_GET[type] == "fax") { ?>
                    <div id="matt2">
                        <b>Upload Fax Message</b><br/><br/>
                        Please select a tiff file from your computer that you would like to use
                        as one of your faxes and then click Upload.<br/><br/>
                    </div>
                    <form enctype="multipart/form-data" name="postform" action="/cgi-bin/upload.cgi?sid=<?php echo $sid; ?>&target=<? echo normal_target('receivefaxmessage.php'); ?>" method="post">
                        <? } ?>

                        <center>
                            <table>
                                <tr>
                                    <td>
                                        <div id="matt">
                                            <input type="file" name="file_1"/>
                                            <input type="hidden" value="<? echo $_POST[campaignid]; ?>" name="id">
                    </form>
                    <br/><br/>
                    <input type="button" class="btn-primary btn" onclick="beginUpload('<?php echo $sid ?>');" value="Upload">
                    </div>
            </td>
        </tr>
        <tr>
            <td colspan=2 width=250>
                <div id="progressbox" style="display: none">
                    Please wait while your message is uploaded.<br/><br/><br/>

                    <div class="progresscontainer">
                        <div class="progressbar" id="progress"></div>
                    </div>
                </div>

           <?box_end();?>
</center>
</td>
<td>
</td></tr>
</table>
</center>


<br/>
