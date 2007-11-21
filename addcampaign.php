<?
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);

$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');
if (isset($_POST[name])){
    $id=$_POST[id];
    $name=$_POST[name];
    $description=$_POST[description];
    $messageid=$_POST[messageid];
    $messageid2=$_POST[messageid2];
    $messageid3=$_POST[messageid3];
    $sql="INSERT INTO campaign (groupid,name,description,messageid,messageid2,messageid3) VALUES ('$campaigngroupid','$name', '$description', '$messageid','$messageid2','$messageid3')";
//    echo $sql;
    $result=mysql_query($sql, $link) or die (mysql_error());;
    include("campaigns.php");
    exit;
}
require "header.php";
require "header_campaign.php";
?>
                              
<FORM ACTION="addcampaign.php" METHOD="POST">
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2">
<?
?>
<TR><TD CLASS="thead">Campaign Name</TD><TD>
<INPUT TYPE="HIDDEN" NAME="id" VALUE="<?echo $_GET[id];?>">
<INPUT TYPE="TEXT" NAME="name" VALUE="<?echo $row[name];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">Campaign Description</TD><TD>
<INPUT TYPE="TEXT" NAME="description" VALUE="<?echo $row[description];?>" size="60">
</TD>
</TR>
<?
$sql="SELECT * from campaignmessage";
$result=mysql_query($sql,$link) or die (mysql_error());
$count=0;
while ($row2[$count] = mysql_fetch_assoc($result)) {
    $count++;
    //echo "<OPTION VALUE=\"".$row[id]."\">".$row[name]."</OPTION>";
}
?>

<TR><TD CLASS="thead">Live Message</TD><TD>
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
<INPUT TYPE="SUBMIT" VALUE="Add Campaign">
</TD>
</TR>
<?
?>

</TABLE>
</FORM>
<?
require "footer.php";
?>

