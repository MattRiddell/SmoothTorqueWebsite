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
                            System Billing Table
  ======================================================================*/
if (!mysql_is_table($db_host,$db_user,$db_pass,"SineDialer","system_billing")){
  include "admin/db_config.php";
  $sql = "CREATE TABLE `system_billing` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `groupid` int(11) default NULL,
  `totalcost` double default '0',
  `timestamp` timestamp NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
)";
    $result = mysql_query($sql,$link);
  $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_POST[user]', 'Created System Timestamp Billing Table')";
  $result=mysql_query($sql, $link);
}

/*======================================================================
                            campaign Table
  ======================================================================*/
if (!mysql_is_table($db_host,$db_user,$db_pass,"SineDialer","campaign")){
  include "admin/db_config.php";
$sql = "CREATE TABLE `campaign` (
  `id` int(200) NOT NULL auto_increment,
  `description` varchar(250) default NULL,
  `name` varchar(200) NOT NULL default '',
  `groupid` int(200) NOT NULL default '0',
  `messageid` int(200) NOT NULL default '0',
  `campaignconfigid` int(11) NOT NULL default '0',
  `messageid2` int(200) unsigned NOT NULL default '0',
  `messageid3` int(200) unsigned NOT NULL default '0',
  `astqueuename` varchar(255) default NULL,
  `mode` int(11) default '0',
  `clid` varchar(255) default 'nocallerid <>',
  `trclid` varchar(255) default 'nocallerid',
  `maxagents` int(11) default '30',
  `did` varchar(255) default 'nodid',
  `context` varchar(255) default 'ls3',
  `cost` varchar(10) default NULL,
  PRIMARY KEY  (`id`)
)";

    $result = mysql_query($sql,$link);
  $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_POST[user]', 'Created campaign Table')";
  $result=mysql_query($sql, $link);
}


/*======================================================================
                            campaigngroup Table
  ======================================================================*/
if (!mysql_is_table($db_host,$db_user,$db_pass,"SineDialer","campaigngroup")){
  include "admin/db_config.php";
$sql = "CREATE TABLE `campaigngroup` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(200) NOT NULL default '',
  `description` varchar(200) default NULL,
  PRIMARY KEY  (`id`)
)";

    $result = mysql_query($sql,$link);
  $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_POST[user]', 'Created campaigngroup Table')";
  $result=mysql_query($sql, $link);
    $sql = "insert  into campaigngroup values
(1, 'VentureVoIP', 'A demonstation group which contains a single demo campaign')";
 $result = mysql_query($sql,$link);

}


/*======================================================================
                            campaignmessage Table
  ======================================================================*/
if (!mysql_is_table($db_host,$db_user,$db_pass,"SineDialer","campaignmessage")){
  include "admin/db_config.php";
$sql = "CREATE TABLE `campaignmessage` (
  `id` int(11) NOT NULL auto_increment,
  `filename` varchar(250) NOT NULL default '',
  `name` varchar(200) NOT NULL default '',
  `description` varchar(250) NOT NULL default '',
  `customer_id` int(11) default '0',
  `length` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
)";

    $result = mysql_query($sql,$link);
  $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_POST[user]', 'Created campaignmessage Table')";
  $result=mysql_query($sql, $link);
  $sql = "insert  into campaignmessage values
(27, 'fax-33e5c3b94674a138bc5b390c06e2dba2e7488cb6.tiff', 'New Test Fax', 'A fax broadcasting test', 1, ''),
(20, 'fax-454d812f68762413e83903248236096ec83c492c.tiff', 'Do_Not_Use', 'A fax file', 1, null),
(14, 'x-afa871459b4fff189d78420ad7f3158918ca8333.sln', 'Ringin', 'The windows ring in sound', 1, '0.905500'),
(13, 'x-aba93245ef688df351b4c1765307c1e00a7d3b2e.sln', 'Chord', 'The windows chord sound', 1, '1.099000'),
(19, 'x-02c4778bdf0e525aa5bbfc5190a9ff7b184136b2.sln', 'Popcorn', 'Popcorn song', 1, '28.585125'),
(23, 'x-3534874a26eccf76813a3d47549959a0175abcc7.sln', 'UMR Introduction', 'Calling on behalf of... press 1 to take a short poll.', 26, '8.981750'),
(21, 'x-df6efd23c65b97ae1920ceb5ad7b2ee2a2732431.sln', 'Tada', 'The windows tada sound', 26, '1.939000'),
(24, 'x-d91f8f58dd14d004a31780540d34bba034f3bb1c.sln', 'Transfer 1 -Great', 'Great -here we go', 26, '1.656625'),
(26, 'fax-46d26b1fe019de1a711c021a01e426ce65fe4de0.tiff', 'Fax_Lara', 'Picture of lara as a test', 1, null),
(28, 'x-f9036629b654fffe0bdee6db47521dcd2ceb84b1.sln', 'Ding', 'The windows ding alert sound', 85, '0.915750')";
$result = mysql_query($sql,$link);
}


