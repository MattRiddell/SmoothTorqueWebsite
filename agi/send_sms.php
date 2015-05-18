#!/usr/bin/php
<?
require "phpagi.php";
$agi = new AGI();
ob_implicit_flush(TRUE);
set_time_limit(6);
$in = fopen("php://stdin", "r");
$stdlog = fopen("/var/log/asterisk/my_agi.log", "w");
$today = date("Y-m-d H:i:s");


// toggle debugging output (more verbose)
$debug = FALSE;
function write_to_asterisk($line) {
    echo $line;
    read();
}

function read() {
    global $in, $debug, $stdlog;
    $input = str_replace("\n", "", fgets($in, 4096));
    if ($debug) {
        fputs($stdlog, "read: $input\n");
    }
    return $input;
}

function errlog($line) {
    global $err;
    echo "VERBOSE \"$line\"\n";
}

$message_var = $agi->get_var("message3");

$message = urlencode(str_replace("#~#", ".", $message_var));
write_to_asterisk("VERBOSE \"$message_var\"\n");
//print_r($message_var);
$phonenumber = "64".substr($agi->get_var("phonenumber"), 1);
$url = "http://api.clickatell.com/http/sendmsg?user=x&password=x&api_id=x&to='.$phonenumber.'&text=".$message;
write_to_asterisk("VERBOSE \"\n\n\n\nOpening $url\n\n\n\"\n");

$handle = fopen($url, "rb");
fclose($handle);
?>