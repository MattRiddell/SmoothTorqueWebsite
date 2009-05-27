<?
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);


$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);
if (isset($_GET[queueID])){
    $sql = 'update queue set status='.$_GET[status].' where queueID='.$_GET[queueID];
    $result=mysql_query($sql, $link) or die (mysql_error());;
    header("Location: schedule.php?campaignid=".$_GET[campaignid]);
}

require "header.php";
$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');
require "header_schedule.php";
$sql = 'SELECT * FROM campaign JOIN schedule ON schedule.campaignid = campaign.id WHERE campaign.groupid='.$campaigngroupid.'';
//echo $sql."<br />";
$result=mysql_query($sql, $link) or die (mysql_error());;
if (mysql_num_rows($result) > 0) {
    ?><table align="center" border="0" cellpadding="2" cellspacing="0">
    <TR>
    <TD CLASS="thead">
    Name
    </TD>
    <TD CLASS="thead">
    Details
    </TD>
    <TD CLASS="thead">
    Campaign
    </TD>
    <TD CLASS="thead">
    Time
    </TD>
    <TD CLASS="thead">
    Regularity
    </TD>
    <TD  CLASS="thead">
    </TD>
    </TR>
    <?
    while ($row = mysql_fetch_assoc($result)) {
        if ($toggle){
            $toggle=false;
            $class=" class=\"tborder2\"";
        } else {
            $toggle=true;
            $class=" class=\"tborderx\"";
        }
        echo "<tr $class>";
        echo "<td>";
        echo "<a href=\"editschedule.php?id=$row[id]\">";
        if (strlen($row[name])>34){
            echo trim(substr($row[name],0,35))."...";
        } else {
            echo $row[name];
        }
        echo "<img src=\"/images/pencil.png\" border=\"0\" align=\"right\" title=\"Edit the schedule\"></a>";
        echo "</td>";
        echo "<td>";
        if (strlen($row[description])>34){
            echo trim(substr($row[description],0,35))."...";
        } else {
            echo "$row[description]";
        }
        echo "</td>";
        $sql2= 'SELECT name from campaign where id='.$row[campaignid];
        $result2=mysql_query($sql2, $link) or die (mysql_error());;
        $name=mysql_result($result2,0,'name');
        echo "<td>";
        echo "<a href=\"editcampaign.php?id=$row[campaignid]\">";
        if (strlen($name)>34){
            echo trim(substr($name,0,35))."...";
        } else {
            echo $name;
        }
        echo "<img src=\"/images/pencil.png\" border=\"0\" align=\"right\" title=\"Edit the campaign\"></a>";
        echo "</td>";
        echo "<td>$row[start_hour]:$row[start_minute]-$row[end_hour]:$row[end_minute]</td>";
        echo "<td>";
        switch ($row[regularity]) {
            case "every-day":
                echo "Daily";
                break;
            case "mon-fri":
                echo "Week Days";
                break;
            case "mon-sat":
                echo "Monday to Saturday";
                break;

        }
        echo "</td>";
        echo "<td>";
        echo "<a href=\"deleteschedule.php?id=$row[id]\"><IMG SRC=\"/images/cross.gif\" BORDER=\"0\" ALT=\"Delete Schedule\"></a>";
        echo "</td>";
        echo "</tr>";
    }
} else {  // No MySQL Rows Returned
    box_start(320);
    echo "<center><img src=\"/images/icons/gtk-dialog-info.png\" border=\"0\" width=\"64\" height=\"64\"><br /><b>You haven't created any schedules</b><br /><br />You can create one by clicking the add schedule button above";
    box_end();
}
require "footer.php";
?>
