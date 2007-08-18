<?
$pagenum=5;
require "header.php";
//$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';




//$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=$groupid;
?>

<table class="" align="center" border="0" cellpadding="2" cellspacing="0">
<TR>
<TD CLASS="thead">
Name
</TD>
<TD CLASS="thead">
WebSite
</TD>
<TD CLASS="thead">
Username
</TD>
<TD CLASS="thead">
Address Line 1
</TD>
<TD CLASS="thead">
Address Line 2
</TD>
<TD CLASS="thead">
City
</TD>
<TD CLASS="thead">
Country
</TD>
<TD CLASS="thead">
Phone
</TD>
<TD CLASS="thead">
Fax
</TD>
<TD CLASS="thead">
</TD>
</TR>
<?





//$sql = 'SELECT * FROM customer';
//$result=mysql_query($sql, $link) or die (mysql_error());;
//$campaigngroupid=mysql_result($result,0,'campaigngroupid');

// $row is an array of one item's fields

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
//echo $result."<BR>";





//while ($row = mysql_fetch_assoc($result)) {

if ($toggle){
$toggle=false;
$class=" class=\"tborder2\"";
} else {
$toggle=true;
$class=" class=\"tborderx\"";
}

?>
<TR <?echo $class;?>>
<TD>
<?
if (strlen($row[company])<15){
echo "<A HREF=\"editcustomer.php?id=".$row[id]."\">".$row[company]."</A>";
} else {
echo "<A HREF=\"editcustomer.php?id=".$row[id]."\">".trim(substr($row[company],0,15))."...</A>";
}
?>
</TD>
<TD><A HREF="<?echo $row[website];?>" TARGET="<?echo $row[website];?>"><?echo $row[website];?></A>
</TD>
<TD>
<?echo $row[username];?>
</TD>
<TD>
<?echo $row[address1];?>
</TD>
<TD>
<?echo $row[address2];?>
</TD>
<TD>
<?echo $row[city];?>
</TD>
<TD>
<?echo $row[country];?>
</TD>
<TD>
<?echo $row[phone];?>
</TD>
<TD>
<?echo $row[fax];?>
</TD>
<TD>
<?echo "<A HREF=\"deletecustomer.php?id=".$row[id]."\"><IMG SRC=\"/images/cross.gif\" BORDER=\"0\"></A>";?>
</TD>
</TR>

<?
}
$telnet->Disconnect();
sleep(1);
//exit(0);

?>

</TABLE>
<?
require "footer.php";
?>
