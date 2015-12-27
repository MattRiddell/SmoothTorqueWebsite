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
if (isset($_GET['upload'])) {
    $response = array();
    $response['files'] = array();
    $i = 0;

    $origname = $_FILES['files']['name'];
    $tmpname = $_FILES['files']['tmp_name'];


    // Move the file
    move_uploaded_file($tmpname[0], getcwd()."/uploads/".basename($tmpname[0]));

    $response['files'][$i]['name'] = getcwd()."uploads/".basename($tmpname[0]);
    $response['files'][$i]['size'] = "Test";
    $response['files'][$i]['error'] = "Test";


    /*?>{"files": [
    {
    "name": "picture1.jpg",
    "size": 902604,
    "error": "Filetype not allowed"
    },
    {
    "name": "picture2.jpg",
    "size": 841946,
    "error": "Filetype not allowed"
    }
    ]}
    <?
    exit(0);*/
    //$response = array();
    //$response['error'] = "Some Error";
    echo json_encode($response);
    exit(0);
}
require "header.php";
if (isset($_GET['file_import'])) {
    ?>
    <div class="jumbotron">
        <h3>File Uploaded</h3>
        <p id="status">Please wait while we import your file...</p>
        <p id="imported"></p>
        <div id="progress" class="progress">
            <div class="progress-bar progress-bar-success"></div>
        </div>
        <?
        //for ($i = 0;$i<=10;$i++) {
        ?>
        <script>
        $('#imported').html("Reading the file into memory");
        </script>
        <?
        flush();
        ob_flush();
        $contents = file_get_contents("uploads/".basename($_GET['file_import']));
        $exploded = explode(PHP_EOL, $contents);
        $total = count($exploded);
        $i = 0;
        ?>
        <script>
            $('#imported').html("Importing into database");
        </script>
        <?
        flush();
        ob_flush();
        $good = 0;
        $bad = 0;
        foreach ($exploded as $line) {
            $i++;
            $number = preg_replace("/[^0-9]/", "", $line);
            if (strlen($number) == 10) {
                $good++;
                $sql = "INSERT INTO number (campaignid, phonenumber, status, random_sort, datetime) VALUES (".sanitize($_GET['id']).",".sanitize($number).",'new',".sanitize(rand(0, 999999999)).",NOW())";
                mysql_query($sql);

            } else if (strlen(trim($number)) == 0) {
            } else {
                $bad++;
            }
            if ($i % 1000 == 0) {
                ?>
                <script>
                    $('#progress .progress-bar').css(
                        'width',
                        <?=round($i / $total * 100)?> +'%'
                    );
                    $('#imported').html("Done <?=($i."/".$total)?> - Good Numbers: <?=$good?> Bad Numbers: <?=$bad?> ");
                </script>
                <?
                flush();
                ob_flush();
            }
        }
        flush();
        ob_flush();
        echo '<script>$("#progress").hide();</script>';
        echo '<script>$("#imported").hide();</script>';
        echo '<script>$("#status").html("Import Complete - '.$good.' Good Numbers and '.$bad.' Bad Numbers</p>");</script>';
        ?>
        <p>
            <a href="manage_numbers.php?import=<?= $_GET['id'] ?>" class="btn btn-primary">Import More Numbers</a>
        </p>
        <p>
            <a href="new_campaign.php?view=1" class="btn btn-primary">Return to your campaign list</a>
        </p>
    </div>
    <?
}

