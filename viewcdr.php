<?
require "header.php";
$db_host=$config_values['CDR_HOST'];
$db_user=$config_values['CDR_USER'];
$db_pass=$config_values['CDR_PASS'];
$cdrlink = mysql_connect($db_host, $db_user, $db_pass) OR die(mysql_error());
mysql_select_db($config_values['CDR_DB'], $cdrlink);
$sql = "SELECT count(*) from ".$config_values['CDR_TABLE'];
$result = mysql_query($sql,$cdrlink);
$count = mysql_result($result,0,0);
//echo $count." Total Records";
$page = $_GET[page];
if ($_GET[page]>0) {
    $start = $_GET[page]*100;
} else {
    $start = 0;
}
echo '<a href="viewcdr.php?page=0"><img src="/images/resultset_first.png" border="0"></a> ';
if ($page > 0) {
    echo '<a href="viewcdr.php?page='.($page-1).'"><img src="/images/resultset_previous.png" border="0"></a> ';
}
if ($page > 5) {
    $pagex= $page-4;
} else {
    $pagex = 0;
}
for ($i = $pagex;$i<($count/100);$i++) {
    if ($i < $page + 20) {
        if ($page == $i) {
            echo "<b>$i</b> ";
        } else {
            echo '<a href="viewcdr.php?page='.$i.'">'.$i.'</a> ';
        }
    }
}

echo '<a href="viewcdr.php?page='.($page+1).'"><img src="/images/resultset_next.png" border="0"></a> ';
echo '<a href="viewcdr.php?page='.round($count/100).'"><img src="/images/resultset_last.png" border="0"></a> ';
$sql = "SELECT * from ".$config_values['CDR_TABLE']." order by calldate DESC LIMIT $start,100";
$result = mysql_query($sql,$cdrlink);
$i = 0;
echo "<table border=1>";
echo "<tr><td>CallDate</td><td>DContext</td><td>CLID</td><td>Channel</td><td>
DST Channel</td><td>Last App</td><td>Last Data</td><td>Duration</td><td>Billsec</td><td>Disposition</td><td>AccountCode</td><td>UserField</td></tr>";
while ($row = mysql_fetch_assoc($result)) {
    $calldate[$i] = $row[calldate];
    $dcontext[$i] = $row[dcontext];
    $dst[$i] = $row[dst];
    $src[$i] = $row[src];
    $clid[$i] = $row[clid];
    $channel[$i] = $row[channel];
    $dstchannel[$i] = $row[dstchannel];
    $lastapp[$i] = $row[lastapp];
    $lastdata[$i] = $row[lastdata];
    $duration[$i] = $row[duration];
    $billsec[$i] = $row[billsec];
    $disposition[$i] = $row[disposition];
    $amaflags[$i] = $row[amaflags];
    $accountcode[$i] = $row[accountcode];
    $userfield[$i] = $row[userfield];
    if ($disposition[$i] == "ANSWERED") {
        $td = "<td bgcolor=\"#00ff00\">";
    } else if ($disposition[$i] == "NO ANSWER") {
        $td = "<td bgcolor=\"#0000ff\">";
    } else if ($disposition[$i] == "FAILED") {
        $td = "<td bgcolor=\"#ff0000\">";
    } else if ($disposition[$i] == "BUSY") {
        $td = "<td bgcolor=\"#00ffff\">";
    } else {
        $td = "<td>";
    }
    echo     "<tr>";
    echo $td.$calldate[$i]."</td>$td".$dcontext[$i]."</td>$td".
    $clid[$i]."</td>$td".$channel[$i]."</td>$td".$dstchannel[$i]."</td>$td".
    $lastapp[$i]."</td>$td".$lastdata[$i]."</td>$td".$duration[$i]."</td>$td".$billsec[$i]."</td>$td<b>".
    $disposition[$i]."</b></td>$td".$accountcode[$i]."</td>$td".$userfield[$i]."</td>";
    echo "</tr>";
    $i++;
}
echo "</table>";
exit(0);
?>

<br /><br /><br /><br />
<center>
<table background="/images/sdbox.png" width="300" height="200" class="dragme22">
<tr>
<td>
</td>
<td width="260">
<b>Welcome to <?echo $config_values['TITLE'];?>.</b><br />
<br />
<?
echo $config_values['MAIN_PAGE_TEXT'];
?><br />
</td>
<td>
</td></tr>
</table>
</center>
<?
require "footer.php";
?>
