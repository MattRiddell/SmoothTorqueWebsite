<?
require "header.php";
$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');
require "header_campaign.php";

$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);

if (!isset($_POST[campaignid])&&!isset($_GET[campaignid])){
    ?>


    <br /><br /><br /><br />
<center>
<table background="images/sdbox.png" width="300" height="200" class="dragme22">
<tr>
<td>
</td>
<td width="260">
<b>View Numbers</b><br /><br />
From here you can chose a campaign that you would like to see the numbers for.<br /><br />
<FORM ACTION="viewnumbers.php" METHOD="POST">
    <table class="tborderdd" align="center" border="0" cellpadding="0" cellspacing="2"><TR>
    <TD>Select Campaign:</TD><TD>
        <SELECT  class="form-control" NAME="campaignid">
        <?
        $sql = 'SELECT id,name FROM campaign WHERE groupid='.$campaigngroupid;
        $result=mysql_query($sql, $link) or die (mysql_error());;
        //$campaigngroupid=mysql_result($result,0,'campaigngroupid');
        while ($row = mysql_fetch_assoc($result)) {
            echo "<OPTION VALUE=\"".$row[id]."\">".substr($row[name],0,22)."</OPTION>";
        }
        ?>
        </SELECT>

    </TD>
    </TR><TR>
    <TD COLSPAN=2 ALIGN="CENTER"><br />
    <INPUT class="btn btn-primary" TYPE="SUBMIT" VALUE="Display Numbers">
    </TD>
    </TR></table>
    </FORM><br />
</td>
<td>
</td></tr>
</table>
</center>







    <?
} else {
if (isset($_GET[campaignid])){
    $_POST[campaignid]=$_GET[campaignid];
}
?>
<br />
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
$start=0;
if(isset($_POST[campaignid])){
    $campaignid=$_POST[campaignid];
} else {
    $campaignid=$_GET[campaignid];
}
if ($_GET[start]>0){
    $start=$_GET[start];
}
$sql = 'SELECT count(*) FROM number WHERE campaignid='.$campaignid.' and status=\''.$_GET[type].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$max=mysql_result($result,0,'count(*)');

$sql = 'SELECT * FROM number WHERE campaignid='.$campaignid.' and status=\''.$_GET[type].'\' LIMIT '.$start.',20';
$result=mysql_query($sql, $link) or die (mysql_error());;
//$campaigngroupid=mysql_result($result,0,'campaigngroupid');

echo '<a href="viewnumbers.php?campaignid='.$campaignid.'&status='.$_GET[type].'&start=0"><img src="images/resultset_first.png" border="0"></a> ';
echo '<a href="viewnumbers.php?campaignid='.$campaignid.'&status='.$_GET[type].'&start='.($start-20).'"><img src="images/resultset_previous.png" border="0"></a> ';

for ($x=$start;$x<$start+200;$x+=20){
echo '<a href="viewnumbers.php?campaignid='.$campaignid.'&status='.$_GET[type].'&start='.$x.'">'.($x/20).'</a> ';
}
echo '<a href="viewnumbers.php?campaignid='.$campaignid.'&status='.$_GET[type].'&start='.($x+20).'"><img src="images/resultset_next.png" border="0"></a> ';
echo '<a href="viewnumbers.php?campaignid='.$campaignid.'&status='.$_GET[type].'&start='.(($max-20)+$max%20).'"><img src="images/resultset_last.png" border="0"></a> ';
echo '<br />';
echo '<br />';
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
<A TITLE="Delete this Number" HREF="deletenumber.php?campaignid=<?echo $_POST[campaignid];?>&number=<?echo $row[phonenumber];?>"><img src="images/delete.png" border="0" alt="Delete Number"></A>
<?
if ($row[status] != "new") {
?>
<A TITLE="Reset the status of this Number" HREF="resetlist.php?campaignid=<?echo $_POST[campaignid];?>&type=<?echo $_GET[type];?>&number=<?echo $row[phonenumber];?>"><img src="images/control_repeat_blue.png" border="0" alt="Reset Number"></A>
<?
}
?>
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

