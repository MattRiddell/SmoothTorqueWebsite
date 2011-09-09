<?php
	/* 
	 * phpagi.php : PHP AGI Functions for Asterisk 
	 * Website: http://phpagi.sourceforge.net/
	 * 
	 * $Id: phpagi.php,v 1.12 2004/10/01 18:40:19 masham Exp $
	 * 
	 * Copyright (c) 2003,2004 Matthew Asham <matthewa@bcwireless.net>
	 * All Rights Reserved.
	 *
	 * This software is released under the terms of the GNU Public License v2
	 *  A copy of which is available from http://www.fsf.org/licenses/gpl.txt
	 * 
	 *
	 *  You are requested to drop me an Email letting me know that you're 
	 *  using it.  This is more of a courtesy than anything else, but I am 
	 *  interested to know how it is being used.
	 */


	/*
	 * Written for PHP 4.3.4, should work with older PHP 4.x versions.  
	 * Please submit bug reports, patches, etc to http://sourceforge.net/projects/phpagi/
	 * Gracias. :)
	 *
	 */

			
define("DEFAULT_PHPAGI_CONFIG", "/etc/asterisk/phpagi.conf");
define("AGIRES_OK",200);

class AGI {

	var $request;
	var $response;
	
	var $in;
	
	var $out;

	function conlog($str,$vbl=1)
	{/*
		if($this->config['phpagi']['debug']!='false') {
			$str = str_replace("\\", "\\\\", $str);
			$str = str_replace("\"", "\\\"", $str);
			$str = str_replace("\n","\\n",$str);
			if(defined("STDOUT")) {
			//	fwrite(STDOUT,"VERBOSE \"$str\" $vbl\n");
				fflush(STDOUT);
			} else {
			//	fwrite($this->out, "VERBOSE \"$str\" $vbl\n");
				fflush($this->out);
			}
			fgets($this->in,1024);
		}*/
	}
  
	function agi_exec($str)
	{
		$this->conlog(">> $str");
		if(defined("STDOUT")) {
			fwrite(STDOUT, $str."\n");
			fflush(STDOUT);
		} else {
			fwrite($this->out, $str."\n");
			fflush($this->out);
		}
			
	
		return($this->agi_readresult());
	}

    function agi_is_error($retarr)
	{
		// Returns TRUE if the command returned an error.
		
		if($retarr['code'] != AGIRES_OK ) {
			$this->conlog("DEBUG:  Bad command?  Returned code is ".$retarr['code']." result=".$retarr['result']);
			return(TRUE);
		}
		
		if(!isset($retarr['result'])){
			$this->conlog("DEBUG:  No 'result' value returned from asterisk!  Eww!");
			return(TRUE);
		}
		
		if($retarr['result'] == -1 )
			return(TRUE);
		
		return(FALSE);
		
	}
	
