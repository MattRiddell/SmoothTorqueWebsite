<?
$pagenum="2";
require "header.php";
require "header_message.php";
/*================= Log Access ======================================*/
$sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_COOKIE[user]', 'Viewed Messages Page')";
$result=mysql_query($sql, $link);
/*================= Log Access ======================================*/

$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');

$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);
?>
<script>
function EvalSound(soundobj) {
  var thissound=document.getElementById(soundobj);
  try {
     thissound.Play();
  } catch (e) {
     thissound.DoPlay();
  }

}
function EvalSound2(soundobj) {
  var thissound=document.getElementById(soundobj);
  try {
     thissound.Stop();
  } catch (e) {
     thissound.DoStop();
  }

}
</script>


<table class="" align="center" border="0" cellpadding="2" cellspacing="0">
<TR>
<TD CLASS="thead">
Name
</TD>
<TD CLASS="thead">
Description
</TD>
<TD CLASS="thead">
</TD>
<TD CLASS="thead">

</TD>
</TR>
<?
if ($_COOKIE[level] == sha1("level100")) {
    $sql = 'SELECT * FROM campaignmessage ';
} else {
    $sql = 'SELECT * FROM campaignmessage WHERE customer_id='.$campaigngroupid;
}
/*$row1=$SMDB->executeQuery($sql);
//$campaigngroupid=mysql_result($result,0,'campaigngroupid');
$countx=0;*/
$result=mysql_query($sql, $link) or die (mysql_error());;
//$campaigngroupid=mysql_result($result,0,'campaigngroupid');
while ($row = mysql_fetch_assoc($result)) {

//while ($countx<sizeof($row1)) {
//    $row = $row1[$countx];
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
<?
if (strlen($row[name])<15){
echo "<A HREF=\"editmessage.php?id=".$row[id]."\"><img src=\"/images/pencil.png\" border=\"0\" align=\"right\" title=\"Edit $row[name]\">".$row[name]."</A>";
} else {
echo "<A HREF=\"editmessage.php?id=".$row[id]."\"><img src=\"/images/pencil.png\" border=\"0\" align=\"right\" title=\"Edit $row[name]\">".trim(substr($row[name],0,15))."...</A>";
}
?>
</TD>
<TD>
<?
if (strlen($row[description])<25){
echo $row[description];
} else {
echo trim(substr($row[description],0,25))."...";
}
?>
</TD>
<TD>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

<?if (substr($row[filename],0,2) == "x-") {
/*<embed src="<?echo "/uploads/".str_replace(".sln",".wav",$row[filename]);?>"
autostart=false width=0 height=0 id="sound<?echo $row[id];?>"
enablejavascript="true">*/
?>

<a href="<?echo "/uploads/".str_replace(".sln",".wav",$row[filename]);?>" title="Play <?echo $row[name];?> sound">
<img src="/images/control_play_blue.png" border="0"></a>

<?

/*<a href="#" onClick="EvalSound('sound<?echo $row[id];?>')" title="Play <?echo $row[name];?> sound">
<img src="/images/control_play_blue.png" border="0"></a>
<a href="#" onClick="EvalSound2('sound<?echo $row[id];?>')" title="Pause <?echo $row[name];?> sound">
<img src="/images/control_pause_blue.png" border="0"></a>
*/
<?

} else {
/* FAX FILE */
?>


<a href="<?echo "/uploads/".str_replace(".sln",".wav",$row[filename]);?>" title="Open <?echo $row[name];?> fax">
<img src="/images/page.png" border="0"></a>

<?

}

/*?>
<embed src="<?echo "/uploads/".str_replace(".sln",".wav",$row[filename]);?>" width="144" height="20" autostart="false" loop="false">
<?*/?>
</TD>


<TD>
<?echo "<A title=\"Delete the ".$row[name]." Message\" HREF=\"deleteMessage.php?id=".$row[id]."\"><IMG SRC=\"/images/delete.png\" BORDER=\"0\"></A>";?>
</TD>
</TR>

<?
$countx++;
}
?>

</TABLE>
<?
require "footer.php";
?>
