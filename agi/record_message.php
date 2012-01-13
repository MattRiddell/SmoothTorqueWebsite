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

a_echo("+---------------------------------------------------------+");
a_echo("|                                                         |");
a_echo("|            Inbound DDI Recording System                 |");
a_echo("|                                                         |");
a_echo("+---------------------------------------------------------+");
$response = "";
while (strlen($response) != 4) {
    $res = $agi->get_data("agent-pass", 15000, 4);     
    $response = $res['result'];
    if (strlen($response) != 4) {
        a_echo("Got pin of ".$response);
    }
}
a_echo("Got good pin of ".$response);
$agi->stream_file("auth-thankyou");
record:
$agi->stream_file("vm-intro");
$agi->record_file("record_$response", "sln", "#", "-1", NULL, true, NULL);

menu:
/* Press 1 to accept this recording. Press 2 to listen to it. Press 3 to re-record your message. */
$res = $agi->get_data("vm-review", 15000, 1);     
$response = $res['result'];

switch ($response) {
    case 1:
        // accept
        break;
    case 2:
        // listen
        $agi->stream_file("record_$response");
        goto menu;
        break;
    case 3:
        // re-record
        goto record;
        break;
    default:
        goto menu;
        break;
}

fclose($in);
fclose($stdlog);
exit;
?>
