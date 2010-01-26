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

$pagenum="2";
require "header.php";
require "header_trunk.php";
/*================= Log Access ======================================*/
$sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_COOKIE[user]', 'Viewed the trunks page')";
$result=mysql_query($sql, $link);
/*================= Log Access ======================================*/

$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');

$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);

?>
<?/* start of shadow */?>
<table align="center"><tr><td><div class="example" id="v6"><div id="main"><div class="wrap1"><div class="wrap2"><div class="wrap3" align="center">

<table class="" align="center" border="0" cellpadding="2" cellspacing="0">
<TR>
<?if ($backend == 0) {?><TD CLASS="thead">
Default
</TD>
<?}?>
<TD CLASS="thead">
Name
</TD>
<TD CLASS="thead">
Dial String
</TD>
<TD CLASS="thead">
Max CPS
</TD>
<TD CLASS="thead">
Max Chans
</TD>
<TD CLASS="thead">

</TD>
</TR>
<?
$sql = 'SELECT * FROM trunk ';
/*$row1=$SMDB->executeQuery($sql);
//$campaigngroupid=mysql_result($result,0,'campaigngroupid');
$countx=0;*/
$result=mysql_query($sql, $link) or die (mysql_error());;
//$campaigngroupid=mysql_result($result,0,'campaigngroupid');
while ($row = mysql_fetch_assoc($result)) {
//print_r($row);
//while ($countx<sizeof($row1)) {
//    $row = $row1[$countx];
if ($toggle){
$toggle=false;
$class=" class=\"tborder2\" onmouseover=\"style.backgroundColor='#84DFC1';\" onmouseout=\"style.backgroundColor='#f8f8f8'\"   ";
} else {
$toggle=true;
$class=" class=\"tborderx\"  onmouseover=\"style.backgroundColor='#84DFC1';\" onmouseout=\"style.backgroundColor='#f0f0f0'\" ";
}
        if ($row["current"]==1){
$class=" class=\"tborderxxx\"  onmouseover=\"style.backgroundColor='#84DFC1';\" onmouseout=\"style.backgroundColor='#bbffbb'\" ";
    }

?>
<TR <?echo $class;?>>
<?if ($backend == 0) {?>
<TD>
<?
if ($row["current"]==1){
?>
<IMG SRC="images/tick.png" BORDER="1" WIDTH="16" HEIGHT="16" class="abcd">
<?
} else {
?>
<a href="setdefault.php?id=<?echo $row[id];?>"><IMG SRC="images/ch.gif" BORDER="1" WIDTH="16" HEIGHT="16"></A>
<?
}

?>
</TD>
<?}?>
<TD>
<?
if (strlen($row[name])<15){
echo "<A HREF=\"edittrunk.php?id=".$row[id]."\"><img src=\"images/pencil.png\" border=\"0\" align=\"right\" title=\"Edit\">".$row[name]."</A>";
} else {
echo "<A HREF=\"edittrunk.php?id=".$row[id]."\"><img src=\"images/pencil.png\" border=\"0\" align=\"right\" title=\"Edit\">".trim(substr($row[name],0,15))."...</A>";
}
?>
</TD>
<TD>
<?
if (strlen($row[dialstring])<25){
echo $row[dialstring];
} else {
echo trim(substr($row[dialstring],0,25))."...";
}
?>
</TD>
<TD>
<?echo $row[maxcps];?>
</TD>
<TD>
<?echo $row[maxchans];?>
</TD>

<TD>
<a href="#" onclick="displaySmallMessage('includes/confirmDeleteTrunk.php?id=<?echo $row[id];?>');return false"><IMG SRC="images/delete.png" BORDER="0"></a><br>
</TD>
</TR>

<?
$countx++;
}
?>

</TABLE>
<?/*end of shadow */?>
</div></div></div></div></div></td></tr></table>

<?}
require "footer.php";
?>
