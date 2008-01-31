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
Trunk
</TD>
<TD CLASS="thead">
Press 1s
</TD>
<TD CLASS="thead">
</TD>
</TR>
<?

$sql = 'SELECT customer.*,trunk.name AS trunkname FROM customer LEFT JOIN trunk ON customer.trunkid=trunk.id';
$result=mysql_query($sql, $link) or die (mysql_error());;
//$campaigngroupid=mysql_result($result,0,'campaigngroupid');
$count = 0;
while ($row = mysql_fetch_assoc($result)) {




$count++;
if ($toggle){
$toggle=false;
$class=" class=\"tborder2\"  onmouseover=\"style.backgroundColor='#84DFC1';\" onmouseout=\"style.backgroundColor='#f8f8f8'\"   ";
} else {
$toggle=true;
$class=" class=\"tborderx\"  onmouseover=\"style.backgroundColor='#84DFC1';\" onmouseout=\"style.backgroundColor='#f0f0f0'\" ";
}

?>
<TR <?echo $class;?>  >
<TD >
<?
if (strlen($row[company])<15){
echo "<A HREF=\"editcustomer.php?id=".$row[id]."\"><img src=\"/images/pencil.png\" border=\"0\" align=\"right\" title=\"Edit\">".$row[company]."</A>";
} else {
echo "<A HREF=\"editcustomer.php?id=".$row[id]."\"><img src=\"/images/pencil.png\" border=\"0\" align=\"right\" title=\"Edit\">".trim(substr($row[company],0,15))."...</A>";
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

<?
if (strlen(trim($row[trunkname]))<1){
    echo "Default";
} else {
    echo "<b>".$row[trunkname]."</b>";
}
?>
</TD>
<TD>
<?
/* this week */
$sql = 'select count(*) from cdr.cdr where date(calldate)<=curdate() and date(calldate)>=DATE_ADD(CURDATE(), INTERVAL -7
DAY)
and dst="1" and accountcode="stl-'.$row[username].'"';
$resultaa=mysql_query($sql, $link) or die (mysql_error());;
$thisweek = mysql_result($resultaa,0,0);
echo "This Week: $thisweek <br />";

/* yesterday */
$sql = 'select count(*) from cdr.cdr where date(calldate)<curdate() and date(calldate)>=DATE_ADD(CURDATE(), INTERVAL -1 DAY)
and dst="1"  and accountcode="stl-'.$row[username].'"';
$resultaa=mysql_query($sql, $link) or die (mysql_error());;
$yesterday = mysql_result($resultaa,0,0);
echo "yesterday: $yesterday <br />";

/* today */
$sql = 'select count(*) from cdr.cdr where date(calldate)=curdate() and dst="1"  and accountcode="stl-'.$row[username].'"';
$resultaa=mysql_query($sql, $link) or die (mysql_error());;
$today = mysql_result($resultaa,0,0);
echo "today: $today <br />";
?>
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
