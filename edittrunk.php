<?
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);

$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);

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
    $name=$_POST[name];
    $dialstring=$_POST[dialstring];
    //    $sql="INSERT INTO campaign (groupid,name,description,messageid,messageid2,messageid3) VALUES ('customerid','$name', '$description', '$filename')";
//    echo $sql;

/*    require_once "sql.php";
    $SMDB2=new SmDB();*/
    $sql="update trunk ".
         "set name='$name', dialstring='$dialstring' where id=".$_POST[id];
    $result=mysql_query($sql, $link) or die (mysql_error());;
/*    $SMDB2->executeUpdate($sql);*/


    header("Location: /trunks.php");
    exit;
}
//require "header_campaign.php";
$pagenum="2";
require "header.php";
require "header_trunk.php";
$campaigngroupid=$groupid;
$sql = 'SELECT * FROM trunk WHERE id='.$_GET[id];
$result=mysql_query($sql, $link) or die (mysql_error());;
while ($row = mysql_fetch_assoc($result)) {
?>

<FORM ACTION="edittrunk.php" METHOD="POST">
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2">
<?
?>
<TR><TD CLASS="thead">Trunk Name</TD><TD>
<INPUT TYPE="HIDDEN" NAME="customerid" VALUE="<?echo $groupid;?>">
<INPUT TYPE="HIDDEN" NAME="id" VALUE="<?echo $row[id];?>">
<INPUT TYPE="TEXT" NAME="name" VALUE="<?echo $row[name];?>" size="60">
</TD>
</TR>

<TR><TD CLASS="thead">Dial String</TD><TD>
<INPUT TYPE="TEXT" NAME="dialstring" VALUE="<?echo $row[dialstring];?>" size="60">
</TD>
</TR>

<TR><TD COLSPAN=2 ALIGN="RIGHT">
<INPUT TYPE="SUBMIT" VALUE="Save Trunk">
</TD>
</TR>
<?
}
?>

</TABLE>
</FORM>
<?
require "footer.php";
?>