	function agi_readresult($str=FALSE)
	{
		/*
		 * agi_readresult reads a response from asterisk and returns an array
		 * consisting of:
		 *	
		 *	['code']   - the numeric return code
		 *	[$varname] - a value associated with a specific variable, $varname, ie: ['result']
		 *	['data']   - data returned encapsulated by ()'s (ie: return data from a DATABASE GET
		 *
		 *
		 *	CAVEATS:
		 *	 - This is a badly written function, I don't like it at all.
		 *	 - Values in a var=val pair can not have a space, this will seriously
		 *	   bugger things up causing the data after ' ' to be appended to the
		 *	   next variable.  
 		 *
 		 *	 - This function does NOT handle multiline replies from Asterisk.
		 */

		if($str==FALSE)	
			$str=fgets($this->in,4096);

		$str=str_replace("\n","",$str);
//		$this->conlog("<< $str");		
		
		$ret['code']=substr($str,0,3);
		
		$mline=0;
		
		if($str[4]=='-'){
			$mline++;
				$ret['data']=substr($str,4);			
			do{
				$str=fgets($this->in,4096);
				if($str==FALSE)
					return($ret);
				
				$ret['data'].=substr($str,4);
				if($str[4]=='-')
					return($ret);
			}while(1);
			// multiline
		}
		
		$rest=substr($str,4);
		/* UGh!  I don't like this at all. */
		
		$tstr="";

		define("ASTP_VARFIND",0);	// finding a var
		define("ASTP_VALFIND",1);	// finding a value
		define("ASTP_BFIND",2);		// finding text after (

		$mode=ASTP_VARFIND;
		$var="";
		$val="";

		/* This will break if a space is ever returned in a result= */

		$len=strlen($rest);
		for($i=0;$i<$len;$i++){
//			$this->conlog( ">> ".$rest[$i] ." ".$mode."  $var\n");
			if($rest[$i]=='(') {
				$mode=ASTP_BFIND;
				$var="";
				continue;
			} if ($rest[$i]==')'){
				$mode=ASTP_VARFIND;
				continue;
			} else if($rest[$i]=='=') {
				$mode=ASTP_VALFIND;
				continue;
			} else {
			
				if($rest[$i]==' ' && $mode==ASTP_VALFIND) {
					// end of result=, start new var search.
					$var="";					
					$mode=ASTP_VARFIND;
					continue;
				}
			}
				
			switch($mode){
				case(ASTP_VARFIND);
					if($rest[$i]==' ')	// XXX:  No spaces in var names!
						continue;
					$var.=$rest[$i];
				break;

				case(ASTP_VALFIND);
					$ret["$var"].=$rest[$i];
				break;
				
				case(ASTP_BFIND);
					$data.=$rest[$i];
				break;
			}
		}
		
		if($data != "" )
			$ret['data']=$data;
	
		$this->response = $ret;		
//		$this->con_print_r($ret);
		return($ret);
	}

	function agi_verbose($str,$vbl=1)
	{
		$str = str_replace("\\", "\\\\", $str);
		$str = str_replace("\"", "\\\"", $str);
		$str = str_replace("\n","\\n",$str);
		if(defined("STDOUT")) {
			fwrite(STDOUT,"VERBOSE \"$str\" $vbl\n");
			fflush(STDOUT);
		} else {
			fwrite($this->out, "VERBOSE \"$str\" $vbl\n");
			fflush($this->out);
		}
		fgets($this->in,1024);
	}

	function agi_response_code()
	{
		return($this->response['code']);
	}
	
	function agi_response_result()
	{
		$this->conlog("result is ".$this->response['result']);
		return($this->response['result']);
	}	
	
	function agi_response_data()
	{
		return($this->response['data']);
	}
	
	function agi_response_var($var)
	{
		if(!isset($this->response[$var]))
			return(FALSE);

		return($this->response[$var]);
	}	
	
	function agi_response_is_error()
	{
		return($this->agi_is_error($this->response));
	}

	function agi_read()
	{
		/* This function reads a raw reply from Asterisk and returns a result= string.
		   ** DEPRECATED; don't use me.  Use readresult instead.
		 */
		   
		$str=str_replace("\n","",$str);
		$this->conlog("<< $str");		
		$code=intval(trim(substr($str,0,strpos($str," "))));
		if($code==AGIRES_OK) {
			$result=intval(substr($str,strpos($str,"result=")+strlen("result=")));
			return($result);
		}
		$this->conlog("Error result '$str'");
	
//XXX: eep

		return($str);	
	}

	function con_print_r($arr,$label='',$lvl=0)
	{
		/* con_print_r dumps a variable to the console.  printing large arrays
		   may cause significant overhead within the agi interface.  someone fix this.
		 */
		
		 	
		if($lvl==0 && $label != '')
			$this->conlog("debug: $label");
			
		if(is_array($arr)){
			foreach($arr as $k=>$v)
				if(is_array($v)) {
					$this->conlog(str_repeat(" ",$lvl)." ".gettype($v)." $k = (");
					$this->con_print_r($v,$label,$lvl+4);
					$this->conlog(str_repeat(" ",$lvl),")");
				} else {
					$this->conlog(str_repeat(" ",$lvl).gettype($v)." $k = $v",$lvl);
				}
					
		} else {
			$this->conlog(str_repeat(" ",$lvl).$arr);	
		}
		
	}

