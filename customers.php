<?
$level=$_COOKIE[level];

if ($level!=sha1("level100")) {
include "header.php";
$ip = $_SERVER['REMOTE_ADDR'];
echo "Attempted break in attempt from $ip ($_COOKIE[user])";
/*================= Log Access ======================================*/
$sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_COOKIE[user]', ' $ip attempted to view the admin page')";
$result=mysql_query($sql, $link);
/*================= Log Access ======================================*/

} else {

require "header.php";
require "header_customer.php";
/*================= Log Access ======================================*/
$sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_COOKIE[user]', 'Viewed the customers page')";
$result=mysql_query($sql, $link);
/*================= Log Access ======================================*/

$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');
//shadow_start();
?>

<table class="table table-bordered" align="center" border="0" cellpadding="2" cellspacing="0">
<TR>
<TD CLASS="">
Name
</TD>
<TD CLASS="">
Username
</TD>
<TD CLASS="">
Call Details
</TD>
<TD CLASS="">
City
</TD>
<TD CLASS="">
Country
</TD>
<TD CLASS="">
Phone
</TD>
<?if ( $config_values['USE_BILLING'] == "YES") {?>
<TD CLASS="">
Credit
</TD>
<TD CLASS="">
Credit Limit
</TD>
<?}?>
<TD CLASS="">
Trunk
</TD>
<TD CLASS="">
</TD>
</TR>
<?

$sql = 'SELECT customer.*,trunk.name AS trunkname, billing.credit as credit, billing.creditlimit as creditlimit FROM customer LEFT JOIN trunk ON customer.trunkid=trunk.id LEFT JOIN billing on customer.id=billing.customerid order by customer.company';
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
echo "<A HREF=\"editcustomer.php?id=".$row[id]."\"><img src=\"images/pencil.png\" border=\"0\" align=\"right\" title=\"Edit\">".$row[company]."</A>&nbsp;";
} else {
echo "<A HREF=\"editcustomer.php?id=".$row[id]."\"><img src=\"images/pencil.png\" border=\"0\" align=\"right\" title=\"Edit\">".trim(substr($row[company],0,15))."...</A>";
}
?>
</TD>
<TD>
<?
?>
<?echo "<A HREF=\"changepassword.php?id=".$row[id]."\" title=\"Change Password\"><img src=\"images/lock_edit.png\" border=\"0\" align=\"right\" title=\"Change Password\"></A>";?>
<?
if (strlen($row[username])<15){
    echo $row[username];
} else {
    echo trim(substr($row[username],0,15))."...";
}
?>
</TD>
<TD>
<?echo "<A HREF=\"viewcdr.php?accountcode=stl-".$row[username]."\" title=\"View CDR Information\"><img src=\"images/table.png\" border=\"0\" align=\"left\" title=\"View CDR Information\">View CDR</A>";?>
</TD>
<TD>
<?echo $row[city];?>
</TD>
<TD>
<?echo $row[country];?>
</TD>
<TD>
<?
if (strlen($row[phone])<15){
    echo $row[phone];
} else {
    echo substr($row[phone],15)."...";
}
?>
</TD>
<?if ( $config_values['USE_BILLING'] == "YES") {?>
<TD>
<?
echo "<A HREF=\"billing.php?id=".$row[id]."\" title=\"View Billing Information\"><img src=\"images/cart_edit.png\" border=\"0\" align=\"right\" title=\"View Billing Information\">";
if (isset($row[credit])){
echo $config_values['CURRENCY_SYMBOL']." ".number_format($row[credit],2)."</A>";
} else {
echo "Add Billing</A>";
}
?>
</TD>
<TD>
<?
echo "<A HREF=\"billing.php?id=".$row[id]."\" title=\"View Billing Information\"><img src=\"images/cart_edit.png\" border=\"0\" align=\"right\" title=\"View Billing Information\">";
if (isset($row[credit])){
echo $config_values['CURRENCY_SYMBOL']." ".number_format($row[creditlimit],2)."</A>";
} else {
echo "Add Billing</A>";
}
?>
</TD>
<?}?>
<TD>

<?
if (strlen(trim($row[trunkname]))<1){
    echo "Default";
} else {
    echo "<b>".$row[trunkname]."</b>";
}
?>
</TD>
<?/*<TD>

 this week
$sql = 'select count(*) from cdr.cdr where date(calldate)<=curdate() and date(calldate)>=DATE_ADD(CURDATE(), INTERVAL -7
DAY)
and dst="1" and accountcode="stl-'.$row[username].'"';
$resultaa=mysql_query($sql, $link) or die (mysql_error());;
$thisweek = mysql_result($resultaa,0,0);
echo "This Week: $thisweek <br />";

 yesterday
$sql = 'select count(*) from cdr.cdr where date(calldate)<curdate() and date(calldate)>=DATE_ADD(CURDATE(), INTERVAL -1 DAY)
and dst="1"  and accountcode="stl-'.$row[username].'"';
$resultaa=mysql_query($sql, $link) or die (mysql_error());;
$yesterday = mysql_result($resultaa,0,0);
echo "yesterday: $yesterday <br />";

 today
$sql = 'select count(*) from cdr.cdr where date(calldate)=curdate() and dst="1"  and accountcode="stl-'.$row[username].'"';
$resultaa=mysql_query($sql, $link) or die (mysql_error());;
$today = mysql_result($resultaa,0,0);
echo "today: $today <br />";

</TD>  */?>
<TD>
<a href="#" onclick="displaySmallMessage('includes/confirmDeleteCustomer.php?id=<?echo $row[id];?>');return false"><IMG SRC="images/delete.png" BORDER="0"></a><br>
</TD>
</TR>

<?
}
?>

</TABLE>

<?
shadow_end();
require "footer.php";}
?>
