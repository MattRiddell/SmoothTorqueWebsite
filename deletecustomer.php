<?
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);

if (isset($_GET[sure])){
    $id=mysql_real_escape_string($_GET[id]);
    $sql="DELETE FROM customer where id=$id limit 1";
    $result=mysql_query($sql, $link) or die (mysql_error());;
    include("customers.php");
    exit;
}
require "header.php";
require "header_customer.php";

?>
<table class="tborder" align="center" border="0" cellpadding="0" cellspacing="2">
<TR><TD>
Are you Sure You want to delete this record?<BR><BR>
</TD></TR>
<TR><TD>
<?
/*
   seeing as the id is unique (given that it's the primary key), this means that we can only ever get 
   one user given any ID (and thus the limit 1), there doesn't seem to much point for the while loop below
*/
$sql = 'SELECT * FROM customer WHERE id='.mysql_real_escape_string($_GET[id]).' limit 1';
$result=mysql_query($sql, $link) or die (mysql_error());;
while ($row = mysql_fetch_assoc($result)) {
    echo "<CENTER><B>".$row[company]." - ".$row[city]."</B><BR><BR>";
    echo '<A HREF="deletecustomer.php?id='.imysql_real_escape_string($_GET[id]).'&sure=yes">Yes, Delete it</A><BR>';
    echo '<A HREF="customers.php">No, Don\'t Delete It</A></CENTER>';
?>
</TD></TR>
<TR><TD>

</TD></TR>
</TABLE>
</FORM>
<?
}
require "footer.php";
?>

