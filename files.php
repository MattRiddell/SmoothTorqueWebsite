<?
require "header.php";
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

if ($page < 1) {
    $page = 1;
}
$sql = "SELECT * FROM files WHERE filename like 'question_%' order by datetime desc limit ".(($page-1)*20+1).",20";
//echo $sql;
?>
<a href="files.php?page=<?=($page-1)?>">Previous Page</a> Current Page: <?=$page?> <a href="files.php?page=<?=($page+1)?>">Next Page</a><br /><br />
<?
$result = mysql_query($sql) or die(mysql_error());
if (mysql_num_rows($result) == 0) {
    // No rows
} else {
    echo '<center><table class="transfer_history">';
    echo '<thead><tr><th>Audio</th><th>Question Number</th><th>Phone Number</th><th>Time</th><th>Text</th></tr></thead><tbody>';
    while ($row = mysql_fetch_assoc($result)) {
        echo '<tr>';
//        print_pre($row);
        echo '<td>';
        ?>
        <audio controls>
        <source src="recordings/<?=$row['filename']?>" type="audio/wav">
        Your browser does not support the audio element.
        </audio><br />
        <?
        echo '</td>';
        echo '<td>'.str_replace(".wav","",$exp[2]).'</td>';
        
        $exp = explode("-",$row['filename']);
//        print_pre($exp);
        echo '<td>'.$exp[0].'</td>';
        echo '<td>'.$row['datetime'].'</td>';
        echo '<td>';
        ?>
        <input type="text" name="text">
        <input type="submit" value="Save">
        <?
        echo '</td>';
        
        
        echo '</tr>';
    }
    echo '</tbody></table>';
}
require "footer.php";
exit(0);

?>