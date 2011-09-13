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
$result=$agi->get_variable("campaign");
$campaign=$result[data];

/* Get Phone Number */
$result=$agi->get_variable("phonenumber");
$phonenumber=$result[data];

$sql = "SELECT survey FROM campaign WHERE id = ".$campaign;
$result = mysql_query($sql);

a_echo("Executing SQL: $sql");

$survey_id = mysql_result($result,0,0);
a_echo("Got Survey ID: $survey_id");

if (!($survey_id > 0)) {
    a_echo("Survey ID missing - setting to 1");
    $survey_id = 1;
}

a_echo("-----------------------------------");
a_echo("SURVEY STARTING FOR $phonenumber");

/* Get the survey */
$sql = "SELECT * FROM survey_choices WHERE survey_id = ".$survey_id." order by question_number asc";
a_echo("Executing sql: $sql");
$result = mysql_query($sql);
if (mysql_num_rows($result) == 0) {
    a_echo("No results found!");
} else {
    $x = 0;
    while ($row = mysql_fetch_assoc($result)) {
        if ($row['question_number'] == 0) {
            $invalid = $row['soundfile'];
        } else {
            $choices[$x]['filename'] = $row['soundfile'];
            $choices[$x]['expected'] = $row['choices'];
            $choices[$x]['question_num'] = $row['question_number'];        
        }
        $x++;
    }    
    $incorrect = 0;
    for ($i = 1;$i<count($choices);$i++) {     
        a_echo("-----------------------------------");
        a_echo("Question: ".$choices[$i]['question_num']);
        //a_echo("Playing ".$choices[$i]['filename']);
        a_echo("Expecting: ".$choices[$i]['expected']);
        $res = $agi->get_data($choices[$i]['filename'], 2000, 1);        
        $response = $res['result'];
        if (strlen($response) > 0) {
            $response = substr($response,0,1);
        } 
        if (strlen($choices[$i]['expected']) > 0) {
            $expected = explode(",",$choices[$i]['expected']);
            $found = false;
            foreach ($expected as $expect) {
                if ($response == $expect) {
                    $found = true;
                    $incorrect = 0;
                    break;
                }
            }
        } else {
            $found = true;
            $incorrect = 0;
        }
        if ($found == false) {
            a_echo("You did not enter a correct choice");
            $res = $agi->get_data($invalid, 2000, 1);        
            $incorrect++;
            $i--;
            if ($incorrect > 3) {
                echo "HANGUP \n";
                $i = count($choices);
            }
        } else {
            a_echo("You did enter a correct choice");
            $sql = "INSERT INTO survey_results (campaign_id, phonenumber, question, choice) VALUES ($campaign, '$phonenumber', ".$choices[$i]['question_num'].", '$response')";
            a_echo ("Running $sql");
            $result = mysql_query($sql);
            a_echo("-----------------------------------");
        }
    }    
}

//setVal();
fclose($in);
fclose($stdlog);
exit;
?>
