<?
require "header.php";
include "admin/db_config.php";
mysql_select_db("SineDialer", $link);
if (!isset($_POST[campaignid])&&!isset($_GET[campaignid])){
    ?>

   <link rel="stylesheet" type="text/css" href="/css/default.css">

    <br /><br /><br /><br />
<center>
<table background="images/sdbox.png" width="300" height="200" class="dragme22">
<tr>
<td>
</td>
<td width="260">
<b>Manual Dialing</b><br /><br />
From here you can chose a campaign that you would like to see the numbers for.<br /><br />
<FORM ACTION="manualdial.php" METHOD="GET">
    <table class="tborderdd" align="center" border="0" cellpadding="0" cellspacing="2"><TR>
    <TD>Select Campaign:</TD><TD>
        <SELECT NAME="campaignid">
        <?
        $sql = 'SELECT id,name FROM campaign';
        $result=mysql_query($sql, $link) or die (mysql_error());;
        //$campaigngroupid=mysql_result($result,0,'campaigngroupid');
        while ($row = mysql_fetch_assoc($result)) {
            echo "<OPTION VALUE=\"".$row[id]."\">".substr($row[name],0,22)."</OPTION>";
        }
        ?>
        </SELECT>

    </TD>
    </TR><TR>
    <TD COLSPAN=2 ALIGN="CENTER"><br />
    <INPUT TYPE="SUBMIT" VALUE="Display Numbers">
    </TD>
    </TR></table>
    </FORM><br />
</td>
<td>
</td></tr>
</table>
</center>







    <?
} else {
if (isset($_GET[campaignid])){
    $_POST[campaignid]=$_GET[campaignid];
}
// Ok, so we have a campaign id to use numbers from
// How many numbers has it got?
$result = mysql_query("UPDATE number set status = \"manual_dial\" where campaignid=$_POST[campaignid] AND status = 'new'") or die(mysql_error());
echo "<br /><br />Manual Dialing Campaign Initiation Complete";
?>
<META HTTP-EQUIV=REFRESH CONTENT="3; URL=campaigns.php">
<?
}?>








