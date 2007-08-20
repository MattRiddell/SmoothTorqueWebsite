<?
$pagenum="3";
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
$telnet = new PHPTelnet();
$result = $telnet->Connect();
while (substr(trim($result),0,3)!="END") {
    $telnet->DoCommand('getallca', $result);
    if (substr(trim($result),0,3)!="END"){
        $pieces = explode("\n",$result);
        $row[id]= $pieces[0];
        $row[description]= $pieces[1];
        $row[name]= $pieces[2];
        $row[campaigngroupid]= $pieces[3];
        $row[messageid]= $pieces[4];
        $row[messageid2]= $pieces[5];
        $row[messageid3]= $pieces[6];
        echo $result."<BR>";
        if ($groupid==trim($row[campaigngroupid])){
            echo "<option value=\"$row[id]\">$row[name]</option>
";
        } else {
//            echo $groupid."!=".$row[campaigngroupid];
        }

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
    //echo $i."<BR>";
    $myarray[$count]=$i;
    $sql="INSERT INTO number (campaignid,phonenumber,status) VALUES ($_POST[campaignid],$i,'new')";
//    $result=mysql_query($sql, $link) or die (mysql_error());;
    $SMDB->executeUpdate($sql);

    $count++;
    $count2++;
    echo "<!-- . -->";
    if ($count2>($_POST[end]-$_POST[start])/100){
        if ($count3>9){
            echo (round($count/($_POST[end]-$_POST[start])*100)-1)."% ";
            echo "<BR>";
            $count3=0;
        }
        $count3++;

//        echo round($count/($_POST[end]-$_POST[start])*100)."% ";

        echo "|";
        flush();
        $count2=0;
    }

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
