#!/usr/bin/php -q
<?
require "phpagi.php";
$agi = new AGI();
ob_implicit_flush(true);
set_time_limit(600);
$in = fopen("php://stdin","r");

// toggle debugging output (more verbose)
$debug = true;

function read() {
    global $in, $debug, $stdlog;
    $input = str_replace("\n", "", fgets($in, 4096));
    if ($debug) fputs($stdlog, "read: $input\n");
    return $input;
}

function errlog($line) {
    global $err;
    echo "VERBOSE \"$line\"\n";
}

function write($line) 
{
    global $debug, $stdlog;
    if ($debug) fputs($stdlog, "write: $line\n");
    echo $line."\n";
}

function a_echo($line) 
{
    echo "VERBOSE \"".$line."\" \n";
    read();
}

a_echo("###########################################################");
a_echo("#                                                         #");
a_echo("#            Inbound DDI Recording System                 #");
a_echo("#                                                         #");
a_echo("###########################################################");
$res = $agi->get_data("agent-pass", 2000, 4);     
$response = $res['result'];
a_echo("Got pin of ".$response);
record_file("record_$response", "sln", "#", "-1", NULL, true, NULL);
stream_file("record_$response");
fclose($in);
fclose($stdlog);
exit;
?>