	function agi_getdtmf($len,$timeout,$terminator=FALSE,$prompt=FALSE){
		
		if($prompt != FALSE){
			if(is_array($prompt)){
				foreach($prompt as $p){
					if($p[0]=='$'){
						$this->text2wav(substr($p,1));
					} else {
						$this->agi_exec("STREAM FILE $p #");
					}				
				}
			} else {
				if($prompt[0]=='$'){
					$this->text2wav(substr($prompt,1));
				} else {
					$this->agi_exec("STREAM FILE $prompt #");
				}	
			}
		}
		

		for($i=0;$i<$len;$i++){
		
			$res=$this->agi_exec("WAIT FOR DIGIT $timeout");
			$this->con_print_r($res);
			if($this->agi_response_is_error()) {
				$this->conlog("error?");
				return(FALSE);
			}
			$ch=chr($this->agi_response_result());
			$this->conlog("got $ch");
			if(strval($ch) == strval($terminator) && $terminator != FALSE )
				return($ret);
				
			$ret[$i]=$ch;
		}
		return($ret);
	}
	

	/* agi_dtmf2text -- i wrote this for the sake of it, not because it was
	   a good idea.  feel free to improve it. */
	   	   
	function agi_dtmf2text($len,$timeout,$terminator=FALSE,$prompt=FALSE){
		$numbers=array(
			"1"=>"1",
			"2"=>"2abc",
			"3"=>"3def",
			"4"=>"4ghi",
			"5"=>"5jkl",
			"6"=>"6mno",
			"7"=>"7pqrs",
			"8"=>"8tuv",
			"9"=>"9wxyz",
			"0"=>"0"
			
			
		);
			$last=FALSE;		
		// find out how many times the key was pressed.
			

			$i=$times=0;
			$i=0;
			$abort=0;
			
			do {
				$res=$this->agi_getdtmf(1,4000);
				$res=$res[0];

				if($res==FALSE)
					break;
					

				if($last==FALSE )
					$last=$res;
				else if($last != $res || $res==FALSE) {
					$ret[$i]=$char;
					$this->conlog("Character $i is ".$char);
//					$this->text2wav($char);					
					$times=0;
					$i++;
				}					

				$char=$numbers[$res][$times++];
				$this->conlog("Number $res is '$char'");

				if(strlen($numbers[$res])==$times)
					$times=0;
					
				$last=$res;
					
			} while ($i<$len && !$abort);	// yeah yeah, bad me.  - $last == $res) ;
			
		$str="";
		foreach($ret as $k)
			$str.=$k." ";

		$this->text2wav($str);
		return($ret);
	}	

	function arr2str($arr) {
		for($i=0;$i<count($arr);$i++){
			$str.=$arr[$i]." ";
		}
		return(trim($str));
	}
	
