<?
require "header.php";
$sql = 'SELECT security,campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');
$security=mysql_result($result,0,'security');
require "header_numbers.php";

$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);

if (!isset($_POST[campaignid])&&!isset($_GET[campaignid])){
    ?>


    <br /><br /><br /><br />
<center>
<table background="/images/sdbox.png" width="300" height="200" class="dragme22">
<tr>
<td>
</td>
<td width="260">
<b>View Numbers</b><br /><br />
From here you can chose a campaign that you would like to see the numbers for.<br /><br />
<FORM ACTION="viewnumbers.php" METHOD="POST">
    <table class="tborderdd" align="center" border="0" cellpadding="0" cellspacing="2"><TR>
    <TD>Select Campaign:</TD><TD>
        <SELECT NAME="campaignid">
        <?
        $sql = 'SELECT id,name FROM campaign WHERE groupid='.$campaigngroupid;
	if($security >= 100)
        	$sql = 'SELECT id,name FROM campaign';
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
    <INPUT TYPE="SUBMIT" VALUE="Display Numbers">
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
Last Updated
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
$sql = 'SELECT count(*) FROM number WHERE campaignid='.$campaignid;
$result=mysql_query($sql, $link) or die (mysql_error());;
$max=mysql_result($result,0,'count(*)');
if (isset($_GET[type])){
    if ($_GET[type]!="all") {
        if ($_GET[type]!="unknown") {
            if (strlen($_GET[type])>0){
            $type = " and status='$_GET[type]'";
            }
        } else {
            $type = " and status like 'unknown%'";
        }
    }
}

$sql = 'SELECT *, UNIX_TIMESTAMP(datetime) as newdate FROM number WHERE campaignid='.$campaignid.' '.$type.' order by status asc LIMIT '.$start.','.$config_values['PER_PAGE'];
$result=mysql_query($sql, $link) or die (mysql_error());;
//$campaigngroupid=mysql_result($result,0,'campaigngroupid');
if ($_GET[type]!="all") {
    echo '<a href="viewnumbers.php?campaignid='.$campaignid.'&start=0&type=all">All Numbers</a> &nbsp;';
} else {
    echo "<b>All Numbers</b>&nbsp;";
}
if ($_GET[type]!="pressed1") {
    echo '<a href="viewnumbers.php?campaignid='.$campaignid.'&start=0&type=pressed1">Pressed 1</a> &nbsp;';
} else {
    echo "<b>Pressed 1</b>&nbsp;";
}
if ($_GET[type]!="busy") {
    echo '<a href="viewnumbers.php?campaignid='.$campaignid.'&start=0&type=busy">Busy</a> &nbsp;';
} else {
    echo "<b>Busy</b>&nbsp;";
}
if ($_GET[type]!="failed") {
    echo '<a href="viewnumbers.php?campaignid='.$campaignid.'&start=0&type=failed">Failed</a> &nbsp;';
} else {
    echo "<b>Failed</b>&nbsp;";
}
if ($_GET[type]!="amd") {
    echo '<a href="viewnumbers.php?campaignid='.$campaignid.'&start=0&type=amd">Answer Machine</a> &nbsp;';
} else {
    echo "<b>Answer Machine</b>&nbsp;";
}
if ($_GET[type]!="unknown") {
    echo '<a href="viewnumbers.php?campaignid='.$campaignid.'&start=0&type=unknown">Unknown</a> &nbsp;';
} else {
    echo "<b>Unknown</b>&nbsp;";
}
if ($_GET[type]!="hungup") {
    echo '<a href="viewnumbers.php?campaignid='.$campaignid.'&start=0&type=hungup">Answered</a> &nbsp;';
} else {
    echo "<b>Answered</b>&nbsp;";
}
if ($_GET[type]!="congested") {
    echo '<a href="viewnumbers.php?campaignid='.$campaignid.'&start=0&type=congested">Congested</a> &nbsp;';
} else {
    echo "<b>Congested</b>&nbsp;";
}
echo "<br />";

echo '<a href="viewnumbers.php?campaignid='.$campaignid.'&type='.$_GET[type].'&start=0"><img src="/images/resultset_first.png" border="0"></a> ';
echo '<a href="viewnumbers.php?campaignid='.$campaignid.'&type='.$_GET[type].'&start='.($start-$config_values['PER_PAGE']).'"><img src="/images/resultset_previous.png" border="0"></a> ';

for ($x=$start;$x<$start+($config_values['PER_PAGE']*10);$x+=$config_values['PER_PAGE']){
echo '<a href="viewnumbers.php?campaignid='.$campaignid.'&type='.$_GET[type].'&start='.$x.'">'.($x/$config_values['PER_PAGE']).'</a> ';
}
echo '<a href="viewnumbers.php?campaignid='.$campaignid.'&type='.$_GET[type].'&start='.($x+$config_values['PER_PAGE']).'"><img src="/images/resultset_next.png" border="0"></a> ';
echo '<a href="viewnumbers.php?campaignid='.$campaignid.'&type='.$_GET[type].'&start='.(($max-$config_values['PER_PAGE'])+$max%$config_values['PER_PAGE']).'"><img src="/images/resultset_last.png" border="0"></a> ';
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
<TD><b>
<?echo $row[phonenumber];?>
</b>
</TD>
<TD>
<?
$newdate = date('l dS \of F Y h:i:s A', $row["newdate"]);

echo $newdate;?>
</TD>
<TD>
<?echo $row[status];?>
</TD>
<TD>
<A TITLE="Delete this Number" HREF="deletenumber.php?campaignid=<?echo $_POST[campaignid];?>&number=<?echo $row[phonenumber];?>"><img src="/images/delete.png" border="0" alt="Delete Number"></A>
<?
if ($row[status] != "new") {
?>
<A TITLE="Reset the status of this Number" HREF="resetnumber.php?campaignid=<?echo $_POST[campaignid];?>&number=<?echo $row[phonenumber];?>"><img src="/images/control_repeat_blue.png" border="0" alt="Reset Number"></A>
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
