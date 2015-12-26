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
if (isset($_GET['delete'])) {
    // For ajax reset
    include "admin/db_config.php";
    require "functions/sanitize.php";
    //print_r($_POST);
    $result = mysql_query("DELETE FROM number WHERE campaignid = ".sanitize($_POST['campaignid'])." and phonenumber = ".sanitize($_POST['phonenumber'])) or die(mysql_error());
    echo "Success";
    exit(0);
}
/**
 * Created by PhpStorm User: mattriddell Date: 24/12/15 Time: 09:47
 */

require "header.php";
if (isset($_GET['search'])) {
    //print_pre($_POST);
    $result = mysql_query("SELECT * FROM number WHERE phonenumber = ".sanitize($_POST['number'])." AND campaignid = ".sanitize($_GET['search'])) or die(mysql_error());
    echo '<div class="jumbotron"><h3>Searching for '.$_POST['number'].'</h3>';

    if (mysql_num_rows($result) == 0) {
        // No rows
        ?>
        <p>We were unable to find that number in the database</p>
        <p><a href="manage_numbers.php?view=<?=$_GET['search']?>" class="btn btn-primary">Go back to the list of numbers</a></p>

        <?
    } else {
        while ($row = mysql_fetch_assoc($result)) {
            //print_pre($row);
            ?>
            <p>We found the following information for <?=format_phone($row['phonenumber'])?>:</p>
            <p>Last Update: <?=date("d/m/Y H:i:s",strtotime($row['datetime']))?></p>
            <p>Status: <?=$row['status']?></p>
            <p>
                <?
                echo '<button class="btn btn-primary" onclick="reset(\''.$_GET['view'].'\',\''.$row['phonenumber'].'\');"><i class="glyphicon glyphicon-refresh" ></i> Reset Number back to New</button><br /><br />';

                echo '<button class="btn btn-danger" onclick="delete(\''.$_GET['view'].'\',\''.$row['phonenumber'].'\');" data-toggle="modal" data-target="#myModal'.$row['phonenumber'].'"><i class="glyphicon glyphicon-remove" ></i> Delete Number</button>';

                ?>
                <!-- Modal -->
                <div class="modal fade" id="myModal<?=$row['phonenumber']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Delete <?=$row['phonenumber']?></h4>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to delete <?=format_phone($row['phonenumber'])?> from the database?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary" onclick="delete_number('<?=$_GET['search']?>','<?=$row['phonenumber']?>')">Yes, delete it</button>
                            </div>
                        </div>
                    </div>
                </div>
            </p>
            <script>
        function delete_number(campaignid, phonenumber) {
            //alert("deleting");
            $.post("manage_numbers.php?delete=1", {campaignid: campaignid, phonenumber: phonenumber})
                .done(function (data) {
                    window.location="manage_numbers.php?view=<?=$_GET['search']?>"
                });
        }
        function reset(campaignid, phonenumber) {
            //alert("Resetting");
            $.post("manage_numbers.php?reset=1", {campaignid: campaignid, phonenumber: phonenumber})
                .done(function (data) {
                    window.location="manage_numbers.php?view=<?=$_GET['search']?>"
                });

        }
    </script>
            <?
        }
    }
    echo '</div>';
    require "footer.php";
    exit(0);
}
if (isset($_GET['add'])) {
    ?>
    <div class="jumbotron">
        <h3>Add Numbers To Campaign</h3>
        <p>When you add numbers to a campaign you have two choices.  You can either copy and paste them from somewhere else (or type them manually), or you can import a text file.</p>
        <p>If you are importing a text file you need to make sure that the file only has numbers in it (no names or anything else) and just one 10 digit number per line</p>
        <p>
            <a href="manage_numbers.php?add_manual=<?=$_GET['add']?>" class="btn btn-primary"><i class="glyphicon glyphicon-pencil"></i> Add by copying and pasting or typing</a>
        </p>
        <p>
            <a href="manage_numbers.php?import=<?=$_GET['add']?>" class="btn btn-primary"><i class="glyphicon glyphicon-upload"></i> Upload a text file</a>
        </p>
    </div>
    <?
    require "footer.php";
    exit(0);
}
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
    <form action="manage_numbers.php?search=<?=$_GET['view']?>" method="post" class="form-inline">
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
                echo '<td>'.format_phone($row['phonenumber']).'</td>';
                echo '<td><p id="'.$row['phonenumber'].'">'.ucwords(str_replace("_", " ", $row['status'])).'</p></td>';
                echo '<td>'.$row['datetime'].'</td>';
                echo '<td><button class="btn btn-primary" onclick="reset(\''.$_GET['view'].'\',\''.$row['phonenumber'].'\');"><i class="glyphicon glyphicon-refresh" ></i></button></td>';

                echo '<td><button class="btn btn-danger" onclick="delete(\''.$_GET['view'].'\',\''.$row['phonenumber'].'\');" data-toggle="modal" data-target="#myModal'.$row['phonenumber'].'"><i class="glyphicon glyphicon-remove" ></i></button></td>';
                echo "</tr>";
                ?>

                <!-- Modal -->
                <div class="modal fade" id="myModal<?=$row['phonenumber']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Delete <?=format_phone($row['phonenumber'])?></h4>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to delete <?=format_phone($row['phonenumber'])?> from the database?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary" onclick="delete_number('<?=$_GET['view']?>','<?=$row['phonenumber']?>')">Yes, delete it</button>
                            </div>
                        </div>
                    </div>
                </div>

                <?
            }
        }
        ?>
        </tbody>
    </table>

    <script>
        function delete_number(campaignid, phonenumber) {
            //alert("deleting");
            $.post("manage_numbers.php?delete=1", {campaignid: campaignid, phonenumber: phonenumber})
                .done(function (data) {
                    location.reload();
                });
        }
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