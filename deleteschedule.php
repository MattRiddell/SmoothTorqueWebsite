<?

if (isset($_GET[sure])){

    $id=$_GET[id];
    $sql="DELETE FROM queue where queueID=$id";
    //$result=mysql_query($sql, $link) or die (mysql_error());;

    require "sql.php";
    $SMDB=new SmDB();
    $SMDB->executeUpdate($sql);
    header("Location: schedule.php");



    //include("schedule.php");
    exit;
}
require "header.php";
$campaigngroupid=$groupid;
?>
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2">
<TR><TD>
Are you Sure You want to delete this record?<BR><BR>
</TD></TR>
<TR><TD>
<?

$sql = 'SELECT * FROM queue WHERE queueID='.$_GET[id];
$row1=$SMDB->executeQuery($sql);
$row=$row1[0];
    echo "<CENTER><B>".$row[queuename]." - ".$row[details]."</B><BR><BR>";
    echo '<A HREF="deleteschedule.php?id='.$_GET[id].'&sure=yes">Yes, Delete it</A><BR>';
    echo '<A HREF="schedule.php">No, Don\'t Delete It</A></CENTER>';
?>
</TD></TR>
<TR><TD>

</TD></TR>
</TABLE>
</FORM>
<?
require "footer.php";
?>
