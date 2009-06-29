<?
require "header.php";
require "header_queue.php";
$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');


$_POST = array_map(mysql_real_escape_string,$_POST);
$_GET = array_map(mysql_real_escape_string,$_GET);

$sql = 'SELECT * FROM queue_table';
$result=mysql_query($sql, $link) or die (mysql_error());;
if (mysql_num_rows($result) == 0) {
    /* There are no queues in this system */
    box_start(320);
    echo "<center><img src=\"/images/icons/gtk-dialog-info.png\" border=\"0\" width=\"64\" height=\"64\"><br /><br /><b>You haven't created any queues</b><br /><br />You can create one by clicking the \"Add Queue Wizard\" button above";
    box_end();
} else {
    /* start of shadow */?>
    <table align="center"><tr><td><div class="example" id="v6"><div id="main"><div class="wrap1"><div class="wrap2"><div class="wrap3" align="center">

    <?/*                                <img class="xxx" src="images/ball.jpg" width="72" height="72" alt="demo" />*/?>
    <table class="table1" align="center" border="0" cellpadding="2" cellspacing="0">
    <TR>
    <TD CLASS="thead">
    Name
    </TD>
    <TD CLASS="thead">
    Strategy
    </TD>
    <TD CLASS="thead">
    Timeout
    </TD>
    <TD CLASS="thead">
    Members
    </TD>
    <TD CLASS="thead">
    </TD>
    </TR>
    <?
    //$campaigngroupid=mysql_result($result,0,'campaigngroupid');
    while ($row = mysql_fetch_assoc($result)) {

    $sql2 = 'SELECT count(*) FROM queue_member_table where queue_name = "'.$row[name].'"';
    $result2=mysql_query($sql2, $link) or die (mysql_error());;
    $count=mysql_result($result2,0,'count(*)');

    if ($toggle){
    $toggle=false;
    $class=" class=\"tborder2\"  onmouseover=\"style.backgroundColor='#84DFC1';\" onmouseout=\"style.backgroundColor='#f8f8f8'\"   ";
    } else {
    $toggle=true;
    $class=" class=\"tborderx\"  onmouseover=\"style.backgroundColor='#84DFC1';\" onmouseout=\"style.backgroundColor='#f0f0f0'\" ";
    }

    ?>
    <TR <?echo $class;?>>
    <TD>
    <?
    if (strlen($row[name])<15){
    echo "<A HREF=\"editqueue.php?name=".$row[name]."\"><img src=\"/images/pencil.png\" border=\"0\" align=\"right\" title=\"Edit\">".$row[name]."</A>";
    } else {
    echo "<A HREF=\"editqueue.php?name=".$row[name]."\"><img src=\"/images/pencil.png\" border=\"0\" align=\"right\" title=\"Edit\">".trim(substr($row[name],0,15))."...</A>";
    }
    ?>
    </TD>
    <TD>
    <a href="addagent.php?name=<?echo $row[name];?>">
    <img src="/images/group_add.png" align="left" border="0" title="Add a SIP account for an agent to connect to">
    </a>
    <a href="agents.php?name=<?echo $row[name];?>">
    <img src="/images/group.png" align="left" border="0" title="Show the agents for this queue">
    </a>
    <?echo $row[strategy];?>
    </TD>
    <TD>
    <?echo $row[timeout];?>
    </TD>
    <TD>
    <?echo $count;?>
    </TD>
    <TD>
    <a href="#" onclick="displaySmallMessage('includes/confirmDeleteQueue.php?name=<?echo $row[name];?>');return false"><IMG SRC="/images/delete.png" BORDER="0"></a><br>
    </TD>
    </TR>

    <?
    }
    ?>

    </TABLE>
    <?/*end of shadow */?>
</div></div></div></div></div></td></tr></table>
<?
}
require "footer.php";
?>
