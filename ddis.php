<?
/* DDI Management
 * ==============
 * 
 * Works in conjunction with the SineDialer.ddis table to provide 
 * realtime Asterisk extensions for setting up DDI numbers.
 *
 * A DDI contains three components:
 * 1. A DDI Number
 * 2. A Message to play when it is answered
 * 3. A Campaign it is associated with
 */

require "header.php";
require "header_surveys.php";
if (isset($_GET['save_new'])) {
    $sql = "INSERT INTO dids (number, message_id, campaign_id) VALUES (".sanitize($_POST['ddi_number']).",".sanitize($_POST['message_id']).",".sanitize($_POST['campaign_id']).")";
    require "footer.php";
    exit(0);
}
if (isset($_GET['add'])) {
    box_start(400);
    ?>
    <center>
    <form action="ddis.php?save_new=1" method="post">
    DDI Number: <input type="text" name="ddi_number"><br />
    Message To Play on Answer: <select name="message_id"><?
    $result = mysql_query("SELECT * FROM campaignmessage where filename like 'x-%'");
    while ($row = mysql_fetch_assoc($result)) {
        echo '<option value="'.$row['id'].'">'.substr($row['name'],0,20).'</option>';
    }
    ?></select><br />
    Campaign To Associate With:<select name="campaign_id"><?
    $result = mysql_query("SELECT * FROM campaign");
    while ($row = mysql_fetch_assoc($result)) {
        echo '<option value="'.$row['id'].'">'.substr($row['name'],0,20).'</option>';
    }    
    ?></select><br />
    <input type="submit" value="Add DDI">
    </form>
    <?
    box_end();
    require "footer.php";
    exit(0);
}
$result = mysql_query("SELECT * FROM dids");
if (mysql_num_rows($result) > 0) {
    while ($row = mysql_fetch_assoc($result)) {
        print_pre($row);
    }
}
require "footer.php";
?>