<?
    require "header.php";
    $sql = "select * from log order by timestamp desc";
    $result=mysql_query($sql, $link);
    echo "<center><table border=\"1\">";
    echo "<tr><td>TimeStamp</td><td>Activity</td><td>User Name</td></tr>";
    while ($row = mysql_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>$row[timestamp]</td>";
        echo "<td>$row[activity]</td>";
        echo "<td>$row[username]</td>";
        echo "</tr>";
    }
    echo "</table>";
?>

