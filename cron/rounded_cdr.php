#!/usr/bin/php
<?
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
mysql_connect($db_host, $db_user, $db_pass);
$result = mysql_query("SELECT * FROM SineDialer.cdr WHERE rounded_billsec is NULL");
if (mysql_num_rows($result) > 0) {
    $x = 0;
    $z = 0;
    while ($row = mysql_fetch_assoc($result)) {
        if ($x == 0) {
            echo "Processing Row $z of ".mysql_num_rows($result)."\n";
        }
        echo $row['billsec']."\n";
        $x++;
        $z++;
    }
}
?>