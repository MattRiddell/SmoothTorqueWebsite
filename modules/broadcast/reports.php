<?
$override_directory = dirname(__FILE__)."/../../";
require $override_directory."header.php";
?>

<?



$sql = 'SELECT campaigngroupid FROM customer WHERE username=\''.$_COOKIE[user].'\'';
$result=mysql_query($sql, $link) or die (mysql_error());;
$campaigngroupid=mysql_result($result,0,'campaigngroupid');

$result = mysql_query("select * from campaign where groupid = '".$campaigngroupid."'") or die (mysql_error());
if (!mysql_num_rows($result) > 0) {
	/* This customer has no campaigns */
	echo "No campaigns for $campaigngroupid ($_COOKIE[user])";
} else {
//echo $level;
if (1||$level==sha1("level100")) {
	$datafile = "chart-data-system.php";
} else {
	$datafile = "chart-data-overview.php";
}
?>
<script type="text/javascript" src="/js/swfobject.js"></script>
<script type="text/javascript">
swfobject.embedSWF(
  "/open-flash-chart.swf", "my_chart", "550", "250",
  "9.0.0", "expressInstall.swf",
  {"data-file":"/modules/broadcast/<?=$datafile?>?user=<?=$_COOKIE[user]?>&x=<?echo rand()*1000;?>"}
  );
</script>
<script>
function findSWF(movieName) {
  return document[movieName];
}

function count()
{
 findSWF('my_chart').reload('/modules/broadcast/<?=$datafile?>?user=<?=$_COOKIE[user]?>&x=<?echo rand()*1000;?>');
}
function init()
{
  setInterval("count()",15000);
}
window.onload = init;
</script>
</head>
<body>


<div id="my_chart" ></div>

<?
}
require $override_directory."footer.php";
?>
