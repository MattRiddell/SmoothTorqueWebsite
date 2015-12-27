<?
require "header.php";
if (!isset($_GET[startdate])) {
    ?>
    <div class="jumbotron">
        <h3>Billing Log</h3>

        <p>Please select the dates you would like to view</p>
        <form action="billinglog.php">
            From: <input name="startdate">
            <input type=button value="select" onclick="displayDatePicker('startdate', false, 'ymd', '-');"><BR><BR>
            To: <input name="enddate">
            <input type=button value="select" onclick="displayDatePicker('enddate', false, 'ymd', '-');"><BR><BR>
            <? if (isset($_GET[accountcode])) { ?>
                <input type="hidden" name="accountcode" value="<? echo $_GET[accountcode]; ?>">
            <? } ?>
            <input class="btn btn-primary" type="submit" value="Select">
        </form>
    </div>
    <?
} else {
    $startdate = $_GET[startdate];
    $enddate = $_GET[startdate];
    /*================= Log Access ======================================*/
    $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_COOKIE[user]', 'Viewed the billing logs')";
    $result = mysql_query($sql, $link);
    /*================= Log Access ======================================*/
    $sql = "select * from billinglog where timestamp between '".$_GET['startdate']." 00:00:00' and '".$_GET['enddate']." 23:59:59' order by timestamp desc";
    $result = mysql_query($sql, $link);
    echo '<div class="table-responsive">';
    echo '<table class="table table-striped table-hover">';
    echo "<tr><th>TimeStamp</th><th>Amount</th><th>Receipt</th><th>Payment Mode</th><th>User Name</th><th>Added By</th></tr>";
    while ($row = mysql_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>$row[timestamp]</td>";
        echo "<td>$row[activity]</td>";
        echo "<td>$row[receipt]</td>";
        echo "<td>$row[paymentmode]</td>";
        echo "<td>$row[username]</td>";
        echo "<td>$row[addedby]</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "</div>";
}
?>
