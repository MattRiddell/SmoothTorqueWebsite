#!/usr/bin/php
<?
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
mysql_connect($db_host, $db_user, $db_pass);
$result = mysql_query("SELECT count(*) from SineDialer.cdr");
$result3 = mysql_query("REPLACE INTO SineDialer.config (parameter, value) VALUES ('cdr_count', '".mysql_result($result,0,0)."')");
?>
