<?
function print_pre($text) {
    echo "<pre>";
    print_r($text);
    echo "</pre>";
}

require "header.php";
?>
<link type="text/css" href="css/cupertino/jquery-ui-1.8.20.custom.css" rel="stylesheet" />
<script src="js/jquery-1.7.2.min.js" type="text/javascript"></script>
<script src="js/jquery-ui-1.8.20.custom.min.js" type="text/javascript"></script>
<div id="progress_dialog" style="display: none"><center>
<span id="status">Running timezone script</span>
<div id="progress"></div>
</center>
</div>
<script>
$("#progress_dialog").dialog({modal: true});
$("#progress").progressbar({value: 0});
</script>
<?
/* Get all of the timezone prefixes and times */
$result = mysql_query("select time_zones.start, time_zones.end, prefix from time_zones, timezone_prefixes where timezone_prefixes.timezone = time_zones.id");
$count = mysql_num_rows($result);
$x = 0;
while ($row = mysql_fetch_assoc($result)) {
    $x++;
    if ($x % 100) {
        ?>
        <script>
    $("#progress").progressbar({value: <?=round($x/$count*100)?>});
    $("#status").text("Updating prefix <?=$row['prefix']?>");
        </script>
        <?
}
    $sql = "UPDATE number set start_time = '".$row['start']."', end_time = '".$row['end']."' WHERE phonenumber like '".$row['prefix']."%'";
    $result2 = mysql_query($sql);
    //echo $sql."<br />";
    flush();
}
?>