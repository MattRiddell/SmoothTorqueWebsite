<?
/* Include the libraries for drawing graphs 
include("./jpgraph.php");
include("./jpgraph_pie.php");
include("./jpgraph_pie3d.php");
$graph = new PieGraph(730, 450,  "auto");
$graph -> SetScale("textlin");

exit(0);*/
mysql_pconnect("localhost","root","");
$result = mysql_query("SELECT count(*), campaignid, status from SineDialer.number group by campaignid,status order by campaignid");
echo "<pre>";
$campaignid = -1;
?>
<style>
th {
	background: #008;
	color: #ff4;
	border: 1px solid #ccc;
}
td {
	border: 1px solid #ccc;
}
</style>
<?
echo "<div style=\"width:100%;float:left\"><span><table>";

while ($row = mysql_fetch_assoc($result)) {
//	print_r($row);
	if ($row['campaignid'] != $campaignid) {
		$result_x = mysql_query("SELECT name FROM SineDialer.campaign where id = ".$row['campaignid']) or die(mysql_error());
		$name = mysql_result($result_x,0,0);
		echo "</table></span>";
		$campaignid = $row['campaignid'];
		echo "<span style=\"float: left;padding: 10px;\"><table border=\"0\" padding=\"0\">";
		echo "<tr><th>Campaign</th><th>Status</th><th>Count</th></tr>";
	}
	if ($row['status'] == "new") {
		$row['status'] = '<b>New</b>';
		$row['count(*)'] = '<b>'.$row['count(*)'].'</b>';
		$name2 = '<b>'.$name.'</b>';
	} else {
		$name2 = $name;
	}
	echo "<tr><td>$name2</td><td>$row[status]</td><td>".$row['count(*)']."</td></tr>";
}
echo "</table></span></div><div style=\"width:100%;float:left\">";
mysql_pconnect("192.168.1.17","popper","pass*()");
//CONVERT_TZ(sugarcrm.calls.date_start, '+0:00', 'SYSTEM')
//$result = mysql_query("SELECT count(*), lead_source from sugarcrm.st_calls, sugarcrm.leads where DATE(event_datetime) = CURDATE() and sugarcrm.st_calls.id = sugarcrm.leads.id group by sugarcrm.leads.lead_source order by count(*) desc");

	$result = mysql_query("select distinct lead_source from sugarcrm.leads where DATE(CONVERT_TZ(sugarcrm.leads.date_entered, '+0:00', 'SYSTEM')) = DATE_SUB(CURDATE(),INTERVAL 0 DAY) and deleted = 0 order by lead_source asc");
$x = 0;
echo "<center>";
while ($row = mysql_fetch_assoc($result)) {
//	print_r($row);
	if ($row['lead_source'] == "") {
//		$row['lead_source'] = "null";
	}
	$img = "billboard_pie.php?lead_source=".urlencode($row['lead_source']);
	echo '<img src="'."$img\">";
	$x++;
	if ($x > 1) {
		$x = 0;
		echo "<br />";
	}
}
exit(0);
$result = mysql_query("select sugarcrm.leads.lead_source, count(*), sugarcrm.lc_customstatus.name from sugarcrm.leads, sugarcrm.lc_customstatus where DATE(CONVERT_TZ(sugarcrm.leads.date_entered, '+0:00', 'SYSTEM')) = DATE_SUB(CURDATE(),INTERVAL 1 DAY)  and sugarcrm.leads.status = sugarcrm.lc_customstatus.id group by sugarcrm.leads.lead_source, sugarcrm.lc_customstatus.name order by lead_source asc, count(*) desc");

$source = "something";
echo "<hr>";
echo '<span style="float: left"><table>';
$x =0;
while ($row = mysql_fetch_assoc($result)) {
	if ($source != $row['lead_source']) {
		$x++; 
		if ($x > 3) {
			$x =0;
			echo "</table></span></div><div style=\"width:100%;float:left\">\n<span style=\"float:left\">";
		}
		$source = $row['lead_source'];
		echo "</table></span>";
		echo '<span style="float: left;padding: 10px"><table>';
		echo "<tr><th>Lead Source</th><th>Status</th><th>Count</th></tr>";
	
	}
	if (strlen(trim($source)) < 1) {
		$print_source = "<b>No Lead Source</b>";
	} else {
		$print_source = $source;
	}
	echo "<tr><td>$print_source</td><td>".$row['name']."</td><td>".$row['count(*)']."</td></tr>";
//	print_r($row);
}
	echo '</table></span>';
echo "</div>";
?>
