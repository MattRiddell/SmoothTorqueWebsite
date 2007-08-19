<?
$pagenum="1";
$campaigngroupid=$groupid;
if (isset($_POST[id])){

    require "sql.php";
    $SMDB=new SmDB();
    $id=$_POST[id];
    $name=$_POST[name];
    $description=$_POST[description];
    $messageid=$_POST[messageid];
    $messageid2=$_POST[messageid2];
    $messageid3=$_POST[messageid3];
    $sql="UPDATE campaign SET name='$name', description='$description', messageid='$messageid',messageid2='$messageid2',messageid3='$messageid3' WHERE id=$id";
    $SMDB->executeUpdate($sql);

    header("Location: campaigns.php");
    echo "redirected";
    exit;
}
require "header.php";
//require "header_campaign.php";

if (isset($_GET[id])){
?>


<FORM ACTION="editcampaign.php?id=<?echo $_GET[id];?>" METHOD="POST">
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2">
<?
//$sql = 'SELECT * FROM campaign WHERE groupid='.$campaigngroupid.' and id='.$_GET[id].'';
//$result=mysql_query($sql, $link) or die (mysql_error());;
//$campaigngroupid=mysql_result($result,0,'campaigngroupid');
require_once "PHPTelnet.php";

$telnet = new PHPTelnet();

// if the first argument to Connect is blank,
// PHPTelnet will connect to the local host via 127.0.0.1
$telnet = new PHPTelnet();
$result = $telnet->Connect();
//echo "CONNECTION REQUEST: ".$result."<BR>";
$x=10;
$count=0;
while (substr(trim($result),0,3)!="END") {
    $telnet->DoCommand('getallm', $result);
    //echo $result."<BR>";
    if (substr(trim($result),0,3)!="END"){
        $pieces = explode("\n",$result);
        //echo $result."<BR>";
        $row2[$count][id]= $pieces[0];
        $row2[$count][filename]= $pieces[1];
        $row2[$count][name]= $pieces[2];
        $row2[$count][description]= $pieces[3];
        $count++;
    }
}

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
        if ($_GET[id]==trim($row[id])) {

            ?>
            <TR><TD CLASS="thead">Campaign Name</TD><TD>
            <INPUT TYPE="HIDDEN" NAME="id" VALUE="<?echo $_GET[id];?>">
            <INPUT TYPE="TEXT" NAME="name" VALUE="<?echo $row[name];?>" size="60">
            </TD>
            </TR><TR><TD CLASS="thead">Campaign Description</TD><TD>
            <INPUT TYPE="TEXT" NAME="description" VALUE="<?echo $row[description];?>" size="60">
            </TD>
            <?

            ?>
            </TR><TR><TD CLASS="thead">Live Message</TD><TD>
            <SELECT name="messageid">
            <?
            for ($count2=0;$count2<$count;$count2++){
                $selected="";
                if ($row[messageid]==$row2[$count2][id]){
                    $selected=" SELECTED";
                }
                echo "<OPTION VALUE=\"".$row2[$count2][id]."\"$selected>".$row2[$count2][description]."</OPTION>";
            }
            ?>
            </SELECT>
            </TD>
            </TR><TR><TD CLASS="thead">Answer Machine Message</TD><TD>
            <SELECT name="messageid2">
            <?
            for ($count2=0;$count2<$count;$count2++){
                $selected="";
                if ($row[messageid2]==$row2[$count2][id]){
                    $selected=" SELECTED";
                }
                echo "<OPTION VALUE=\"".$row2[$count2][id]."\"$selected>".$row2[$count2][description]."</OPTION>";
            }
            ?>
            </SELECT>
            </TD>
            </TR><TR><TD CLASS="thead">Transfer Message</TD><TD>
            <SELECT name="messageid3">
            <?
            for ($count2=0;$count2<$count;$count2++){
                $selected="";
                if ($row[messageid3]==$row2[$count2][id]){
                    $selected=" SELECTED";
                }
                echo "<OPTION VALUE=\"".$row2[$count2][id]."\"$selected>".$row2[$count2][description]."</OPTION>";
            }
            ?>
            </SELECT>
            </TD>
            </TR>
            </TR><TR><TD COLSPAN=2 ALIGN="RIGHT">
            <INPUT TYPE="SUBMIT" VALUE="Save Changes">
            </TD>
            </TR>
            <?
        }

    }
}
?>





</TABLE>
</FORM>
<?
} else {




?>
<form action="editcampaign.php" method="get">
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2"><TR>
    <TD>Select Campaign:</TD><TD>    <select name="id">

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
</td></tr><tr><td colspan=2>
<INPUT TYPE="SUBMIT" VALUE="Edit Campaign">
</form>
<?
}
require "footer.php";
?>
