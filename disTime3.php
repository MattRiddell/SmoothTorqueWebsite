<?
/* Find out what the base directory name is for two reasons:
    1. So we can include files
    2. So we can explain how to set up things that are missing */
$current_directory = dirname(__FILE__);

/* What page we are currently on - this is used to highlight the menu
   system as well as to not cache certain pages like the graphs */
$self=$_SERVER['PHP_SELF'];

/* Load in the functions we may need - these are the list of available
   custom functions - for more information, read the comments in the
   functions.php file - most functions are in their own file in the
   functions subdirectory */
require "/".$current_directory."/functions/functions.php";

$url = "default";
include "admin/db_config.php";
mysql_select_db("SineDialer", $link) or die("Unable to connect: ".mysql_error());
$totalcost = array();
//echo "Loading config information...\n";
$result_config = mysql_query("SELECT * FROM web_config WHERE LANG = 'en' AND url = '$url'") or die(mysql_error());
if (mysql_num_rows($result_config) == 0) {
    echo "Even though we were sucessful reading the config, it has no values.  Please send an email to smoothtorque@venturevoip.com";
    exit(0);
}
/* Now that we have the config values, put them into the array */
while ($header_row = mysql_fetch_assoc($result_config) ) {
    foreach ($header_row as $key=>$value) {
        if ($key != "contact_text") {
            $config_values[strtoupper($key)] = $value;
        } else {
            $config_values["TEXT"] = $value;
        }
    }
}

if (isset($_GET[campaigngroupid])){
    $campaigngroupid = ($_GET[campaigngroupid]);
}
if (isset($_POST[id])){
    $id = $_POST[id];
}
mysql_select_db("SineDialer", $link);
$sql = 'SELECT value FROM config WHERE parameter=\'backend\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$row = mysql_fetch_assoc($result);
$backend=$row[value];
$level=$_COOKIE[level];
if ($level==sha1("level100") && $_GET[type]=="all") {
    $sql = 'SELECT * FROM campaign order by name';
} else {
    $sql = 'SELECT * FROM campaign WHERE groupid='.$campaigngroupid.' order by name';
}
$result=mysql_query($sql, $link) or die (mysql_error());;
if (mysql_num_rows($result)==0){
?>
<br /><br />
<?box_start();
echo "<br /><center><img src=\"/images/icons/gtk-dialog-info.png\" border=\"0\" width=\"64\" height=\"64\"><br /><br />";
?>

<b>You don't have any campaigns created.</b><br />
<br />
A campaign is a collection of phone <br />numbers you would like to call.<br />
<br />
<a href="addcampaign.php">
<img src="images/icons/gtk-add.png" border="0" width="64" height="64"><br />
Click here to create your first campaign</a><br /> or click the Add Campaign button above.
<br />
<br />
<?
box_end();
exit(0);
}
?>
    <?box_start(580);?>
    <center>
    <b>Key for the icons below:</b><br />
    <img width="16" height="16" src="/images/pencil.png" border="0">&nbsp;Edit Campaign&nbsp;
    <img width="16" height="16" src="/images/chart_pie.png" border="0">&nbsp;View Number Statistics&nbsp;
    <img width="16" height="16" src="/images/control_stop_blue.png" border="0"> Stop Campaign
    <img width="16" height="16" src="/images/control_play_blue.png" border="0"> Start Campaign
    <br />
<?    if ($config_values['ALLOW_NUMBERS_MANUAL'] == "YES") {?>
    <img width="16" height="16" src="/images/database_lightning.png" border="0"> Initialise Manual Dialing
    <?}?>
    <img width="16" height="16" src="/images/chart_curve.png" border="0"> Realtime Campaign Monitor
    <img width="16" height="16" src="/images/table.png" border="0"> View Numbers
    <img width="16" height="16" src="/images/delete.png" border="0"> Delete Campaign
    </center>
    <?box_end();?><br />

<?
$user = $_COOKIE[user];
?>
<table class="" align="center" border="0" cellpadding="2" cellspacing="0">
<TR>
<td style="background-image: url(/images/clb.gif);" width=2></td>

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
<TD CLASS="thead">

</TD>
<TD CLASS="thead">

</TD>
<?if ( $config_values['USE_BILLING'] == "YES") {?>
<TD CLASS="thead">
Cost
</TD>
<?}?>



