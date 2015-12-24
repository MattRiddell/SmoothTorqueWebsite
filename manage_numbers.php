<?
if (isset($_GET['reset'])) {
    // For ajax reset
    include "admin/db_config.php";
    require "functions/sanitize.php";
    //print_r($_POST);
    $result = mysql_query("UPDATE number SET status = 'new' WHERE campaignid = ".sanitize($_POST['campaignid'])." and phonenumber = ".sanitize($_POST['phonenumber'])) or die(mysql_error());
    echo "Success";
    exit(0);
}
/**
 * Created by PhpStorm User: mattriddell Date: 24/12/15 Time: 09:47
 */

require "header.php";
if (isset($_GET['view'])) {
    if ($_GET['view'])
        ?>

        <?
    $result = mysql_query("SELECT Count(*) FROM number WHERE campaignid = ".sanitize($_GET['view'])) or die(mysql_error());
    if (mysql_num_rows($result) == 0) {
        // No rows
    } else {
        while ($row = mysql_fetch_assoc($result)) {
            if ($row['Count(*)'] == 0) {
                ?>
                <div class="jumbotron">
                    <h3>No Numbers</h3>
                    <p>There are currently no numbers for this campaign. Click the button to below to add some numbers.</p>
                    <p><a href="manage_numbers.php?add=<?= $_GET['view'] ?>" class="btn btn-primary">Add Numbers</a></p>
                </div>
                <?
                require "footer.php";
                exit(0);
            } else {
                $total_nums = $row['Count(*)'];
            }
        }
    }
    ?>

    <? box_start(); ?>
    <form action="manage_numbers.php?search=1" method="post" class="form-inline">
        <div class="form-group">
            <label for="number">Number: </label>
            <input type="text" class="form-control" id="number" name="number" placeholder="I.E. 4071231234" value="<?= $record['number'] ?>">
        </div>
        <button type="submit" class="btn btn-default navbar-btn">
            <i class="glyphicon glyphicon-search"></i> Search
        </button>
    </form>
    <? box_end();

    if ($total_nums > 100) {


        ?>


        <nav style="text-align: center">
            <ul class="pagination">
                <li>
                    <a href="#" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <li><a href="#">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#">4</a></li>
                <li><a href="#">5</a></li>
                <li>
                    <a href="#" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
        <?
    }
    ?>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>Phone Number</th>
            <th>Status</th>
            <th>Last Update</th>
            <th>Reset</th>
            <th>Delete</th>
        </tr>
        </thead>
        <tbody>
        <?
        $result = mysql_query("SELECT * FROM number WHERE campaignid = ".sanitize($_GET['view'])) or die(mysql_error());
        if (mysql_num_rows($result) == 0) {
            // No rows

        } else {
            while ($row = mysql_fetch_assoc($result)) {
                echo "<tr>";
                echo '<td>'.$row['phonenumber'].'</td>';
                echo '<td><p id="'.$row['phonenumber'].'">'.ucwords(str_replace("_", " ", $row['status'])).'</p></td>';
                echo '<td>'.$row['datetime'].'</td>';
                echo '<td><button class="btn btn-primary" onclick="reset(\''.$_GET['view'].'\',\''.$row['phonenumber'].'\');"><i class="glyphicon glyphicon-refresh" ></i></button></td>';

                echo '<td>'.$row['phonenumber'].'</td>';
                echo "</tr>";
            }
        }
        ?>
        </tbody>
    </table>
    <script>
        function reset(campaignid, phonenumber) {
            //alert("Resetting");
            $.post("manage_numbers.php?reset=1", {campaignid: campaignid, phonenumber: phonenumber})
                .done(function (data) {
                    $('#' + phonenumber).html("New");
                    $('#' + phonenumber).fadeIn(300).fadeOut(300).fadeIn(300).fadeOut(300).fadeIn(300);
                });

        }
    </script>
    <?
}
require "footer.php";
exit(0);