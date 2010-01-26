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
    //echo "<br />";
    //echo "No customer selected";
    $sql = "SELECT customer.*, billing.customerid FROM customer LEFT JOIN billing ON customer.id = billing.customerid where billing.customerid is not null";
    $result = mysql_query($sql) or die(mysql_error());
?>
    <br /><br /><br /><br />
<center>
<table background="images/sdbox.png" width="300" height="200" class="dragme22">
<tr>
<td>
</td>
<td width="260">


        Please select a customer:<br /><br />
        <form action="addfunds.php" METHOD="get">
        <select name="id"><?
    while ($row = mysql_fetch_assoc($result)) {
        //echo $row[id]." - ".$row[customerid]."<br>";
        ?>
       <option value="<?echo $row[id];?>"><?echo $row[company];?></option>

        <?
        //echo '<A HREF="addfunds.php?id='.$row[id].'">'.$row[company].'</a>';
    }
    ?>        </select>
    <br /><br /><input type="submit" value="Select Customer">

        </form><br />
</td>
<td>
</td></tr>
</table>
</center>
      <?
} else {
if (!isset($_GET[confirm]) && isset($_POST[credit])) {
include "header.php";
?>
<form action = "addfunds.php?id=<?echo $_GET[id];?>&confirm=yes" method="post">
<input type="hidden" name="credit" value="<?echo $_POST[credit]?>">
<input type="hidden" name="receipt" value="<?echo $_POST[receipt]?>">
<input type="hidden" name="paymentmode" value="<?echo $_POST[paymentmode]?>">
    <br /><br /><br /><br />
<center>
<table background="images/sdbox.png" width="300" height="200" class="dragme22">
<tr>
<td>
</td>
<td width="260">
Are you sure you would like to Add funds to <?echo $_POST[company];?>?
Doing so will immediately update their funds. <br />
<br />
<input type="submit" value="Yes">
<input type="button" value="No" onclick="window.location='addfunds.php'">

<br />
</td>
<td>
</td></tr>
</table>
</center>
</form>
<?
exit(0);
} else {
if (isset($_POST[credit])){
    $credit = $_POST[credit];
    $credit2 = $_POST[credit];
    $receipt = $_POST[receipt];
    $paymentmode = $_POST[paymentmode];

$sql = 'SELECT accountcode FROM billing WHERE customerid='.$_GET[id];
$result=mysql_query($sql, $link);
$accountcode = mysql_result($result,0,0);


    $sql = "select credit from billing where accountcode='$accountcode'";
$result = mysql_query($sql, $link) or die(mysql_error());
$before = mysql_result($result,0,0);


    $sql="update billing set credit=credit + '$credit' where customerid=".$_GET[id];
//    echo $sql;
    $result=mysql_query($sql, $link) or die (mysql_error());;
/*    $SMDB2->executeUpdate($sql);*/


/*================= Log Access ======================================*/
$addedby = $_COOKIE[user];

$sql = "INSERT INTO billinglog (timestamp, username, activity, addedby, receipt, paymentmode) VALUES (NOW(), '$accountcode', '$credit','$addedby', '$receipt', '$paymentmode')";
$result=mysql_query($sql, $link);

$sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_COOKIE[user]', '".$addedby." Added ".$credit." credit to customer: ".$accountcode."')";
$result=mysql_query($sql, $link);
/*================= Log Access ======================================*/
$sql = "select credit from billing where accountcode='$accountcode'";
$result = mysql_query($sql, $link);
$after = mysql_result($result,0,0);
$result=mysql_query($sql, $link);

    $before = $config_values['CURRENCY_SYMBOL']." ".number_format($before,2);
    $after = $config_values['CURRENCY_SYMBOL']." ".number_format($after,2);
    $credit2 = $config_values['CURRENCY_SYMBOL']." ".number_format($credit2,2);
    require "header.php";
?>
    <br /><br /><br /><br />
<center>
<table background="images/sdbox.png" width="300" height="200" class="dragme22">
<tr>
<td>
</td>
<td width="260">

    <?echo $credit2." added to ".$accountcode.". Their credit was $before and is now $after";
    echo "<br />";
    echo "<br />";
    echo '<a href="addfunds.php">Add funds to another account</a><br />';
    echo '<a href="billinglog.php">View Billing Log</a><br />';
?>
<br />
</td>
<td>
</td></tr>
</table>
</center>
<?
exit(0);
}
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

<TR><TD CLASS="thead">Receipt Number</TD><TD>
<INPUT TYPE="TEXT" NAME="receipt" size="60">
</TD>
</TR>

<TR><TD CLASS="thead">Payment Mode</TD><TD>
<SELECT NAME="paymentmode">
<OPTION VALUE="Cash Payment">Cash</OPTION>
<OPTION VALUE="Bank Deposit">Bank Deposit</OPTION>
<OPTION VALUE="Credit Card">Credit Card</OPTION>
<OPTION VALUE="Other">Other</OPTION>
</SELECT>
</TD>
</TR>
<INPUT TYPE="HIDDEN" NAME="company" value="<?echo $row[accountcode];?>">

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
