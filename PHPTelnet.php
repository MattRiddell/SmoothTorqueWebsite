<?php
/*
PHPTelnet 1.0
by Antone Roundy
adapted from code found on the PHP website
public domain
*/

class PHPTelnet {
	var $use_usleep=1;	// change to 1 for faster execution
		// don't change to 1 on Windows servers unless you have PHP 5
	var $sleeptime=125000;
	var $loginsleeptime=1000000;

	var $fp=NULL;
	var $loginprompt;

	/*
	0 = success
	1 = couldn't open network connection
	2 = unknown host
	3 = login failed
	4 = PHP version too low
	*/
	function Connect() {
		$rv=0;
		
				
		$ip='127.0.0.1';
		
	if ($this->fp=fsockopen($ip,6901)) {				}
					
		return $rv;
	}
	
	function Disconnect($exit=1) {
		if ($this->fp) {
			if ($exit) $this->DoCommand('exit',$junk);
			fclose($this->fp);
			$this->fp=NULL;
		}
	}

	function DoCommand($c,&$r) {
		if ($this->fp) {
			fputs($this->fp,"$c\r");
			if ($this->use_usleep) usleep($this->sleeptime);
			else sleep(1);
			$this->GetResponse($r);
			$r=preg_replace("/^.*?\n(.*)\n[^\n]*$/","$1",$r);
		}
		return $this->fp?1:0;
	}
	
	function GetResponse(&$r) {
		set_time_limit(600);
		$r='';
		do { 
			$r.=fread($this->fp,1000);
			$s=socket_get_status($this->fp);
		} while ($s['unread_bytes']);
	}
}
?>
