<?
if (isset($_GET['delete_choice'])) {
    require "header.php";
    $result = mysql_query("DELETE FROM survey_choices WHERE survey_id = ".sanitize($_GET['survey'])." AND question_number = ".sanitize($_GET['delete_choice']));
    $result = mysql_query("UPDATE survey_choices SET question_number = question_number - 1 WHERE survey_id = ".sanitize($_GET['survey'])." AND question_number > ".sanitize($_GET['delete_choice']));
    ?><center><img src="images/progress.gif" border="0"><br />Removing choice...
    <META HTTP-EQUIV=REFRESH CONTENT="1; URL=surveys.php?edit=<?=$_GET['survey']?>"><?

    require "footer.php";
    exit(0);
}
if (isset($_GET['add_choice'])) {
    require "admin/db_config.php";
    require "functions/sanitize.php";
    $survey_id = sanitize($_GET['survey']);
    $question_number = sanitize($_GET['question_number']);
    $soundfile = sanitize($_GET['filename']);
    if ($_GET['choices'] == "No keys accepted") {
        $choices = sanitize("");
    } else {
        $choices = sanitize($_GET['choices']);
    }
    $sql = "INSERT INTO survey_choices (survey_id, question_number, soundfile, choices) VALUES ($survey_id, $question_number, $soundfile, $choices)";
    mysql_query($sql);
    exit(0);
}
if (isset($_GET['display_message'])) {
    require "admin/db_config.php";
    require "functions/sanitize.php";
    ?>
    <br />
    <center>Please select a message:<br />
    <br />
    <select name="message" id="message">
    <?
    $result = mysql_query("SELECT * FROM campaignmessage where filename like 'x-%'");
    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_assoc($result)) {
            echo '<option value="'.$row['filename'].'">'.$row['name'].'</option>';
        }
    }
    $result = mysql_query("SELECT question_number FROM survey_choices WHERE survey_id = ".sanitize($_GET['display_message'])." order by question_number desc limit 1");
    if (mysql_num_rows($result) > 0) {
        $next = mysql_result($result,0,0)+1;
    } else {
        $next = 1;
    }
    ?>
    </select>
    <br />
    <br />
    Acceptable Choices:<br />
    <input type="checkbox" value="0" class="chk">&nbsp;0
    <input type="checkbox" value="1" class="chk">&nbsp;1
    <input type="checkbox" value="2" class="chk">&nbsp;2<br />
    <input type="checkbox" value="3" class="chk">&nbsp;3
    <input type="checkbox" value="4" class="chk">&nbsp;4
    <input type="checkbox" value="5" class="chk">&nbsp;5<br />
    <input type="checkbox" value="6" class="chk">&nbsp;6
    <input type="checkbox" value="7" class="chk">&nbsp;7
    <input type="checkbox" value="8" class="chk">&nbsp;8<br />
    <input type="checkbox" value="9" class="chk">&nbsp;9
    <input type="checkbox" value="*" class="chk">&nbsp;*
    <input type="checkbox" value="#" class="chk">&nbsp;#<br />
    <input type="hidden" name="allvals" id="allvals" value="No keys accepted">
    <a href="#" onclick="$('#choices').append('<span class=\x22survey_choice\x22 id=\x22question_<?=$next?>\x22><b>File <?=$next?></b>&nbsp;'+$('#message option:selected').text()+'&nbsp;<b>Choices: </b>'+$('#allvals').val()+'<a href=\x22surveys.php?delete_choice=<?=$next?>&survey=<?=$_GET['display_message']?>\x22><img src=\x22images/delete.png\x22 alt=\x22Delete Choice\x22></a></span>');$.ajax({url: 'surveys.php?add_choice=1&survey=<?=$_GET['display_message']?>&filename='+$('#message option:selected').val()+'&question_number=<?=$next?>&choices='+$('#allvals').val()});closeMessage();">Add Question</a>
    <script type="text/javascript">
    $('.chk').click(function () {
                    var allVals = [];
                    $('.chk:checked').each(function() {allVals.push($(this).val());});
                    if (allVals.length > 0) {
                        $('#allvals').val(allVals.toString());
                    } else {                    
                        $('#allvals').val("No keys accepted");
                    }
                    }
                    );
    </script>
    <?
    exit(0);
}
require "header.php";
require "header_surveys.php";
if (isset($_GET['edit'])) {
    ?><script type="text/javascript" src="<?=$http_dir_name?>ajax/jquery.js"></script><?
    
    $result = mysql_query("SELECT * FROM surveys WHERE id = ".sanitize($_GET['edit']));
    if (mysql_num_rows($result) == 0) {
        echo "Incorrect ID";
    } else {
        $row = mysql_fetch_assoc($result);
        $name = $row['name'];
        $description = $row['description'];
        box_start(600);
        ?>
        <center>
        <form action="surveys.php?save_edit=1" method="post">
        <table>
        <tr>
        <td>Name</td>
        <td><input type="text" name="name" value="<?=$name?>"></td>
        </tr>
        <tr>
        <td>Description</td>
        <td><textarea name="description"><?=$description?></textarea></td>
        </tr>
        </table>
        </form>
        <h3>Choices</h3>        
        <a href="#" onclick="ko = new Date();displayMessage('surveys.php?display_message=<?=$_GET['edit']?>&x='+ko.getTime());"><img src="images/add.png" alt="Add Choice">&nbsp;Add Choice</a><br />
        
        <div id="choices">
        <?
        $delete_link_part1 = "<A href=\x22#\x22 onclick=\x22$('#question_";
        $delete_link_part2 = "').remove();\x22><img src=\x22images/delete.png\x22></a>";

        $result_choices = mysql_query("SELECT * FROM survey_choices WHERE survey_id = ".$row['id']." order by question_number");
        if (mysql_num_rows($result_choices) > 0) {
            while ($row_choices = mysql_fetch_assoc($result_choices)) {
                if ($row_choices['question_number'] == 0) {
                    // Invalid choice message
                    echo '<span class="survey_choice" id="question_'.$row_choices['question_number'].'"><b>Invalid Choice Message</b>&nbsp;';
                    $sql = "SELECT name FROM campaignmessage WHERE filename like ".sanitize($row_choices['soundfile']."%");
                    $result_file = mysql_query($sql) or die(mysql_error());
                    $message_name = mysql_result($result_file,0,0);
                    echo ''.$message_name.'';
                    
                    echo ''.$row_choices['choices'].'';
                    echo $delete_link."</span>";
                } else {
                    // Valid choice message
                    echo '<span class="survey_choice" id="question_'.$row_choices['question_number'].'">';
                    echo '<b>File '.$row_choices['question_number'].'</b>&nbsp;';
                    
                    $sql = "SELECT name FROM campaignmessage WHERE filename like ".sanitize($row_choices['soundfile']."%");
                    $result_file = mysql_query($sql) or die(mysql_error());
                    $message_name = mysql_result($result_file,0,0);
                    echo ''.$message_name.'&nbsp;';
                    
                    if (strlen($row_choices['choices']) > 0) {
                        echo '<b>Choices:</b>&nbsp;'.$row_choices['choices'].'';
                    } else {
                        echo '<b>No keys accepted</b>';
                    }
                    echo '<a href="surveys.php?delete_choice='.$row_choices['question_number'].'&survey='.$_GET['edit'].'"><img src="images/delete.png" alt="Delete Choice"></a>'."</span>";
                }
            }
        }
        echo "</div>";
        box_end();
    }
    require "footer.php";
    exit(0);
}

