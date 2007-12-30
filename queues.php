<?
require "header.php";
require "header_customer.php";
$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');
?>

<table class="" align="center" border="0" cellpadding="2" cellspacing="0">
<TR>
<TD CLASS="thead">
Name
</TD>
<TD CLASS="thead">
WebSite
</TD>
<TD CLASS="thead">
Username
</TD>
<TD CLASS="thead">
Address Line 1
</TD>
<TD CLASS="thead">
Address Line 2
</TD>
<TD CLASS="thead">
City
</TD>
<TD CLASS="thead">
Country
</TD>
<TD CLASS="thead">
Phone
</TD>
<TD CLASS="thead">
Fax
</TD>
<TD CLASS="thead">
</TD>
</TR>
<?
$sql = 'SELECT * FROM customer';
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
<?
if (strlen($row[company])<15){
echo "<A HREF=\"editcustomer.php?id=".$row[id]."\">".$row[company]."</A>";
} else {
echo "<A HREF=\"editcustomer.php?id=".$row[id]."\">".trim(substr($row[company],0,15))."...</A>";
}
?>
</TD>
<TD><A HREF="<?echo $row[website];?>" TARGET="<?echo $row[website];?>"><?echo $row[website];?></A>
</TD>
<TD>
<?echo $row[username];?>
</TD>
<TD>
<?echo $row[address1];?>
</TD>
<TD>
<?echo $row[address2];?>
</TD>
<TD>
<?echo $row[city];?>
</TD>
<TD>
<?echo $row[country];?>
</TD>
<TD>
<?echo $row[phone];?>
</TD>
<TD>
<?echo $row[fax];?>
</TD>
<TD>
<a href="#" onclick="displaySmallMessage('includes/confirmDeleteCustomer.php?id=<?echo $row[id];?>');return false"><IMG SRC="/images/delete.png" BORDER="0"></a><br>
</TD>
</TR>

<?
}
?>

</TABLE>
<?
require "footer.php";
?>
