

<?
require "header.php";


$passwordHash = sha1($_POST['password']);

$sql = 'INSERT INTO customer (username,password) VALUES (\''.$_POST[user].'\',\''.$passwordHash.'\')';
//$result = $db->query($sql, array($_POST['username'], $passwordHash));
echo $sql;
//$query="SELECT password
$result=mysql_query($sql, $link) or die (mysql_error());;
//$total=0;
//$total=mysql_result($result,0,'Count(*)');

?>
You enterred: <?echo $_POST[user];?>
You enterred: <?echo $_POST[pass];?>
<?
require "footer.php";
?>


