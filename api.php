<?
/**
 * Created by PhpStorm User: mattriddell Date: 28/12/15 Time: 20:10
 */

require "header.php";
if (isset($_GET['start'])) {
    ?>
    <div class="jumbotron">
        <h3>Starting your campaign</h3>
        <p>Please wait while we start your campaign...</p>
        <div class="progress" id="bar_holder">
            <div class="progress-bar progress-bar-striped active" style="width: 0%" id="bar"></div>
        </div>
        <div id="status"></div>
    </div>
    <?
    ob_flush();
    flush();
}
if (isset($_GET['stop'])) {
    ?>
    <div class="jumbotron">
        <h3>Stopping your campaign</h3>
        <p>Please wait while we stop your campaign...</p>
        <div class="progress" id="bar_holder">
            <div class="progress-bar progress-bar-striped active" style="width: 0%" id="bar"></div>
        </div>
        <div id="status"></div>
    </div>
    <?
    ob_flush();
    flush();
}
?>

<?


if (isset($_GET['start'])) {
    $field['status'] = 1;
    $id = $_GET['start'];
} else {
    $field['status'] = 2;
    $id = $_GET['stop'];
}


$result = mysql_query("SELECT * FROM campaign WHERE id = ".sanitize($id)) or die(mysql_error());
$row = mysql_fetch_assoc($result);

$result = mysql_query("SELECT * FROM customer WHERE id = ".sanitize($row['groupid'])) or die(mysql_error());
$customer_row = mysql_fetch_assoc($result);

if ($customer_row['trunkid'] == -1) {
    // Default Trunk
    $result = mysql_query("SELECT * FROM trunk WHERE current = '1'") or die(mysql_error());
    $trunk_row = mysql_fetch_assoc($result);
} else {
    // Specific Trunk
    $result = mysql_query("SELECT * FROM trunk WHERE id = ".sanitize($customer_row['trunkid'])) or die(mysql_error());
    $trunk_row = mysql_fetch_assoc($result);
}

$field['queuename'] = "autostart-".$_GET['start'];


$field['campaignID'] = $id;
$field['details'] = "No Details";
$field['flags'] = 0;
$field['transferclid'] = $row['trclid'];
$field['starttime'] = '00:00:00';
$field['endtime'] = '23:59:59';
$field['startdate'] = '2005-01-01';
$field['enddate'] = '2055-01-01';
$field['did'] = $row['did'];
$field['clid'] = $row['clid'];
$field['context'] = $row['context'];
$field['maxcalls'] = $row['maxagents'];
$field['maxchans'] = $trunk_row['maxchans'];
$field['maxretries'] = 0;
$field['retrytime'] = 0;
$field['waittime'] = 30;
$field['trunk'] = $trunk_row['dialstring'];
$field['accountcode'] = 'stl-'.$customer_row['username'];
$field['trunkid'] = $trunk_row['id'];
$field['customerID'] = $customer_row['id'];
$field['maxcps'] = $trunk_row['maxcps'];
$field['drive_min'] = '43.0';
$field['drive_max'] = '61.0';

mysql_query("DELETE FROM queue WHERE campaignID = ".sanitize($id));
$sql1 = "INSERT INTO queue (";
$sql2 = ") VALUES (";
foreach ($field as $key => $value) {
    $sql1 .= $key.",";
    $sql2 .= sanitize($value).",";
}
$sql = substr($sql1, 0, strlen($sql1) - 1).substr($sql2, 0, strlen($sql2) - 1).")";
mysql_query($sql) or die(mysql_error());
$new_id = mysql_insert_id();
//print_pre($field);

// Query Done, wait to see if it worked.
$done = FALSE;
$i = 0;
while ($done == FALSE) {
    $i++;
    if ($i > 100) {
        $done = TRUE;
        ?>
        <script>
            $("#status").html("<h3 class=\"text-danger\">Nothing happened - is the back end running?</h3>");
            $("#bar_holder").hide();
        </script>
        <?
        break;
    }
    $result = mysql_query("SELECT status FROM queue WHERE queueID = ".$new_id." LIMIT 1") or die(mysql_error());
    if (mysql_num_rows($result) == 0) {
        // No rows
        $done = TRUE;
        echo "<h3>Couldn't find your campaign</h3>";
    } else {
        $row = mysql_fetch_assoc($result);
        if ($row['status'] != $field['status']) {
            $done = TRUE;
            ?>
            <script>
                window.location = "new_campaign.php?view=1";
            </script>
            <?
        }
    }
    ?>
    <script>

        $('#bar').css('width', <?=round($i * 1)?>+'%').attr('aria-valuenow', <?=round($i * 1)?>);
    </script>
    <?
    ob_flush();
    flush();
    sleep(1);
}

require "footer.php";
exit(0);
