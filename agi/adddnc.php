#!/usr/bin/php -q
<?php
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

     function setVal()
     {
       global $agi;
       global $connection;
       //echo "VERBOSE \"Query1: ".$query1." \" \n";
	$result=$agi->get_variable(stage);
      $stage=$result[data];
	$result=$agi->get_variable(campaign);
      $campaign=$result[data];
	$result=$agi->get_variable(phonenumber);
      $phonenumber=$result[data];
	$query='INSERT INTO dnclist (addedby_id,dncnumber,campaign_id) VALUES (NULL,\''.$phonenumber.'\','.$campaign.')';
       echo "VERBOSE \"Stage: ".$stage." \" \n";
       echo "VERBOSE \"PhoneNumber: ".$phonenumber." \" \n";
       echo "VERBOSE \"CampaignID: ".$campaign." \" \n";
       echo "VERBOSE \"Query: ".$query." \" \n";

       $result=mysql_query($query, $connection) or die("VERBOSE \"".mysql_error()."\"\r\n");
       echo "VERBOSE \"Query Completed ".$result." \" \n";
     }
     function write($line) 
     {
       global $debug, $stdlog;
       if ($debug) fputs($stdlog, "write: $line\n");
       echo $line."\n";
     }
//     echo "VERBOSE \"bla\"\r\n";

     // main program
     $connection = mysql_connect($db_host,$db_user,$db_pass) or die("Error connecting to database");
     mysql_select_db($db_name, $connection);
     setVal();
     fclose($in);
     fclose($stdlog);
     exit;
 ?>
