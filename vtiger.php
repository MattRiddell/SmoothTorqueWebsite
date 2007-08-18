<?
$stripfirstdigit=false;
if (isset($_GET[clid])){
if ($stripfirstdigit){
$_GET[clid]=substr($_GET[clid],1);
}
$link=mysql_connect("127.0.0.1:3307", "root",'') or die (mysql_error());
mysql_select_db("vtiger");
$phone=$_GET['clid'];
$replacearray=array("(",")","-","."," ","+");
$phone=str_replace($replacearray,"",$phone);
$query="SELECT contactid, phone,mobile FROM vtiger_contactdetails where LENGTH(phone)>0 OR LENGTH(mobile)>0";
//echo $query;
$result=mysql_query($query,$link) or die(mysql_error());
while ($row=mysql_fetch_assoc($result)){
	$checkNum[$i]=str_replace($replacearray,"",$row['phone']);
	$checkNum2[$i]=str_replace($replacearray,"",$row['mobile']);
	$checkId[$i]=$row['contactid'];
	if ($checkNum[$i]==$phone){
		$realcheckid=$checkId[$i];
	}
	if ($checkNum2[$i]==$phone){
		$realcheckid=$checkId[$i];
	}
	$i++;
}

header('Location: http://data.venturevoip.com:81/index.php?module=Notes&action=EditView&parenttab=Sales&contact_id='.$realcheckid);
} else {
?>
<FORM ACTION="/vtiger.php" METHOD="GET">
<INPUT TYPE="TEXT" NAME="clid" VALUE="001 800 385 7000">
<INPUT TYPE="SUBMIT" VALUE="Find Number">
<?
}
?>