	function config_load($file)
	{	// Returns array of config variables
		// $ret['sectionname']['parameter'] == 'value';
		
		
		$fh=fopen($file,"r");
		if($fh==FALSE)
			return(FALSE);
			
		$ret=FALSE;
		$section="";
		
		while ( feof($fh) == FALSE ) {
			$str=fgets($fh,4096);
			if($str==FALSE)
				break;
				
			$str=str_replace("\r","",$str);
			$str=str_replace("\n","",$str);
			$str=trim($str);
			if($str=="" || $str[0] == '#' || $str[0] == '/')
				continue;

			if($str[0] == '['){
				$section = trim(substr($str,strpos($str,"[")+1,strrpos($str,"]")-1));
			}
			
			if(strstr($str,"=")){
				$ret[$section][trim(substr($str,0,strrpos($str,"=")))] = trim(substr($str,strpos($str,"=")+1));
			}
		}
		fclose($fh);
		return($ret);
	}
		
	
	function AGI( $config=FALSE, $optconfig=FALSE)
	{
		/* $config is the name of the config file to parse
		   $optconfig is an array of configuration vars and vals, stuffed into $this->config['phpagi']
		*/
		
		if($config != FALSE ){
			$this->config=$this->config_load($config);
		} else if ( file_exists(DEFAULT_PHPAGI_CONFIG)){
			$this->config=$this->config_load(DEFAULT_PHPAGI_CONFIG);
		} else {
//			echo "Skipped ".DEFAULT_PHPAGI_CONFIG;
		}

		/* If optconfig is specified, stuff vals and vars into 'phpagi' config array. */
		if(is_array($optconfig)){
			foreach($optconfig as $var=>$val){
				$this->config['phpagi'][$var]=$val;
			}
		}
		
		$this->in=fopen("php://stdin","r");
	
		if(!defined("STDOUT"))
			$this->out = fopen("php://stdout","w");

		do {
			$str=fgets($this->in,1024);
			if($str == "\n")
				break;
	
			$this->request[substr($str,0,strpos($str,":"))] = trim(substr($str,strpos($str,":")+1));
		
		} while ( $str != "\n" );

		$this->con_print_r($this->request,"AGI Request: ");
		$this->con_print_r($this->config['phpagi'],"PHPAGI internal configuration");
		
		if($this->config['phpagi']['error_handler']=='true') {
			$this->conlog("set error handler");
			set_error_handler('phpagi_error_handler');
		}
		
	}
	
	
	function db_get($family,$key)
	{
		$res=$this->agi_exec("DATABASE GET \"$family\ \"$key\"");
		if($res['code']==0)
			return(FALSE);
		return($res['data']);
	}
	
	function db_put($family,$key,$val)
	{
		$res=$this->agi_exec("DATABASE PUT \"$family\" \"$key\" \"$val\"");
		return($res['code']);
		
	}
	
	function db_del($family,$key)
	{
		$res=$this->agi_exec("DATABASE DEL \"$family\" \"$key\"");
		return($res['code']);
	}
	
	function get_var($var)
	{
		// XXX: quote $var?
		$res=$this->agi_exec("GET VARIABLE $var");
		//if($res['code']==0)
		//	return(FALSE);
		return($res['data']);
	}
	
	function set_var($var,$val)
	{
		// XXX: quote $var and $val?
		$res=$this->agi_exec("SET VARIABLE $var $val");
		return($res['code']);
	}
	
	function set_callerid($cid)
	{
		$res=$this->agi_exec("SET CALLERID $cid");
	}
	
	function agi_hangup()
	{
		$this->agi_exec("HANGUP");
	}

	function text2wav($text)
	{
		if(!isset($this->config['festival']['text2wave'])) {
			$this->conlog("festival program location not defined");
			return(FALSE);
		} else {
			$festival=$this->config['festival']['text2wave'];
			if(!file_exists($festival)) {
				$this->conlog("$festival does not exist");
				return(FALSE);
			}
		}
		
		if(!isset($this->config['festival']['tempdir'])){
			$this->conlog("temporary directory not defined for festival");
			return(FALSE);
		}
			
		$text=trim($text);
		if($text==FALSE)
			return;
		$fname=txt2wav.".".$this->request['agi_uniqueid'];

		
		$fname=str_replace(".","_",$fname);
		
		// this shouldn't be hard coded.  i suck.  boo hoo.

		$nam=$this->config['festival']['tempdir']."/".$fname;		
		$this->conlog("Geez!  $nam");
//		system("/bin/echo '$text'  | 
		$p=popen("$festival -F 8000 > $nam".".wav","w");
		fputs($p,$text);
		pclose($p);
		
		$res=$this->agi_exec("STREAM FILE tmp/$fname #");
		$this->con_print_r($res);
		if($this->agi_is_error($res))
			$this->conlog("Shit happened!");
			
		unlink($nam.".wav");
		
	}
	