/* Standard display of existing surveys */
$result = mysql_query("SELECT * FROM surveys order by name");
if (mysql_num_rows($result) == 0) {
    echo "There are no surveys.  Click above to create one";
} else {
    /* Table header */
    echo '<center><table border="0" cellpadding="3" cellspacing="0">';
    echo '<tr height="10"><td class="theadl"></td><td class="thead">Survey Name</td><td class="thead">Questions</td><td class="thead">Delete</td><td class="theadr"></td></tr>';
    $toggle = false;
    while ($row = mysql_fetch_assoc($result)) {
        /* Alternate the display */
        if ($toggle){
            $toggle=false;
            $class=" class=\"tborder2\"  onmouseover=\"style.backgroundColor='#84DFC1';\" onmouseout=\"style.backgroundColor='#f8f8f8'\"   ";
        } else {
            $toggle=true;
            $class=" class=\"tborderx\"  onmouseover=\"style.backgroundColor='#84DFC1';\" onmouseout=\"style.backgroundColor='#f0f0f0'\" ";
        }
        echo "<tr $class>";
        echo "<td></td>";
        echo '<td><a href="surveys.php?edit='.$row['id'].'">'.$row['name'].'&nbsp;<img src="images/pencil.png" alt="Edit Survey"></a></td>';
        $result_choices = mysql_query("SELECT count(*) FROM survey_choices WHERE survey_id = ".$row['id']);
        echo "<td>".mysql_result($result_choices,0,0)."</td>";
        echo '<td><a href="surveys.php?delete='.$row['id'].'"><img src="images/delete.png" alt="Delete Survey"></td>';
        echo "<td></td></tr>";
    }
    echo '</table>';
}
require "footer.php";
?>