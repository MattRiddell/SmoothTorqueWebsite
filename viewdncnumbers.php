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
$start=0;
if ($_GET[start]>0){
    $start=$_GET[start];
}
if ($config_values['USE_SEPARATE_DNC'] == "YES") {
    $sql = 'SELECT count(*) FROM dncnumber WHERE campaignid='.$campaigngroupid;
} else {
    $sql = 'SELECT count(*) FROM dncnumber';
}

$result=mysql_query($sql, $link) or die (mysql_error());;
$max=mysql_result($result,0,'count(*)');
echo '<a href="viewdncnumbers.php?start=0"><img src="/images/resultset_first.png" border="0"></a> ';
echo '<a href="viewdncnumbers.php?start='.($start-$config_values['PER_PAGE']).'"><img src="/images/resultset_previous.png" border="0"></a> ';

for ($x=$start;$x<$start+($config_values['PER_PAGE']*10);$x+=$config_values['PER_PAGE']){
echo '<a href="viewdncnumbers.php?start='.$x.'">'.($x/$config_values['PER_PAGE']).'</a> ';
}
echo '<a href="viewdncnumbers.php?start='.($x+$config_values['PER_PAGE']).'"><img src="/images/resultset_next.png" border="0"></a> ';
echo '<a href="viewdncnumbers.php?start='.(($max-$config_values['PER_PAGE'])+$max%$config_values['PER_PAGE']).'"><img src="/images/resultset_last.png" border="0"></a> ';
echo '<br />';
echo '<br />';


if ($config_values['USE_SEPARATE_DNC'] == "YES") {
    $sql = 'SELECT * FROM dncnumber where campaignid = '.$campaigngroupid.' LIMIT '.$start.','.$config_values['PER_PAGE'];
} else {
    $sql = 'SELECT * FROM dncnumber LIMIT '.$start.','.$config_values['PER_PAGE'];
}



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
