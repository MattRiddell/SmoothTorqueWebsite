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
<td bgcolor=\"#000000\"><font color=\"#ffffff\">Average CPS</td>
<td bgcolor=\"#000000\"><font color=\"#ffffff\">Min CPS</td>
<td bgcolor=\"#000000\"><font color=\"#ffffff\">Max CPS</td>
<td bgcolor=\"#000000\"><font color=\"#ffffff\">Overall CPS</td>
<td bgcolor=\"#000000\"><font color=\"#ffffff\">Overs</td>
</tr>";
$result = mysql_query("select id, description, campaignconfigid from campaign where name like 'load-sim-%' order by id") or die(mysql_error());
if (mysql_num_rows($result) > 0) {
	while ($row = mysql_fetch_assoc($result)) {
		$avgx_result = mysql_query("SELECT min(value) FROM SineDialer.sleeps WHERE campaignid = ".$row['id']." and value > 0");
		if (mysql_num_rows($avgx_result) > 0) {
			$row_avgx = mysql_fetch_assoc($avgx_result);
			$min = $row_avgx['min(value)'];
			if ($min > 0) {
				$min = number_format(1000/$min,2);
			} else {
				$min = "N/A";
			}
		}
		$avg_result = mysql_query("SELECT value FROM SineDialer.profracs WHERE campaignid = ".$row['id']);
		$count = 0;
//		$avg_result = mysql_query("SELECT sum(value), count(idx) FROM SineDialer.profracs WHERE campaignid = ".$row['id']);
		if (mysql_num_rows($avg_result) > 0) {
			while ($row_avg = mysql_fetch_assoc($avg_result)) {
				$avg+=$row_avg['value'];
				$count++;
			}
			/*if ($row_avg['count(idx)'] > 0) {
				$avg = $row_avg['sum(value)'];
				$count = $row_avg['count(idx)'];
				//$avg = number_format($row_avg['sum(value)']/$row_avg['count(idx)'], 2)."%";
				$bold = true;
			} else {
				$avg = "N/A";
				$bold = false;
			}*/
		} else {
			$avg = "N/A";
			$bold = false;
		}
		
		$slp_result = mysql_query("SELECT sum(value), count(idx), min(value), max(value)  FROM SineDialer.sleeps WHERE campaignid = ".$row['id']);
		if (mysql_num_rows($slp_result) > 0) {
			$row_slp = mysql_fetch_assoc($slp_result);
			$max = $row_slp['max(value)'];
			if ($max > 0) {
				$max = number_format(1000/$max,2);
			} else {
				$max = "N/A";
			}
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
		unset ($overs_2);
		if (mysql_num_rows($result_inner) > 0) {
			while ($row_inner = mysql_fetch_assoc($result_inner)) {
				$timespent = ($row_inner['time_spent']);
				$dialed = $row_inner['dialed'];
				$ms_sleep = $row_inner['ms_sleep'];
				$overs_2 = round($row_inner['overs_1']/$timespent*100,2)."%";
				
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
//		echo "Average: $avg Timespent: $timespent Count: $count<br />";
		if ($count > $timespent) {
			$avg = $avg/$timespent;
		} else if ($count > 0) {
			$avg = $avg/$count;
		} else {
			$avg = 0.0;
		}
		$avg = number_format($avg,2)."%";
		echo "<tr>";
		echo "<td bgcolor=\"#eeeeee\"><a href=\"test.php?id=".$row['id']."&debug=1\">".$row['id']."</a></td>";
		echo "<td bgcolor=\"#eeeeee\">".$row['description']."</td>";
		echo "<td bgcolor=\"#eeeeee\">".$row['campaignconfigid']."</td>";
		echo "<td bgcolor=\"#eeeeee\">$avg</td>";
		echo "<td bgcolor=\"#eeeeee\">".sec2hms($timespent)."</td>";
		echo "<td bgcolor=\"#eeeeee\">$dialed</td>";
		echo "<td bgcolor=\"#eeeeee\">$slp_avg</td>";
		echo "<td bgcolor=\"#eeeeee\">$max</td>";
		echo "<td bgcolor=\"#eeeeee\">$min</td>";
		echo "<td bgcolor=\"#eeeeee\">".$avg_cps."</td>";
		echo "<td bgcolor=\"#eeeeee\">".$overs_2."</td>";
		echo "</tr>";
		if ($bold) {
//			echo "</b>";
		}
		
	}
}
?>
</table>
<?
require "footer.php";
?>
