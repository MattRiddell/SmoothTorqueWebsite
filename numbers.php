<?
require "header.php";
$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');
require "header_numbers.php";


$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);

?>
<br /><br /><br /><br />
<?
/*<center>
<table background="images/sdbox.png" width="300" height="200" class="dragme22">
<tr>
<td>
</td>
<td width="260">
*/
box_start();
?>
<center>
<h3><?echo $config_values['NUMBERS_TITLE'];?></h3>
<a  class="btn btn-default navbar-btn" href="serverlist.php"><img src="images/report.png" border="0">&nbsp; <?echo $config_values['NUMBERS_SYSTEM'];?></a>
<br />
<a  class="btn btn-default navbar-btn" href="viewnumbers.php"><img src="images/page_white_stack.png" border="0">&nbsp; <?echo $config_values['NUMBERS_VIEW'];?></a>
<br />
<a  class="btn btn-default navbar-btn" href="searchnumbers.php"><img src="images/magnifier.png" border="0">&nbsp; <?echo $config_values['NUMBERS_SEARCH'];?></a>
<br />
<a  class="btn btn-default navbar-btn" href="exportnumbers.php"><img src="images/table_save.png" border="0">&nbsp; <?echo $config_values['NUMBERS_EXPORT'];?></a>
<br />
<?
if ($config_values['use_names'] == 'YES') {
?>
	<a  class="btn btn-default navbar-btn" href="upload_names.php"><img src="images/page_white_get.png" border="0">&nbsp;Upload names and numbers from a text file</a>
	<br />
<?
}
?>
<a  class="btn btn-default navbar-btn" href="upload.php"><img src="images/page_white_get.png" border="0">&nbsp; <?echo $config_values['NUMBERS_UPLOAD'];?></a>
<br />
<a  class="btn btn-default navbar-btn" href="addnumbers.php"><img src="images/page_white_add.png" border="0">&nbsp; <?echo $config_values['NUMBERS_MANUAL'];?></a>
<?
if ($config_values['USE_GENERATE'] == "YES") {
?>
<br />
<a  class="btn btn-default navbar-btn" href="gennumbers.php"><img src="images/page_white_lightning.png" border="0">&nbsp; <?echo $config_values['NUMBERS_GENERATE'];?></a>
<?
}

if ($config_values['NUMBER_EXHAUSTION'] == "YES") {
    ?>
    <br />
    <a  class="btn btn-default navbar-btn" href="notifications.php"><img src="images/email.png" border="0">&nbsp; <?echo $config_values['NUMBERS_WARNING'];?></a>
    <?
}

?>
<br />
</center>

<?
/*
</td>
<td>
</td></tr>
</table>
</center>
*/
box_end();
require "footer.php";




/*
if (!isset($_POST[campaignid])){
    ?>
    <FORM ACTION="numbers.php" METHOD="POST">
    <table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2"><TR>
    <TD>Select Campaign:</TD><TD>
        <SELECT NAME="campaignid">
        <?
        $sql = 'SELECT id,name FROM campaign WHERE groupid='.$campaigngroupid;
        $result=mysql_query($sql, $link) or die (mysql_error());;
        //$campaigngroupid=mysql_result($result,0,'campaigngroupid');
        while ($row = mysql_fetch_assoc($result)) {
            echo "<OPTION VALUE=\"".$row[id]."\">".$row[name]."</OPTION>";
        }
        ?>
        </SELECT>

    </TD>
    </TR><TR>
    <TD COLSPAN=2 ALIGN="CENTER">
    <INPUT class="btn btn-primary" TYPE="SUBMIT" VALUE="Display Numbers">
    </TD>
    </TR></table>
    </FORM>
    <?
} else {
?>
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2">
<TR>
<TD CLASS="thead">
Number
</TD>
<TD CLASS="thead">
Status
</TD>
<TD CLASS="thead">

</TD>
</TR>

<?
$sql = 'SELECT * FROM number WHERE campaignid='.$_POST[campaignid]." LIMIT 50";
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
<?echo $row[status];?>
</TD>
<TD>
<a  class="btn btn-default navbar-btn" HREF="deletenumber.php?campaignid=<?echo $_POST[campaignid];?>&number=<?echo $row[phonenumber];?>">Delete</A>
</TD>
</TR>

<?
}

?>

</TABLE>
<?
}
require "footer.php";*/
?>