if (isset($_GET['import'])) {
    ?>
    <div class="jumbotron">
        <h3>Import Numbers</h3>
        <p>Please select a text file or CSV file that only has numbers in it. One number per line and with each number being a ten digit number.</p>
        <!DOCTYPE HTML>
        <html>
        <head>
            <meta charset="utf-8">
            <title>jQuery File Upload Example</title>
        </head>
        <body>
        <input id="fileupload" type="file" name="files[]" data-url="manage_numbers.php?upload=1">
        <script src="js/jquery.ui.widget.js"></script>
        <script src="js/jquery.iframe-transport.js"></script>
        <script src="js/jquery.fileupload.js"></script>
        <script>

            function baseName(str) {
                var base = new String(str).substring(str.lastIndexOf('/') + 1);
                if (base.lastIndexOf(".") != -1) {
                    base = base.substring(0, base.lastIndexOf("."));
                }
                return base;
            }

            /*jslint unparam: true */
            /*global window, $ */
            $(function () {
                'use strict';
                // Change this to the location of your server-side upload handler:
                $('#fileupload').fileupload({
                    dataType: 'json',
                    fail: function (e, data) {
                        alert("Fail" + data.textStatus);
                    },
                    done: function (e, data) {
                        //alert("done");
                        $.each(data.result.files, function (index, file) {
                            //alert("Received "+file.name);
                            window.location = "manage_numbers.php?id=<?=$_GET['import']?>&file_import=" + baseName(file.name);
                        });
                    },
                    progressall: function (e, data) {
                        var progress = parseInt(data.loaded/data.total*100, 10);
                        $('#progress .progress-bar').css(
                            'width',
                            progress + '%'
                        );
                    }
                }).prop('disabled', !$.support.fileInput)
                    .parent().addClass($.support.fileInput ? undefined : 'disabled');
            });

        </script>
        <div id="progress" class="progress">
            <div class="progress-bar progress-bar-success"></div>
        </div>
        <div id="files"></div>
        </body>
        </html>
    </div>
    <?
}


if (isset($_GET['save_manual'])) {
    //print_pre($_POST);
    $numbers = explode("\n", $_POST['numbers']);
    //print_pre($numbers);
    foreach ($numbers as $number) {
        $number = preg_replace("/[^0-9]/", "", $number);
        if (strlen($number) != 10) {
            $errors[] = $number;
        } else {
            $sql = "REPLACE INTO number (campaignid, phonenumber, status, random_sort, datetime) VALUES (".sanitize($_GET['save_manual']).",".sanitize($number).",'new',".sanitize(rand(0, 999999999)).", NOW())";
            //echo $sql;
            mysql_query($sql) or die('<div class="jumbotron"><h3>Database problem</h3><p>We are sorry to inform you that there was a problem importing your numbers. Please show the following error to your sales representative.</p><p><strong>'.mysql_error()."</strong></p></div>");
        }
    }
    if (isset($errors)) {
        ?>
        <div class="jumbotron">
            <h3>Some Numbers Not Imported</h3>
            <p>The following numbers were not imported because they were not ten digit numbers:</p>
            <p>
                <?
                foreach ($errors as $number) {
                    echo $number."<br/>";
                }
                ?>
            </p>
            <p>
                <a href="manage_numbers.php?add_manual=<?= $_GET['save_manual'] ?>" class="btn btn-primary">Import More Numbers</a>
            </p>
            <p>
                <a href="new_campaign.php?view=1" class="btn btn-primary">Return to your campaign list</a>
            </p>
        </div>
        <?
    } else {
        ?>
        <div class="jumbotron">
            <h3>Numbers Imported</h3>
            <p>All the numbers you typed were imported successfully.</p>

            <p>
                <a href="manage_numbers.php?add_manual=<?= $_GET['save_manual'] ?>" class="btn btn-primary">Import More Numbers</a>
            </p>
            <p>
                <a href="new_campaign.php?view=1" class="btn btn-primary">Return to your campaign list</a>
            </p>
        </div>
        <?
    }
}


if (isset($_GET['add_manual'])) {
    ?>
    <div class="jumbotron">
        <h3>Add Numbers Manually</h3>
        <p>
            Please add one ten digit number per line (with no 1 in front)
        </p>

        <form action="manage_numbers.php?save_manual=<?= $_GET['add_manual'] ?>" method="post">
            <div class="form-group">
                <textarea class="form-control" id="numbers" name="numbers" placeholder="I.E. 4075551234" rows="20"></textarea>
            </div>
            <input type="submit" class="btn btn-primary" value="Save Numbers">
        </form>

    </div>
    <?
}


