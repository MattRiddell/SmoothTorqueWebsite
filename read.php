<?
require "header.php";
$result = mysql_query("REPLACE INTO config (parameter, value) VALUES ('read_1', 'true')");
echo '<META HTTP-EQUIV=REFRESH CONTENT="0; URL=servers.php">';
?>
