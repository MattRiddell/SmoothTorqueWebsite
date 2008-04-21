<?
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);

$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);

$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');

if (!isset($_GET[id])) {
    require "header.php";
    echo "<br />";
    //echo "No customer selected";
    $sql = "SELECT customer.*, billing.customerid FROM customer LEFT JOIN billing ON customer.id = billing.customerid where billing.customerid is not null";
    $result = mysql_query($sql) or die(mysql_error());
?><br /><br />
        Please select a customer to add funds to:<br /><br />
        <form action="addfunds.php" METHOD="get">
        <select name="id"><?
    while ($row = mysql_fetch_assoc($result)) {
        //echo $row[id]." - ".$row[customerid]."<br>";
        ?>
       <option value="<?echo $row[id];?>"><?echo $row[company];?></option>

        <?
        //echo '<A HREF="addfunds.php?id='.$row[id].'">'.$row[company].'</a>';
    }
    ?>        </select>  <br /><br /><input type="submit" value="Select Customer">

        </form>
      <?
} else {


if (isset($_POST[credit])){
    $credit = $_POST[credit];


    $sql="update billing set credit=credit + '$credit' where customerid=".$_GET[id];
//    echo $sql;
    $result=mysql_query($sql, $link) or die (mysql_error());;
/*    $SMDB2->executeUpdate($sql);*/

/*================= Log Access ======================================*/
$sql = 'SELECT accountcode FROM billing WHERE customerid='.$_GET[id];
$result=mysql_query($sql, $link);
$accountcode = mysql_result($result,0,0);

$addedby = $_COOKIE[user];

$sql = "INSERT INTO billinglog (timestamp, username, activity, addedby) VALUES (NOW(), '$accountcode', '$credit.','$addedby')";
$result=mysql_query($sql, $link);

$sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_COOKIE[user]', '".$addedby." Added ".$credit." credit to customer: ".$accountcode."')";
$result=mysql_query($sql, $link);
/*================= Log Access ======================================*/

    header("Location: /customers.php");
    exit;
}

//require "header_campaign.php";
$pagenum="2";
require "header.php";
//require "header_trunk.php";
$campaigngroupid=$groupid;
$sql = 'SELECT * FROM billing WHERE customerid='.$_GET[id];
$result=mysql_query($sql, $link);
if (1) {
while ($row = mysql_fetch_assoc($result)) {
?>
<br /><br />
<FORM ACTION="addfunds.php?id=<?echo $_GET[id]?>" METHOD="POST">
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2">
<?
?>
<TR><TD CLASS="thead">AccountCode</TD><TD>
<?echo $row[accountcode];?>
</TD>
</TR>
<TR><TD CLASS="thead">Current Balance</TD><TD>
<?echo $config_values['CURRENCY_SYMBOL']." ".number_format($row[credit],2);?>
</TD>
</TR>

<TR><TD CLASS="thead">Funds to add</TD><TD>
<INPUT TYPE="TEXT" NAME="credit" size="60" value="0.00">
</TD>
</TR>

<TR><TD COLSPAN=2 ALIGN="RIGHT">
<INPUT TYPE="SUBMIT" VALUE="Add Funds">
</TD>
</TR>
<?
}
}
?>

</TABLE>
</FORM>
<?
}
require "footer.php";
?>
