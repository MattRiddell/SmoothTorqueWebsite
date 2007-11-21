<?
require "header.php";
$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');
require "header_numbers.php";


if (!isset($_POST[campaignid])){
    ?>


    <br /><br /><br /><br />
<center>
<table background="/images/sdbox.png" width="300" height="200">
<tr>
<td>
</td>
<td width="260">
<b>View Numbers</b><br /><br />
From here you can chose a campaign that you would like to see the numbers for.<br /><br />
<FORM ACTION="viewnumbers.php" METHOD="POST">
    <table class="tborderdd" align="center" border="0" cellpadding="0" cellspacing="2"><TR>
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
    <TD COLSPAN=2 ALIGN="CENTER"><br />
    <INPUT TYPE="SUBMIT" VALUE="Display Numbers">
    </TD>
    </TR></table>
    </FORM><br />
</td>
<td>
</td></tr>
</table>
</center>







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
$sql = 'SELECT * FROM number WHERE campaignid='.$_POST[campaignid]." order by status asc LIMIT 50";
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
require "footer.php";
?>
