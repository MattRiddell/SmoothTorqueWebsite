<?
include "admin/db_config.php";
require "header.php";
mysql_select_db("SineDialer", $link);
if (!isset($_POST[campaignid])&&!isset($_GET[campaignid])){
    ?>

   <link rel="stylesheet" type="text/css" href="/css/default.css">

    <br /><br /><br /><br />
<center>
<table background="/images/sdbox.png" width="300" height="200" class="dragme22">
<tr>
<td>
</td>
<td width="260">
<b>Manual Dialing</b><br /><br />
Please select a campaign<br /><br />
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
    <INPUT TYPE="SUBMIT" VALUE="Select Campaign">
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
echo "<br /><br />";

if (isset($_GET[campaignid])){
    $_POST[campaignid]=$_GET[campaignid];
}
// Ok, so we have a campaign id to use numbers from
// How many numbers has it got?

$out=_get_browser();
if ($out[browser]=="MSIE"){
?>

 <?
flush();
?>
<script type="text/javascript" src="/ajax/jquery.js"></script>
<script type="text/javascript">
        $(function(){ // jquery onload
                window.setInterval(function(){
                    $('#ajaxDiv').loadIfModified('number_status.php?campaignid=<?echo $_POST[campaignid];?>&number=<?=$_GET[dialled];?>');  // jquery ajax load into div
                },1000);
        });

</script>
<?} else {?>
<script type="text/javascript" src="/ajax/jquery.js"></script>
<script type="text/javascript">

        $(function(){ // jquery onload
                window.setInterval(
                function(){
                    $('#ajaxDiv').load('number_status.php?campaignid=<?echo $_POST[campaignid];?>&number=<?=$_GET[dialled];?>');  // jquery ajax load into div
                }
                ,1000);
        });
</script>
<?}?>
<center>
<br />

<?

//exit(0);

$result = mysql_query("SELECT count(*) FROM number where status='manual_dial' and campaignid=$_POST[campaignid]");
$num_of_num = mysql_result($result,0,0);
echo $num_of_num." numbers remaining<br /><br />";
$result = mysql_query("SELECT * from number where campaignid=$_POST[campaignid] and status='manual_dial' limit 100");
//srand(time());
$x = 0;
while ($row = mysql_fetch_assoc($result)) {
    $numbers[$x] = $row[phonenumber];
    $rows[$x] = $row;
    $x++;
}
$random = (rand()%sizeof($numbers));
//echo $random." entry is ".$numbers[$random]."<br />";
//$result = mysql_query("INSERT INTO number (campaignid, phonenumber, status) VALUES ($_POST[campaignid],'$numbers[$random]','new')") or die(mysql_error());
$result = mysql_query("SELECT * FROM campaign WHERE id = $_POST[campaignid]") or die(mysql_error());
$row = mysql_fetch_assoc($result);
?>
<button type="Button" onClick="window.location='manual_startcampaign.php?id=<?=$_POST[campaignid]?>&astqueuename=<?echo $row[astqueuename];?>&clid=<?echo $row[clid];?>&trclid=<?echo $row[trclid];?>&agents=<?echo $row[maxagents];?>&did=<?echo $row[did];?>&context=<?echo $row[context];?>'">Call the Next Number</button><br />
<br />
<div id="ajaxDiv">
<table border="0" cellpadding="20">
<tr><td bgcolor = "#ffffff">
<img src="/images/progress.gif" border="0">
</td></tr></table>
</div>

<?
//print_r($rows[$x]);
}?>








