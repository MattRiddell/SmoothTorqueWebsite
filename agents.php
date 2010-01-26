<?
require "header.php";
require "header_queue.php";
$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');


$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);
?>
<?/* start of shadow */?>
<table align="center"><tr><td><div class="example" id="v6"><div id="main"><div class="wrap1"><div class="wrap2"><div class="wrap3" align="center">

<?/*                                <img class="xxx" src="images/ball.jpg" width="72" height="72" alt="demo" />*/?>
<table class="table1" align="center" border="0" cellpadding="2" cellspacing="0">
<TR>
<TD CLASS="thead">
Name
</TD>
<TD CLASS="thead">
Last Seen
</TD>
<TD CLASS="thead">
IP Address
</TD>
<TD CLASS="thead">
</TD>
</TR>
<?
$sql = "SELECT * FROM queue_member_table  where queue_name = '$_GET[name]'";
$result=mysql_query($sql, $link) or die (mysql_error());;
//$campaigngroupid=mysql_result($result,0,'campaigngroupid');
while ($row = mysql_fetch_assoc($result)) {

$sqlx = "SELECT * from sip_buddies WHERE name= '$row[membername]'";
$resultx = mysql_query($sqlx);
$regseconds = mysql_result($resultx,0,'regseconds');
$ipaddr = mysql_result($resultx,0,'ipaddr');
if ($regseconds > 0) {
$time=date("D M j G:i:s T Y",$regseconds);
} else {
$time = "Never";
}

if ($toggle){
$toggle=false;
$class=" class=\"tborder2\"  onmouseover=\"style.backgroundColor='#84DFC1';\" onmouseout=\"style.backgroundColor='#f8f8f8'\"   ";
} else {
$toggle=true;
$class=" class=\"tborderx\"  onmouseover=\"style.backgroundColor='#84DFC1';\" onmouseout=\"style.backgroundColor='#f0f0f0'\" ";
}

?>
<TR <?echo $class;?>>
<TD>
<?/*
if (strlen($row[membername])<15){
echo "<A HREF=\"editagent.php?name=".$row[membername]."\"><img src=\"images/pencil.png\" border=\"0\" align=\"right\" title=\"Edit\">".$row[membername]."</A>";
} else {
echo "<A HREF=\"editagent.php?name=".$row[membername]."\"><img src=\"images/pencil.png\" border=\"0\" align=\"right\" title=\"Edit\">".trim(substr($row[membername],0,15))."...</A>";
}*/
echo $row[membername];
?>
</TD>
<td>
<?echo $time;?>
</td>
<td>
<?echo $ipaddr;?>
</td>
<TD>
<a href="#" onclick="displaySmallMessage('includes/confirmDeleteAgent.php?name=<?echo $row[membername];?>&queue_name=<?echo $row[queue_name];?>');return false"><IMG SRC="images/delete.png" BORDER="0"></a><br>
</TD>
</TR>

<?
}
?>

</TABLE>
<?/*end of shadow */?>
</div></div></div></div></div></td></tr></table>
<?
require "footer.php";
?>