<TD CLASS="thead">
Percentage Busy
</TD>
<td style="background-image: url(/images/crb.gif);" width=2></td>
</TR>
<?
while ($row = mysql_fetch_assoc($result)) {

$sql = 'SELECT status, flags, maxcalls, progress from queue where campaignid='.$row[id];
$resultx=mysql_query($sql, $link) or die (mysql_error());;
$rowx = mysql_fetch_assoc($resultx);

$status=$rowx[status];
$flags=$rowx[flags];
$maxcalls=$rowx[maxcalls];
$progress=$rowx[progress];



    flush();
    $row = array_map(stripslashes,$row);
    if ($status == 101) {
        $class=" class=\"tborder_active\"  onmouseover=\"style.backgroundColor='#84DFC1';\" onmouseout=\"style.backgroundColor='#88f888'\"   ";
    } else if ($toggle){
        $toggle=false;
        $class=" class=\"tborder2\"  onmouseover=\"style.backgroundColor='#84DFC1';\" onmouseout=\"style.backgroundColor='#f8f8f8'\"   ";
    } else {
        $toggle=true;
        $class=" class=\"tborderx\"  onmouseover=\"style.backgroundColor='#84DFC1';\" onmouseout=\"style.backgroundColor='#f0f0f0'\" ";
    }

?>
<TR <?echo $class;?>>
<td></td>
<TD>
<?
if (strlen($row[name])<35){
echo "<A title=\"Edit this campaign\" HREF=\"editcampaign.php?id=".$row[id]."\"><img width=\"16\" height=\"16\" src=\"/images/pencil.png\" border=\"0\" align=\"right\" title=\"Edit This Campaign\">".$row[name]."</A>";
} else {
echo "<A title=\"Edit this campaign\" HREF=\"editcampaign.php?id=".$row[id]."\"><img width=\"16\" height=\"16\" src=\"/images/pencil.png\" border=\"0\" align=\"right\" title=\"Edit This Campaign\">".trim(substr($row[name],0,35))."...</A>";
}
?>
</TD>
<TD>
<?
$max_str_len = 45;

if (strlen($row[description])<$max_str_len){
echo $row[description];
} else {
echo trim(substr($row[description],0,$max_str_len))."...";
}
?>
</TD>
<?
/*
$sql = 'SELECT count(*) from number where campaignid='.$row[id].' and (status="manual_dial" or status="new" or status="no-credit")';
$result2=mysql_query($sql, $link) or die (mysql_error());;
$new_numbers=mysql_result($result2,0,'count(*)');

$sql = 'SELECT count(*) from number where campaignid='.$row[id].' and (status="manual_dial" or status="no-credit")';
$result2=mysql_query($sql, $link) or die (mysql_error());;
$manual_numbers=mysql_result($result2,0,'count(*)');

$sql = 'SELECT count(*) from number where campaignid='.$row[id];
$result2=mysql_query($sql, $link) or die (mysql_error());;
$total_numbers=mysql_result($result2,0,'count(*)');
*/


?>
<TD>
<?if ($backend == 0) {?>
<a title="View the report for this campaign" href="report.php?type=today&id=<?echo $row[id];?>" class="abcd"><img width="16" height="16" src="/images/chart_pie.png" border="0"></a>
<?}?>
<?/*
if ($progress>0){
            ?>
            <img src="/images/percentImage.png" width="123" height="12" title="<?
            echo "Remaining: $new_numbers/$total_numbers\"";?>"
            class="percentImage"
            style="background-position: -<?echo ((100-(($new_numbers/$total_numbers)*100))*1.2)-1; ?>px 0pt;" border="0" />
<?
}else if ($manual_numbers > 0){
            ?>
            <img src="/images/percentImage.png" width="123" height="12" title="<?
            echo "Remaining: $new_numbers/$total_numbers\"";?>"
            class="percentImage3"
            style="background-position: -<?echo ((100-(($new_numbers/$total_numbers)*100))*1.2)-1; ?>px 0pt;" border="0" />
<?
}else {
            if ($total_numbers > 0) {
            ?>
            <img src="/images/percentImage.png" width="123" height="12" title="<?
            echo "Remaining: $new_numbers/$total_numbers\"";?>"
            class="percentImage2"
            style="background-position: -<?echo ((100-(($new_numbers/$total_numbers)*100))*1.2)-1; ?>px 0pt;" border="0" />
<?
            } else {
            ?>
            <img src="/images/percentImage.png" width="123" height="12" title="<?
            echo "Remaining: $new_numbers/$total_numbers\"";?>"
            class="percentImage2"
            style="background-position: -<?echo ((100-((0)*100))*1.2)-1; ?>px 0pt;" border="0" />
<?
            }
}
*/?>
</TD>


<TD>

<?
if ($maxcalls>0){
$perc=round(($flags/$maxcalls)*100);
} else {
$perc=0;
}
if ($perc>100){
    $perc=100;
}
if ($status==101){
?>
<IMG SRC="/images/control_play.png" BORDER="0" width="16" height="16" >
</TD>
<td>
<?if ($user!="demo"){?>
<a title="Stop running this campaign" href="stopcampaign.php?id=<?echo $row[id];?>"><img width="16" height="16" src="/images/control_stop_blue.png" border="0"></a>
<?} else {?>
<a href="#" title="Stop campaign (Not running)"><img width="16" height="16" src="/images/control_stop_blue.png" border="0"></a>
<?
}
} else {

?>
<?if ($user!="demo"){?>
<a title="Start running this campaign" href="startcampaign.php?id=<?echo $row[id];?>&astqueuename=<?echo $row[astqueuename];?>&clid=<?echo $row[clid];?>&trclid=<?echo $row[trclid];?>&agents=<?echo $row[maxagents];?>&did=<?echo $row[did];?>&context=<?echo $row[context];?>">
<IMG width="16" height="16" SRC="/images/control_play_blue.png" BORDER="0"></a><br>
<?} else {?>
<a href="#" title="Start campaign (Already started)"><IMG width="16" height="16" SRC="/images/control_play_blue.png" BORDER="0"></a><br>
<?}?>
</TD>
<td>
<img width="16" height="16" src="/images/control_stop.png" border="0" title="Stop running campaign">
<?
}


if ($config_values['ALLOW_NUMBERS_MANUAL'] == "YES") {
?>
<a href="manual_init.php?campaignid=<?=$row[id]?>">
<img width="16" height="16" src="/images/database_lightning.png" border="0" title="Initialise campaign for manual dialing">
</a>
<?
}

?>
</td>
<TD>
<?if ($backend == 0) {?>
<a title="View the graph for this campaign" href="test.php?id=<?echo $row[id];?>" class="abcd"><img width="16" height="16" src="/images/chart_curve.png" border="0"></a>&nbsp;
<?}?>
<a title="Recycle Numbers" href="recycle.php?id=<?echo $row[id];?>&type_input=<?echo $_GET[type];?>" class="abcd"><img width="16" height="16" src="/images/arrow_refresh.png" border="0"></a>&nbsp;
<a title="List Numbers" href="viewnumbers.php?campaignid=<?echo $row[id];?>" class="abcd"><img width="16" height="16" src="/images/table.png" border="0"></a>&nbsp;
<?
if ($user!="demo"){
echo "<A title=\"Delete this campaign\" HREF=\"deletecampaign.php?id=".$row[id]."\"><IMG width=\"16\" height=\"16\" SRC=\"/images/delete.png\" BORDER=\"0\"></A>";
} else {
echo "<A title=\"Delete this campaign\" HREF=\"#\"><IMG SRC=\"/images/delete.png\" BORDER=\"0\" width=\"16\" height=\"16\" ></A>";
}
?>
</TD>
<?if ( $config_values['USE_BILLING'] == "YES") {?>
<TD>
<?
if ($row[cost]>0) {
    echo '<A HREF="viewcdr_campaign.php?campaignid='.$row[id].'">';
    echo $config_values['CURRENCY_SYMBOL']." ".number_format($row[cost],2)."</a>";
} else {
    echo "-";
}
?>
</TD>
<?}?>

<td>

<img src="/images/percentImage.png" width="123" height="12" title="<?echo
$perc;?>% of staff are busy"
class="percentImage"
style="background-position: -<?echo 119-($perc*1.2); ?>px 0pt;" border="0" />

</td>

<td></td>
</TR>

<?
}
?>

</TABLE>
<?

?>
