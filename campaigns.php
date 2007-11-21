<?
include "admin/db_config.php";//mysql_connect('localhost', 'root', '') OR die(mysql_error());
mysql_select_db("SineDialer", $link);
if (isset($_GET[queueID])){
    $sql = 'update queue set status='.$_GET[status].' where queueID='.$_GET[queueID];
    $result=mysql_query($sql, $link) or die (mysql_error());;
    header("Location: schedule.php?campaignid=".$_GET[campaignid]);
}

require "header.php";
$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');
require "header_campaign.php";

if (isset($_GET[campaignid])){
$_POST[campaignid]=$_GET[campaignid];
}          /*
if (!isset($_POST[campaignid])){
    ?>
    <FORM ACTION="schedule.php" METHOD="POST">
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
    <INPUT TYPE="SUBMIT" VALUE="Display Schedule">
    </TD>
    </TR></table>
    </FORM>
    <?
} else { */
$out=_get_browser();
//print_r($out)
if ($out[browser]=="MSIE"){
?>
<script type="text/javascript" src="/ajax/jquery.js"></script>
        <script type="text/javascript">
        $(function(){ // jquery onload
                window.setInterval(function(){ // setInterval code
                        $('#ajaxDiv').loadIfModified('disTime3.php?campaigngroupid=<?echo $campaigngroupid;?>&id=<?echo $_POST[campaignid];?>');  // jquery ajax load into div
                },1000);
        });

        </script>
 <?} else {?>
<script type="text/javascript" src="/ajax/jquery.js"></script>
        <script type="text/javascript">

        $(function(){ // jquery onload
                window.setInterval(function(){ // setInterval code
                        //alert('hello');

                        $('#ajaxDiv').load('disTime3.php?campaigngroupid=<?echo $campaigngroupid;?>&id=<?echo $_POST[campaignid];?>');  // jquery ajax load into div
                },1000);
        });
                        </script>

<?}?>
<div id="ajaxDiv">
<?
$id=$_POST[campaignid];
include "disTime3.php";
?>
</div>
<?

require "footer.php";
?>
