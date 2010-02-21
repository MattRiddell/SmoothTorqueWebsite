<?
require "admin/db_config.php";
$result = mysql_query("SELECT * FROM SineDialer.servers");
while ($row = mysql_fetch_assoc($result)) {
	$server_names[] = $row['name'];
	$escaped_name = str_replace(" "," ",$row['name']);
	$result2 = mysql_query("SELECT value FROM SineDialer.config WHERE parameter = 's_".$escaped_name."_calls'") or die(mysql_error());
	if (mysql_num_rows($result2) > 0) {
		$server_chans[] = mysql_result($result2,0,0);
	} else {
		$server_chans[] = 0;
	}
}
?>{"servers": [<?

for ($i = 0;$i<count($server_names);$i++) {
//	echo "Name: ".$server_names[$i]." Chans: ".$server_chans[$i]."\n";
	echo '{"name":"'.$server_names[$i].'", "chans":"'.$server_chans[$i].'"}';
}
?>],
<?

$result = mysql_query("SELECT value FROM SineDialer.engine_stats WHERE stat = 'funnel_usage'");
echo '"funnel":"'.mysql_result($result,0,0).'"';

?>
,
<?

$result = mysql_query("SELECT count(*) FROM SineDialer.queue WHERE status = 101");
echo '"campaigns":"'.mysql_result($result,0,0).'"';

?>
,
<?

$result = mysql_query("SELECT value FROM SineDialer.config WHERE parameter='mysql_queue'");
echo '"mysql":"'.mysql_result($result,0,0).'"';

?>
}