/*======================================================================
                            cdr Table
  ======================================================================*/
if (!mysql_is_table($db_host,$db_user,$db_pass,"SineDialer","cdr")){
  include "admin/db_config.php";
$sql = "CREATE TABLE `cdr` (
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
  PRIMARY KEY  (`dcontext`,`userfield`,`userfield2`),
  KEY `calldate` (`calldate`),
  KEY `dst` (`dst`),
  KEY `accountcode` (`accountcode`)
)";

    $result = mysql_query($sql,$link);
  $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_POST[user]', 'Created cdr Table')";
  $result=mysql_query($sql, $link);
}


/*======================================================================
                            config Table
  ======================================================================*/
if (!mysql_is_table($db_host,$db_user,$db_pass,"SineDialer","config")){
  include "admin/db_config.php";
$sql = "CREATE TABLE `config` (
  `parameter` varchar(11) NOT NULL default '0',
  `value` varchar(110) NOT NULL
) ";

    $result = mysql_query($sql,$link);
  $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_POST[user]', 'Created config Table')";
  $result=mysql_query($sql, $link);
  $sql = "insert  into config values
('backend', '0'),
('userid', 'VentureVoIP'),
('licencekey', 'DRFHUJWQIWU')";
 $result = mysql_query($sql,$link);
}

/*======================================================================
                            customer Table
  ======================================================================*/
if (!mysql_is_table($db_host,$db_user,$db_pass,"SineDialer","customer")){
  include "admin/db_config.php";
$sql = "CREATE TABLE `customer` (
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
  `didlogin` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
) ";

    $result = mysql_query($sql,$link);
  $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_POST[user]', 'Created customer Table')";
  $result=mysql_query($sql, $link);
  $sql = "insert  into customer values (2, 'matt', '532b536b7b208d1f81c43c1494b55887dee3328c', 1, '156 Maitland Street', 'Kensington', 'Dunedin', 'New Zealand', '+6434742112', 'matt@venturevoip.com', '+6434742116', 'http://www.venturevoip.com/st.php', 100, 'VentureVoIP', 1, '', '', 1000, 1001, '111,102,101', '1234')";
  $result=mysql_query($sql, $link);
}

/*======================================================================
                            dncnumber Table
  ======================================================================*/
if (!mysql_is_table($db_host,$db_user,$db_pass,"SineDialer","dncnumber")){
  include "admin/db_config.php";
$sql = "CREATE TABLE `dncnumber` (
  `campaignid` int(200) NOT NULL default '0',
  `phonenumber` varchar(50) NOT NULL default '',
  `status` varchar(50) NOT NULL default '',
  `type` int(5) NOT NULL default '0',
  PRIMARY KEY  (`campaignid`,`phonenumber`),
  KEY `test` (`phonenumber`,`campaignid`)
) ";

    $result = mysql_query($sql,$link);
  $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_POST[user]', 'Created dncnumber Table')";
  $result=mysql_query($sql, $link);
}

/*======================================================================
                            number Table
  ======================================================================*/
if (!mysql_is_table($db_host,$db_user,$db_pass,"SineDialer","number")){
  include "admin/db_config.php";
$sql = "CREATE TABLE `number` (
  `campaignid` int(200) NOT NULL default '0',
  `phonenumber` varchar(50) NOT NULL default '',
  `status` varchar(50) NOT NULL default '',
  `type` int(5) NOT NULL default '0',
  `datetime` timestamp NULL default NULL on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`campaignid`,`phonenumber`),
  KEY `test` (`phonenumber`,`campaignid`),
  KEY `status` (`campaignid`,`status`),
  KEY `status2` (`status`)
)";

    $result = mysql_query($sql,$link);
  $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_POST[user]', 'Created number Table')";
  $result=mysql_query($sql, $link);
}


