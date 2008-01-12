<?
$pagenum="2";
require "header.php";
require "header_message.php";
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
echo "<A HREF=\"editmessage.php?id=".$row[id]."\">".$row[name]."</A>";
} else {
echo "<A HREF=\"editmessage.php?id=".$row[id]."\">".trim(substr($row[name],0,15))."...</A>";
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
<embed src="<?echo "/uploads/".str_replace(".sln",".wav",$row[filename]);?>"
autostart=false width=0 height=0 id="sound<?echo $row[id];?>"
enablejavascript="true">

<a href="#" onClick="EvalSound('sound<?echo $row[id];?>')" title="Play sound">
<img src="/images/control_play_blue.png" border="0"></a>
<a href="#" onClick="EvalSound2('sound<?echo $row[id];?>')" title="Pause sound">
<img src="/images/control_pause_blue.png" border="0"></a>


</TD>


<TD>
<?echo "<A HREF=\"deleteMessage.php?id=".$row[id]."\"><IMG SRC=\"/images/delete.png\" BORDER=\"0\"></A>";?>
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
