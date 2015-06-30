<?
if (isset($_GET['save'])) {
    require "admin/db_config.php";
    require "functions/sanitize.php";
    $sql = "UPDATE files SET answer = ".sanitize($_POST['value'])." WHERE filename = ".sanitize($_POST['filename']);
    mysql_query($sql) or die(mysql_error());
//    echo $sql;
//    print_r($_POST);
    exit(0);
}
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
    <script src="js/jquery-1.6.2.min.js"></script>
<a href="files.php?page=<?=($page-1)?>">Previous Page</a> Current Page: <?=$page?> <a href="files.php?page=<?=($page+1)?>">Next Page</a><br /><br />
<?
$z = 0;
$result = mysql_query($sql) or die(mysql_error());
if (mysql_num_rows($result) == 0) {
    // No rows
} else {
    echo '<center><table class="transfer_history">';
    echo '<thead><tr><th>Audio</th><th>Question Number</th><th>Phone Number</th><th>Time</th><th>Text</th></tr></thead><tbody>';
    while ($row = mysql_fetch_assoc($result)) {
        $z++;
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
        
        $exp = explode("-",$row['filename']);
//        print_pre($exp);
        echo '<td>'.$exp[0].'</td>';
        echo '<td>'.str_replace(".wav","",$exp[2]).'</td>';
        echo '<td>'.$row['datetime'].'</td>';
        echo '<td>';
        ?>
        <input type="text" name="text" value="<?echo $row['answer'];?>"  id = "<?=$z?>">
        <input class="btn btn-primary" type="submit" value="Save" onclick="send('<?=$row['filename']?>','<?=$z?>');">
        <?
        echo '</td>';
        
        
        echo '</tr>';
    }
    echo '</tbody></table>';
    ?>
    <script>
    function send( filename,qnum) {
        //alert($("#"+qnum).val());
        //alert(filename);
        $.post( "files.php?save=1", { filename: filename, value:  $("#"+qnum).val()} ).done(function( data ) {
                                                                                            $("#"+qnum).fadeIn(500).fadeOut(500).fadeIn(500).fadeOut(500).fadeIn(500);
                                                                                            $("#"+qnum).css("border","1px solid #0f0");
                                                                                            //alert( "Data Loaded: " + data );
                                                                                            });;
    }
    </script>
    <?
}
require "footer.php";
exit(0);

?>