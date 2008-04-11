<?
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);

if (isset($_POST['enabled'])){
$values = $_POST['enabled'];
foreach ($values as $a){
    $adminlists.=$a.",";
}
$adminlists = substr($adminlists,0,strlen($adminlists)-1);


//exit(0);
//$_POST = array_map(mysql_real_escape_string($_POST));

$description=$_POST[description];
$username=$_POST[username];
$password=sha1($_POST[password]);
$address1=$_POST[address1];
$address2=$_POST[address2];
$city=$_POST[city];
$country=$_POST[country];
$phone=$_POST[phone];
$fax=$_POST[fax];
$email=$_POST[email];
$website=$_POST[website];
$security=$_POST[security];
$company=$_POST[name];
$trunkid=$_POST[trunkid];
$zip=$_POST[zip];
$state=$_POST[state];
$maxcps=$_POST[maxcps];
$maxchans=$_POST[maxchans];

    $sql="update campaigngroup set name='$company',description='$description' where id=".$_POST[campaigngroupid];
//    echo $sql;
    $result=mysql_query($sql, $link) or die (mysql_error());;
  //  $insertedID = mysql_insert_id();

    $sql="update customer set username='$username',address1='$address1',address2='$address2',
    city='$city',country='$country',phone='$phone',fax='$fax',email='$email',website='$website',
    security='$security',company='$company', trunkid='$trunkid', zip='$zip', state='$state' , maxcps=$maxcps, maxchans=$maxchans, adminlists='$adminlists' WHERE id=".$_POST[id];

    //echo $sql;
    $result=mysql_query($sql, $link) or die (mysql_error());;



    include("customers.php");
    exit;
}
require "header.php";
Require "header_customer.php";

