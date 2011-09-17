#!/usr/bin/php -q
<?
require "phpagi.php";
$agi = new AGI();
$db_host = "127.0.0.1";
$db_user = "user";
$db_pass = "pass";
$db_name = "DNC";
ob_implicit_flush(true);
set_time_limit(6);
$in = fopen("php://stdin","r");

// toggle debugging output (more verbose)
$debug = false;

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

// main program

/* Connect to Database */
$connection = mysql_connect($db_host,$db_user,$db_pass) or die("Error connecting to database");
mysql_select_db($db_name, $connection);

/* Set all the variables then go to start-survey 
 * The only difference is that message 1 is a different one 
 */
$extension = $agi->request['agi_extension'];
a_echo("Extension: $extension");
$result = mysql_query("SELECT * FROM dids WHERE number = '".$extension."'");
if (mysql_num_rows($result) == 0) {
    a_echo("DDI not found!");
} else {
    $row = mysql_fetch_assoc($result);
    a_echo("Intro: ".$row['messageid']." Campaign: ".$row['campaignid']);
}


//setVal();
fclose($in);
fclose($stdlog);
exit;
?>
