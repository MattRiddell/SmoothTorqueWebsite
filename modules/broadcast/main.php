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
  "/open-flash-chart.swf", "my_chart", "550", "150",
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


<!-- Save for Web Slices (main.psd) -->
<table id="Table_01" width="607" height="157" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="7">
			<img src="images/main_01.png" width="607" height="27" alt=""></td>
	</tr>
	<tr>
		<td rowspan="2">
			<img src="images/main_02.png" width="39" height="130" alt=""></td>
		<td>
			<a href="upload_list.php">
				<img src="images/main_03.gif" width="124" height="100" border="0" alt=""></a></td>
		<td>
			<a href="upload_messages.php">
				<img src="images/main_04.gif" width="130" height="100" border="0" alt=""></a></td>
		<td rowspan="2">
			<img src="images/main_05.png" width="2" height="130" alt=""></td>
		<td>
			<a href="start_campaign.php">
				<img src="images/main_06.gif" width="139" height="100" border="0" alt=""></a></td>
		<td>
			<a href="reports.php">
				<img src="images/main_07.gif" width="125" height="100" border="0" alt=""></a></td>
		<td rowspan="2">
			<img src="images/main_08.png" width="48" height="130" alt=""></td>
	</tr>
	<tr>
		<td colspan="2">
			<img src="images/main_09.png" width="254" height="30" alt=""></td>
		<td colspan="2">
			<img src="images/main_10.png" width="264" height="30" alt=""></td>
	</tr>
</table>
<!-- End Save for Web Slices -->

<span id="my_chart_container" style="border: 1px solid #000000;display: inline-table; padding: 20px;">
<div id="my_chart" ></div>
</span>

<?
}
require $override_directory."footer.php";
?>
