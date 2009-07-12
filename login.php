<?
/* Login.php                                                              */
/* =========                                                              */
/* This file processes the logins, checks that the website is up to date, */
/* and finally redirects to a location based on whether the login worked  */
/* or not.                                                                */

/* Include database configuration */
if (isset($override_directory)) {
	$current_directory = $override_directory;
} else {
	$current_directory = dirname(__FILE__);
}
include "/".$current_directory."/admin/db_config.php";

/* Include the common functions   */
require "/".$current_directory."/functions/functions.php";

/* Check all connections are ok */
create_missing_tables($db_host,$db_user,$db_pass);

/* Get the SHA hash of the password that was sent to this file */
$passwordHash = sha1($_POST['pass']);

/* Make sure our queries use the SineDialer database */
mysql_select_db("SineDialer") or die(mysql_error());

/* Escape get and post data */
$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);

/* Log an entry to the database saying that someone attempted a login */
$sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_POST[user]', 'Attempted login')";
$result=mysql_query($sql, $link) or die(mysql_error());

/* Try to load the password information from the database for that user */
$sql = 'SELECT password, security, interface_type FROM customer WHERE username=\''.$_POST[user].'\'';
$result=mysql_query($sql, $link);
if (mysql_num_rows($result) > 0) {
    $dbpass=mysql_result($result,0,'password');
    $interface_type = mysql_result($result,0,'interface_type');
} else {
    $interface_type = "default";
}

/* Compare the password from the database with the SHA hash of what they entered */
if (trim($dbpass)!=trim($passwordHash)){
	/***************************************************/
	/* INCORRECT PASSWORD                              */
	/***************************************************/
	/* If the password does not match clear the cookie */
    setcookie("loggedin","--",time()+6000);
    setcookie("user",$_POST[user],time()+6000);
    
    /* Log to the database that someone had an unsuccessful login attempt */
    $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_POST[user]', 'Unuccessful login')";
    $result=mysql_query($sql, $link);
    
    /* Redirect the user back to the start page */
    header("Location: index.php?error=Incorrect%20UserName%20or%20Password");
    exit;
} else {
	/***************************************************/
	/* CORRECT PASSWORD                                */
	/***************************************************/
	/* If the passwords do match set some cookies*/
    setcookie("loggedin",sha1("LoggedIn".$_POST[user]),time()+6000);
    setcookie("user",$_POST[user],time()+6000);
    setcookie("language",$_POST[language],time()+6000);

    /* Check if the url and the language has settings available */
    $url = $_SERVER[SERVER_NAME];
    $sql = "SELECT * FROM web_config WHERE LANG=".sanitize($_POST[language])." AND url = ".sanitize($url);
    $result_url = mysql_query($sql);
    
    /* If there are no settings for this url and language, just use the default settings */
    if (mysql_num_rows($result_url) == 0) {
        $url = "default";
    }
    setcookie("url",$url,time()+6000);

	/* Set their level based on what security level is stored for them in the database */
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
    
    /* Log that we had a successful login */
    $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_POST[user]', 'Successful login')";
    $result=mysql_query($sql, $link);
    
    /* Either redirect to where we were sent to, or we redirect to main.php */
    if (strlen($_GET[redirect]) > 0) {
    	/* TODO: this needs to be sanitized so that someone can't be fooled int a cross site */
    	/* attack.  At the moment, if someone sent you to a url like:                        */
    	/* http://call.venturevoip.com/login.php?redirect=http://badsite.com                 */
    	/* Then you could be tricked into thinking you were at call.venturevoip.com but      */
    	/* actually be sent to badsite.com.                                                  */
    	header("Location: ".$_GET[redirect]);
    } else {
    	/* Now we need to decide where to redirect. Our choices are currently the standard   */
    	/* interface, the message broadcasting interface, and the predictive dialing or call */
    	/* centre interface.                                                                 */
        if (0 && $interface_type == "broadcast") {
        	/* Redirect to the broadcast interface */
        	header("Location: /modules/broadcast/main.php");
        } else if (0 && $interface_type == "cc") {
        	/* Redirect to the call centre interface */
        	header("Location: /modules/cc/main.php");
        } else {
        	/* Redirect to the default interface */
        	header("Location: /main.php");
        }
    }
    exit;
} 
require "footer.php";
?>