$sql = 'SELECT * FROM customer WHERE id='.$_GET[id];
$result=mysql_query($sql, $link) or die (mysql_error());;
while ($row = mysql_fetch_assoc($result)) {

$sql2 = 'SELECT * FROM campaigngroup WHERE id='.$row[campaigngroupid];
$result2=mysql_query($sql2, $link) or die (mysql_error());;

$row2 = mysql_fetch_assoc($result2);

?>

<FORM ACTION="editcustomer.php" METHOD="POST" name="customer">
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2">
<?
?>
<TR><TD CLASS="thead">Customer Name</TD><TD colspan=2>
<INPUT TYPE="HIDDEN" NAME="id" VALUE="<?echo $_GET[id];?>">
<INPUT TYPE="HIDDEN" NAME="campaigngroupid" VALUE="<?echo $row[campaigngroupid];?>">

<INPUT TYPE="TEXT" NAME="name" VALUE="<?echo $row[company];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">Customer Details</TD><TD colspan=2>
<INPUT TYPE="TEXT" NAME="description" VALUE="<?echo $row2[description];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">Maximum Calls Per Second</TD><TD colspan=2>
<INPUT TYPE="TEXT" NAME="maxcps" VALUE="<?echo $row[maxcps];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">Maximum Channels</TD><TD colspan=2>
<INPUT TYPE="TEXT" NAME="maxchans" VALUE="<?echo $row[maxchans];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">Username</TD><TD colspan=2>
<INPUT TYPE="TEXT" NAME="username" VALUE="<?echo $row[username];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">Address Line 1</TD><TD colspan=2>
<INPUT TYPE="TEXT" NAME="address1" VALUE="<?echo $row[address1];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">Address Line 2</TD><TD colspan=2>
<INPUT TYPE="TEXT" NAME="address2" VALUE="<?echo $row[address2];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">City</TD><TD colspan=2>
<INPUT TYPE="TEXT" NAME="city" VALUE="<?echo $row[city];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">State</TD><TD colspan=2>
<INPUT TYPE="TEXT" NAME="state" VALUE="<?echo $row[state];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">Zip</TD><TD colspan=2>
<INPUT TYPE="TEXT" NAME="zip" VALUE="<?echo $row[zip];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">Country</TD><TD colspan=2>
<INPUT TYPE="TEXT" NAME="country" VALUE="<?echo $row[country];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">Phone</TD><TD colspan=2>
<INPUT TYPE="TEXT" NAME="phone" VALUE="<?echo $row[phone];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">Fax</TD><TD colspan=2>
<INPUT TYPE="TEXT" NAME="fax" VALUE="<?echo $row[fax];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">Email</TD><TD colspan=2>
<INPUT TYPE="TEXT" NAME="email" VALUE="<?echo $row[email];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">Website</TD><TD colspan=2>
<INPUT TYPE="TEXT" NAME="website" VALUE="<?echo $row[website];?>" size="60">
</TD>
</TR><TR><TD CLASS="thead">Customer Type</TD><TD colspan=2>
<SELECT NAME="security">
<OPTION VALUE="0" <?if ($row[security]==0){echo "SELECTED";}?>>Normal Customer</OPTION>
<OPTION VALUE="10" <?if ($row[security]==10){echo "SELECTED";}?>>Accounts Management</OPTION>
<OPTION VALUE="100" <?if ($row[security]==100){echo "SELECTED";}?>>Administrator</OPTION>
</SELECT>
</TD>
</TR><TR><TD CLASS="thead">Queue Name</TD><TD colspan=2>
<SELECT NAME="astqueuename">
<?
$resultss=mysql_query("SELECT name from queue_table",$link);
while ($rowx = mysql_fetch_assoc($resultss)) {
//    echo ."<BR>";
    ?>
<OPTION VALUE="<?echo $rowx[name];?>" <?if ($row[astqueuename]==$rowx[name]){echo "SELECTED";}?>><?echo $rowx[name];?></OPTION>
<?
}
?>
</SELECT>
<a href="queues.php"><IMG SRC="/images/pencil.png" border="0"></a>
</TD>
</TR><TR><TD CLASS="thead">Trunk</TD><TD colspan=2>
<SELECT NAME="trunkid">
<?
$resultss=mysql_query("SELECT name,id from trunk",$link);
?>
<OPTION VALUE="-1" <?if ($row[trunkid]==-1){echo "SELECTED";}?>>Default</OPTION>
<?
while ($rowx = mysql_fetch_assoc($resultss)) {
//    echo ."<BR>";
    ?>
<OPTION VALUE="<?echo $rowx[id];?>" <?if ($row[trunkid]==$rowx[id]){echo "SELECTED";}?>><?echo $rowx[name];?></OPTION>
<?
}
?>
</SELECT>
<a href="trunks.php"><IMG SRC="/images/pencil.png" border="0"></a>
</TD>
</TR>
<tr><TD CLASS="thead" colspan="3">Assigned Lead Lists</TD>
</tr>
<tr><TD>
<?
$rows = explode(",",$row['adminlists']);
?>
<select name="enabled[]" id="enabled" size="5" multiple style="width: 200px;">
      <?
$resultss2=mysql_query("SELECT distinct(campaignid) from number where campaignid<0",$link);
while ($rowx2 = mysql_fetch_assoc($resultss2)) {
    $resultss3=mysql_query("SELECT name from campaign where id=".(0-$rowx2[campaignid]),$link);
    $found = 0;
    foreach ($rows as $a){
        if ($a == (0-$rowx2[campaignid])) {
            echo "Found";
            $found = 1;
        }
    }
    if ($found == 1) {
        echo '<option value="'.(0-$rowx2[campaignid]).'">'.mysql_result($resultss3,0,0).'</option>   ';
    }


}
?>
    </select>
</td><TD>
    <input type="button" name="Disable" value="&nbsp;&nbsp;&nbsp; Remove -&gt; " style="width: 100px;"
onClick="MoveOption(this.form.enabled, this.form.disabled)"><br>
    <br>
    <input type="button" name="Enable" value=" &lt;- Add &nbsp;&nbsp;&nbsp;" style="width: 100px;"
onClick="MoveOption(this.form.disabled, this.form.enabled)"><br>

</td><TD>
<select name="disabled[]" id="disabled" size="5" multiple style="width: 200px;">
     <?
//$sqlx = "SELECT adminlists from customer WHERE customerid=".

//$rows = explode (",",select adminlists from customer
$resultss2=mysql_query("SELECT distinct(campaignid) from number where campaignid<0",$link);
while ($rowx2 = mysql_fetch_assoc($resultss2)) {
    $resultss3=mysql_query("SELECT name from campaign where id=".(0-$rowx2[campaignid]),$link);
    $found = 0;
    foreach ($rows as $a){
        if ($a == (0-$rowx2[campaignid])) {
            //echo "Found";
            $found = 1;
        }
    }
    if ($found == 0) {
        echo '<option value="'.(0-$rowx2[campaignid]).'">'.mysql_result($resultss3,0,0).'</option>   ';
    }


}
?>    </select>



</td></tr>
</TR><TR><TD COLSPAN=3 ALIGN="RIGHT">
<INPUT TYPE="SUBMIT" VALUE="Save Customer" onclick="f_selectAll('enabled[]')">

</TD>
</TR>
<?
?>

</TABLE>
</FORM>
<?
}
require "footer.php";
?>
