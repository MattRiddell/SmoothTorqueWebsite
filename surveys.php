<?
if (isset($_GET['save_edit'])) {
    require "admin/db_config.php";
    require "functions/sanitize.php";
    $response['is_error'] = false;
    $response['error_string'] = "";
    $sql = "UPDATE surveys SET ".sanitize($_POST['id'], false)."=".sanitize($_POST['new_value'])." WHERE id = ".sanitize($_GET['save_edit']);
    $result = mysql_query($sql);
    $response['html'] = $_POST['new_value'];
    echo json_encode($response);
    exit(0);
}
if (isset($_GET['delete_choice'])) {
    require "header.php";
    $result = mysql_query("DELETE FROM survey_choices WHERE survey_id = ".sanitize($_GET['survey'])." AND question_number = ".sanitize($_GET['delete_choice']));
    $result = mysql_query("UPDATE survey_choices SET question_number = question_number - 1 WHERE survey_id = ".sanitize($_GET['survey'])." AND question_number > ".sanitize($_GET['delete_choice']));
    ?><center><img src="images/progress.gif" border="0"><br />Removing choice...
    <META HTTP-EQUIV=REFRESH CONTENT="1; URL=surveys.php?edit=<?=$_GET['survey']?>"><?

    require "footer.php";
    exit(0);
}
if (isset($_GET['change_choice'])) {
    require "admin/db_config.php";
    require "functions/sanitize.php";
    $result = mysql_query("UPDATE survey_choices SET soundfile = ".sanitize($_GET['change_choice'])." WHERE question_number = ".sanitize($_GET['question'])." AND survey_id = ".sanitize($_GET['survey']));
    exit(0);
}
if (isset($_GET['change_invalid'])) {
    require "admin/db_config.php";
    require "functions/sanitize.php";
    $result = mysql_query("UPDATE survey_choices SET soundfile = ".sanitize($_GET['change_invalid'])." WHERE question_number = 0 AND survey_id = ".sanitize($_GET['survey']));
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
            echo '<option value="'.substr($row['filename'],0,strlen($row['filename'])-4).'">'.$row['name'].'</option>';
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
    <input type="button" value="Add Question" onclick="$('#choices').append('<span class=\x22survey_choice\x22 id=\x22question_<?=$next?>\x22><b>File <?=$next?></b>&nbsp;'+$('#message option:selected').text()+'&nbsp;<b>Choices: </b>'+$('#allvals').val()+'<a href=\x22surveys.php?delete_choice=<?=$next?>&survey=<?=$_GET['display_message']?>\x22><img src=\x22images/delete.png\x22 alt=\x22Delete Choice\x22></a></span>');$.ajax({url: 'surveys.php?add_choice=1&survey=<?=$_GET['display_message']?>&filename='+$('#message option:selected').val()+'&question_number=<?=$next?>&choices='+$('#allvals').val()});closeMessage();">
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
if (isset($_GET['delete_sure'])) {
    $result = mysql_query("DELETE FROM survey_choices WHERE survey_id = ".sanitize($_GET['delete_sure']));
    $result = mysql_query("DELETE FROM surveys WHERE id = ".sanitize($_GET['delete_sure']));
    ?><center><img src="images/progress.gif" border="0"><br />Deleting survey...
    <META HTTP-EQUIV=REFRESH CONTENT="1; URL=surveys.php"><?
    require "footer.php";
    exit(0);    
}
if (isset($_GET['delete'])) {
    box_start(400);
    echo "<center><br />";
    echo "Are you sure you want to delete this survey?<br />";
    echo "<br />";
    $name = mysql_result(mysql_query("SELECT name FROM surveys WHERE id = ".sanitize($_GET['delete'])),0,0);
    echo "<h3>$name</h3>";
    echo '<a href="surveys.php?delete_sure='.$_GET['delete'].'">Yes, I am sure, delete it</a><br /><br />';
    echo '<a href="surveys.php">No, do not delete it</a><br />';
    require "footer.php";
    exit(0);
}
if (isset($_GET['save_add'])) {
    $result = mysql_query("INSERT INTO surveys (name, description) VALUES (".sanitize($_POST['name']).",".sanitize($_POST['description']).")") or die(mysql_error());
    $id = mysql_insert_id();
    
    ?><center><img src="images/progress.gif" border="0"><br />Adding survey...
    <META HTTP-EQUIV=REFRESH CONTENT="1; URL=surveys.php?edit=<?=$id?>"><?

    require "footer.php";
    exit(0);
}
if (isset($_GET['add'])) {
    box_start(400);
    ?>    
    <center>
    
    <form action = "surveys.php?save_add=1" method="post">
    <table>
    <tr><td>Name: </td><td><input type="text" name="name"></td></tr>
    <tr><td>Description: </td><td><input type="text" name="description"></td></tr>
    <tr><td colspan="2"><input type="submit" value="Add Survey"></td></tr>
    </table>
    
    </form>
    <?
    box_end();
    require "footer.php";
    exit(0);
}
if (isset($_GET['edit'])) {
    
    ?>
    <script src="js/jquery.min.1.6.3.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?=$http_dir_name?>js/jeip.js"></script>
    <?
    
    $result = mysql_query("SELECT * FROM surveys WHERE id = ".sanitize($_GET['edit']));
    if (mysql_num_rows($result) == 0) {
        echo "Incorrect ID";
    } else {
        $row = mysql_fetch_assoc($result);
        $name = $row['name'];
        $description = $row['description'];
        if (strlen(trim($name)) == 0) {
            $name = "No Name";
        }
        if (strlen(trim($description)) == 0) {
            $description = "No Description";
        }
        box_start(600);
        ?>
        <center>
    
        
        <center>
        <form action="surveys.php?save_edit=1" method="post">
        <table>
        <tr>
        <td><b>Name: </b></td>
        <td><span id="name" ><?=$name?></span><img src="images/pencil.png"></td>
        </tr>
        <tr>
        <td><b>Description</b></td>
        <td><span id="description" ><?=$description?></span><img src="images/pencil.png"></textarea></td>
        </tr>
        </table>
        </form>
        <h3>Choices</h3>        
        <a href="#" onclick="ko = new Date();displaySmallMessage('surveys.php?display_message=<?=$_GET['edit']?>&x='+ko.getTime());"><img src="images/add.png" alt="Add Choice">&nbsp;Add Choice</a><br />
        <script>
        $( "#name" ).eip( "surveys.php?save_edit=<?=$_GET['edit']?>", { select_text: true });
        $( "#description" ).eip( "surveys.php?save_edit=<?=$_GET['edit']?>", { select_text: true , form_type: "textarea"});
        </script>
        
        <div id="choices">
        <?
        //$delete_link_part1 = "<A href=\x22#\x22 onclick=\x22$('#question_";
        //$delete_link_part2 = "').remove();\x22><img src=\x22images/delete.png\x22></a>";
        $zero_result = mysql_query("SELECT * FROM survey_choices WHERE survey_id = ".$row['id']." AND question_number = 0");
        if (mysql_num_rows($zero_result) == 0) {
            mysql_query("INSERT INTO survey_choices (survey_id, question_number) VALUES (".$row['id'].",0)");
        }
        $result_choices = mysql_query("SELECT * FROM survey_choices WHERE survey_id = ".$row['id']." order by question_number");
        if (mysql_num_rows($result_choices) > 0) {
            while ($row_choices = mysql_fetch_assoc($result_choices)) {
                if ($row_choices['question_number'] == 0) {
                    // Invalid choice message
                    echo '<span class="survey_choice" id="question_'.$row_choices['question_number'].'"><b>Invalid Choice Message:</b>&nbsp;';
                    
                    $result_x = mysql_query("SELECT * FROM campaignmessage WHERE filename like 'x-%'");
                    echo '<select name="invalid_message" id="invalid_message" onchange="$.ajax({url: \'surveys.php?change_invalid=\'+$(\'#invalid_message option:selected\').val()+\'&survey='.$_GET['edit'].'\'});">';
                    while ($row_x = mysql_fetch_assoc($result_x)) {
                        echo '<option value="'.substr($row_x['filename'],0,strlen($row_x['filename'])-4).'"';
                        if (substr($row_x['filename'],0,strlen($row_choices['soundfile'])) == $row_choices['soundfile']) {
                            echo " selected ";
                        } 
                        echo '>'.$row_x['name'].'</option>';
                    }
                    echo "</select>";
/*                    $sql = "SELECT name FROM campaignmessage WHERE filename like ".sanitize($row_choices['soundfile']."%");
                    $result_file = mysql_query($sql) or die(mysql_error());
                    $message_name = mysql_result($result_file,0,0);
                    echo ''.$message_name.'';
                    
                    echo ''.$row_choices['choices'].'';*/
                    echo $delete_link."</span>";
                } else {
                    // Valid choice message
                    echo '<span class="survey_choice" id="question_'.$row_choices['question_number'].'">';
                    echo '<b>File '.$row_choices['question_number'].'</b>&nbsp;';
                    
                    $result_x = mysql_query("SELECT * FROM campaignmessage WHERE filename like 'x-%'");
                    echo '<select name="choice_'.$row_choices['question_number'].'" id="choice_'.$row_choices['question_number'].'" onchange="$.ajax({url: \'surveys.php?question='.$row_choices['question_number'].'&change_choice=\'+$(\'#choice_'.$row_choices['question_number'].' option:selected\').val()+\'&survey='.$_GET['edit'].'\'});">';
                    while ($row_x = mysql_fetch_assoc($result_x)) {
                        echo '<option value="'.substr($row_x['filename'],0,strlen($row_x['filename'])-4).'"';
                        if (substr($row_x['filename'],0,strlen($row_choices['soundfile'])) == $row_choices['soundfile']) {
                            echo " selected ";
                        } 
                        echo '>'.$row_x['name'].'</option>';
                    }
                    echo "</select>&nbsp;";
                    
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