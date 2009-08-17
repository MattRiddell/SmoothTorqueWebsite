#!/usr/bin/php5
<?
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$link = mysql_connect($db_host, $db_user, $db_pass) OR die(mysql_error());

ob_implicit_flush(true);  
set_time_limit(6);  
error_reporting(0);  //added by user: democritus  
$in = fopen("php://stdin","r");  
pcntl_signal(SIGHUP,  "agi_hangup_handler");

function read() {
  global $in, $debug, $stdlog;  
//  $input = fgets($in, 4096);  
  $input = str_replace("\n", "", fgets($in, 4096));  
  return $input;  
}  

function write($line) {  
  global $debug, $stdlog;  
  echo $line."\n";  
  return read();
}  

// parse agi headers into array  

while ($env=read()) {  
  $s = split(": ",$env);  
  $agi[str_replace("agi_","",$s[0])] = trim($s[1]);  
  if ($env == "")  {  
    break;  
  }  
}  

$a = write("GET VARIABLE phonenumber");  
$num = strstr($a,"(");
$num = substr($num,1,strlen($num)-2);
write("VERBOSE \"Got Phone Number: $num\"");

$a = write("GET VARIABLE campaign");  
$campaign = strstr($a,"(");
$campaign = substr($campaign,1,strlen($campaign)-2);
write("VERBOSE \"Got Campaign ID: $campaign\"");
$sql = "SELECT name FROM SineDialer.names WHERE campaignid = $campaign and phonenumber = '$num'";
write("VERBOSE \"About to do $sql\"");
$result = mysql_query($sql);
if (mysql_num_rows($result) > 0) {
	write("VERBOSE \"Name is: ".mysql_result($result,0,0)."\"");
	write("SET CALLERID \"".mysql_result($result,0,0)."\"<".$num.">");
} else {
	write("VERBOSE \"No name found for this entry\"");
}

function agi_hangup_handler($signo) {
     //this function is run when Asterisk kills your script ($signo is always 1)
     //close file handles, write database records, etc.
	// clean up file handlers etc.  
	fclose($in);  
	fclose($stdlog);  
	exit;  
}
 
// clean up file handlers etc.  
fclose($in);  
fclose($stdlog);  
exit;  
?>
