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
    $result = mysql_query($sql);
    ?><center><img src="images/progress.gif" border="0"><br />Adding DDI...
    <META HTTP-EQUIV=REFRESH CONTENT="1; URL=ddis.php"><?

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
if (isset($_GET['save_edit'])) {
    //print_pre($_POST);
    $result = mysql_query("UPDATE dids SET number=".sanitize($_POST['ddi_number']).", message_id = ".sanitize($_POST['message_id']).", campaign_id = ".sanitize($_POST['campaign_id'])." WHERE number = ".sanitize($_POST['old_number']));
    ?><center><img src="images/progress.gif" border="0"><br />Saving DDI...
    <META HTTP-EQUIV=REFRESH CONTENT="1; URL=ddis.php"><?
    require "footer.php";
    exit(0);
}
if (isset($_GET['edit'])) {
    $row_start = mysql_fetch_assoc(mysql_query("SELECT * FROM dids WHERE number = ".sanitize($_GET['edit'])));
    box_start(400);
    ?>
    <center>
    <form action="ddis.php?save_edit=1" method="post">
    <input type="hidden" name="old_number" value="<?=$row_start['number']?>">
    DDI Number: <input type="text" name="ddi_number" value="<?=$row_start['number']?>"><br />
    Message To Play on Answer: <select name="message_id"><?
    $result = mysql_query("SELECT * FROM campaignmessage where filename like 'x-%'");
    while ($row = mysql_fetch_assoc($result)) {
        if ($row_start['message_id'] == $row['id']) {
            echo '<option value="'.$row['id'].'" SELECTED>'.substr($row['name'],0,20).'</option>';
        } else {
            echo '<option value="'.$row['id'].'">'.substr($row['name'],0,20).'</option>';
        }
    }
    ?></select><br />
    Campaign To Associate With:<select name="campaign_id"><?
    $result = mysql_query("SELECT * FROM campaign");
    while ($row = mysql_fetch_assoc($result)) {
        if ($row_start['campaign_id'] == $row['id']) {
            echo '<option value="'.$row['id'].'" SELECTED>'.substr($row['name'],0,20).'</option>';
        } else {
            echo '<option value="'.$row['id'].'">'.substr($row['name'],0,20).'</option>';
        }
    }    
    ?></select><br />
    <input type="submit" value="Save DDI">
    </form>
    <?
    box_end();
    require "footer.php";
    exit(0);
}
box_start();
echo "<center>";
$result = mysql_query("SELECT * FROM dids");
if (mysql_num_rows($result) > 0) {
    echo "<table  class=\"transfer_history\">";
    echo '<tr>';
    echo '<th class="transfer_history">DID Number</th>';
    echo '<th class="transfer_history">Intro Message</th>';
    echo '<th class="transfer_history">Campaign</th>';
    echo '<th class="transfer_history">Delete</th>';
    echo '</tr>';
    while ($row = mysql_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td class=\"transfer_history\">".'<a href="ddis.php?edit='.$row['number'].'">'.$row['number']."&nbsp;";
        echo '<img src="images/pencil.png">'."</td>";
        echo "</td>";
        $message = mysql_result(mysql_query("SELECT name FROM campaignmessage WHERE id = ".$row['message_id']),0,0);
        echo "<td class=\"transfer_history\">".$message."</td>";
        $campaign = mysql_result(mysql_query("SELECT name FROM campaign WHERE id = ".$row['campaign_id']),0,0);
        echo "<td class=\"transfer_history\">".$campaign."</td>";
        echo "<td class=\"transfer_history\">".'<a href="ddis.php?delete='.$row['number'].'"><img src="images/delete.png">'."</td>";
        //print_pre($row);
        echo "</tr>";
    }
    echo "</table>";
}
box_end();
require "footer.php";
?>