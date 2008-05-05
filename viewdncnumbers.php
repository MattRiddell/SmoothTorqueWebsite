<?
require "header.php";
$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');
require "header_numbers.php";

$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);

if (1){
?>
<br />
<br />
<br />
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2">
<TR>
<TD CLASS="thead">
Number
</TD>
<TD CLASS="thead">

</TD>
</TR>

<?
$sql = 'SELECT * FROM dncnumber where campaignid = '.$campaigngroupid.' LIMIT 50';
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
<A HREF="deletedncnumber.php?number=<?echo $row[phonenumber];?>"><img src="/images/delete.png" border="0"></A>
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
