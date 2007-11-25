<?
include "header.php";

$sql = 'SELECT value FROM config WHERE parameter=\'backend\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$backend = mysql_result($result,0,'value');
?>
<br />
<br />
<table class="" align="center" border="0" cellpadding="2" cellspacing="0">
<TR>
<TD CLASS="thead">
Current
</TD>
<TD CLASS="thead">

</TD>

</TR>
<TR  class="tborderxx">
<TD>
<?
if ($backend == 0) {
?>
<IMG SRC="/images/tick.png" BORDER="1" WIDTH="16" HEIGHT="16" class="abcd">
<?
} else {
?>
<a href="setparameter.php?parameter=backend&value=0">
<IMG SRC="/images/ch.gif" BORDER="1" WIDTH="16" HEIGHT="16">
</a>
<?
}
?>
</TD>
<TD>
Linux Backend</TD>

</TR>

<TR  class="tborder2">
<TD>
<?
if ($backend == 1) {
?>
<IMG SRC="/images/tick.png" BORDER="1" WIDTH="16" HEIGHT="16" class="abcd">
<?
} else {
?>
<a href="setparameter.php?parameter=backend&value=1">
<IMG SRC="/images/ch.gif" BORDER="1" WIDTH="16" HEIGHT="16">
</a>
<?
}
?>
</TD>
<TD>
Windows Backend</TD>

</TR>

</table>
