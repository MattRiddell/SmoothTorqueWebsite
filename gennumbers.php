<?
$pagenum="2";
require "header.php";


//$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
//$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=$groupid;

if (!isset($_POST[campaignid])){
    ?>
    <FORM ACTION="gennumbers.php" METHOD="POST">
    <table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2"><TR>
    <TD>Select Campaign:</TD><TD>
        <select name="campaignid">
<?
$lines2=file("/tmp/Sm1.allCampaigns");
$name="";
foreach ($lines2 as $line_num => $line) {
//              echo $line;
        if (substr($line,0,4)=="id =") {
                $id=trim(substr($line,5));
        } else if (substr($line,0,4)=="grou") {
                   if (trim(substr($line,10))==$groupid){
                    echo "".$name;
                } else {
                    echo "[".trim(substr($line,10))."]";
                }
        } else if (substr($line,0,4)=="name") {
                $name= "<option value=\"$id\">".trim(substr($line,6))."</option>
";
        }
}
?>
</select>

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
 $count=0;
 $count2=0;
 echo "Please Wait, saving data to the database.<BR><BR>This may take some time...<BR><BR>";
 for ($i=$_POST[start];$i<=$_POST[end];$i++){
    echo $i;
    $myarray[$count]=$i;
    $sql="INSERT IGNORE INTO number (campaignid,phonenumber,status,type) VALUES ($_POST[campaignid],$i,'new',0)";
//    $result=mysql_query($sql, $link) or die (mysql_error());;

/*    $count++;
    $count2++;
    echo "<!-- . -->";
    if ($count2>($_POST[end]-$_POST[start])/100){
        if ($count3>10){
            echo "<BR>";
            $count3=0;
            }
                    $count3++;

            echo round($count/($_POST[end]-$_POST[start])*100)."% ";
        flush();
        $count2=0;
    }
    */
 }

// print_r($myarray);
 } else {
?>

<FORM ACTION="gennumbers.php" METHOD="POST">
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2">
<?
?>
<TR><TD CLASS="thead"><font face="arial">Start Number</TD><TD>
<INPUT TYPE="HIDDEN" NAME="campaignid" VALUE="<?echo $_POST[campaignid];?>">
<INPUT TYPE="TEXT" NAME="start" VALUE="16035500000" size="20">
</TD>
</TR><TR><TD CLASS="thead"><font face="arial">End Number</TD><TD>
<INPUT TYPE="TEXT" NAME="end" VALUE="16035599999" size="20">
</TD>
</TR>
<TR><TD COLSPAN=2 ALIGN="RIGHT">
<INPUT TYPE="SUBMIT" VALUE="Generate Numbers">
</TD>
</TR>
<?
?>

</TABLE>
</FORM><?
}}
require "footer.php";
?>
