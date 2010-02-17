<?
require "admin/db_config.php";
while (1) {
//	echo $link;
	while (!@mysql_ping ($link)) {
		echo "Link down\n";
		@mysql_close($link);
		$link = @mysql_connect($db_host, $db_user, $db_pass);
		if (@mysql_ping($link)) {
			@mysql_select_db("SineDialer") or die(mysql_error());
		}
		sleep(1);
	}
	$result = mysql_query("SELECT campaignid FROM queue WHERE status = 101");
	echo "Image Caching Script\n";
	echo "====================\n";
	echo "Running Campaigns: ".mysql_num_rows($result)."\n";
	if (mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_assoc($result)) {
			echo "Found campaign $row[campaignid]...\n";
			if ($stream = fopen('http://italk.venturevoip.com/graph.php?id='.$row[campaignid], 'rb')) {
				$contents = stream_get_contents($stream);
				$output = fopen('images/live/campaign_'.$row[campaignid].'.png', 'wb');
				fwrite($output, $contents);
				fclose($stream);
				fclose($output);
				echo "Saved campaign $row[campaignid]\n";
			}
			if ($stream = fopen('http://italk.venturevoip.com/graph.php?id='.$row[campaignid].'&debug=1', 'rb')) {
				$contents = stream_get_contents($stream);
				$output = fopen('.images/live/debug_'.$row[campaignid].'.png', 'wb');
				fwrite($output, $contents);
				fclose($stream);
				fclose($output);
				echo "Saved debug campaign $row[campaignid]\n";
			}
		}
	}
	echo "=====================\n";
	echo "Sleeping For Next Run\n";
	echo "=====================\n";
	sleep(1);
	echo "=====================\n";
	echo "Sleeping For Next Run\n";
	echo "=====================\n";
	sleep(1);
}
?>
