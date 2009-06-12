<?
    require "header.php";
if (!isset($_GET[startdate])){
?>
<br />
Please select the dates you would like to view:<br />
<br />
<form action="billinglog_account.php">
From: <input name="startdate">
<input type=button value="select" onclick="displayDatePicker('startdate', false, 'ymd', '-');"><BR>
To: <input name="enddate">
<input type=button value="select" onclick="displayDatePicker('enddate', false, 'ymd', '-');"><BR>
<?if (isset($_GET[accountcode])) {?>
<input type="hidden" name="accountcode" value="<?echo $_GET[accountcode];?>">
<?}?>
<input type="submit" value="Select">
</form>
<?
} else {
$startdate = $_GET[startdate];
$enddate = $_GET[startdate];
/*================= Log Access ======================================*/
$sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_COOKIE[user]', 'Viewed the billing logs')";
$result=mysql_query($sql, $link);
/*================= Log Access ======================================*/
    $sql = "select * from billinglog where timestamp between '".$_GET['startdate']." 00:00:00' and '".$_GET['enddate']." 23:59:59' and username='stl-$_COOKIE[user]' order by timestamp desc";
//    echo $sql;
    $result=mysql_query($sql, $link);
    echo "<br /><center><table border=\"0\">";
    echo "<tr><td>TimeStamp</td><td>Amount</td><td>Receipt</td><td>Payment Mode</td><td>User Name</td><td>Added By</td></tr>";
    while ($row = mysql_fetch_assoc($result)) {
    $class_bold=" class=\"tborder2x\"  onmouseover=\"style.backgroundColor='#C10000';\" onmouseout=\"style.backgroundColor='#FF6666'\"   ";
    if ($toggle){
$toggle=false;
$class=" class=\"tborder2\"  onmouseover=\"style.backgroundColor='#84DFC1';\" onmouseout=\"style.backgroundColor='#f8f8f8'\"   ";
} else {
$toggle=true;
$class=" class=\"tborderx\"  onmouseover=\"style.backgroundColor='#84DFC1';\" onmouseout=\"style.backgroundColor='#f0f0f0'\" ";
}

        echo "<tr $class>";
        echo "<td>$row[timestamp]</td>";
        echo "<td>$row[activity]</td>";
        echo "<td>$row[receipt]</td>";
        echo "<td>$row[paymentmode]</td>";
        echo "<td>$row[username]</td>";
        echo "<td>$row[addedby]</td>";
        echo "</tr>";
    }
    echo "</table>";
}
?>
