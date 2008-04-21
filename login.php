<?
include "admin/db_config.php";

function mysql_is_table($host, $user, $pass, $db, $tbl)
{
	$result = FALSE;
    $tables = array();
    $link = mysql_connect($host, $user, $pass) or die(mysql_error());
    mysql_select_db($db) or die(mysql_error());
    $q = @mysql_query("SHOW TABLES");
    while ($r = @mysql_fetch_array($q)) { $tables[] = $r[0]; }
    @mysql_free_result($q);
    @mysql_close($link);
    if (in_array($tbl, $tables)) { $result =  TRUE; }
	return $result;
}

/*======================================================================
                            Billing Table
  ======================================================================*/
if (!mysql_is_table($db_host,$db_user,$db_pass,"SineDialer","billing")){
  include "admin/db_config.php";
  $sql = "CREATE TABLE `billing` (
  `customerid` int(11) unsigned NOT NULL default '0',
  `accountcode` varchar(250) NOT NULL default '',
  `priceperminute` double(10,5) default '0.00000',
  `firstperiod` int(10) unsigned default '1',
  `increment` int(10) unsigned default '1',
  `credit` double(100,10) default '0.0000000000',
  `pricepercall` double(10,5) default '0.00000',
  `priceperconnectedcall` double(10,5) default '0.00000',
  `priceperpress1` double(10,5) default '0.00000',
  `creditlimit` double(100,10) default '0.0000000000',
  PRIMARY KEY  (`customerid`,`accountcode`)
  )";
  $result = mysql_query($sql,$link);
}

/*======================================================================
                            Billing Log Table
  ======================================================================*/
if (!mysql_is_table($db_host,$db_user,$db_pass,"SineDialer","billinglog")){
  include "admin/db_config.php";

  $sql = "CREATE TABLE `billinglog` (
  `timestamp` timestamp NULL default NULL on update CURRENT_TIMESTAMP,
  `activity` varchar(255) default NULL,
  `username` varchar(255) default NULL,
  `addedby` varchar(255) default NULL
  )";
  $result = mysql_query($sql,$link);
  $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_POST[user]', 'Created Billing Log Table')";
  $result=mysql_query($sql, $link);
}

/*======================================================================
                            Log Table
  ======================================================================*/
if (!mysql_is_table($db_host,$db_user,$db_pass,"SineDialer","log")){
  include "admin/db_config.php";

  $sql = "CREATE TABLE `log` (
  `timestamp` timestamp NULL default NULL on update CURRENT_TIMESTAMP,
  `activity` varchar(255) default NULL,
  `username` varchar(255) default NULL
  )";
  $result = mysql_query($sql,$link);
  $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_POST[user]', 'Attempted login')";
  $result=mysql_query($sql, $link);
  $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_POST[user]', 'Created Log Table')";
  $result=mysql_query($sql, $link);
}

/*======================================================================
                            Realtime SIP
  ======================================================================*/
if (!mysql_is_table($db_host,$db_user,$db_pass,"SineDialer","sip_buddies")){
  //echo "Not there";
  include "admin/db_config.php";
  $sql = "CREATE TABLE `sip_buddies` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(80) NOT NULL default '',
  `accountcode` varchar(20) default NULL,
  `callerid` varchar(80) default NULL,
  `canreinvite` char(3) default 'no',
  `context` varchar(80) default 'internal',
  `dtmfmode` varchar(7) default 'rfc2833',
  `host` varchar(31) default 'dynamic',
  `language` char(2) default 'it',
  `nat` varchar(5) default 'yes',
  `port` varchar(5) default '5060',
  `qualify` char(3) default NULL,
  `secret` varchar(80) default NULL,
  `type` varchar(6) NOT NULL default 'friend',
  `username` varchar(80) NOT NULL default '',
  `disallow` varchar(100) default 'all',
  `allow` varchar(100) default 'gsm;ulaw;alaw',
  `regseconds` int(11) NOT NULL default '0',
  `ipaddr` varchar(150) NOT NULL default '',
  `regexten` varchar(80) NOT NULL default '',
  `cancallforward` char(3) default 'yes',
  `setvar` varchar(100) NOT NULL default '',
  `clientid` int(13) default NULL,
  `description` varchar(100) default NULL,
  `fullcontact` varchar(250) default NULL,
  `visible` varchar(11) default NULL,
  `isagent` tinyint(3) unsigned NOT NULL default '0',
  `regserver` varchar(250) default NULL,
  `email` varchar(250) default NULL,
  `lastname` varchar(250) default NULL,
  `firstname` varchar(250) default NULL,
  `country` varchar(250) default NULL,
  `hasaccount` int(11) default NULL,
  `dateadded` datetime default NULL,
  `transfer` varchar(250) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `name_2` (`name`)
  );";
  $result = mysql_query($sql,$link) or die(mysql_error());
}

