<?if (isset($_POST[id])) {
$pagenum=1;
require "header.php";
require_once "PHPTelnet.php";

$telnet = new PHPTelnet();

// if the first argument to Connect is blank,
// PHPTelnet will connect to the local host via 127.0.0.1
$telnet = new PHPTelnet();
$result = $telnet->Connect();
//echo "CONNECTION REQUEST: ".$result."<BR>";
$telnet->DoCommand('deletec', $result);
//echo "XX".$result."<BR>";
$telnet->DoCommand($_POST[id], $result);
echo "".$result."";

$telnet->Disconnect();
sleep(1);
} else {
    if (isset($_GET[id])){
        require_once "sql.php";
        $SMDB2=new SmDB();
        $sql="DELETE FROM campaign WHERE id=".$_GET[id];
        $SMDB2->executeUpdate($sql);
    }
}
?>
<meta http-equiv="refresh" content="0;url=campaigns.php">
