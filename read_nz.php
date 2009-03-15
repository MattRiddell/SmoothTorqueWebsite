<? 
$fp = fopen("nz_geog.php", 'r');
unset($contents);
while (!feof($fp)) {
  $contents .= fread($fp, 8192);
}
fclose($fp);
//echo $contents;
$lines = explode("\n",$contents);
foreach ($lines as $line) {
	list($prefix,$variables) = explode(",",trim($line));
//	echo "Prefix: $prefix Variables: $variables\n";
//	echo $prefix;
	$val = pow(10,$variables);
	for ($i = 0;$i < $val; $i++) {
		$result = sprintf("%06d",$i);
		echo $prefix.$result."\n";
	}
//	echo "\n";
}
?>
