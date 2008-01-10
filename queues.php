<?
require "header.php";
require "header_queue.php";
$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');


$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);
?>

<table class="" align="center" border="0" cellpadding="2" cellspacing="0">
<TR>
<TD CLASS="thead">
Name
</TD>
<TD CLASS="thead">
Strategy
</TD>
<TD CLASS="thead">
Timeout
</TD>
<TD CLASS="thead">
Members
</TD>
<TD CLASS="thead">
</TD>
</TR>
<?
$sql = 'SELECT * FROM queue_table';
$result=mysql_query($sql, $link) or die (mysql_error());;
//$campaigngroupid=mysql_result($result,0,'campaigngroupid');
while ($row = mysql_fetch_assoc($result)) {

$sql2 = 'SELECT count(*) FROM queue_member_table where queue_name = "'.$row[name].'"';
$result2=mysql_query($sql2, $link) or die (mysql_error());;
$count=mysql_result($result2,0,'count(*)');

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
if (strlen($row[name])<15){
echo "<A HREF=\"editqueue.php?name=".$row[name]."\">".$row[name]."</A>";
} else {
echo "<A HREF=\"editqueue.php?name=".$row[name]."\">".trim(substr($row[company],0,15))."...</A>";
}
?>
</TD>
<TD><?echo $row[strategy];?>
</TD>
<TD>
<?echo $row[timeout];?>
</TD>
<TD>
<?echo $count;?>
</TD>
<TD>
<a href="#" onclick="displaySmallMessage('includes/confirmDeleteQueue.php?name=<?echo $row[name];?>');return false"><IMG SRC="/images/delete.png" BORDER="0"></a><br>
</TD>
</TR>

<?
}
?>

</TABLE>
<?
require "footer.php";
?>
