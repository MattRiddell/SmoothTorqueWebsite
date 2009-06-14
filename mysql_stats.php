<?
require "header.php";
require "header_server.php";
$result = mysql_query("SHOW STATUS");
while ($row = mysql_fetch_assoc($result)){
	if ($row[Variable_name] == 'Slow_queries') {
		$slow_queries = $row['Value'];
	}
	if ($row[Variable_name] == 'Questions') {
		$questions = $row['Value'];
	}
	if ($row[Variable_name] == 'Uptime') {
		$uptime = sec2hms($row['Value']);
	}
	if ($row[Variable_name] == 'Connections') {
		$connections = number_format($row['Value']);
	}
}
box_start(600);
echo "<center>";
echo "<b>MySQL Statistics:<br /></b>";
echo "Connections: $connections Queries: ".number_format($questions)." Uptime: $uptime";
box_end();
$result = mysql_query("SHOW TABLE STATUS");
?>
<center><table class="tborder2" boreder="1" cellpadding="5">
<tr>
<td CLASS="thead">Name</td>
<td CLASS="thead">Rows</td>
<td CLASS="thead">Total Size</td>
<td CLASS="thead">Last Update</td>
<td CLASS="thead">Last Check</td>
</tr>
<?
while ($row = mysql_fetch_assoc($result)) {
//	print_pre($row);
	$size = ($row['Data_length']+$row['Index_length'])/1048576;
	$rows = $row[Rows];
	if ($size > 0 && $rows > 0) {
//		if ($toggle){
//			$toggle=false;
//			$class=" class=\"tborder2\"  onmouseover=\"style.backgroundColor='#84DFC1';\" onmouseout=\"style.backgroundColor='#f8f8f8'\"   ";
//		} else {
//			$toggle=true;
//			$class=" class=\"tborderx\"  onmouseover=\"style.backgroundColor='#84DFC1';\" onmouseout=\"style.backgroundColor='#f0f0f0'\" ";
//		}
		echo "<tr ".$class.">";

		if ($size < 1) {
			$size *= 1024;
			$tdstyle = " style=\"background: #ddffdd\"";
			$size_text = "Kb";
			$digits = 0;
		} else if ($size<1024) {
			$tdstyle = " style=\"background: #ffdd88\"";
			$size_text = "Mb";
			$digits = 1;
		} else if ($size <10240) {
			$tdstyle = " style=\"background: #ff8844\"";
			$size/=1024;
	                $size_text = "Gb";
			$digits = 2;
		} else {
			$tdstyle = " style=\"background: #ff4444\"";
			$size/=1024;
	                $size_text = "Gb";
			$digits = 4;
		}
		echo "<td".$tdstyle.">$row[Name]</td>";
		echo "<td".$tdstyle.">".number_format($rows)."</td>";
		echo "<td".$tdstyle."><b>".number_format($size,$digits)." $size_text</b></td>";
		$update_time = strtotime($row[Update_time]);
		$check_time = strtotime($row[Check_time]);
		echo "<td".$tdstyle.">".date("D M j G:i:s Y", $update_time)."</td>";
		echo "<td".$tdstyle.">".date("D M j G:i:s Y", $check_time)."</td>";
		echo "</tr>";
	}
}
require "footer.php";
?>
