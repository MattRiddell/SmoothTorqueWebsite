#!/usr/bin/php
<?
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
mysql_connect($db_host, $db_user, $db_pass);
if ($handle = opendir('/var/spool/asterisk/monitor')) {
    while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "..") {
            //          echo "$file\n";
            $server = substr($file,0,5);
            $filename = $file;
            $uniqueid = substr($filename, 0, strlen($filename)-4);
            //echo "Server: $server Filename: $filename UniqueID: $uniqueid\n";
            $result = mysql_query("INSERT IGNORE into SineDialer.files (filename, uniqueid, server) VALUES ('$filename','$uniqueid', '$server')");
        }
    }
    closedir($handle);
}
?>

