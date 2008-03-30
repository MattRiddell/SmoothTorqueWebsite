<?
$config_file = "/stweb.conf";
$fp = fopen($config_file, "r");
while (!feof($fp)) {
  $line = trim(fgets($fp));
  if ($line && substr($line,0,1)!=$comment) {
    $pieces = explode("=", $line);
    $option = trim($pieces[0]);
    $value = trim($pieces[1]);
    $config_values[$option] = $value;
  }
}
fclose($fp);

include "admin/db_config.php";
mysql_select_db("SineDialer", $link);
$currency = $config_values['CURRENCY_SYMBOL'];
$db_host=$config_values['CDR_HOST'];
$db_user=$config_values['CDR_USER'];
$db_pass=$config_values['CDR_PASS'];
$sql = "Select accountcode from billing";
$result_accounts = mysql_query($sql, $link);
while ($accounts = mysql_fetch_assoc($result_accounts)) {

$accountcode_in = $accounts[accountcode];
$cdrlink = mysql_connect($db_host, $db_user, $db_pass) OR die(mysql_error());
mysql_select_db($config_values['CDR_DB'], $cdrlink);
$sql = "SELECT count(*) from ".$config_values['CDR_TABLE']." WHERE dcontext!='default' and dcontext!='load-simulation' and dcontext!='staff' and dcontext!='ls3' and userfield!='' and accountcode='$accountcode_in' and userfield2!='1'";
$result = mysql_query($sql,$cdrlink);
$count = mysql_result($result,0,0);
//echo $count." Total Records";

echo '<a href="viewcdr.php?page='.($page+1).'&accountcode='.$accountcode_in.'"><img src="/images/resultset_next.png" border="0"></a> ';
echo '<a href="viewcdr.php?page='.round($count/100).'&accountcode='.$accountcode_in.'"><img src="/images/resultset_last.png" border="0"></a> ';
//$sql = "SELECT * from ".$config_values['CDR_TABLE']." order by calldate DESC LIMIT $start,100";
$sql = "SELECT * from ".$config_values['CDR_TABLE']." WHERE dcontext!='default' and dcontext!='load-simulation' and dcontext!='staff' and dcontext!='ls3' and userfield!='' and accountcode='$accountcode_in' and userfield2!='1' order by calldate DESC limit 1000";

$result = mysql_query($sql,$cdrlink);
$i = 0;
$titletd = "<td bgcolor=\"#000000\"><font color=\"#CCCCFF\"><b>&nbsp;&nbsp;";
$titletdc = "&nbsp;&nbsp;</td>";
echo "<center><table border=0>";

echo "<tr>".$titletd."Call Date/Time".$titletdc."".$titletd.
/*"DContext".$titletdc."".$titletd."Caller ID".$titletdc."".$titletd.*/"Duration".
$titletdc."".$titletd."Billsec".$titletdc."".$titletd."Disposition".$titletdc."".$titletd
."AccountCode".$titletdc."".$titletd."Phone Number".$titletdc."".$titletd.
"Result".$titletdc.$titletd."Per Minute".$titletdc.$titletd.
"Lead".$titletdc.$titletd."Connected".$titletdc.$titletd."Press1".$titletdc.$titletd.
"Total".$titletdc.$titletd."Charged".$titletdc.
"</tr>";
while ($row = mysql_fetch_assoc($result)) {
    $calldate[$i] = $row[calldate];
    $dcontext[$i] = $row[dcontext];
    $dst[$i] = $row[dst];
    $src[$i] = $row[src];
    $clid[$i] = $row[clid];
    $channel[$i] = $row[channel];
    $dstchannel[$i] = $row[dstchannel];
    $lastapp[$i] = $row[lastapp];
    $lastdata[$i] = $row[lastdata];
    $duration[$i] = $row[duration];
    $billsec[$i] = $row[billsec];
    $disposition[$i] = $row[disposition];
    $amaflags[$i] = $row[amaflags];
    $accountcode[$i] = $row[accountcode];
    $userfield[$i] = $row[userfield];
    $userfield2[$i] = $row[userfield2];
    if ($userfield2[$i] != 1) {
        $userfield2[$i] = 0;
        $paid[$i] = '<td bgcolor="#FFEEEE"><img src="/images/cross.png" border="0" align="center">';
    } else {
        $paid[$i] = '<td bgcolor="#EEFFEE"><img src="/images/tick.png" border="0" align="center">';
    }
    $display = true;
    if ($disposition[$i] == "ANSWERED") {
        $td = "<td bgcolor=\"#CCffCC\">";
    } else if ($disposition[$i] == "NO ANSWER") {
        $td = "<td bgcolor=\"#DDDDDD\">";
    } else if ($disposition[$i] == "FAILED") {
        $td = "<td bgcolor=\"#999999\">";
        $display = false;
    } else if ($disposition[$i] == "BUSY") {
        $td = "<td bgcolor=\"#99ffff\">";
    } else {
        $td = "<td>";
    }
    if ($dst[$i] != "s") {
        /* Failed */
        $td = "<td bgcolor=\"#ff9999\">";
    }
    if ($dst[$i] == "timeout") {
        $td = "<td bgcolor=\"#DDDDDD\">";
        $dst[$i] = "no answer";
    }
    if ($dst[$i] == "busy") {
        $td = "<td bgcolor=\"#FF99FF\">";
        $dst[$i] = "busy";
    }
    if ($dst[$i] == "1") {
        $td = "<td bgcolor=\"#9999ff\">";
        $dst[$i] = "pressed 1";
    }

    if ($dst[$i] == "s") {
        if ($disposition[$i]=="FAILED"){
            $dst[$i] = "";
        } else {
            if ($dcontext[$i] == "do-amd") {
                $dst[$i] = "answer machine";
            } else if ($dcontext[$i] == "do-live") {
                $dst[$i] = "human";
            } /*else {
                $dst[$i] = "answered";
            }*/
        }

    }
    //$billtype[$i] = "Per Minute";
    if (!($customerid[$accountcode[$i]]>0)) {
        $sqlx = "SELECT * from billing where accountcode = '".$accountcode[$i]."'";
        //echo $sqlx;
        $resultx = mysql_query($sqlx,$link);
        $priceperminute[$accountcode[$i]] = mysql_result($resultx, 0, 'priceperminute');
        //echo mysql_result($resultx, 0, 'priceperminute');
        $customerid[$accountcode[$i]] = mysql_result($resultx, 0, 'customerid');
        $firstperiod[$accountcode[$i]] = mysql_result($resultx, 0, 'firstperiod');
        $increment[$accountcode[$i]] = mysql_result($resultx, 0, 'increment');
        $firstperiod[$accountcode[$i]] = mysql_result($resultx, 0, 'firstperiod');
        $credit[$accountcode[$i]] = mysql_result($resultx, 0, 'credit');
        $pricepercall[$accountcode[$i]] = mysql_result($resultx, 0, 'pricepercall');
        $priceperconnectedcall[$accountcode[$i]] = mysql_result($resultx, 0, 'priceperconnectedcall');
        $priceperpress1[$accountcode[$i]] = mysql_result($resultx, 0, 'priceperpress1');
    }
    //$cost[$i] = round((1/60)*$billsec[$i],2);
    $cost[$i] = 0;
    $costperpress1[$i] = 0;
    $costpercall[$i] = 0;
    $costperminute[$i] = 0;
    $costperconnect[$i] = 0;

    if ($pricepercall[$accountcode[$i]] > 0) {
        if ($display) {
            $costpercall[$i] = round($pricepercall[$accountcode[$i]],2);
            $cost[$i] += $costpercall[$i];
        }
    }
    if ($disposition[$i] == "ANSWERED") {
        if ($billsec[$i] > $firstperiod[$accountcode[$i]]) {
            if ($increment[$accountcode[$i]] == 1) {
                $costperminute[$i] = round(($priceperminute[$accountcode[$i]]/60) * $billsec[$i],2);
            } else {
                /*30
                27
                if the increment is 30 seconds and the call is 73 seconds they should be
                charged for 73/30 = 2.4 blocks - round up to 3 = 3*30 = 90*/
                //echo "Billsec: $billsec[$i] Increment: $increment[$accountcode[$i]]";
                $blocks = ceil($billsec[$i]/$increment[$accountcode[$i]]);
                //echo "Blocks: $blocks";
                $newsecs = $blocks * $increment[$accountcode[$i]];
                //echo "Newsecs: $newsecs";
                $costperminute[$i] = round(($priceperminute[$accountcode[$i]]/60) * $newsecs,2);
                //echo "costperminute: $costperminute[$i]";
            }
            $cost[$i]+=$costperminute[$i];
        } else {
            $costperminute[$i] = round(($priceperminute[$accountcode[$i]]/60) * $firstperiod[$accountcode[$i]],2);
            $cost[$i]+=$costperminute[$i];
        }
        $costperconnect[$i] = round($priceperconnectedcall[$accountcode[$i]],2);
        $cost[$i]+=$costperconnect[$i];
        if ($dst[$i] == "pressed 1") {
            $costperpress1[$i] = round($priceperpress1[$accountcode[$i]],2);
            $cost[$i] += $costperpress1[$i];
        }
    }
    if ($display) {
        echo     "<tr>";
        echo $td.$calldate[$i]."</td>$td"/*.$dcontext[$i]."</td>$td".
        $clid[$i]."</td>$td"*/.
        /*$lastapp[$i]."</td>$td".$lastdata[$i]."</td>$td".*/$duration[$i]."</td>$td".$billsec[$i]."</td>$td".
        $disposition[$i]."</td>$td".$accountcode[$i]."</td>$td".$userfield[$i]."</b></td>$td<b>".$dst[$i]."</b></td>";
        echo $td.$currency.$costperminute[$i]."</td>".$td.$currency.$costpercall[$i]."</td>".
        $td.$currency.$costperconnect[$i]."</td>".$td.$currency.$costperpress1[$i]."</td>".$td.$currency.$cost[$i]."</td>".$paid[$i]."</td>";
        $totalcost[$accountcode[$i]]+=$cost[$i];
        echo "</tr>";
        $sql = "update cdr set userfield2 = '1' where calldate = '$calldate[$i]' and duration = '$duration[$i]' and accountcode = '$accountcode[$i]' and userfield = '$userfield[$i]'";
        echo $sql."<br />";
    }
    $i++;
}
echo "</table>";
echo "About to print out totals for $accountcode_in<br />";
//foreach ($totalcost as $key => $value) {
//    echo "Key: $key Value: $value";
//}
echo $totalcost[$accountcode_in];
$sqlx = "select credit,creditlimit from billing where accountcode = '$accountcode_in'";
$result_credit = mysql_result($sqlx,$link)  or die (mysql_error());
if (mysql_num_rows($result_credit) > 0) {
    $credit = mysql_result($result_credit,0,'credit') or die (mysql_error());
    $credit_limit = mysql_result($result_credit,0,'creditlimit');
    echo "Credit was $credit and will now be ".($credit - $totalcost[$accountcode_in])."<br />";
}
echo "<hr>";
}
require "footer.php";
?>
