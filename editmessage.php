<?
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);

$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result = mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid = mysql_result($result, 0, 'campaigngroupid');

if (isset($_POST[name])) {
    $_POST = array_map(mysql_real_escape_string, $_POST);
    $id = $_POST[id];
    $name = $_POST[name];
    $description = $_POST[description];
    $filename = $_POST[filename];
    $length = $_POST[length];
    $sql = "update campaignmessage set filename='$filename',name='$name',description='$description', length='$length'"." where id=$id";
    $result = mysql_query($sql, $link) or die (mysql_error());;
    header("Location: messages.php");
    exit;
}
$pagenum = "2";
require "header.php";
require "header_message.php";
$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result = mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid = mysql_result($result, 0, 'campaigngroupid');

$sql = "SELECT * from campaignmessage where id=$_GET[id]";
$result = mysql_query($sql, $link) or die (mysql_error());;
$row = mysql_fetch_assoc($result);


?>

<FORM ACTION="editmessage.php" METHOD="POST">
    <table class="table table-striped" align="center" border="0" cellpadding="0" cellspacing="2">
        <?
        ?>
        <TR>
            <td>Message Name</td>
            <td>
                <INPUT TYPE="HIDDEN" NAME="id" VALUE="<? echo $_GET[id]; ?>">
                <INPUT TYPE="TEXT" NAME="name" VALUE="<? echo $row[name]; ?>" size="60">
            </td>
        </TR>

        <TR>
            <td>Message Description</td>
            <td>
                <INPUT TYPE="TEXT" NAME="description" VALUE="<? echo $row[description]; ?>" size="60">
                <INPUT TYPE="HIDDEN" NAME="filename" VALUE="<? echo $row[filename]; ?>" size="60">
            </td>
        </TR>


        <TR>
            <td COLSPAN=2 ALIGN="RIGHT">
                <INPUT class="btn btn-primary" TYPE="SUBMIT" VALUE="Save Message">
            </td>
        </TR>

        <?
        $command = $config_values['SOX']." uploads/".str_replace("sln", "wav", str_replace(" ", "\ ", $row[filename])).' -e stat';
        //$command = "sox";
        //echo $command;
        $x = exec($command." 2>&1", $y);
        $z = explode(" ", $x);
        //echo nl2br($y);
        //print_r($y);
        foreach ($y as $line) {
            if (substr($line, 0, 6) == "Length") {
                $length = trim(substr(strstr($line, ": "), 2));
                //echo $length."<br />";
            }
        }
        ?>
        <INPUT TYPE="HIDDEN" NAME="length" VALUE="<? echo $length; ?>" size="60">

    </TABLE>
</FORM>
<?
require "footer.php";
?>
