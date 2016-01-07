<?
require "header.php";
if (!isset($_GET[startdate])) {

    ?>
    <div class="jumbotron">
        <h3>Call Detail Records</h3>
        <p>
            You have a couple of options for viewing your call detail records. You can either view today's records or you can view all records over a date range.
        </p>
        <p>
            <?
            if (isset($_GET['all'])) {
                ?>
                <a href="viewcdr.php?startdate=<?= @Date("Y-m-d") ?>&enddate=<?= @Date("Y-m-d") ?>&all=1" class="btn btn-primary">View records for today</a>
                <br/>
                <?
            } else {

                ?>
                <? if (isset($_GET['accountcode'])) { ?>

                    <a href="viewcdr.php?startdate=<?= @Date("Y-m-d") ?>&enddate=<?= @Date("Y-m-d") ?>&accountcode=<?=$_GET['accountcode']?>" class="btn btn-primary">View records for today</a>
                <? } else { ?>
                    <a href="viewcdr.php?startdate=<?= @Date("Y-m-d") ?>&enddate=<?= @Date("Y-m-d") ?>" class="btn btn-primary">View records for today</a>
                <?
                } ?>
                <br/>
                <?
            }
            ?>
        </p>
        <p>
            Or select the dates you would like to view:</p>
        <form action="viewcdr.php">
            From: <input name="startdate">
            <input type=button value="Choose Start Date" onclick="displayDatePicker('startdate', false, 'ymd', '-');"><br/><br/>
            To: <input name="enddate">
            <input type=button value="Choose End Date" onclick="displayDatePicker('enddate', false, 'ymd', '-');"><br/><br/>
            <?
            if (isset($_GET['all'])) {
                ?><input type="hidden" name="all" value="1"><?
            }
            ?>
            <? if (isset($_GET['accountcode'])) { ?>
                <input type="hidden" name="accountcode" value="<? echo $_GET[accountcode]; ?>">
            <? } ?>
            <input class="btn btn-primary" type="submit" value="Search">
        </form>
        </p>
    </div>
    <?

} else {
    $startdate = $_GET[startdate];
    $enddate = $_GET[enddate];

    /*================= Log Access ======================================*/
    $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_COOKIE[user]', 'Viewed the CDR')";
    $result = mysql_query($sql, $link);
    /*================= Log Access ======================================*/

    $currency = $config_values['CURRENCY_SYMBOL']." ";
    $db_host = $config_values['CDR_HOST'];
    $db_user = $config_values['CDR_USER'];
    $db_pass = $config_values['CDR_PASS'];
    if ($level == sha1("level100")) {
        if (isset($_REQUEST['accountcode'])) {
            $accountcode_in = $_GET[accountcode];
        } else {
            $accountcode_in = "stl-".$_COOKIE[user];
        }
    } else {
        $accountcode_in = "stl-".$_COOKIE[user];
    }
    $cdrlink = mysql_connect($db_host, $db_user, $db_pass) OR die(mysql_error());
    mysql_select_db($config_values['CDR_DB'], $cdrlink);
    if (!isset($_GET['count'])) {
        if (isset($_GET['all']) && $_GET['all'] == "1") {
            $sql = "SELECT count(*) from ".$config_values['CDR_TABLE']." WHERE calldate between '".$_GET['startdate']." 00:00:00' and '".$_GET['enddate']." 23:59:59' and dcontext!='default' and dcontext!='load-simulation' and dcontext!='staff' and dcontext!='ls3' and userfield!=''";
        } else {
            $sql = "SELECT count(*) from ".$config_values['CDR_TABLE']." WHERE calldate between '".$_GET['startdate']." 00:00:00' and '".$_GET['enddate']." 23:59:59' and dcontext!='default' and dcontext!='load-simulation' and dcontext!='staff' and dcontext!='ls3' and userfield!='' and accountcode='$accountcode_in'";
            $_GET['all'] = 0;
        }

        $result = mysql_query($sql, $cdrlink);
        $count = mysql_result($result, 0, 0);
    } else {
        $count = $_GET['count'];
    }
    //echo $count." Total Records";
    $page = $_GET[page];
    if ($_GET[page] > 0) {
        $start = $_GET[page] * 20;
    } else {
        $start = 0;
    }
    echo '<ul class="pagination">';

    echo '<li><a href="viewcdr.php?startdate='.$startdate.'&enddate='.$enddate.'&page=0&accountcode='.$accountcode_in.'&all='.$_GET['all'].'&count='.$count.'"><i class="glyphicon glyphicon-step-backward"></i>  First</a></li> ';
    if ($page > 0) {
        echo '<li>';
    } else {
        echo '<li class="disabled">';
    }
    echo '<a href="viewcdr.php?startdate='.$startdate.'&enddate='.$enddate.'&page='.($page - 1).'&accountcode='.$accountcode_in.'&all='.$_GET['all'].'&count='.$count.'" ><i class="glyphicon glyphicon-chevron-left"></i> Back</a></li> ';


    if ($page > 5) {
        $pagex = $page - 4;
    } else {
        $pagex = 0;
    }
    for ($i = $pagex; $i < ($count / 15); $i++) {
        if ($i < $page + 15) {
            if ($page == $i) {
                echo '<li class="active"><a href="#">'.$i.'</a></li> ';
            } else {
                echo '<li><a href="viewcdr.php?startdate='.$startdate.'&enddate='.$enddate.'&page='.$i.'&accountcode='.$accountcode_in.'&all='.$_GET['all'].'&count='.$count.'">'.$i.'</a></li> ';
            }
        }
    }

    echo '<li><a href="viewcdr.php?startdate='.$startdate.'&enddate='.$enddate.'&page='.($page + 1).'&accountcode='.$accountcode_in.'&all='.$_GET['all'].'&count='.$count.'" ><i class="glyphicon glyphicon-chevron-right"></i> Next</a></li> ';
    echo '<li><a href="viewcdr.php?startdate='.$startdate.'&enddate='.$enddate.'&page='.round($count / 15).'&accountcode='.$accountcode_in.'&all='.$_GET['all'].'&count='.$count.'" ><i class="glyphicon glyphicon-step-forward"></i> Last</a></li> ';
    echo '</ul>&nbsp;';
//$sql = "SELECT * from ".$config_values['CDR_TABLE']." order by calldate DESC LIMIT $start,20";
    if (isset($_GET['all']) && $_GET['all'] == "1") {
        $sql = "SELECT * from ".$config_values['CDR_TABLE']." WHERE calldate between '".$_GET['startdate']." 00:00:00' and '".$_GET['enddate']." 23:59:59' and dcontext!='default' and dcontext!='load-simulation' and dcontext!='staff' and dcontext!='ls3' and userfield!='' order by calldate DESC LIMIT $start,20";
    } else {
        $sql = "SELECT * from ".$config_values['CDR_TABLE']." WHERE calldate between '".$_GET['startdate']." 00:00:00' and '".$_GET['enddate']." 23:59:59' and dcontext!='default' and dcontext!='load-simulation' and dcontext!='staff' and dcontext!='ls3' and userfield!='' and accountcode='$accountcode_in' order by calldate DESC LIMIT $start,20";
    }
    //echo $sql;
    $result = mysql_query($sql, $cdrlink) or die(mysql_error());
    $i = 0;
    $titletd = "<th>";
    $titletdc = "</th>";
    echo '<div class="table-responsive"><table class="table table-striped">';
    if (isset($_GET['all']) && $_GET['all'] == "1") {
        echo "<tr>".$titletd."Call Date/Time".$titletdc."".$titletd.
            /*"DContext".$titletdc."".$titletd."Caller ID".$titletdc."".$titletd.*/
            "Duration".
            $titletdc."".$titletd."Billsec".$titletdc."".$titletd."Disposition".$titletdc."";

        if ($config_values['CDR_USE_STATE'] == "YES") {
            echo $titletd."State".$titletdc;
        }

        if ($config_values['disable_all_types'] == "YES") {
            echo $titletd."Phone Number".$titletdc."".$titletd.
                "Result".$titletdc."</tr>";
        } else {
            echo $titletd."AccountCode".$titletdc."".$titletd."Phone Number".$titletdc."".$titletd.
                "Result".$titletdc."</tr>";
        }

    } else {
        echo "<tr>".$titletd."Call Date/Time".$titletdc."".$titletd.
            /*"DContext".$titletdc."".$titletd."Caller ID".$titletdc."".$titletd.*/
            "Duration".
            $titletdc."".$titletd."Billsec".$titletdc."".$titletd."Disposition".$titletdc."";

        if ($config_values['CDR_USE_STATE'] == "YES") {
            echo $titletd."State".$titletdc;
        }

        if ($config_values['disable_all_types'] == "YES") {
            echo $titletd."Phone Number".$titletdc."".$titletd.
                "Result".$titletdc.$titletd.$config_values['PER_MINUTE'].$titletdc.$titletd.
                "Total".$titletdc.$titletd."Charged".$titletdc.
                "</tr>";
        } else {
            echo $titletd."AccountCode".$titletdc."".$titletd."Phone Number".$titletdc."".$titletd.
                "Result".$titletdc.$titletd.$config_values['PER_MINUTE'].$titletdc.$titletd.
                "Lead".$titletdc.$titletd."Connected".$titletdc.$titletd."Press1".$titletdc.$titletd.
                "Total".$titletdc.$titletd."Charged".$titletdc.
                "</tr>";
        }
    }
    while ($row = mysql_fetch_assoc($result)) {
        //print_pre($row);
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
        $pos = strpos($userfield[$i], '-');
        if ($pos === FALSE) {
            // This is not a split
            $phonenumber[$i] = $userfield[$i];
            //echo "no split";
        } else {
            $campaignid = substr($userfield[$i], $pos + 1);
            $phonenumber[$i] = substr($userfield[$i], 0, $pos);
            //echo "x".$campaignid." - $cost[$i] x ";
        }

        if ($config_values['CDR_USE_STATE'] == "YES") {
            $result_state = mysql_query("SELECT state FROM SineDialer.states WHERE prefix = ".sanitize(substr($phonenumber[$i], 0, 6)));
            if (mysql_num_rows($result_state) == 0) {
                $state[$i] = "Unknown";
            } else {
                $state[$i] = mysql_result($result_state, 0, 0);
            }
        }

        $userfield2[$i] = $row[userfield2];
        if ($userfield2[$i] != 1) {
            $userfield2[$i] = 0;
            $paid[$i] = '<td><img src="images/cross.png" border="0" align="center">';
        } else {
            $paid[$i] = '<td><img src="images/tick.png" border="0" align="center">';
        }
        $display = TRUE;
        if ($disposition[$i] == "ANSWERED") {
            $td = "<td>";
        } else if ($disposition[$i] == "NO ANSWER") {
            $td = "<td>";
        } else if ($disposition[$i] == "FAILED") {
            $td = "<td>";
            $display = FALSE;
        } else if ($disposition[$i] == "BUSY") {
            $td = "<td>";
        } else {
            $td = "<td>";
        }
        if ($dst[$i] != "s") {
            /* Failed */
            $td = "<td>";
        }
        if ($dst[$i] == "timeout") {
            $td = "<td>";
            $dst[$i] = "no answer";
        }
        if ($dst[$i] == "busy") {
            $td = "<td>";
            $dst[$i] = "busy";
        }
        if ($dst[$i] == "1") {
            $td = "<td>";
            $dst[$i] = "pressed 1";
        }

        if ($dst[$i] == "s") {
            if ($disposition[$i] == "FAILED") {
                $dst[$i] = "";
            } else {
                if ($dcontext[$i] == "do-amd") {
                    $dst[$i] = "answer machine";
                } else if ($dcontext[$i] == "do-live") {
                    $dst[$i] = "human";
                } else {
                    $dst[$i] = $dcontext[$i];
                } /*else {
                   $dst[$i] = "answered";
                   }*/
            }

        }
        //$billtype[$i] = "Per Minute";
        if ($config_values['USE_BILLING'] == "YES") {
            if (!($customerid[$accountcode[$i]] > 0)) {
                $sqlx = "SELECT * from SineDialer.billing where accountcode = '".$accountcode[$i]."'";
                //echo $sqlx;
                $resultx = mysql_query($sqlx, $link);
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
        } else {
            $priceperminute[$accountcode[$i]] = 0;
            //echo mysql_result($resultx, 0, 'priceperminute');
            $customerid[$accountcode[$i]] = "";
            $firstperiod[$accountcode[$i]] = 1;
            $increment[$accountcode[$i]] = 1;
            $firstperiod[$accountcode[$i]] = 1;
            $credit[$accountcode[$i]] = 0;
            $pricepercall[$accountcode[$i]] = 0;
            $priceperconnectedcall[$accountcode[$i]] = 0;
            $priceperpress1[$accountcode[$i]] = 0;
        }
        //$cost[$i] = round((1/60)*$billsec[$i],2);
        $cost[$i] = 0;
        $costperpress1[$i] = 0;
        $costpercall[$i] = 0;
        $costperminute[$i] = 0;
        $costperconnect[$i] = 0;

        if ($pricepercall[$accountcode[$i]] > 0) {
            if ($display) {
                $costpercall[$i] = round($pricepercall[$accountcode[$i]], 2);
                $cost[$i] += $costpercall[$i];
            }
        }
        if ($disposition[$i] == "ANSWERED") {
            if ($billsec[$i] > $firstperiod[$accountcode[$i]]) {
                if ($increment[$accountcode[$i]] == 1) {
                    $costperminute[$i] = round(($priceperminute[$accountcode[$i]] / 60) * $billsec[$i], 2);
                } else {
                    /*30
                     27
                     if the increment is 30 seconds and the call is 73 seconds they should be
                     charged for 73/30 = 2.4 blocks - round up to 3 = 3*30 = 90

                     if the increment is 9 seconds and the call is 288 seconds they should be
                     charged for 288/9 = 32 blocks - no rounding because it is accurate.

                     they are then charged for $6 per minute

                     288/60 = 4.8 minutes

                     4.8 minutes * $6 per minute = 28.8

                     */
                    //echo "Billsec: $billsec[$i] Increment: $increment[$accountcode[$i]]";
                    if ($increment[$accountcode[$i]] < 1) {
                        $increment[$accountcode[$i]] = 1;
                    }
                    $blocks = ceil($billsec[$i] / $increment[$accountcode[$i]]);
                    //echo "Blocks: $blocks";
                    $newsecs = $blocks * $increment[$accountcode[$i]];
                    //echo "Newsecs: $newsecs";
                    $costperminute[$i] = round(($priceperminute[$accountcode[$i]] / 60) * $newsecs, 2);
                    //echo "costperminute: $costperminute[$i]";
                }
                $cost[$i] += $costperminute[$i];
            } else {
                $costperminute[$i] = round(($priceperminute[$accountcode[$i]] / 60) * $firstperiod[$accountcode[$i]], 2);
                $cost[$i] += $costperminute[$i];
            }
            $costperconnect[$i] = round($priceperconnectedcall[$accountcode[$i]], 2);
            $cost[$i] += $costperconnect[$i];
            if ($dst[$i] == "pressed 1") {
                $costperpress1[$i] = round($priceperpress1[$accountcode[$i]], 2);
                $cost[$i] += $costperpress1[$i];
            }
        }
        if ($display) {
            echo "<tr>";

            if (isset($_GET['all']) && $_GET['all'] == "1") {
                echo $td.$calldate[$i]."</td>$td"/*.$dcontext[$i]."</td>$td".
                                                  $clid[$i]."</td>$td"*/.
                    /*$lastapp[$i]."</td>$td".$lastdata[$i]."</td>$td".*/
                    $duration[$i]."</td>$td".$billsec[$i]."</td>$td".
                    $disposition[$i]."</td>";
                if ($config_values['CDR_USE_STATE'] == "YES") {
                    echo $td.$state[$i]."</td>";
                }

                echo "$td".$accountcode[$i]."</td>$td".$phonenumber[$i]."</b></td>$td<b>".$dst[$i]."</b></td>";
                echo "</tr>";
            } else {
                echo $td.$calldate[$i]."</td>$td"/*.$dcontext[$i]."</td>$td".
                                                  $clid[$i]."</td>$td"*/.
                    /*$lastapp[$i]."</td>$td".$lastdata[$i]."</td>$td".*/
                    $duration[$i]."</td>$td".$billsec[$i]."</td>$td".
                    $disposition[$i]."</td>";

                if ($config_values['CDR_USE_STATE'] == "YES") {
                    echo $td.$state[$i]."</td>";
                }

                if ($config_values['disable_all_types'] == "YES") {
                    echo "$td".$phonenumber[$i]."</b></td>$td<b>".$dst[$i]."</b></td>";
                    echo $td.$currency.$costperminute[$i]."</td>".
                        $td.$currency.$cost[$i]."</td>".$td.$paid[$i]."</td>";
                } else {
                    echo "$td".$accountcode[$i]."</td>$td".$phonenumber[$i]."</b></td>$td<b>".$dst[$i]."</b></td>";
                    echo $td.$currency.$costperminute[$i]."</td>".$td.$currency.$costpercall[$i]."</td>".
                        $td.$currency.$costperconnect[$i]."</td>".$td.$currency.$costperpress1[$i]."</td>".$td.$currency.$cost[$i]."</td>".$td.$paid[$i]."</td>";
                }
                echo "</tr>";
            }
        }
        $i++;
    }

    echo "</table></div>";
}
require "footer.php";
?>