/*======================================================================
                            queue Table
  ======================================================================*/
if (!mysql_is_table($db_host,$db_user,$db_pass,"SineDialer","queue")){
  include "admin/db_config.php";
$sql = "CREATE TABLE `queue` (
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
  `trunk` varchar(255) default 'Local/s@\${EXTEN}',
  `accountcode` varchar(255) default 'noaccount',
  `trunkid` int(11) default '-1',
  `customerID` int(11) default '-1',
  `maxcps` int(11) default '31',
  PRIMARY KEY  (`queueID`)
) ";

    $result = mysql_query($sql,$link);
  $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_POST[user]', 'Created queue Table')";
  $result=mysql_query($sql, $link);
}





/*======================================================================
                            campaignconfig Table
  ======================================================================*/
if (!mysql_is_table($db_host,$db_user,$db_pass,"SineDialer","campaignconfig")){
  include "admin/db_config.php";
$sql = "CREATE TABLE `campaignconfig` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `type` int(11) default '0',
  `astqueuename` varchar(255) default NULL,
  `did` varchar(255) default NULL,
  `clid` varchar(255) default NULL,
  `trclid` varchar(255) default NULL,
  `maxchans` int(11) default '10',
  `numagents` int(11) default '10',
  PRIMARY KEY  (`id`)
) ";

    $result = mysql_query($sql,$link);
  $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_POST[user]', 'Created campaignconfig Table')";
  $result=mysql_query($sql, $link);
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
  `receipt` varchar(255) default NULL,
  `paymentmode` varchar(255) default NULL,
  `username` varchar(255) default NULL,
  `addedby` varchar(255) default NULL
  )";
  $result = mysql_query($sql,$link);
  $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_POST[user]', 'Created Billing Log Table')";
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
  `trunk` varchar(255) default 'Local/s@\${EXTEN}',
  `accountcode` varchar(255) default 'noaccount',
  `trunkid` int(11) default '-1',
  `customerID` int(11) default '-1',
  `maxcps` int(11) default '31',
  PRIMARY KEY  (`queueID`)
        );";
        $result = mysql_query($sql,$link);
}

/*======================================================================
                            servers Table
  ======================================================================*/
if (!mysql_is_table($db_host,$db_user,$db_pass,"SineDialer","servers")){
  include "admin/db_config.php";
$sql = "CREATE TABLE `servers` (
  `id` int(11) NOT NULL auto_increment,
  `address` varchar(250) NOT NULL default '',
  `name` varchar(200) NOT NULL default '',
  `username` varchar(250) NOT NULL default '',
  `password` varchar(250) NOT NULL default '',
  `status` int(10) default '0',
  PRIMARY KEY  (`id`)
)";

    $result = mysql_query($sql,$link);
  $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_POST[user]', 'Created servers Table')";
  $result=mysql_query($sql, $link);
}

/*======================================================================
                            stage Table
  ======================================================================*/
if (!mysql_is_table($db_host,$db_user,$db_pass,"SineDialer","stage")){
  include "admin/db_config.php";
$sql = "CREATE TABLE `stage` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `phonenumber` varchar(50) NOT NULL default '',
  `stage` int(3) NOT NULL default '0',
  `campaignid` int(3) NOT NULL default '0',
  `timestamp` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ";

    $result = mysql_query($sql,$link);
  $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_POST[user]', 'Created stage Table')";
  $result=mysql_query($sql, $link);
}


/*======================================================================
                            trunk Table
  ======================================================================*/
if (!mysql_is_table($db_host,$db_user,$db_pass,"SineDialer","trunk")){
  include "admin/db_config.php";
$sql = "CREATE TABLE `trunk` (
  `id` int(15) unsigned NOT NULL auto_increment,
  `name` varchar(250) NOT NULL default '',
  `dialstring` varchar(250) NOT NULL default '',
  `current` int(1) NOT NULL default '0',
  `maxchans` int(11) unsigned default '100',
  `maxcps` varchar(255) default '30',
  PRIMARY KEY  (`id`)
) ";

    $result = mysql_query($sql,$link);
  $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_POST[user]', 'Created trunk Table')";
  $result=mysql_query($sql, $link);
  $sql = "insert  into trunk values