	function agi_channel_status($channel)
	{
		$stats=array(
			"0"=>"Channel is down and available",
			"1"=>"Channel is down, but reserved",
			"2"=>"Channel is off hook",
			"3"=>"Digits (or equivalent) have been dialed",
			"4"=>"Line is ringing",
			"5"=>"Remote end is ringing",
			"6"=>"Line is up",
			"7"=>"Line is busy"
		);
			
			
		$res=$this->agi_exec("CHANNEL STATUS $channel");
		
		$status=$this->agi_response_result();
		if(!isset($stats["$status"])) {
			return(array("status"=>-1,"description"=>"unknown"));
		} else {
			return(array("status"=>$status,"description"=>$stats["$status"]));
		}
	}
	
	function agi_recordfile($file,$format,$timeout=5000,$prompt=FALSE)
	{
		if($prompt != FALSE )
			$this->agi_play($prompt);
			
		$res=$this->agi_exec("RECORD FILE $file $format # $timeout beep");
		
	}
	
	function agi_play($file)
	{
		$this->agi_exec("STREAM FILE $file #");
	}
	
	function agi_goto($con,$ext='s',$pri=1)
	{
		$this->agi_exec("SET CONTEXT $con");
		$this->agi_exec("SET EXTENSION $ext");
		$this->agi_exec("SET PRIORITY $pri");
	}
	
	function agi_saydigits($digits)
	{
		$this->agi_exec("SAY DIGITS $digits #");
	}

	function agi_saynumber($number)
	{
		$this->agi_exec("SAY NUMBER $number #");
	}
	
	function agi_saytime($time="")
	{
		if($time == "") $time = time();
		$this->agi_exec("SAY TIME $time #");
	}

	function agi_setlanguage($language="en")
	{
		$this->agi_exec("EXEC SetLanguage ".$language);
	}

	function parse_callerid($ID=FALSE) {
		// lifted from http://www.sbuehl.com/projects/asterisk/asterisk-howto-3.html 
		// parses a caller ID string (or the caller id AGI request var) and 
		// returns the name and number as seperate array elements.
		
		if($ID===FALSE)	
			$ID=$this->request['agi_callerid'];
		
        if(preg_match("/\"(.*)\"[ ]*\<(.*)\>/",$ID,$tmp)) {
                return array("name"=>$tmp[1],"number"=>$tmp[2]);
        }
        if(preg_match("/^[0-9]+$/",$ID,$tmp)) {
                return array("name"=>"","number"=>$ID);
        }
        return(FALSE);
	}	
	function enum_lookup($telnumber,$rDNS="e164.org")
	{
		// returns a sorted array of enum records 
		
		if($telnumber[0]=='+')
			$telnumber=substr($telnumber,1);
			
		for($i = 0; $i < strlen($telnumber); $i++)
			$rDNS = $telnumber[$i].".".$rDNS;

		$dig=trim($this->config['phpagi']['dig']);
		if($dig=='') {
			if(file_exists("/usr/bin/dig")) {
				$dig="/usr/bin/dig";
				$this->agi_verbose("Used possibly unsafe dig binary '$dig', you should define the dig location within phpagi.conf");
			} else {
				$this->conlog("'dig' location not defined in phpagi.conf");
				return(FALSE);
			} 
		}
		
		if(!file_exists($dig)){
			$this->verbose("unable to execute $dig, binary inaccessable.");
			return(FALSE);
		}
			
		$execstr= $dig." +short ".escapeshellarg($rDNS)." NAPTR";
		$lines = trim(`$execstr`);

		$lines = explode("\n", $lines);
		$arr = array();
		foreach($lines as $line)
		{
			$line = trim($line);
			$line = str_replace("\t", " ", $line);
			while(strstr($line, "  "))
				$line = str_replace("  ", " ", $line);
			$line = str_replace("\"", "", $line);
			$line = str_replace("\'", "", $line);
			$line = str_replace(" ", "|", $line);
			$bits = explode("|", $line);
			$bit = explode("!", stripslashes($bits[4]));
			$URI = @ereg_replace($bit[1], $bit[2], "+".$telnumber);
			if($URI[3] == ":")
				$URI[3] = "/";
			if($URI[4] == ":")
				$URI[4] = "/";
			$arr[] = array("order" => $bits[0], "prio" => $bits[1], "tech" => $bits[3], "URI" => $URI);
		}

		foreach($arr as $key => $row)
		{
			$order[$key] = $row["order"];
			$prio[$key] = $row["prio"];
		}
	
		array_multisort($order, SORT_ASC, $prio, SORT_ASC, $arr);
		return($arr);
	}

