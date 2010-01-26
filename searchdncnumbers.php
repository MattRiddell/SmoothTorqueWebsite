<?
require "header.php";
$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');
require "header_numbers.php";

$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);

if (!isset($_POST[campaignid])&&!isset($_GET[campaignid])){
    ?>


    <br /><br /><br /><br />
<center>
<table background="images/sdbox.png" width="300" height="200" class="dragme22">
<tr>
<td>
</td>
<td width="260">
<b>Search DNC Numbers</b><br /><br />
<FORM ACTION="searchdncnumbers.php" METHOD="POST">
        <input type="hidden" NAME="campaignid" value="-1">
    <table class="tborderdd" align="center" border="0" cellpadding="0" cellspacing="2">
    <TR>
    <TD COLSPAN=1 ALIGN="CENTER">Number to find</TD>
    <TD><INPUT TYPE="TEXT" name="phonenumber"></TD>

    </TR>
    <TR>

    <TD COLSPAN=2 ALIGN="CENTER">
    <INPUT TYPE="SUBMIT" VALUE="Find Number">
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
if (isset($_GET[campaignid])){
    $_POST[campaignid]=$_GET[campaignid];
}
?>
<br />
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2">
<TR>
<TD CLASS="thead">
Number
</TD>
<TD CLASS="thead">
Last Updated
</TD>
</TR>

<?
$start=0;
if(isset($_POST[campaignid])){
    $campaignid=$_POST[campaignid];
} else {
    $campaignid=$_GET[campaignid];
}
if(isset($_POST[phonenumber])){
    $phonenumber=$_POST[phonenumber];
} else {
    $phonenumber=$_GET[phonenumber];
}
if ($_GET[start]>0){
    $start=$_GET[start];
}
$sql = 'SELECT count(*) FROM dncnumber';
$result=mysql_query($sql, $link) or die (mysql_error());;
$max=mysql_result($result,0,'count(*)');

$sql = 'SELECT * FROM dncnumber WHERE phonenumber="'.$phonenumber.'"order by status asc';
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
<?echo "<b>".$row[phonenumber]."</b></td><td>";
$newdate = date(DATE_RFC822, $row["newdate"]);
echo " ".$newdate;?>

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
