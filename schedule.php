<?
$pagenum="4";
if (isset($_GET[queueID])){
    $sql = 'update queue set status='.$_GET[status].' where queueID='.$_GET[queueID];
    require "sql.php";
    $SMDB=new SmDB();
    $SMDB->executeUpdate($sql);
    header("Location: schedule.php?campaignid=".$_GET[campaignid]);
}

require "header.php";
$campaigngroupid=$groupid;

if (isset($_GET[campaignid])){
$_POST[campaignid]=$_GET[campaignid];
}
$out=_get_browser();
if ($out[browser]=="MSIE"){
?>
<script type="text/javascript" src="ajax/jquery.js"></script>
        <script type="text/javascript">
        $(function(){ // jquery onload
                window.setInterval(function(){ // setInterval code
                        $('#ajaxDiv').loadIfModified('disTime.php?campaigngroupid=<?echo $campaigngroupid;?>&id=<?echo $_POST[campaignid];?>');  // jquery ajax load into div
                },1500);
        });

        </script>
<?} else {?>
<script type="text/javascript" src="ajax/jquery.js"></script>
        <script type="text/javascript">
        $(function(){ // jquery onload
                window.setInterval(function(){ // setInterval code
                        $('#ajaxDiv').load('disTime.php?campaigngroupid=<?echo $campaigngroupid;?>&id=<?echo $_POST[campaignid];?>');  // jquery ajax load into div
                },1500);
        });

        </script>

<?}?>
<div id="ajaxDiv">
<?
$id=$_POST[campaignid];include "disTime.php";?>
</div>
<?

require "footer.php";
?>
