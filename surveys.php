<?
if (isset($_GET['display_message'])) {
    require "admin/db_config.php";
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
    $result = mysql_query("SELECT question_number FROM survey_choices order by question_number desc limit 1");
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
    <a href="#" onclick="$('#choices').append('<span class=\x22survey_choice\x22 id=\x22question_<?=$next?>\x22><b>File <?=$next?></b>&nbsp;'+$('#message option:selected').text()+'&nbsp;<b>Choices: </b>'+$('#allvals').val()+'</span>');closeMessage();">Add Question</a>
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
                    echo "</span><br />";
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
                    echo "</span><br />";
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