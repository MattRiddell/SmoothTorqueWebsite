<?
require "admin/db_config.php";
while (1) {
	$result = mysql_query("SELECT campaignid FROM queue WHERE status = 101");
	if (mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_assoc($result)) {
			if ($stream = fopen('http://italk.venturevoip.com/graph.php?id='.$row[campaignid], 'rb')) {
				$contents = stream_get_contents($stream);
				$output = fopen('./images/live/campaign_'.$row[campaignid].'.png', 'wb');
				fwrite($output, $contents);
				fclose($stream);
				fclose($output);
				echo "Saved campaign $row[campaignid]\n";
			}
		}
	}
	sleep(5);
}
?>
