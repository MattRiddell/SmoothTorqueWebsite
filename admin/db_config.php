<?
$config_file = "/stweb.conf";
$comment = "#";

if (file_exists($config_file)) {
$fp = fopen($config_file, "r");
while (!feof($fp)) {
  $line = trim(fgets($fp));
  if ($line && substr($line,0,1)!=$comment) {
    $pieces = explode("=", $line);
    $option = trim($pieces[0]);
    $value = trim($pieces[1]);
    $config_values_temp[$option] = $value;
  }
}
fclose($fp);
}
if ($config_values_temp['ST_MYSQL_HOST'] == "") {
    $db_host="localhost";
    $db_user="root";
    $db_pass="";
    $link = mysql_connect($db_host, $db_user, $db_pass) OR die(mysql_error());
} else {
    $db_host=$config_values_temp['ST_MYSQL_HOST'];
    $db_user=$config_values_temp['ST_MYSQL_USER'];
    $db_pass=$config_values_temp['ST_MYSQL_PASS'];
    $link = mysql_connect($db_host, $db_user, $db_pass) OR die(mysql_error());
}
if (!mysql_select_db("SineDialer")) {
	$result = mysql_query("CREATE DATABASE SineDialer");
	mysql_select_db("SineDialer");
}
?>
