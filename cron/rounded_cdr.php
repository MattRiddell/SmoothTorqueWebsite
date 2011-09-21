#!/usr/bin/php
<?
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
mysql_connect($db_host, $db_user, $db_pass);
$result = mysql_query("SELECT * FROM SineDialer.cdr WHERE rounded_billsec is NULL limit 20000");
if (mysql_num_rows($result) > 0) {
    $x = 1000;
    $z = 0;
    while ($row = mysql_fetch_assoc($result)) {
        if ($x > 999) {
            echo "Processing Row $z of ".mysql_num_rows($result)."\n";
            $x = 0;
        }
        echo $row['billsec']." = ";
        echo (round(($row['billsec']/6)+(0.05), 1)*6)."\n";
        $x++;
        $z++;
    }
}
?>