/*======================================================================
                            Realtime IAX2
  ======================================================================*/
if (!mysql_is_table($db_host,$db_user,$db_pass,"SineDialer","iax_buddies")){
  include "admin/db_config.php";
  $sql = "CREATE TABLE `iax_buddies` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(32) NOT NULL default '',
  `username` varchar(30) default NULL,
  `type` varchar(6) NOT NULL default 'friend',
  `secret` varchar(50) default NULL,
  `transfer` varchar(10) default 'mediaonly',
  `accountcode` varchar(100) default NULL,
  `callerid` varchar(100) default NULL,
  `context` varchar(100) default 'freevoip',
  `host` varchar(31) NOT NULL default 'dynamic',
  `language` varchar(5) default 'it',
  `mailbox` varchar(50) default NULL,
  `qualify` varchar(4) default '400',
  `disallow` varchar(100) default 'all',
  `allow` varchar(100) default 'gsm,ulaw,alaw',
  `ipaddr` varchar(15) default NULL,
  `port` int(11) default '0',
  `regseconds` int(11) default '0',
  `clientid` int(13) unsigned default NULL,
  `description` varchar(100) default NULL,
  `visible` varchar(11) default NULL,
  `encryption` varchar(40) default NULL,
  `auth` varchar(10) default NULL,
  `isagent` tinyint(3) unsigned NOT NULL default '0',
  `firstname` varchar(255) default NULL,
  `lastname` varchar(255) default NULL,
  `email` varchar(255) default NULL,
  `country` varchar(255) default NULL,
  `hasaccount` int(11) default NULL,
  `dateadded` datetime default NULL,
  `trunk` char(3) default 'no',
  `sendmail` int(3) default '1',
  `regcontext` varchar(60) default 'iaxregs',
  `jitterbuffer` varchar(4) default 'no',
  PRIMARY KEY  (`id`)
  );";
    $result = mysql_query($sql,$link);

}
/*======================================================================
                            Campaign
  ======================================================================*/
if (!mysql_is_table($db_host,$db_user,$db_pass,"SineDialer","campaign")){
  include "admin/db_config.php";
	$sql = "Create table `campaign` (
	`id` int(200) NOT NULL auto_increment,
	`description` varchar(250) default NULL,
	`name` varchar(200) NOT NULL default '',
	`groupid` int(200) NOT NULL default '0',
	`messageid` int(200) NOT NULL default '0',
	`campaignconfigid` int(11) NOT NULL default '0',
	`messageid2` INT(200) NOT NULL unsigned default '0',
	`messageid3` INT(200) NOT NULL unsigned default '0',
	`astqueuename` VARCHAR(255) default NULL,
	`mode` INT(11)  default '0',
	`clid` varchar(255) default 'nocallerid <>',
	`trclid` varchar(255) default 'nocallerid',
	`maxagents` int(11) default '30',
	`did` varchar(255) default 'nodid',
	`context` varchar(255) default 'ls3',
	`cost` varchar(10) default NULL,
	PRIMARY KEY (`id`)
	);";
	$result = mysql_query($sql,$link);
}

/*======================================================================
                            Campaign Config
  ======================================================================*/
if (!mysql_is_table($db_host,$db_user,$db_pass,"SineDialer","campaignconfig")){
  include "admin/db_config.php";
        $sql = "Create table `campaignconfig` (
	`id` int(10) unsigned not null auto_increment,
	`type` int (11) default '0',
	`astqueuename` varchar(255) default NULL,
	`did` varchar(255) default NULL,
	`clid` varchar(255) default NULL,
	`trclid` varchar(255) default NULL,
	`maxchans` int(11) default 10,
	`numagents` int(11) default 10,
	PRIMARY KEY(`id`)
        );";
	$result = mysql_query($sql,$link);
}

/*======================================================================
                            Campaign Message
  ======================================================================*/
