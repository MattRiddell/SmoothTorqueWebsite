<?
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);

$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');

if (isset($_POST[name])){
/*    require_once "PHPTelnet.php";

    $telnet = new PHPTelnet();
    $result = $telnet->Connect();
    $telnet->DoCommand('selectcg', $result);
    $telnet->DoCommand($_COOKIE[user], $result);
    if (substr(trim($result),0,7)=="GroupID") {
        $campaigngroupid=substr(trim($result),8);
    }
    $telnet->Disconnect();
*/
	$_POST = array_map(mysql_real_escape_string,$_POST);
    $id=$_POST[id];
    $name=$_POST[name];
    $description=$_POST[description];
    $filename=$_POST[filename];
//    $sql="INSERT INTO campaign (groupid,name,description,messageid,messageid2,messageid3) VALUES ('customerid','$name', '$description', '$filename')";
//    echo $sql;

    $sql="update campaignmessage set filename='$filename',name='$name',description='$description'".
         " where id=$id";
$result=mysql_query($sql, $link) or die (mysql_error());;


    header("Location: messages.php");
    exit;
}
//require "header_campaign.php";
$pagenum="2";
require "header.php";
require "header_message.php";
$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');

//$row2=$SMDB->executeQuery("SELECT * from campaignmessage where id=$_GET[id]");
$sql = "SELECT * from campaignmessage where id=$_GET[id]";
//print_r($row2);
//$row=$row2[0];
//echo $row[name];
$result=mysql_query($sql, $link) or die (mysql_error());;
//$campaigngroupid=mysql_result($result,0,'campaigngroupid');
$row = mysql_fetch_assoc($result);


?>

<FORM ACTION="editmessage.php" METHOD="POST">
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2">
<?
?>
<TR><TD CLASS="thead">Message Name</TD><TD>
<INPUT TYPE="HIDDEN" NAME="id" VALUE="<?echo $_GET[id];?>">
<INPUT TYPE="TEXT" NAME="name" VALUE="<?echo $row[name];?>" size="60">
</TD>
</TR>

<TR><TD CLASS="thead">Message Description</TD><TD>
<INPUT TYPE="TEXT" NAME="description" VALUE="<?echo $row[description];?>" size="60">
<INPUT TYPE="HIDDEN" NAME="filename" VALUE="<?echo $row[filename];?>" size="60">
</TD>
</TR>



<TR><TD COLSPAN=2 ALIGN="RIGHT">
<INPUT TYPE="SUBMIT" VALUE="Save Message">
</TD>
</TR>
<?
?>

</TABLE>
</FORM>
<?
require "footer.php";
?>
