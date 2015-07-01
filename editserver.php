<?
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);

if (isset($_POST[name])){
    $id=$_POST[id];
    $name=$_POST[name];
    $username=$_POST[username];
    $password=$_POST[password];
    $address=$_POST[address];
    if ($_POST[password] == 'xxxxxxxxxxxx') {
	    $sql="update servers  set address='$address',username='$username',name='$name' where id=$id";
    } else {
	    $sql="update servers  set address='$address',username='$username',password='$password',name='$name' where id=$id";
    }
    $result=mysql_query($sql, $link) or die (mysql_error());;
    header("Location: servers.php");
    exit;
}
//require "header_campaign.php";
$pagenum="7";
require "header.php";
require "header_server.php";

$sql = "select * from servers where id=".$_GET[id];
$result=mysql_query($sql,$link);
$row=mysql_fetch_assoc($result);
?>

<form class="form-horizontal" action="addserver.php" method="post">
    <input type="hidden" name="id" value="<?echo $_get[id];?>">
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
            <input type="password" class="form-control" id="password" name="password"value="xxxxxxxxxxxx">
        </div>
    </div>

    <div class="col-sm-12">
        <input class="btn btn-primary" type="submit" value="Save server">
    </div>
</form>


<?
require "footer.php";
?>
