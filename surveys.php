<?
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
        <a href="#"><img src="images/add.png" alt="Add Choice">&nbsp;Add Choice</a><br />
        <div id="choices">
        <?
        $result_choices = mysql_query("SELECT * FROM survey_choices WHERE survey_id = ".$row['id']." order by question_number");
        if (mysql_num_rows($result_choices) > 0) {
            while ($row_choices = mysql_fetch_assoc($result_choices)) {
                if ($row_choices['question_number'] == 0) {
                    // Invalid choice message
                    echo '<span class="survey_choice" id="question_'.$row_choices['question_number'].'">Invalid Choice Message&nbsp;';
                    echo ''.$row_choices['soundfile'].'';
                    echo ''.$row_choices['choices'].'';
                    echo "</span><br />";
                } else {
                    // Valid choice message
                    echo '<span class="survey_choice" id="question_'.$row_choices['question_number'].'">';
                    echo ''.$row_choices['question_number'].'&nbsp;';
                    echo ''.$row_choices['soundfile'].'&nbsp;';
                    echo ''.$row_choices['choices'].'';
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