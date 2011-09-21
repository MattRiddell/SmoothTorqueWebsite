#!/usr/bin/php
<?
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
mysql_connect($db_host, $db_user, $db_pass);
$result = mysql_query("UPDATE SineDialer.cdr set rounded_billsec = (CEILING(billsec/6)*6) WHERE rounded_billsec is NULL");
?>