if (!mysql_is_table($db_host,$db_user,$db_pass,"SineDialer","campaignconfig")){
  include "admin/db_config.php";
        $sql = "Create table `campaignmessage` (
        `id` int(10) unsigned not null auto_increment,
	`filename` varchar(250) not null,
	`name` varchar(200) not null,
	`description` varchar(250) not null,
	`customer_id` int(11),
	primary key(`id`)
        );";
        $result = mysql_query($sql,$link);
}


/*======================================================================
                            CDR
  ======================================================================*/
if (!mysql_is_table($db_host,$db_user,$db_pass,"SineDialer","cdr")){
  include "admin/db_config.php";
        $sql = "Create table `cdr` (
  `calldate` datetime NOT NULL default '0000-00-00 00:00:00',
  `clid` varchar(80) NOT NULL default '',
  `src` varchar(80) NOT NULL default '',
  `dst` varchar(80) NOT NULL default '',
  `dcontext` varchar(80) NOT NULL default '',
  `channel` varchar(80) NOT NULL default '',
  `dstchannel` varchar(80) NOT NULL default '',
  `lastapp` varchar(80) NOT NULL default '',
  `lastdata` varchar(80) NOT NULL default '',
  `duration` int(11) NOT NULL default '0',
  `billsec` int(11) NOT NULL default '0',
  `disposition` varchar(45) NOT NULL default '',
  `amaflags` int(11) NOT NULL default '0',
  `accountcode` varchar(20) NOT NULL default '',
  `userfield` varchar(255) NOT NULL default '',
  `userfield2` varchar(255) NOT NULL default '',
  `userfield3` varchar(255) NOT NULL default '',
  `userfield4` varchar(255) NOT NULL default '',
  `userfield5` varchar(255) NOT NULL default '',
  KEY `calldate` (`calldate`),
  KEY `dst` (`dst`),
  KEY `accountcode` (`accountcode`)
        );";
        $result = mysql_query($sql,$link);
}


/*======================================================================
                            Config
  ======================================================================*/
if (!mysql_is_table($db_host,$db_user,$db_pass,"SineDialer","config")){
  include "admin/db_config.php";
        $sql = "Create table `config` (
`parameter` varchar(11) NOT NULL default '0',
  `value` varchar(110) NOT NULL
        );";
        $result = mysql_query($sql,$link);
}

/*======================================================================
                            Customer
  ======================================================================*/
if (!mysql_is_table($db_host,$db_user,$db_pass,"SineDialer","customer")){
  include "admin/db_config.php";
        $sql = "Create table `customer` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `username` varchar(30) NOT NULL default '',
  `password` varchar(200) NOT NULL default '',
  `campaigngroupid` int(10) unsigned NOT NULL default '0',
  `address1` varchar(250) default NULL,
  `address2` varchar(250) default NULL,
  `city` varchar(250) default NULL,
  `country` varchar(250) default NULL,
  `phone` varchar(250) default NULL,
  `email` varchar(250) default NULL,
  `fax` varchar(250) default NULL,
  `website` varchar(250) default NULL,
  `security` int(3) unsigned default '0',
  `company` varchar(250) default NULL,
  `trunkid` int(11) default '-1',
  `zip` varchar(25) default NULL,
  `state` varchar(250) default NULL,
  `maxcps` int(11) default '10',
  `maxchans` int(11) default '100',
  `adminlists` varchar(2555) default NULL,
  PRIMARY KEY  (`id`)
        );";
        $result = mysql_query($sql,$link);
	$result = mysql_query("INSERT INTO customer (`username`,`password`,`security`) VALUES ('matt','starsock',100)",$link);
}

/*======================================================================
                         DNC Number
  ======================================================================*/
if (!mysql_is_table($db_host,$db_user,$db_pass,"SineDialer","dncnumber")){
  include "admin/db_config.php";
        $sql = "Create table `dncnumber` (
  `campaignid` int(200) NOT NULL default '0',
  `phonenumber` varchar(50) NOT NULL default '',
  `status` varchar(50) NOT NULL default '',
  `type` int(5) NOT NULL default '0',
  PRIMARY KEY  (`campaignid`,`phonenumber`),
  KEY `test` (`phonenumber`,`campaignid`)
        );";
        $result = mysql_query($sql,$link);
}

/*======================================================================
                         Number
  ======================================================================*/
