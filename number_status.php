<?
include "admin/db_config.php";
mysql_select_db("SineDialer", $link);
    if (isset($_GET[campaignid])){
    $_POST[campaignid]=$_GET[campaignid];
}
// Ok, so we have a campaign id to use numbers from
// How many numbers has it got?

if (isset($_GET[number])) {
    $dialled = $_GET[number];
    // XXX - THIS NEEDS SANITATION
    $sql = "SELECT * FROM number WHERE phonenumber = '".$_GET[number]."' AND campaignid=$_GET[campaignid]";
    //echo $sql."x";
    $result = mysql_query($sql);
    if (mysql_num_rows($result) > 0) {
    $status = mysql_result($result,0,"status");
    if ($status == "hungup") {
        $bgcolor = "#ffcccc";
    } else if ($status == "new") {
        $bgcolor = "#cccccc";
    } else if ($status == "dialing") {
        $bgcolor = "#ccffff";
    } else if ($status == "dialed") {
        $bgcolor = "#ffffcc";
    } else if ($status == "pressed1") {
        $bgcolor = "#ccffcc";
    } else {
        $bgcolor = "#ccccff";
    }
    echo '<table border="0" cellpadding="20"><tr><td bgcolor = "'.$bgcolor.'">';
    echo "Status of $dialled: <b>$status</b><br />";
    } else {
        echo "<b>Please click the button below to dial a number</b>";
    }
}
?>
</td></tr></table>
