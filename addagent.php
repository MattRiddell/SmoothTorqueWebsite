<?
if (isset($_POST[name])) {
    $queue_name = $_GET[queue_name];
    include "admin/db_config.php";
    mysql_select_db("SineDialer", $link);

    //echo "Saving";
    $name = $_POST[name];
    $password = $_POST[password];
    $codec1 = $_POST[codec1];
    $codec2 = $_POST[codec2];
    if ($codec1 == "ulaw"||$codec1 == "alaw") {
        $dtmfmode = "inband";
    } else {
        $dtmfmode = "auto";
    }
    $allowed = $codec1.";".$codec2;
    $callerid = $name." <0000>";
    $sql = "INSERT INTO sip_buddies (name, accountcode, callerid, canreinvite,
    context, dtmfmode, host, language, nat, qualify, secret, type, username,
    disallow, allow) values ('$name', '$name', '$callerid', 'no',
    'internal', '$dtmfmode', 'dynamic', 'en', 'yes', 'yes', '$password', 'friend', '$name',
    'all', '$allowed')";
//    echo $sql;
    $result = mysql_query($sql, $link) or die(mysql_error());

    $sql = "INSERT INTO queue_member_table (membername, queue_name, interface) values
    ('$name','$queue_name','SIP/$name')";
    $result = mysql_query($sql, $link) or die(mysql_error());

    include "queues.php";
} else {
require "header.php";
require "header_queue.php";

?>
<form action = "addagent.php?queue_name=<?echo $_GET[name];?>" method="post">
<table class="" align="center" border="0" cellpadding="2" cellspacing="0">
<TR>
<td style="background-image: url(images/clb.gif);" width=2></td>

<TD CLASS="thead" colspan="2">

Name
</TD>
<td style="background-image: url(images/crb.gif);" width=2></td>
</TR>

<TR  class="tborderx">
<td></td>
<td>Name:</td>
<td><input type="text" name="name"></td>
<td></td>
</tr>

<TR  class="tborderx">
<td></td>
<td>Password:</td>
<td><input type="password" name="password"></td>
<td></td>
</tr>

<TR  class="tborderx">
<td></td>
<td>Codec 1</td>
<td>
<select name = "codec1">
<option value="ulaw">G711 Ulaw</option>
<option value="alaw">G711 Alaw</option>
<option value="gsm">GSM</option>
<option value="g729">G729a</option>
<option value="ilbc">ILBC</option>
</select>
</td>
<td></td>
</tr>

<TR  class="tborderx">
<td></td>
<td>Codec 2</td>
<td>
<select name = "codec2">
<option value="ulaw">G711 Ulaw</option>
<option value="alaw">G711 Alaw</option>
<option value="gsm">GSM</option>
<option value="g729">G729a</option>
<option value="ilbc">ILBC</option>
</select>
</td>
<td></td>
</tr>

<TR  class="tborderx">
<td></td>
<td colspan = "2">
<input type="submit" value="Save Account"></td>
<td></td>
</tr>


</table>

</form>
<?
}
require "footer.php";
?>
