<?
include "admin/db_config.php";

function mysql_is_table($host, $user, $pass, $db, $tbl)
{
    $tables = array();
    $link = @mysql_connect($host, $user, $pass);
    @mysql_select_db($db);
    $q = @mysql_query("SHOW TABLES");
    while ($r = @mysql_fetch_array($q)) { $tables[] = $r[0]; }
    @mysql_free_result($q);
    @mysql_close($link);
    if (in_array($tbl, $tables)) { return TRUE; }
    else { return FALSE; }
}

/*======================================================================
                            Billing Table
  ======================================================================*/
if (!mysql_is_table($db_host,$db_user,$db_pass,"SineDialer","billing")){
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
                            Log Table
  ======================================================================*/
if (!mysql_is_table($db_host,$db_user,$db_pass,"SineDialer","log")){
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

$passwordHash = sha1($_POST['pass']);


//need these lines for the mysql_real_escape_string to work below;
//if you don't have them then it tries to connect to the DB using mysql_connect()
//so end up with access being denied because www-data (or whatever) doesn't have access without a password
$link = mysql_connect($db_host, $db_user, $db_pass) OR die(mysql_error());
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
