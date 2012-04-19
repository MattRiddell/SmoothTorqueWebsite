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
    a_echo("DDI not found in DDIs!  Checking campaigns");
    $result = mysql_query("SELECT * FROM SineDialer.campaign WHERE clid = '".$extension."'");
    if (mysql_num_rows($result) == 0) {
        a_echo("DDI not found in campaigns - getting first one we can find");
        $result = mysql_query("SELECT * FROM dids limit 1");
    } else {
        $row = mysql_fetch_assoc($result);
        $result = mysql_query("SELECT filename FROM campaignmessage WHERE id = ".$row['messageid']);
        $message = mysql_result($result,0,0);
        $message = substr($message,0,strlen($message)-4);
        $result=$agi->get_variable("phonenumber");
        $num=$result['data'];
        $agi->set_variable("CDR(userfield)",$num."-".$row['id']);
        $agi->set_variable("message",$message);
        
    }    
    $row = mysql_fetch_assoc($result);
    a_echo("Intro: ".$row['message_id']." Campaign: ".$row['campaign_id']);
    $agi->set_variable("campaign",$row['campaign_id']);
    
    // Get Message 1
    $result_message = mysql_query("SELECT filename FROM campaignmessage WHERE id = ".$row['message_id']);
    $message = mysql_result($result_message,0,0);
    
    // Strip the extension
    $message = substr($message,0,strlen($message)-4);
    $agi->set_variable("message",$message);
    
    $result_campaign = mysql_query("SELECT * FROM campaign WHERE id = ".$row['campaign_id']);
    $row_campaign = mysql_fetch_assoc($result_campaign);
    
    // Get Message 2
    $result_message2 = mysql_query("SELECT filename FROM campaignmessage WHERE id = ".$row_campaign['messageid2']);
    $message2 = mysql_result($result_message2,0,0);
    // Strip the extension
    $message2 = substr($message2,0,strlen($message2)-4);
    $agi->set_variable("message2",$message2);
    
    // Get Message 3
    $result_message3 = mysql_query("SELECT filename FROM campaignmessage WHERE id = ".$row_campaign['messageid3']);
    $message3 = mysql_result($result_message3,0,0);
    // Strip the extension
    $message3 = substr($message,0,strlen($message3)-4);
    $agi->set_variable("message3",$message3);
    
    // Get Destination DID
    $trunk_did = "SIP/1".$row_campaign['did']."@transfer";
    $agi->set_variable("trunk-did",$trunk_did);
    
    $result=$agi->get_variable("phonenumber");
    $num=$result['data'];
    $agi->set_variable("CDR(userfield)",$num."-".$row['campaign_id']);
    
    
    
    //setVal();
    fclose($in);
    fclose($stdlog);
    exit;
    ?>
