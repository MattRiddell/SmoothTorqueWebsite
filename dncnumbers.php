<?
require "header.php";
$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');

if ($config_values['USE_SEPARATE_DNC'] == "YES") {
    $sql = 'SELECT count(*) FROM dncnumber WHERE campaignid='.$campaigngroupid;
} else {
    $sql = 'SELECT count(*) FROM dncnumber';
}
$result=mysql_query($sql, $link) or die (mysql_error());;
$count=mysql_result($result,0,'count(*)');

require "header_numbers.php";


?>
<br /><br /><br /><br />
<center>
<table background="/images/sdbox.png" width="300" height="200" class="dragme22">
<tr>
<td>
</td>
<td width="260">
<b><?echo $config_values['DNC_NUMBERS_TITLE'];?> (<?echo $count;?> numbers)</b><br />
<br />
Make a choice from one of the following: <br /><br />
<a href="searchdncnumbers.php"><img src="/images/magnifier.png" border="0"><?echo $config_values['DNC_SEARCH'];?></a><br />
<br />
<a href="/viewdncnumbers.php"><img src="/images/magnifier.png" border="0"><?echo $config_values['DNC_VIEW'];?></a><br />
<br />
<a href="/uploaddnc.php"><img src="/images/page_white_get.png" border="0">Upload numbers from a text file</a><br />
<br />
<a href="/adddncnumbers.php"><img src="/images/page_white_add.png" border="0">Add DNC number(s) manually.</a><br />
<br />
</td>
<td>
</td></tr>
</table>
</center>
<?
require "footer.php";




/*
if (!isset($_POST[campaignid])){
    ?>
    <FORM ACTION="numbers.php" METHOD="POST">
    <table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2"><TR>
    <TD>Select Campaign:</TD><TD>
        <SELECT NAME="campaignid">
        <?
        $sql = 'SELECT id,name FROM campaign WHERE groupid='.$campaigngroupid;
        $result=mysql_query($sql, $link) or die (mysql_error());;
        //$campaigngroupid=mysql_result($result,0,'campaigngroupid');
        while ($row = mysql_fetch_assoc($result)) {
            echo "<OPTION VALUE=\"".$row[id]."\">".$row[name]."</OPTION>";
        }
        ?>
        </SELECT>

    </TD>
    </TR><TR>
    <TD COLSPAN=2 ALIGN="CENTER">
    <INPUT TYPE="SUBMIT" VALUE="Display Numbers">
    </TD>
    </TR></table>
    </FORM>
    <?
} else {
?>
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2">
<TR>
<TD CLASS="thead">
Number
</TD>
<TD CLASS="thead">
Status
</TD>
<TD CLASS="thead">

</TD>
</TR>

<?
$sql = 'SELECT * FROM number WHERE campaignid='.$_POST[campaignid]." LIMIT 50";
$result=mysql_query($sql, $link) or die (mysql_error());;
//$campaigngroupid=mysql_result($result,0,'campaigngroupid');
while ($row = mysql_fetch_assoc($result)) {
if ($toggle){
$toggle=false;
$class=" class=\"tborder2\"";
} else {
$toggle=true;
$class=" class=\"tborderx\"";
}
?>
<TR <?echo $class;?>>
<TD>
<?echo $row[phonenumber];?>
</TD>
<TD>
<?echo $row[status];?>
</TD>
<TD>
<A HREF="deletenumber.php?campaignid=<?echo $_POST[campaignid];?>&number=<?echo $row[phonenumber];?>">Delete</A>
</TD>
</TR>

<?
}

?>

</TABLE>
<?
}
require "footer.php";*/
?>
