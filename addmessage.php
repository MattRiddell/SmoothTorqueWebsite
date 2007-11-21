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
    $id=$_POST[id];
    $name=$_POST[name];
    $description=$_POST[description];
    $filename=$_POST[filename];

//    $sql="INSERT INTO campaign (groupid,name,description,messageid,messageid2,messageid3) VALUES ('customerid','$name', '$description', '$filename')";
//    echo $sql;

/*    require_once "sql.php";
    $SMDB2=new SmDB();*/
    $sql="insert  into campaignmessage (filename,name,description,customer_id) values".
         "('$filename', '$name', '$description',$campaigngroupid)";
    $result=mysql_query($sql, $link) or die (mysql_error());;
/*    $SMDB2->executeUpdate($sql);*/


    header("Location: /messages.php");
    exit;
}
//require "header_campaign.php";
$pagenum="2";
require "header.php";
require "header_message.php";
$campaigngroupid=$groupid;

?>

<FORM ACTION="addmessage.php" METHOD="POST">
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2">
<?
?>
<TR><TD CLASS="thead">Message Name</TD><TD>
<INPUT TYPE="HIDDEN" NAME="customerid" VALUE="<?echo $groupid;?>">
<INPUT TYPE="TEXT" NAME="name" VALUE="<?echo $row[name];?>" size="60">
</TD>
</TR>

<TR><TD CLASS="thead">Message Description</TD><TD>
<INPUT TYPE="TEXT" NAME="description" VALUE="<?echo $row[description];?>" size="60">
</TD>
</TR>

<TR><TD CLASS="thead">Message Filename</TD><TD>
<INPUT TYPE="TEXT" NAME="filename" VALUE="<?echo $row[filename];?>" size="60">
</TD>
</TR>



<TR><TD COLSPAN=2 ALIGN="RIGHT">
<INPUT TYPE="SUBMIT" VALUE="Add Message">
</TD>
</TR>
<?
?>

</TABLE>
</FORM>
<?
require "footer.php";
?>
