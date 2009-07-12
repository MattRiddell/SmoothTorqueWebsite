<?
    require "header.php";
	/*================= Log Access ======================================*/
	$sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_COOKIE[user]', 'Viewed the system logs')";
	$result=mysql_query($sql, $link);
	/*================= Log Access ======================================*/
    
    $sql = "select * from log order by timestamp desc limit 100";
    $result=mysql_query($sql, $link) or die (mysql_error());
    
    echo "<br /><center><table border=\"0\">";
    echo "<tr><td>TimeStamp</td><td>Activity</td><td>User Name</td></tr>";
    while ($row = mysql_fetch_assoc($result)) {
    $class_bold=" class=\"tborder2x\"  onmouseover=\"style.backgroundColor='#C10000';\" onmouseout=\"style.backgroundColor='#FF6666'\"   ";
    if ($toggle){
$toggle=false;
$class=" class=\"tborder2\"  onmouseover=\"style.backgroundColor='#84DFC1';\" onmouseout=\"style.backgroundColor='#f8f8f8'\"   ";
} else {
$toggle=true;
$class=" class=\"tborderx\"  onmouseover=\"style.backgroundColor='#84DFC1';\" onmouseout=\"style.backgroundColor='#f0f0f0'\" ";
}

        if ($row[activity] == "Unuccessful login") {
            echo "<tr $class_bold bgcolor=\"#FF6666\">";
            echo "<td><b>$row[timestamp]</b></td>";
            echo "<td><b>$row[activity]</b></td>";
            echo "<td><b>$row[username]</b></td>";
        } else {
            echo "<tr $class>";
            echo "<td>$row[timestamp]</td>";
            echo "<td>$row[activity]</td>";
            echo "<td>$row[username]</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
?>
