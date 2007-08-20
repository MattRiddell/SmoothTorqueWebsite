<?
require_once "PHPTelnet.php";


class SmDB {
    function executeQuery($sql) {
        $telnet = new PHPTelnet();
        $result = $telnet->Connect();
        $telnet->DoCommand('sqlq', $result);
        flush();
        $telnet->DoCommand($sql, $result);
        $recordnum=0;
        $index=0;
        while (substr(trim($result),0,2)!="OK") {
            $index++;
            $pieces = explode("|",$result);
            if (trim($pieces[0])=="==========") {
                $recordnum++;
                $index=0;
            } else {
                $row[$recordnum][trim($pieces[0])]=trim($pieces[1]);
            }
            flush();
            $telnet->DoCommand("OK", $result);
        }
        $telnet->Disconnect();

        //print_r($row[0]);

        return $row;
    }
    function executeUpdate($sql) {
        $telnet = new PHPTelnet();
        $result = $telnet->Connect();
        $telnet->DoCommand('sql', $result);
        $telnet->DoCommand($sql, $result);
        $telnet->Disconnect();
    }
    function isLoaded(){
        return true;
    }
}
?>
