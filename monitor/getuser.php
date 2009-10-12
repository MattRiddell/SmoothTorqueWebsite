<?
//echo "1:".rand(0,1000);
//84.45.112.70
//$sql = "SELECT value FROM config WHERE parameter='mysql_queue'";
$sql = "SHOW INNODB STATUS";
$hostnames[1] = "localhost";
$usernames[1] = "root";
$passwords[1] = "";





if (isset($hostnames[$_GET['q']])) {
    $link = mysql_connect($hostnames[$_GET['q']], $usernames[$_GET['q']], $passwords[$_GET['q']], true);
    if ($link) {
        //@mysql_select_db("SineDialer");
        $result = mysql_query($sql) or die(mysql_error());
        if ($result) {
            //echo "<pre>";
            while ($row = mysql_fetch_assoc($result)) {
                //echo "<hr />";
            
                //print_r($row);
                $status = $row[Status];
                //echo $status."<hr />";
                $lines = split("\n",$status);
                $found = false;
                foreach ($lines as $line) {
                    if (substr($line,0,14) == "ROW OPERATIONS") {
                        $found = true;
                    }
                    if ($found && strpos($line,"inserts/s") !== false && strpos($line,"updates/s") !== false && strpos($line,"deletes/s") !== false && strpos($line,"reads/s") !== false ) {
                        //echo "Line: $line<br />";
                        $split = split(",",$line);
                        $insert = 0;
                            $read = 0;
                            $delete = 0;
                            $update = 0;
                        foreach ($split as $entry) {
                            $trimmed = trim($entry);
                            $split_again = split(" ",$trimmed);
                            //print_r($split_again);
                            
                            switch ($split_again[1]) {
                                case "inserts/s":
                                    $insert = round($split_again[0]);
                                    break;
                                case "updates/s":
                                    $update = round($split_again[0]);
                                    break;
                                case "deletes/s":
                                    $delete = round($split_again[0]);
                                    break;
                                case "reads/s":
                                    $read = round($split_again[0]);
                                    break;

                            }
                            
                        }
                        $result = mysql_query("SELECT value FROM SineDialer.config WHERE parameter = 'mysql_queue'") or die(mysql_error());
                        //$read/=100;
                        //$queue*=10;
                        echo $_GET['q'].":".$read.":".$update.":".$delete.":".$insert.":".mysql_result($result,0,0);

                        exit(0);
                        //print_r($split);
                    }
                }
            }
            /*
            if (@mysql_result($result,0,0) > 1000) {
                echo $_GET['q'].":1000";
                exit(0);
            }
            echo $_GET['q'].":".@mysql_result($result,0,0);
            exit(0);
                         */
        }
    }
} 
echo $_GET[q].":";

?>