	function enum_txtlookup($telnumber,$rDNS="e164.org")
	{
		// returns the contents of a TXT record associated with an ENUM dns record.
		// ala reverse caller ID lookup.
		if($telnumber[0]=='+')
			$telnumber=substr($telnumber,1);
			
		for($i = 0; $i < strlen($telnumber); $i++)
			$rDNS = $telnumber[$i].".".$rDNS;

		$dig=trim($this->config['phpagi']['dig']);
		if($dig=='') {
			if(file_exists("/usr/bin/dig")) {
				$dig="/usr/bin/dig";
				$this->agi_verbose("Used possibly unsafe dig binary '$dig', you should define the dig location within phpagi.conf");
			} else {
				$this->conlog("'dig' location not defined in phpagi.conf");
				return(FALSE);
			} 
		}
		
		if(!file_exists($dig)){
			$this->verbose("unable to execute $dig, binary inaccessable.");
			return(FALSE);
		}
			
		$execstr= $dig." +short ".escapeshellarg($rDNS)." TXT";
		$lines = trim(`$execstr`);

		$lines = explode("\n", $lines);
		$arr = array();
		foreach($lines as $line)
		{
			$line = trim($line);
			$line = str_replace("\t", " ", $line);
			while(strstr($line, "  "))
				$line = str_replace("  ", " ", $line);
			$line = str_replace("\"", "", $line);
			$line = str_replace("\'", "", $line);
			$ret.=$line;
			
		}
		$line=trim($line);
		if($line=="")
			return(FALSE);
		return($line);	
	}
	
	function send_text($txt)
	{
		$res=$this->agi_exec("SEND TEXT \"$txt\"");
		if($res['code']!=AGIRES_OK)
			return(FALSE);
		return(TRUE);
	}
	
	function send_image($image)
	{
		$res=$this->agi_exec("SEND IMAGE $image");
		if($res['code']!=AGIRES_OK)
			return(FALSE);
		return(TRUE);
	}	
	
}

function phpagi_error_handler($errno, $errstr, $errfile, $errline)
{
	if (!(error_reporting() & $errno)) return;

 	$errtype = '';
 	if ($errno & 1) $errtype .= 'E_ERROR ';
 	if ($errno & 2) $errtype .= 'E_WARNING ';
	if ($errno & 4) $errtype .= 'E_PARSE ';
	if ($errno & 8) $errtype .= 'E_NOTICE ';
	if ($errno & 16) $errtype .= 'E_CORE_ERROR ';
	if ($errno & 32) $errtype .= 'E_CORE_WARNING ';
	if ($errno & 64) $errtype .= 'E_COMPILE_ERROR ';
	if ($errno & 128) $errtype .= 'E_COMPILE_WARNING ';
	if ($errno & 256) $errtype .= 'E_USER_ERROR ';
	if ($errno & 512) $errtype .= 'E_USER_WARNING ';
	if ($errno & 1024) $errtype .= 'E_USER_NOTICE ';

	echo "VERBOSE \"".str_replace("\"", "\\\"", "PHP $errtype: $errstr in$errfile at line $errline")."\" 1\n";
	exit;
}

?>