if (!mysql_is_table($db_host,$db_user,$db_pass,"SineDialer","number")){
  include "admin/db_config.php";
        $sql = "Create table `number` (
  `campaignid` int(200) NOT NULL default '0',
  `phonenumber` varchar(50) NOT NULL default '',
  `status` varchar(50) NOT NULL default '',
  `type` int(5) NOT NULL default '0',
  `datetime` timestamp NULL default NULL on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`campaignid`,`phonenumber`),
  KEY `test` (`phonenumber`,`campaignid`)
  KEY `status` (`campaignid`,`status`),
  KEY `status2` (`status`)
        );";
        $result = mysql_query($sql,$link);
}

/*======================================================================
                         Queue
  ======================================================================*/
if (!mysql_is_table($db_host,$db_user,$db_pass,"SineDialer","queue")){
  include "admin/db_config.php";
        $sql = "Create table `queue` (
  `queueID` int(11) NOT NULL auto_increment,
  `queuename` varchar(100) default NULL,
  `status` tinyint(4) NOT NULL default '0',
  `campaignID` int(11) NOT NULL default '0',
  `details` varchar(250) default NULL,
  `flags` int(11) NOT NULL default '0',
  `transferclid` varchar(20) default '0',
  `starttime` time default NULL,
  `endtime` time default NULL,
  `startdate` date default NULL,
  `enddate` date default NULL,
  `did` varchar(20) default NULL,
  `clid` varchar(20) default NULL,
  `context` int(1) NOT NULL default '0',
  `maxcalls` int(11) default '100',
  `maxchans` int(11) default '100',
  `maxretries` int(11) default '0',
  `retrytime` int(11) default '30',
  `waittime` int(11) default '30',
  `timespent` varchar(20) default '0',
  `progress` varchar(20) default '0',
  `expectedRate` float NOT NULL default '100',
  `mode` varchar(120) default '0',
  `astqueuename` varchar(20) default '',
  `trunk` varchar(255) default 'Local/s@${EXTEN}',
  `accountcode` varchar(255) default 'noaccount',
  `trunkid` int(11) default '-1',
  `customerID` int(11) default '-1',
  `maxcps` int(11) default '31',
  PRIMARY KEY  (`queueID`)
        );";
        $result = mysql_query($sql,$link);
}

/*======================================================================
                         Queue_Member_Table
  ======================================================================*/
if (!mysql_is_table($db_host,$db_user,$db_pass,"SineDialer","queue_member_table")){
  include "admin/db_config.php";
        $sql = "Create table `queue_member_table` (
  `uniqueid` int(10) unsigned NOT NULL auto_increment,
  `membername` varchar(40) default NULL,
  `queue_name` varchar(128) default NULL,
  `interface` varchar(128) default NULL,
  `penalty` int(11) default NULL,
  `paused` tinyint(1) default NULL,
  PRIMARY KEY  (`uniqueid`),
  UNIQUE KEY `queue_interface` (`queue_name`,`interface`)
        );";
        $result = mysql_query($sql,$link);
}


/*======================================================================
                         Queue_Table
  ======================================================================*/
if (!mysql_is_table($db_host,$db_user,$db_pass,"SineDialer","queue_table")){
  include "admin/db_config.php";
        $sql = "Create table `queue_table` (
  `name` varchar(128) NOT NULL,
  `musiconhold` varchar(128) default 'default',
  `announce` varchar(128) default NULL,
  `context` varchar(128) default NULL,
  `timeout` int(11) default NULL,
  `monitor_join` tinyint(1) default NULL,
  `monitor_format` varchar(128) default NULL,
  `queue_youarenext` varchar(128) default 'queue-youarenext',
  `queue_thereare` varchar(128) default 'queue-thereare',
  `queue_callswaiting` varchar(128) default 'queue-callswaiting',
  `queue_holdtime` varchar(128) default 'queue-holdtime',
  `queue_minutes` varchar(128) default 'queue-minutes',
  `queue_seconds` varchar(128) default 'queue-seconds',
  `queue_lessthan` varchar(128) default 'queue-less-than',
  `queue_thankyou` varchar(128) default 'queue-thankyou',
  `queue_reporthold` varchar(128) default NULL,
  `announce_frequency` int(11) default 0,
  `announce_round_seconds` int(11) default NULL,
  `announce_holdtime` varchar(128) default NULL,
  `retry` int(11) default NULL,
  `wrapuptime` int(11) default NULL,
  `maxlen` int(11) default NULL,
  `servicelevel` int(11) default NULL,
  `strategy` varchar(128) default NULL,
  `joinempty` varchar(128) default NULL,
  `leavewhenempty` varchar(128) default NULL,
  `eventmemberstatus` tinyint(1) default NULL,
  `eventwhencalled` tinyint(1) default NULL,
  `reportholdtime` tinyint(1) default NULL,
  `memberdelay` int(11) default NULL,
  `weight` int(11) default NULL,
  `timeoutrestart` tinyint(1) default NULL,
  `periodic_announce` varchar(50) default NULL,
  `periodic_announce_frequency` int(11) default NULL,
  PRIMARY KEY  (`name`)
        );";
        $result = mysql_query($sql,$link);
}


