<?
require "header.php";
$result = mysql_query("SELECT * FROM files WHERE filename like 'question_%' limit 10") or die(mysql_error());
if (mysql_num_rows($result) == 0) {
    // No rows
} else {
    while ($row = mysql_fetch_assoc($result)) {
//        print_pre($row);
        ?>
        <audio controls>
        <source src="recordings/<?=$row['filename']?>" type="audio/wav">
        Your browser does not support the audio element.
        </audio>
        <?
    }
}
require "footer.php";
exit(0);

?>