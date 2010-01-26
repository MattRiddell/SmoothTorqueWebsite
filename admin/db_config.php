<?
if (!isset($config_values) || $config_values['ST_MYSQL_HOST'] == "") {
    $db_host="localhost";
    $db_user="root";
    $db_pass="";
    $link = mysql_connect($db_host, $db_user, $db_pass) OR die(mysql_error());
} else {
    $db_host=$config_values['ST_MYSQL_HOST'];
    $db_user=$config_values['ST_MYSQL_USER'];
    $db_pass=$config_values['ST_MYSQL_PASS'];
    $link = mysql_connect($db_host, $db_user, $db_pass) OR die(mysql_error());
}
if (!mysql_select_db("SineDialer")) {
	$result = mysql_query("CREATE DATABASE SineDialer");
	mysql_select_db("SineDialer");
}
?>
