<?
//require "header.php";
$passwordHash = sha1($_POST['pass']);

require_once "PHPTelnet.php";

$telnet = new PHPTelnet();

// if the first argument to Connect is blank,
// PHPTelnet will connect to the local host via 127.0.0.1
$telnet = new PHPTelnet();
$result = $telnet->Connect();
//echo "CONNECTION REQUEST: ".$result."<BR>";
$telnet->DoCommand('login', $result);
//echo "XX".$result."<BR>";
$telnet->DoCommand($_POST[user], $result);
//echo "YY:".$result."<BR>";
$telnet->DoCommand($passwordHash, $result);
//echo "YY2:".$result."<BR>";
if (substr(trim($result),0,14)=="Security Level") {
    $level=substr(trim($result),14);
    //echo "FOUND SECURITY LEVEL $level";
}
//$pieces = explode("\n",$result);
//foreach ($pieces as $line_num => $line) {
//}



//$dbpass = sha1($_POST['pass']);
//$result=mysql_query($sql, $link) or die (mysql_error());;
//$dbpass=mysql_result($result,0,'password');
if ($level>0) {
    setcookie("loggedin",sha1("LoggedIn".$_POST[user]),time()+6000);
    setcookie("user",$_POST[user],time()+6000);
    if ($level==100){
        $levelout=sha1("level100");
    } else {
        $levelout=sha1("level0");
    }
    setcookie("level",$levelout,time()+6000);
    header("Location: home.php");
    exit;
} else {
    setcookie("loggedin","--",time()+6000);
    setcookie("user",$_POST[user],time()+6000);
    header("Location: index.php?error=Incorrect%20UserName%20or%20Password");
    exit;
?>
<?
}
require "footer.php";
?>
