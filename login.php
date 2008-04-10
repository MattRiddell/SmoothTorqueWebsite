<?
//require "header.php";
include "admin/db_config.php";


/*
if ($config_values['ST_MYSQL_HOST'] == "") {
    $add = @fopen("/stweb.conf",'w');
    fwrite($add,"ST_MYSQL_HOST=$db_host\n");
    fwrite($add,"ST_MYSQL_USER=$db_user\n");
    fwrite($add,"ST_MYSQL_PASS=$db_pass\n");
    fclose($add);

}*/

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
if (mysql_is_table($dbhost,$dbuser,$dbpass,"SineDialer","billing")){
//echo "billing exists";
} else {
//echo "billing does not exist";
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
$result = mysql_query($sql);

}
//exit(0);

$passwordHash = sha1($_POST['pass']);


$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);

$sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_POST[user]', 'Attempted login')";
$result=mysql_query($sql, $link);
if (mysql_error() == "Table 'SineDialer.log' doesn't exist") {
    $sql = "CREATE TABLE `log` (
  `timestamp` timestamp NULL default NULL on update CURRENT_TIMESTAMP,
  `activity` varchar(255) default NULL,
  `username` varchar(255) default NULL
  )";
    $result=mysql_query($sql, $link);
$sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_POST[user]', 'Attempted login')";
$result=mysql_query($sql, $link);
$sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_POST[user]', 'Created Log Table')";
$result=mysql_query($sql, $link);
}


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
$dbpass=mysql_result($result,0,'password');
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