(1, 'Load Test', 'Local/s@staff/\${EXTEN}', 1, 300, '10'),
(11, 'Local Hardware', 'Zap/g1/\${EXTEN}', 0, 10, '3'),
(13, 'Dialplan', 'Local/\${EXTEN}@my_context', 0, 1000, '3'),
(16, 'IAX2 Trunk', 'IAX2/my-provider/\${EXTEN}', 0, 100, '10'),
(17, 'SIP Trunk', 'SIP/\${EXTEN}@my-provider', 0, 100, '5')";
  $result=mysql_query($sql, $link);

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

/*****************************************************************
*           ALTER customer TABLE TO ADD astqueuename FIELD       *
******************************************************************/
unset($field_array);
$fields = mysql_list_fields('SineDialer', 'customer', $link);
$columns = mysql_num_fields($fields);
for ($i = 0; $i < $columns; $i++) {
    $field_array[] = mysql_field_name($fields, $i);
}
if (!in_array('astqueuename', $field_array))
{
    $result = mysql_query('ALTER TABLE customer ADD astqueuename VARCHAR(255)');
    $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_POST[user]', 'Added customer astqueuename field')";
    $result=mysql_query($sql, $link);
}


/*****************************************************************
*           ALTER sip_buddies TABLE TO ADD call-limit FIELD      *
******************************************************************/
unset($field_array);
$fields = mysql_list_fields('SineDialer', 'sip_buddies', $link);
$columns = mysql_num_fields($fields);
for ($i = 0; $i < $columns; $i++) {
    $field_array[] = mysql_field_name($fields, $i);
}
if (!in_array('call-limit', $field_array))
{
    $result = mysql_query('ALTER TABLE sip_buddies ADD `call-limit` int(8) default 1') or die(mysql_error());
    $result = mysql_query('UPDATE sip_buddies SET `call-limit`=1') or die(mysql_error());
    $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_POST[user]', 'Added sip_buddies call-limit field')";
    $result=mysql_query($sql, $link);
}


/*****************************************************************
*           ALTER customer TABLE TO ADD didlogin FIELD             *
******************************************************************/
unset($field_array);
$fields = mysql_list_fields('SineDialer', 'customer', $link);
$columns = mysql_num_fields($fields);
for ($i = 0; $i < $columns; $i++) {
    $field_array[] = mysql_field_name($fields, $i);
}

if (!in_array('didlogin', $field_array))
{
    $result = mysql_query('ALTER TABLE customer ADD didlogin VARCHAR(255)');
    $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_POST[user]', 'Added customer didlogin field')";
    $result=mysql_query($sql, $link);
}

/*****************************************************************
*           ALTER MESSAGE TABLE TO ADD length FIELD             *
******************************************************************/
unset($field_array);
$fields = mysql_list_fields('SineDialer', 'campaignmessage', $link);
$columns = mysql_num_fields($fields);
for ($i = 0; $i < $columns; $i++) {
    $field_array[] = mysql_field_name($fields, $i);
}

if (!in_array('length', $field_array))
{
    $result = mysql_query('ALTER TABLE campaignmessage ADD length VARCHAR(255)');
    $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_POST[user]', 'Added campaignmessage length field')";
    $result=mysql_query($sql, $link);
}

/*****************************************************************
*           ALTER BILLING TABLE TO ADD receipt FIELD             *
******************************************************************/
unset($field_array);
$fields = mysql_list_fields('SineDialer', 'billinglog', $link);
$columns = mysql_num_fields($fields);
for ($i = 0; $i < $columns; $i++) {
    $field_array[] = mysql_field_name($fields, $i);
}

if (!in_array('receipt', $field_array))
{
    $result = mysql_query('ALTER TABLE billinglog ADD receipt VARCHAR(255)');
    $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_POST[user]', 'Added billinglog receipt field')";
    $result=mysql_query($sql, $link);
}

if (!in_array('paymentmode', $field_array))
{
    $result = mysql_query('ALTER TABLE billinglog ADD paymentmode VARCHAR(255)');
    $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_POST[user]', 'Added billinglog paymentmode field')";
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
    setcookie("language",$_POST[language],time()+6000);
    if (mysql_result($result,0,'security')==100){
        $level=sha1("level100");
    } else if (mysql_result($result,0,'security')==0){
        $level=sha1("level0");
    } else if (mysql_result($result,0,'security')==5){
        $level=sha1("level5");
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