if (isset($_GET['search'])) {
    //print_pre($_POST);
    $result = mysql_query("SELECT * FROM number WHERE phonenumber = ".sanitize($_POST['number'])." AND campaignid = ".sanitize($_GET['search'])) or die(mysql_error());
    echo '<div class="jumbotron"><h3>Searching for '.$_POST['number'].'</h3>';

    if (mysql_num_rows($result) == 0) {
        // No rows
        ?>
        <p>We were unable to find that number in the database</p>
        <p>
            <a href="manage_numbers.php?view=<?= $_GET['search'] ?>" class="btn btn-primary">Go back to the list of numbers</a>
        </p>

        <?
    } else {
        while ($row = mysql_fetch_assoc($result)) {
            //print_pre($row);
            ?>
            <p>We found the following information for <?= format_phone($row['phonenumber']) ?>:</p>
            <p>Last Update: <?= date("d/m/Y H:i:s", strtotime($row['datetime'])) ?></p>
            <p>Status: <?= $row['status'] ?></p>

            <?
            echo '<button class="btn btn-primary" onclick="reset(\''.$_GET['view'].'\',\''.$row['phonenumber'].'\');"><i class="glyphicon glyphicon-refresh" ></i> Reset Number back to New</button><br /><br />';

            echo '<button class="btn btn-danger" onclick="delete(\''.$_GET['view'].'\',\''.$row['phonenumber'].'\');" data-toggle="modal" data-target="#myModal'.$row['phonenumber'].'"><i class="glyphicon glyphicon-remove" ></i> Delete Number</button>';

            ?>
            <!-- Modal -->
            <div class="modal fade" id="myModal<?= $row['phonenumber'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Delete <?= $row['phonenumber'] ?></h4>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete <?= format_phone($row['phonenumber']) ?> from the database?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" onclick="delete_number('<?= $_GET['search'] ?>','<?= $row['phonenumber'] ?>')">Yes, delete it</button>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                function delete_number(campaignid, phonenumber) {
                    //alert("deleting");
                    $.post("manage_numbers.php?delete=1", {campaignid: campaignid, phonenumber: phonenumber})
                        .done(function (data) {
                            window.location = "manage_numbers.php?view=<?=$_GET['search']?>"
                        });
                }
                function reset(campaignid, phonenumber) {
                    //alert("Resetting");
                    $.post("manage_numbers.php?reset=1", {campaignid: campaignid, phonenumber: phonenumber})
                        .done(function (data) {
                            window.location = "manage_numbers.php?view=<?=$_GET['search']?>"
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
        <p>When you add numbers to a campaign you have two choices. You can either copy and paste them from somewhere else (or type them manually), or you can import a text file.</p>
        <p>If you are importing a text file you need to make sure that the file only has numbers in it (no names or anything else) and just one 10 digit number per line</p>
        <p>
            <a href="manage_numbers.php?add_manual=<?= $_GET['add'] ?>" class="btn btn-primary"><i class="glyphicon glyphicon-pencil"></i> Add by copying and pasting or typing</a>
        </p>
        <p>
            <a href="manage_numbers.php?import=<?= $_GET['add'] ?>" class="btn btn-primary"><i class="glyphicon glyphicon-upload"></i> Upload a text file</a>
        </p>
    </div>
    <?
    require "footer.php";
    exit(0);
}
if (isset($_GET['view'])) {
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
    <form action="manage_numbers.php?search=<?= $_GET['view'] ?>" method="post" class="form-inline">
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

        // TODO: Make pagination work
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
                <div class="modal fade" id="myModal<?= $row['phonenumber'] ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Delete <?= format_phone($row['phonenumber']) ?></h4>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to delete <?= format_phone($row['phonenumber']) ?> from the database?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary" onclick="delete_number('<?= $_GET['view'] ?>','<?= $row['phonenumber'] ?>')">Yes, delete it</button>
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