<?
require "header.php";
$db_host=$config_values['CDR_HOST'];
$db_user=$config_values['CDR_USER'];
$db_pass=$config_values['CDR_PASS'];
$cdrlink = mysql_connect($db_host, $db_user, $db_pass) OR die(mysql_error());
mysql_select_db($config_values['CDR_DB'], $cdrlink);
$sql = "SELECT count(*) from ".$config_values['CDR_TABLE']." WHERE dcontext!='default' and dcontext!='load-simulation' and dcontext!='staff' and dcontext!='ls3' and userfield!='' ";
$result = mysql_query($sql,$cdrlink);
$count = mysql_result($result,0,0);
//echo $count." Total Records";
$page = $_GET[page];
if ($_GET[page]>0) {
    $start = $_GET[page]*100;
} else {
    $start = 0;
}
echo '<a href="viewcdr.php?page=0"><img src="/images/resultset_first.png" border="0"></a> ';
if ($page > 0) {
    echo '<a href="viewcdr.php?page='.($page-1).'"><img src="/images/resultset_previous.png" border="0"></a> ';
}
if ($page > 5) {
    $pagex= $page-4;
} else {
    $pagex = 0;
}
for ($i = $pagex;$i<($count/100);$i++) {
    if ($i < $page + 20) {
        if ($page == $i) {
            echo "<b>$i</b> ";
        } else {
            echo '<a href="viewcdr.php?page='.$i.'">'.$i.'</a> ';
        }
    }
}

echo '<a href="viewcdr.php?page='.($page+1).'"><img src="/images/resultset_next.png" border="0"></a> ';
echo '<a href="viewcdr.php?page='.round($count/100).'"><img src="/images/resultset_last.png" border="0"></a> ';
//$sql = "SELECT * from ".$config_values['CDR_TABLE']." order by calldate DESC LIMIT $start,100";
$sql = "SELECT * from ".$config_values['CDR_TABLE']." WHERE dcontext!='default' and dcontext!='load-simulation' and dcontext!='staff' and dcontext!='ls3' and userfield!='' order by calldate DESC LIMIT $start,100";

$result = mysql_query($sql,$cdrlink);
$i = 0;
$titletd = "<td bgcolor=\"#000000\"><font color=\"#CCCCFF\"><b>&nbsp;&nbsp;";
$titletdc = "&nbsp;&nbsp;</td>";
echo "<center><table border=0>";

echo "<tr>".$titletd."CallDate".$titletdc."".$titletd./*"DContext".$titletdc."".$titletd.*/"CLID".$titletdc."".$titletd."Duration".$titletdc."".$titletd."Billsec".$titletdc."".$titletd."Disposition".$titletdc."".$titletd."AccountCode".$titletdc."".$titletd."Phone Number".$titletdc."".$titletd."Result".$titletdc."</tr>";
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
        $dst[$i] = "engaged";
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
    if ($display) {
    echo     "<tr>";
    echo $td.$calldate[$i]."</td>$td"/*.$dcontext[$i]."</td>$td"*/.
    $clid[$i]."</td>$td".
    /*$lastapp[$i]."</td>$td".$lastdata[$i]."</td>$td".*/$duration[$i]."</td>$td".$billsec[$i]."</td>$td".
    $disposition[$i]."</td>$td".$accountcode[$i]."</td>$td".$userfield[$i]."</b></td>$td<b>".$dst[$i]."</b></td>";
    echo "</tr>";
    }
    $i++;
}
echo "</table>";
require "footer.php";
?>
