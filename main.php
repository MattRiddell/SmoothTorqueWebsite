<?

require "header.php";




$level = $_COOKIE['level'];

/* If we are an administrator and billing is enabled */
if ($level == sha1("level100") && $config_values['USE_BILLING'] == "YES" && $config_values['FRONT_PAGE_BILLING'] == "YES") {
    if (!isset($_GET['size'])) {
        $size = 144;
    } else {
        $size = $_GET['size'];
    }
    mysql_select_db("SineDialer", $link);
    $resultx = mysql_query("select distinct system_billing.groupid, customer.* from system_billing left join customer on system_billing.groupid=customer.campaigngroupid");
    $x = 0;
    $highest = 0;

    ?>

<div class="jumbotron">
    <?

    //$size_x= $size;
    while ($rowx = mysql_fetch_assoc($resultx)) {
        $result = mysql_query("select max(totalcost) from system_billing where groupid = ".$rowx['groupid']);
        $totalcost[$x] = mysql_result($result, 0, 0);
        if ($totalcost[$x] > $highest) {
            $highest_array[$x] = $totalcost[$x];
        }
        $company[$x] = $rowx[company];
        $groupid[$x] = $rowx[groupid];
        $real_total_cost += $totalcost[$x];
        $x++;
    }
    for ($i = 0; $i < $x; $i++) {
        $highest += $highest_array[$i];
//echo "Adding $company[$i]";
    }
    $highest = $highest + ($highest / 10);
    //for($i = 0;$i<$x;$i++) {
    //        //echo $size_x;
    //        $totalcost
    //
    //    }
    $totalcost_cr = $config_values['CURRENCY_SYMBOL']." ".number_format($real_total_cost, 2);
    echo "<h3>Total System Revenue: $totalcost_cr</h3><br />";

    ?>
    <p>Below you can find information on billing that the system has done. This page is only viewable by an administrator account when billing is enabled.</p>
    <?

    if ($size == 144) {
        $class = "btn btn-info";
    } else {
        $class = "btn btn-primary";
    }
    echo '<a href="main.php?size=144" class="'.$class.'">Today</a>&nbsp;';
    if ($size == 700) {
        $class = "btn btn-info";
    } else {
        $class = "btn btn-primary";
    }
    echo '<a href="main.php?size=700" class="'.$class.'">5 Days</a>&nbsp;';
    if ($size == 1400) {
        $class = "btn btn-info";
    } else {
        $class = "btn btn-primary";
    }
    echo '<a href="main.php?size=1400" class="'.$class.'">10 Days</a>&nbsp;';
    if ($size == 4200) {
        $class = "btn btn-info";
    } else {
        $class = "btn btn-primary";
    }
    echo '<a href="main.php?size=4200" class="'.$class.'">30 Days</a>&nbsp;';

    echo "<br /><br />";

    //include "system_bill_graph.php";
    echo '<a href="system_bill_graph.php?xsize=800&ysize=600&size='.$size.'&max='.$highest.'&groupid=-1"><img src="system_bill_graph.php?xsize=1020&ysize=400&size='.$size.'&max='.$highest.'&groupid=-1" width="1020" height="400" style="border: 1px solid #ccc" class="img-responsive img-rounded"></a>';
    echo "<br>";
    echo '</div>';
} else {
    ?>
    <div class="jumbotron">

    <h2><? echo stripslashes($config_values['TITLE']); ?></h2>

    <p>

    <?
    if ($level == sha1("level5")) {
        /* Manual Dialing agent logging in */
        echo "Please wait."; ?>
        </p></div>
        <META HTTP-EQUIV=REFRESH CONTENT="0; URL=manualdial.php">

        <?
        exit(0);
    }
    if ($level == sha1("level10")) {
        /* Accounts user logging in */
        echo '<a href="addfunds.php">Add funds to a registered account</a><br />';
        echo '<a href="billinglog.php">View Billing Log</a><br />';

    } else {
        echo "<p>".$config_values['MAIN_PAGE_TEXT']."</p>";
        if ($config_values['disable_all_types'] == "YES") {
            $url = "new_campaign.php?add=1";
        } else {
            $url = "addcampaign.php";
        }
        ?>
        <p>
            <a class="btn btn-primary btn-lg" href="<?= $url ?>" role="button"><i class="glyphicon glyphicon-plus"></i> Add A New Campaign</a>
        </p>
        <?
    }
    echo '</p></div>';

}


?>







<?
require "footer.php";
?>
