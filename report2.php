<?
require "header.php";
require "header_campaign.php";
$id=$_GET[id];
$debug=$_GET[debug];
$type=$_GET[type];
if ($id<1){
        exit(0);
}
?>

<a href="test.php?id=<?echo $id;?>"><img src="/images/chart_curve.png"  border="0"> View Engine Status</a>&nbsp;
<a href="report3.php?id=<?echo $id;?>"><img src="/images/chart_line.png"  border="0"> Daily Number Stats</a>&nbsp;
<br /><br />
<?/*
if ($_GET[type]!="today") {
    ?>
    <a href="report.php?type=today&id=<?echo $id;?>"><img src="/images/chart_pie.png"  border="0"> Today</a>&nbsp;
    <?
} else {
    echo '<b><img src="/images/chart_pie.png"  border="0"> Today</b>&nbsp;';
}
if ($_GET[type]!="") {
    ?>
    <a href="report.php?id=<?echo $id;?>"><img src="/images/chart_pie.png"  border="0"> All Time</a>&nbsp;
    <?
} else {
    echo '<b><img src="/images/chart_pie.png"  border="0"> All Time</b>&nbsp;';
}
if ($_GET[type]!="yesterday") {
    ?>
    <a href="report.php?type=yesterday&id=<?echo $id;?>"><img src="/images/chart_pie.png"  border="0"> Yesteday</a><br />
    <?
} else {
    echo '<b><img src="/images/chart_pie.png"  border="0"> Yesterday</b>&nbsp;<br />';
}*/
?>


<script type="text/javascript" src="js/swfobject.js"></script>
<script type="text/javascript">
swfobject.embedSWF(
  "open-flash-chart.swf", "my_chart", "700", "450",
  "9.0.0", "expressInstall.swf",
  {"data-file":"chart-data.php?campaignid=<?=$_GET[id]?>"}
  );
</script>
<script>
function findSWF(movieName) {
  return document[movieName];
}

function count()
{
 findSWF('my_chart').reload('/chart-data.php?campaignid=<?=$_GET[id]?>');
}
function init()
{
  setInterval("count()",15000);
}
window.onload = init;
</script>

</head>
<body>



<div id="my_chart"></div>


</body>
</html>

<?
