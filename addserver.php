<?
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);

if (isset($_POST[name])) {
    $id = $_POST[id];
    $name = $_POST[name];
    $username = $_POST[username];
    $password = $_POST[password];
    $address = $_POST[address];
    $sql = "insert  into servers (address,username,password,name) values".
        "('$address', '$username', '$password','$name')";
    $result = mysql_query($sql, $link) or die (mysql_error());;
    header("Location: servers.php");
    exit;
}
//require "header_campaign.php";
$pagenum = "7";
require "header.php";
require "header_server.php";

?>
<form class="form-horizontal" action="addserver.php" method="post">
    <div class="form-group">
        <label for="name" class="col-sm-4 control-label">Asterisk server name</label>

        <div class="col-sm-8">
            <input type="text" class="form-control" id="name" name="name"value="<? echo $row[name]; ?>" >
        </div>
    </div>
    <div class="form-group">
        <label for="address" class="col-sm-4 control-label">Asterisk server address</label>

        <div class="col-sm-8">
            <input type="text" class="form-control" id="address" name="address"value="<? echo $row[address]; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="username" class="col-sm-4 control-label">Asterisk server username</label>

        <div class="col-sm-8">
            <input type="text" class="form-control" id="username" name="username"value="<? echo $row[username]; ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="password" class="col-sm-4 control-label">Asterisk server password</label>

        <div class="col-sm-8">
            <input type="password" class="form-control" id="password" name="password"value="<? echo $row[password]; ?>">
        </div>
    </div>

    <div class="col-sm-12">
    <input class="btn btn-primary" type="submit" value="Add server">
    </div>
</form>

<?
require "footer.php";
?>
