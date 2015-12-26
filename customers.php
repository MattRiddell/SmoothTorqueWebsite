<?
$level = $_COOKIE[level];

if ($level != sha1("level100")) {
    include "header.php";
    $ip = $_SERVER['REMOTE_ADDR'];
    echo "Attempted break in attempt from $ip ($_COOKIE[user])";
    /*================= Log Access ======================================*/
    $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_COOKIE[user]', ' $ip attempted to view the admin page')";
    $result = mysql_query($sql, $link);
    /*================= Log Access ======================================*/

} else {

    require "header.php";
    require "header_customer.php";
    /*================= Log Access ======================================*/
    $sql = "INSERT INTO log (timestamp, username, activity) VALUES (NOW(), '$_COOKIE[user]', 'Viewed the customers page')";
    $result = mysql_query($sql, $link);
    /*================= Log Access ======================================*/

    $sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
    $result = mysql_query($sql, $link) or die (mysql_error());;
    $campaigngroupid = mysql_result($result, 0, 'campaigngroupid');
//shadow_start();
    ?>

    <div class="table-responsive">
        <table class="table table-striped" align="center" border="0" cellpadding="2" cellspacing="0">
            <TR>
                <TD CLASS="">
                    Name
                </TD>
                <TD CLASS="">
                    Username
                </TD>
                <TD CLASS="">
                    Call Details
                </TD>

                <? if ($config_values['USE_BILLING'] == "YES") { ?>
                    <TD CLASS="">
                        Credit
                    </TD>
                    <TD CLASS="">
                        Credit Limit
                    </TD>
                    <?
                } ?>
                <TD CLASS="">
                    Trunk
                </TD>
                <TD CLASS="">
                </TD>
            </TR>
            <?

            $sql = 'SELECT customer.*,trunk.name AS trunkname, billing.credit as credit, billing.creditlimit as creditlimit FROM customer LEFT JOIN trunk ON customer.trunkid=trunk.id LEFT JOIN billing on customer.id=billing.customerid order by customer.company';
            $result = mysql_query($sql, $link) or die (mysql_error());;
            //$campaigngroupid=mysql_result($result,0,'campaigngroupid');
            $count = 0;
            while ($row = mysql_fetch_assoc($result)) {


                $count++;

                ?>
                <TR <? echo $class; ?> >
                    <TD>
                        <?
                        if (strlen($row[company]) < 15) {
                            echo ''.$row[company].'';
                        } else {
                            echo ''.trim(substr($row[company], 0, 15))."...";
                        }
                        ?>
                    </TD>
                    <TD class="vert-align">
                        <?
                        if (strlen($row[username]) < 15) {
                            echo '<A HREF="editcustomer.php?id='.$row[id].'" class="btn btn-primary"><i class="glyphicon glyphicon-pencil"></i> '.$row[username].'</a>';
                        } else {
                            echo '<A HREF="editcustomer.php?id='.$row[id].'" class="btn btn-primary"><i class="glyphicon glyphicon-pencil"></i> '.trim(substr($row[username], 0, 15))."...</a>";
                        }
                        echo '&nbsp;<A HREF="changepassword.php?id='.$row[id].'" title="Change Password" class="btn btn-info"><i class="glyphicon glyphicon-lock"></i> Reset Password</A>';
                        ?>
                    </TD>
                    <TD>
                        <?
                        echo '<A HREF="viewcdr.php?accountcode=stl-'.$row[username].'" title="View CDR Information" class="btn btn-primary">View CDR</A>'; ?>
                    </TD>

                    <? if ($config_values['USE_BILLING'] == "YES") { ?>
                        <TD>
                            <?
                            echo '<a href="billing.php?id='.$row[id].'" title="View Billing Information" class="btn btn-primary">';
                            if (isset($row[credit])) {
                                echo '<i class="glyphicon glyphicon-pencil"></i> ';
                                echo $config_values['CURRENCY_SYMBOL']." ".number_format($row[credit], 2)."</a>";
                            } else {
                                echo '<i class="glyphicon glyphicon-plus"></i> Add Billing</a>';
                            }
                            ?>
                        </TD>
                        <TD>
                            <?
                            echo '<A HREF="billing.php?id='.$row[id].'" title="View Billing Information" class="btn btn-primary">';
                            if (isset($row[credit])) {
                                echo '<i class="glyphicon glyphicon-pencil"></i> ';
                                echo $config_values['CURRENCY_SYMBOL']." ".number_format($row[creditlimit], 2)."</A>";
                            } else {
                                echo '<i class="glyphicon glyphicon-plus"></i> ';
                                echo "Add Billing</A>";
                            }
                            ?>
                        </TD>
                        <?
                    } ?>
                    <TD>

                        <?
                        if (strlen(trim($row[trunkname])) < 1) {
                            echo "Default";
                        } else {
                            echo "<b>".$row[trunkname]."</b>";
                        }
                        ?>
                    </TD>
                    <?/*<TD>

 this week
$sql = 'select count(*) from cdr.cdr where date(calldate)<=curdate() and date(calldate)>=DATE_ADD(CURDATE(), INTERVAL -7
DAY)
and dst="1" and accountcode="stl-'.$row[username].'"';
$resultaa=mysql_query($sql, $link) or die (mysql_error());;
$thisweek = mysql_result($resultaa,0,0);
echo "This Week: $thisweek <br />";

 yesterday
$sql = 'select count(*) from cdr.cdr where date(calldate)<curdate() and date(calldate)>=DATE_ADD(CURDATE(), INTERVAL -1 DAY)
and dst="1"  and accountcode="stl-'.$row[username].'"';
$resultaa=mysql_query($sql, $link) or die (mysql_error());;
$yesterday = mysql_result($resultaa,0,0);
echo "yesterday: $yesterday <br />";

 today
$sql = 'select count(*) from cdr.cdr where date(calldate)=curdate() and dst="1"  and accountcode="stl-'.$row[username].'"';
$resultaa=mysql_query($sql, $link) or die (mysql_error());;
$today = mysql_result($resultaa,0,0);
echo "today: $today <br />";

</TD>  */ ?>
                    <TD>
                        <a href="#" onclick="displaySmallMessage('includes/confirmDeleteCustomer.php?id=<? echo $row[id]; ?>');return false" class="btn btn-warning"><i class="glyphicon glyphicon-remove"></i> Delete</a><br>
                    </TD>
                </TR>

                <?
            }
            ?>

        </TABLE>
    </div>
    <?
    shadow_end();
    require "footer.php";
}
?>
