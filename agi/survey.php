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
/*
function setVal()
{
    global $agi;
    global $connection;

    $query='INSERT INTO dnclist (addedby_id,dncnumber,campaign_id) VALUES (NULL,\''.$phonenumber.'\','.$campaign.')';
    echo "VERBOSE \"Stage: ".$stage." \" \n";
    echo "VERBOSE \"PhoneNumber: ".$phonenumber." \" \n";
    echo "VERBOSE \"CampaignID: ".$campaign." \" \n";
    echo "VERBOSE \"Query: ".$query." \" \n";
    
    $result=mysql_query($query, $connection) or die("VERBOSE \"".mysql_error()."\"\r\n");
    echo "VERBOSE \"Query Completed ".$result." \" \n";
}*/
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

/* Get Campaign ID */
$result=$agi->get_variable(campaign);
$campaign=$result[data];

/* Get Phone Number */
$result=$agi->get_variable(phonenumber);
$phonenumber=$result[data];

$result = mysql_query("SELECT survey FROM campaign WHERE campaignid = ".$campaign);
$survey_id = mysql_result($result,0,0);
a_echo("Got Survey ID: $survey_id");

//setVal();
fclose($in);
fclose($stdlog);
exit;
?>
