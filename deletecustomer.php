<?
$pagenum="6";

if (isset($_GET[sure])){
    $id=$_GET[id];
    $sql="DELETE FROM customer where id=$id";
    //$result=mysql_query($sql, $link) or die (mysql_error());;

    require_once "PHPTelnet.php";

$telnet = new PHPTelnet();
$result = $telnet->Connect();
$telnet->DoCommand('sql', $result);
//echo "".$result."<BR>";
flush();
$telnet->DoCommand($sql, $result);
//echo "".$result."<BR>";
flush();
$telnet->Disconnect();





    include("customers.php");
    exit;
}
require "header.php";

?>
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2">
<TR><TD>
Are you Sure You want to delete this record?<BR><BR>
</TD></TR>
<TR><TD>
<?
require_once "PHPTelnet.php";

$telnet = new PHPTelnet();

// if the first argument to Connect is blank,
// PHPTelnet will connect to the local host via 127.0.0.1
$telnet = new PHPTelnet();
$result = $telnet->Connect();
//echo "CONNECTION REQUEST: ".$result."<BR>";
$x=10;
while (substr(trim($result),0,3)!="END") {
    $telnet->DoCommand('getallc', $result);
    if (substr(trim($result),0,3)!="END"){
        $pieces = explode("\n",$result);
        $row[username]= $pieces[0];
        $row[address1]= $pieces[2];
        $row[address2]= $pieces[3];
        $row[city]= $pieces[4];
        $row[country]= $pieces[5];
        $row[phone]= $pieces[6];
        $row[email]= $pieces[7];
        $row[fax]= $pieces[8];
        $row[website]= $pieces[9];
        $row[security]= $pieces[10];
        $row[company]= $pieces[11];
        $row[id]= $pieces[12];

        /*for ($i=0;$i<sizeof($pieces);$i++){
            echo "[$i]:$pieces[$i]<BR>";
        }*/
    }
}
$sql = 'SELECT * FROM customer WHERE id='.$_GET[id];
//$result=mysql_query($sql, $link) or die (mysql_error());;
//while ($row = mysql_fetch_assoc($result)) {
    echo "<CENTER><B>".$row[company]." - ".$row[city]."</B><BR><BR>";
    echo '<A HREF="deletecustomer.php?id='.$_GET[id].'&sure=yes">Yes, Delete it</A><BR>';
    echo '<A HREF="customers.php">No, Don\'t Delete It</A></CENTER>';
?>
</TD></TR>
<TR><TD>

</TD></TR>
</TABLE>
</FORM>
<?
//}
require "footer.php";
?>
