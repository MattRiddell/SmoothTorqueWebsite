<?

require "header.php";
echo "<br />";
echo "<table cellspacing=\"1\" cellpadding=\"7\">";
echo "<tr>
<td bgcolor=\"#000000\"><font color=\"#ffffff\">Campaign ID</td>
<td bgcolor=\"#000000\"><font color=\"#ffffff\">Description</td>
<td bgcolor=\"#000000\"><font color=\"#ffffff\">Channels</td>
<td bgcolor=\"#000000\"><font color=\"#ffffff\">Average Busy</td>
<td bgcolor=\"#000000\"><font color=\"#ffffff\">TimeSpent</td>
<td bgcolor=\"#000000\"><font color=\"#ffffff\">Dialed</td>
<td bgcolor=\"#000000\"><font color=\"#ffffff\">Overall CPS</td>
</tr>";
$result = mysql_query("select id, description, campaignconfigid from campaign where name like 'load-sim-%' order by id") or die(mysql_error());
if (mysql_num_rows($result) > 0) {
	while ($row = mysql_fetch_assoc($result)) {
		$avg_result = mysql_query("SELECT sum(value), count(idx) FROM SineDialer.profracs WHERE campaignid = ".$row['id']);
		if (mysql_num_rows($avg_result) > 0) {
			$row_avg = mysql_fetch_assoc($avg_result);
			if ($row_avg['count(idx)'] > 0) {
				$avg = number_format($row_avg['sum(value)']/$row_avg['count(idx)'], 2)."%";
				$bold = true;
			} else {
				$avg = "N/A";
				$bold = false;
			}
		} else {
			$avg = "N/A";
			$bold = false;
		}
		
		$slp_result = mysql_query("SELECT sum(value), count(idx) FROM SineDialer.sleeps WHERE campaignid = ".$row['id']);
		if (mysql_num_rows($slp_result) > 0) {
			$row_slp = mysql_fetch_assoc($slp_result);
			if ($row_slp['count(idx)'] > 0) {
				$slp_avg = number_format(1000/(($row_slp['sum(value)']/$row_slp['count(idx)'])),2);
				$bold = true;
			} else {
				$slp_avg = "N/A";
				$bold = false;
			}
		} else {
			$slp_avg = "N/A";
			$bold = false;
		}
		
		
		$result_inner = mysql_query("select * from campaign_stats where campaignid = ".$row['id']);
		if (mysql_num_rows($result_inner) > 0) {
			while ($row_inner = mysql_fetch_assoc($result_inner)) {
				$timespent = ($row_inner['time_spent']);
				$dialed = $row_inner['dialed'];
				$ms_sleep = $row_inner['ms_sleep'];
				
				foreach ($row_inner as $key=>$value) {
					//echo "Key: $key Value: $value<br />";
				}
			}
		} else {
				$timespent = 0;
				$dialed = "N/A";
				$ms_sleep = "N/A";
		}
		if ($bold) {
//			echo "<b>";
		}
		if ($timespent > 0) {
			$avg_cps = number_format($dialed/$timespent,2);
		} else {
			$avg_cps = 0;
		}
		echo "<tr>";
		echo "<td bgcolor=\"#eeeeee\"><a href=\"test.php?id=".$row['id']."&debug=1\">".$row['id']."</a></td>";
		echo "<td bgcolor=\"#eeeeee\">".$row['description']."</td>";
		echo "<td bgcolor=\"#eeeeee\">".$row['campaignconfigid']."</td>";
		echo "<td bgcolor=\"#eeeeee\">$avg</td>";
		echo "<td bgcolor=\"#eeeeee\">".sec2hms($timespent)."</td>";
		echo "<td bgcolor=\"#eeeeee\">$dialed</td>";
		echo "<td bgcolor=\"#eeeeee\">".$avg_cps."</td>";
		echo "</tr>";
		if ($bold) {
//			echo "</b>";
		}
		
	}
}
?>
</table>
<meta http-equiv="refresh" content="5;url=test_results.php">
<?
require "footer.php";
?>
