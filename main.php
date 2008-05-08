<?
require "header.php";
?>

<br /><br /><br /><br />
<center>
<table background="/images/sdbox.png" width="300" height="200" class="dragme22">
<tr>
<td>
</td>
<td width="260">
<b>Welcome to <?echo $config_values['TITLE'];?>.</b><br />
<br />
<?
if ($level==sha1("level10")){
    echo '<a href="addfunds.php">Add funds to a registered account</a><br />';
    echo '<a href="billinglog.php">View Billing Log</a><br />';

} else {
echo $config_values['MAIN_PAGE_TEXT'];
}
?><br />
</td>
<td>
</td></tr>
</table>
</center>






<?
$level=$_COOKIE[level];

if ($level!=sha1("level100")) {
$ip = $_SERVER['REMOTE_ADDR'];
echo "Attempted break in attempt from $ip ($_COOKIE[user])";
/*================= Log Access ======================================*/
$sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_COOKIE[user]', ' $ip attempted to view the admin page')";
$result=mysql_query($sql, $link);
/*================= Log Access ======================================*/

} else {
if (!isset($_GET[size])) {
    $size=144;
} else {
$size = $_GET[size];
}
mysql_select_db("SineDialer", $link);
$resultx = mysql_query("select distinct system_billing.groupid, customer.* from system_billing left join customer on system_billing.groupid=customer.campaigngroupid");
$x = 0;
$highest = 0;
?>
<table class="tborder" align="center" border="1" cellpadding="0" cellspacing="2"><TR>
<td class=subheader>
</td>


<?
if ($size == 144) {
    echo "<td class=subheader><b>Today&nbsp;</b></td>";
} else {
    echo '<td class=subheader><a href="main.php?size=144">Today</a>&nbsp;</td>';
}
if ($size == 700) {
    echo "<td class=subheader><b>5 Days&nbsp;</b></td>";
} else {
    echo '<td class=subheader><a href="main.php?size=700">5 Days</a>&nbsp;</td>';
}
if ($size == 1400) {
    echo "<td class=subheader><b>10 Days&nbsp;</b></td>";
} else {
    echo '<td class=subheader><a href="main.php?size=1400">10 Days</a>&nbsp;</td>';
}
if ($size == 4200) {
    echo "<td class=subheader><b>30 Days&nbsp;</b></td>";
} else {
    echo '<td class=subheader><a href="main.php?size=4200">30 Days</a>&nbsp;</td>';
}
echo"    </TR></table>";


echo "<br /><br />";
//$size_x= $size;
while ($rowx = mysql_fetch_assoc($resultx)) {
    $result = mysql_query("select max(totalcost) from system_billing where groupid = ".$rowx[groupid]);
    $totalcost[$x] = mysql_result($result,0,0);
    if ($totalcost[$x] > $highest) {
        $highest = $totalcost[$x];
    }
    $company[$x] = $rowx[company];
    $groupid[$x] = $rowx[groupid];
    $real_total_cost += $totalcost[$x];
    $x++;
}
$highest = $highest + ($highest/10);
//for($i = 0;$i<$x;$i++) {
//        //echo $size_x;
//        $totalcost
//
//    }
    $totalcost_cr = $config_values['CURRENCY_SYMBOL']." ".number_format($real_total_cost,2);
        echo "<b>Total System Revenue: $totalcost_cr</b><br />";
        echo '<a href="system_bill_graph.php?xsize=640&ysize=480&size='.$size.'&max='.$highest.'&groupid=-1"><img src="system_bill_graph.php?xsize=800&ysize=200&size='.$size.'&max='.$highest.'&groupid=-1" width="800" height="200" border="0"></a>';
        echo "<br>";
}
?>







<?
require "footer.php";
?>
