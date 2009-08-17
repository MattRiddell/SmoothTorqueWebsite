#!/usr/bin/php5
<?
pcntl_signal(SIGHUP,  "agi_hangup_handler");
function read() {  
  global $in, $debug, $stdlog;  
  $input = str_replace("\n", "", fgets($in, 4096));  
  return $input;  
}  
function write($line) {  
  global $debug, $stdlog;  
  echo $line."\n";  
  read();
}  

// parse agi headers into array  

while ($env=read()) {  
  $s = split(": ",$env);  
  $agi[str_replace("agi_","",$s[0])] = trim($s[1]);  
  if $env == "")  {  
    break;  
  }  
}  

write("GET VARIABLE phonenumber");  
$a = read();  

write("Got Phone Number: $a");

write("GET VARIABLE campaign");  
$a = read();  

write("Got CampaignID: $a");


function agi_hangup_handler($signo) {
     //this function is run when Asterisk kills your script ($signo is always 1)
     //close file handles, write database records, etc.
	// clean up file handlers etc.  
	fclose($in);  
	fclose($stdlog);  
	exit;  
}
 
// clean up file handlers etc.  
fclose($in);  
fclose($stdlog);  
exit;  
?>