<?
require "header.php";
$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');
?>

<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2"><TR>
    <TD><A HREF="addnumbers.php">Add Numbers</A>&nbsp;&nbsp;</TD>
    <TD><A HREF="numbers.php">View Numbers</A>&nbsp;&nbsp;</TD>
    </TR></table>
     <BR>
<?
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
<TD>
Number
</TD>
<TD>
Status
</TD>
</TR>

<?
$sql = 'SELECT * FROM number WHERE campaignid='.$_POST[campaignid]." LIMIT 50";
$result=mysql_query($sql, $link) or die (mysql_error());;
//$campaigngroupid=mysql_result($result,0,'campaigngroupid');
while ($row = mysql_fetch_assoc($result)) {
?>
<TD>
<?echo $row[phonenumber];?>
</TD>
<TD>
<?echo $row[status];?>
</TD>
</TR>

<?
}

?>

</TABLE>
<?
}
require "footer.php";
?>

