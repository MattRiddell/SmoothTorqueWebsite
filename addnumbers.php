<?
require "header.php";
require "header_numbers.php";

$link = mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);

$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');

if (!isset($_POST[campaignid])){
    ?>
    <FORM ACTION="addnumbers.php" METHOD="POST">
    <table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2"><TR>
    <TD>Select Campaign:</TD><TD>
        <SELECT NAME="campaignid">
        <?
        //
        $sql = 'SELECT id,name FROM campaign WHERE groupid='.$campaigngroupid;
        $result=mysql_query($sql, $link) or die (mysql_error());;
        //$campaigngroupid=mysql_result($result,0,'campaigngroupid');
        while ($row = mysql_fetch_assoc($result)) {
            echo "<OPTION VALUE=\"".$row[id]."\">".$row[name]."</OPTION>";
        }
        ?>
        </SELECT>

    </TD>
    </TR><TR>
    <TD COLSPAN=2 ALIGN="CENTER">
    <INPUT TYPE="SUBMIT" VALUE="Select Campaign">
    </TD>
    </TR></table>
    </FORM>
    <?
} else {
 if (isset($_POST[start])){ 
    echo "Please Wait, saving data to the database.<BR><BR>This may take some time...<BR><BR>";
    /*for ($i=$_POST[start];$i<=$_POST[end];$i++){       */
    $split= split("\n",$_POST[start]);
    foreach ($split as $number){
        //$myarray[$count]=$i;
        if (strlen($number>0)){
        $sql="INSERT IGNORE INTO number (campaignid,phonenumber,status,type) VALUES ($_POST[campaignid],$number,'new',0)";
        $result=mysql_query($sql, $link) or die (mysql_error());;
        echo "<!-- . -->";  
        flush();
        }
    }
    echo "<BR>Save Completed<BR>";
 } else {
?>
                              
<FORM ACTION="addnumbers.php" METHOD="POST">
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2">
<?
?>
<TR><TD CLASS="thead">Enter Numbers (One Per Line)</TD></TR>
<TR><TD>
<INPUT TYPE="HIDDEN" NAME="campaignid" VALUE="<?echo $_POST[campaignid];?>">
<TEXTAREA NAME="start" COLS="20" ROWS="20"></TEXTAREA>
</TD>
</TR>
<TR><TD COLSPAN=2 ALIGN="RIGHT">
<INPUT TYPE="SUBMIT" VALUE="Add Numbers">
</TD>
</TR>
<?
?>

</TABLE>
</FORM><?
}}
require "footer.php";
?>

