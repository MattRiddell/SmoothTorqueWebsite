<?
require "header.php";
require "header_timezones.php";
?>
<?
box_start(360);
?>
<center>
<br />
Please select a timezone to add prefixes to<br />
<br />
<FORM ACTION="index_prefixes.php" METHOD="POST">
<table class="tborderx2xx" align="center" border="0" cellpadding="0" cellspacing="2"><TR>
<TD>
<SELECT  class="form-control" NAME="timezone">
<?
$sql = 'SELECT id, name FROM time_zones order by name';
$result=mysql_query($sql, $link) or die (mysql_error());;

while ($row = mysql_fetch_assoc($result)) {
    echo "<OPTION VALUE=\"".$row['id']."\">".substr($row['name'],0,22)."</OPTION>";
}
?>
</SELECT>

</TD>
</TR><TR>
<TD COLSPAN=2 ALIGN="CENTER"><br />
<INPUT class="btn btn-primary" TYPE="SUBMIT" VALUE="Select TimeZone">
</TD>
</TR></table>
</FORM>
<br />
<?
box_end();
?>
