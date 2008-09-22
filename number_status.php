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
    $screenpop = false;
    if ($status == "hungup") {
        $bgcolor = "#ffcccc";
        $screenpop=true;
    } else if ($status == "new") {
        $bgcolor = "#cccccc";
    } else if ($status == "dialing") {
        $bgcolor = "#ccffff";
    } else if ($status == "dialed") {
        $bgcolor = "#ffffcc";
    } else if ($status == "pressed1") {
        $bgcolor = "#ccffcc";
        $screenpop=true;
    } else if ($status == "busy") {
        $bgcolor = "#ffcccc";
        $screenpop=true;
    } else if ($status == "indnc") {
        $bgcolor = "#ffcccc";
        $screenpop=false;
    } else if ($status == "timeout") {
        $bgcolor = "#ffcccc";
        $screenpop=true;
    } else if ($status == "calldropped") {
        $bgcolor = "#ffcccc";
        $screenpop=true;
    } else {
        $bgcolor = "#ccccff";
    }
    echo '<table border="0" cellpadding="20"><tr><td bgcolor = "'.$bgcolor.'">';
    echo "Status of $dialled: <b>$status</b><br />";
    } else {
        echo "<b>Please click the button above to dial a number</b>";
    }
    if ($screenpop) {
    /*
        echo "bla";
        ?>
<SCRIPT LANGUAGE="JavaScript">
day = new Date();
id = day.getTime();
eval("page" + id + " = window.open('http://www.google.com/q=<?=$dialled?>', '" + id + "', 'toolbar=1,scrollbars=1,location=1,statusbar=1,menubar=1,resizable=1,width=800,height=600,left = 240,top = 100');");
        </SCRIPT>
        <? */

    }
}
?>
</td></tr></table>
