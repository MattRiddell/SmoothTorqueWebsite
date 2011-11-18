<?
require "header.php";
require "header_numbers.php";
box_start(400);
echo "<center>";
?>
<a href="notifications.php?add=1"><img src="images/email_add.png" border="0">&nbsp;Add Notification</a>
<a href="notifications.php"><img src="images/email.png" border="0">&nbsp;View Notifications</a>

<?
echo "<br />";
box_end();
echo "<br />";

if (isset($_GET['save_new'])) {
    $sql1 = "INSERT INTO notifications (";
    $sql2 = ") VALUES (";
    foreach ($_POST as $field=>$value) {
        $sql1.=sanitize($field,false).",";
        $sql2.=sanitize($value,true).",";
    }
    $sql = substr($sql1,0,strlen($sql1)-1).substr($sql2,0,strlen($sql2)-1).")";;
    $result = mysql_query($sql);
    redirect("notifications.php","Adding your notification");
    require "footer.php";
    exit(0);
}

if (isset($_GET['add'])) {
    box_start(500);
    echo "<br /><center>";
    ?>
    <form action="notifications.php?save_new=1" method="post">
    <table>
    <tr>
    <td>Campaign:</td>
    <td><select name="campaign_id">
    <option value="-1">All Camapaigns</option>
    <?
    $result = mysql_query("SELECT id, name FROM campaign");
    while ($row = mysql_fetch_assoc($result)) {
        echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';        
    }
    ?>
    </select></td>
    </tr>
    <tr>
    <td>Percentage to warn at</td>    
    <td><input type="text" name="percent_remaining" value="10">%</td>
    </tr>
    <tr>
    <td>Email address</td>    
    <td><input type="text" name="email_address" value=""></td>
    </tr>
    <tr>
    <td colspan="2"><input type="submit" value="Add Notification"></td>    
    </tr>
    
    </table>
    </form>
    <?
    box_end();
    require "footer.php";
    exit(0);
}

if (isset($_GET['edit'])) {
    require "footer.php";
    exit(0);
}

if (isset($_GET['delete'])) {
    require "footer.php";
    exit(0);
}

box_start(600);
echo "<br /><center>";


$result = mysql_query("SELECT * FROM notifications");
if (mysql_num_rows($result) == 0) {
    echo "No notifcations set up<br />";
} else {
    ?>
    <table border="0" cellpadding="3" cellspacing="0">
    <tr height="10"><td class="theadl"></td><td class="thead">Campaign</td><td class="thead">Warn at %</td><td class="thead">Email</td><td class="thead">Delete</td><td class="theadr"></td></tr>
    <?
    $toggle = false;
    while ($row = mysql_fetch_assoc($result)) {
        /* Alternate the display */
        if ($toggle){
            $toggle=false;
            $class=" class=\"tborder2\"  onmouseover=\"style.backgroundColor='#84DFC1';\" onmouseout=\"style.backgroundColor='#f8f8f8'\"   ";
        } else {
            $toggle=true;
            $class=" class=\"tborderx\"  onmouseover=\"style.backgroundColor='#84DFC1';\" onmouseout=\"style.backgroundColor='#f0f0f0'\" ";
        }
        echo "<tr $class>";
        echo "<td></td>";
        $url = '<a href="notifications.php?edit=1&campaign_id='.$row['campaign_id'].'&email='.$row['email_address'].'">';
        if ($row['campaign_id'] == -1) {
            $row2['name'] = "All Campaigns";
        } else {
            $sql = "SELECT * FROM campaign WHERE id = ".$row['campaign_id'];
            $row2 = mysql_fetch_assoc(mysql_query($sql)) or die (mysql_error());
        }
        echo '<td>'.$url.$row2['name'].'&nbsp;<img src="images/pencil.png" alt="Edit Notification"></a></td>';
        echo "<td>".$row['percent_remaining']."%</td>";
        echo "<td>".$row['email_address']."</td>";
        echo '<td><a href="notifications.php?delete=1&campaign_id='.$row['campaign_id'].'&email='.$row['email_address'].'"><img src="images/delete.png" alt="Delete Notification"></td>';
        echo "<td></td></tr>";
    }
    ?>
    </table>    
    <?
}
echo "<br />";
box_end();
require "footer.php";
?>