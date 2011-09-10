<?
require "header.php";
require "header_surveys.php";
$result = mysql_query("SELECT * FROM surveys order by name");
if (mysql_num_rows($result) == 0) {
    echo "There are no surveys.  Click above to create one";
} else {
    echo '<center><table border="0" cellpadding="3" cellspacing="0">';
    echo '<tr height="10"><td class="theadl"></td><td class="thead">Survey Name</td><td class="thead">Questions</td><td class="thead">Delete</td><td class="theadr"></td></tr>';
    $toggle = false;
    while ($row = mysql_fetch_assoc($result)) {
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
        $result_questions = mysql_query("SELECT count(*) FROM survey_choices WHERE survey_id = ".$row['id']);
        echo "<td>".mysql_result($result_questions,0,0)."</td>";
        echo '<td><a href="surveys.php?delete='.$row['id'].'"><img src="images/delete.png" alt="Delete Survey"></td>';
        echo "<td></td></tr>";
    }
    echo '</table>';
}
require "footer.php";
?>