/*======================================================================
                         Servers
  ======================================================================*/
if (!mysql_is_table($db_host,$db_user,$db_pass,"SineDialer","servers")){
  include "admin/db_config.php";
        $sql = "Create table `servers` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `phonenumber` varchar(50) NOT NULL default '',
  `stage` int(3) NOT NULL default '0',
  `campaignid` int(3) NOT NULL default '0',
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
        );";
        $result = mysql_query($sql,$link);
}

/*======================================================================
                         Stage
  ======================================================================*/
if (!mysql_is_table($db_host,$db_user,$db_pass,"SineDialer","stage")){
  include "admin/db_config.php";
        $sql = "Create table `stage` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `phonenumber` varchar(50) NOT NULL default '',
  `stage` int(3) NOT NULL default '0',
  `campaignid` int(3) NOT NULL default '0',
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
        );";
        $result = mysql_query($sql,$link);
}

/*======================================================================
                        Trunk 
  ======================================================================*/
if (!mysql_is_table($db_host,$db_user,$db_pass,"SineDialer","trunk")){
  include "admin/db_config.php";
        $sql = "Create table `trunk` (
  `id` int(15) unsigned NOT NULL auto_increment,
  `name` varchar(250) NOT NULL default '',
  `dialstring` varchar(250) NOT NULL default '',
  `current` int(1) NOT NULL default '0',
  `maxchans` int(11) unsigned default '100',
  `maxcps` varchar(255) default '30',
  PRIMARY KEY  (`id`)
        );";
        $result = mysql_query($sql,$link);
}

$passwordHash = sha1($_POST['pass']);


include "admin/db_config.php";
mysql_select_db("SineDialer") or die(mysql_error());


$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);

$sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_POST[user]', 'Attempted login')";
$result=mysql_query($sql, $link) or die(mysql_error());

$fields = mysql_list_fields('SineDialer', 'campaign', $link);
$columns = mysql_num_fields($fields);
for ($i = 0; $i < $columns; $i++) {
    $field_array[] = mysql_field_name($fields, $i);
}

if (!in_array('cost', $field_array))
{
    $result = mysql_query('ALTER TABLE campaign ADD cost VARCHAR(10)');
    $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_POST[user]', 'Added campaign cost field')";
    $result=mysql_query($sql, $link);
}


/*

*/

$sql = 'SELECT password, security FROM customer WHERE username=\''.$_POST[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
if (mysql_num_rows($result) > 0) {
    $dbpass=mysql_result($result,0,'password');
} else {
    $dbpass = "";
}
if (trim($dbpass)==trim($passwordHash)){

    setcookie("loggedin",sha1("LoggedIn".$_POST[user]),time()+6000);
    setcookie("user",$_POST[user],time()+6000);
    if (mysql_result($result,0,'security')==100){
        $level=sha1("level100");
    } else if (mysql_result($result,0,'security')==0){
        $level=sha1("level0");
    } else {
        $level=sha1("level10");
    }
    setcookie("level",$level,time()+6000);
    $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_POST[user]', 'Successful login')";
    $result=mysql_query($sql, $link);
    if (strlen($_GET[redirect]) > 0) {
        header("Location: ".$_GET[redirect]);
    } else {
        header("Location: /main.php");
    }
    exit;
} else {
    setcookie("loggedin","--",time()+6000);
    setcookie("user",$_POST[user],time()+6000);
    $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_POST[user]', 'Unuccessful login')";
    $result=mysql_query($sql, $link);
    header("Location: index.php?error=Incorrect%20UserName%20or%20Password");
    exit;
?>
<?
}
require "footer.php";
?>
