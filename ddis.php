<?
/* DDI Management
 * ==============
 * 
 * Works in conjunction with the SineDialer.extensions table to provide 
 * realtime Asterisk extensions for setting up DDI numbers.
 *
 * A DDI contains three components:
 * 1. A DDI Number
 * 2. A Message to play when it is answered
 * 3. A Campaign it is associated with
 * 
 * Rather than create a separate table for DDI info and extensions we will
 * just parse the extensions_table for the info we're looking for.
 */

require "header.php";
require "header_surveys.php";
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
$result = mysql_query("SELECT * FROM extensions_table");
if (mysql_num_rows($result) > 0) {
    while ($row = mysql_fetch_assoc($result)) {
        print_pre($row);
    }
}
require "footer.php";
?>