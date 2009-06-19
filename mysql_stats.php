<?
require "header.php";
require "header_server.php";
if (isset($_GET[convert_table])) {
    echo "Please wait - converting to InnoDB - this may take a while<br />";
    flush();
    $result = mysql_query("ALTER TABLE SineDialer.".sanitize($_GET[convert_table],false)." ENGINE = InnoDB") or die (mysql_error());
    echo "<br /><b>Conversion completed</b><br />";
    ?><META HTTP-EQUIV=REFRESH CONTENT="0; URL=/mysql_stats.php"><?
    exit(0);
}
if (isset($_GET[kill])) {
	$result = mysql_query("KILL $_GET[kill]") or die (mysql_error());
}
 function _get_browser()
{
  $browser = array ( //reversed array
   "OPERA",
   "MSIE",            // parent
   "NETSCAPE",
   "FIREFOX",
   "SAFARI",
   "KONQUEROR",
   "MOZILLA"        // parent
  );

  $info[browser] = "OTHER";

  foreach ($browser as $parent)
  {
   if ( ($s = strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), $parent)) !== FALSE )
   {
     $f = $s + strlen($parent);
     $version = substr($_SERVER['HTTP_USER_AGENT'], $f, 5);
     $version = preg_replace('/[^0-9,.]/','',$version);

     $info[browser] = $parent;
     $info[version] = $version;
     break; // first match wins
   }
  }

  return $info;
}
$level=$_COOKIE[level];
$out=_get_browser();
if ($out[browser]=="MSIE"){
?>
<script type="text/javascript" src="/ajax/jquery.js"></script>
<script type="text/javascript">
        $(function(){ // jquery onload
                window.setInterval(function(){
                        $('#ajaxDiv').loadIfModified('mysql_details.php');  // jquery ajax load into div
                },2000);
        });

</script>
<?} else {?>
<script type="text/javascript" src="/ajax/jquery.js"></script>
<script type="text/javascript">

        $(function(){ // jquery onload
                window.setInterval(
                function(){
                        $('#ajaxDiv').load('mysql_details.php');  // jquery ajax load into div
                }
                ,2000);
        });
</script>
<?}
box_start(700);
echo "<center>";
?>

<div id = "ajaxDiv">
<?
include "mysql_details.php";
?>
</div>

<?
box_end();
$result = mysql_query("SHOW TABLE STATUS");
?>
<center><table class="tborder2p" cellspacing="1" boreder="0" cellpadding="5">
<tr>
<td CLASS="thead">Name</td>
<td CLASS="thead">Rows</td>
<td CLASS="thead">Total Size</td>
<td CLASS="thead">Last Update</td>
<td CLASS="thead">Last Check</td>
<td CLASS="thead">Engine</td>
</tr>
<?
while ($row = mysql_fetch_assoc($result)) {
//	print_pre($row);
	$size = ($row['Data_length']+$row['Index_length'])/1048576;
	$rows = $row[Rows];
	if ($size > 0 && $rows > 0) {
//		if ($toggle){
//			$toggle=false;
//			$class=" class=\"tborder2\"  onmouseover=\"style.backgroundColor='#84DFC1';\" onmouseout=\"style.backgroundColor='#f8f8f8'\"   ";
//		} else {
//			$toggle=true;
//			$class=" class=\"tborderx\"  onmouseover=\"style.backgroundColor='#84DFC1';\" onmouseout=\"style.backgroundColor='#f0f0f0'\" ";
//		}
		echo "<tr ".$class.">";

		if ($size < 1) {
			$size *= 1024;
			$tdstyle = " style=\"background: #eeffee \"";
			$size_text = "Kb";
			$digits = 0;
		} else if ($size<1024) {
			$tdstyle = " style=\"background: #ffdd88 url('images/grad_green.gif');\"";
			$size_text = "Mb";
			$digits = 1;
		} else if ($size <10240) {
			$tdstyle = " style=\"background: #ff8844 url('images/grad_orange.gif');\"";
			$size/=1024;
	                $size_text = "Gb";
			$digits = 2;
		} else {
			$tdstyle = " style=\"background: #ff4444\"";
			$size/=1024;
	                $size_text = "Gb";
			$digits = 4;
		}
		if ($row[Engine] == "InnoDB") {
			if ($size_text != "Gb") {
//				$tdstyle = " style=\"background: #8888ff url('images/grad_blue.png')\"";
			}
			echo "<td".$tdstyle.">$row[Name]</td>";
			echo "<td".$tdstyle.">".number_format($rows)." (approx)</td>";
			echo "<td".$tdstyle."><b>".number_format($size,$digits)." $size_text</b></td>";
			echo "<td".$tdstyle." colspan=\"2\">$row[Comment]</td>";
			echo "<td".$tdstyle.">";
			echo "<b>".$row[Engine]."</b>";
		} else {
			echo "<td".$tdstyle.">$row[Name]</td>";
			echo "<td".$tdstyle.">".number_format($rows)."</td>";
			echo "<td".$tdstyle."><b>".number_format($size,$digits)." $size_text</b></td>";
			$update_time = strtotime($row[Update_time]);
			$check_time = strtotime($row[Check_time]);
			echo "<td".$tdstyle.">".date("D M j G:i:s Y", $update_time)."</td>";
			echo "<td".$tdstyle.">".date("D M j G:i:s Y", $check_time)."</td>";
			echo "<td".$tdstyle.">";
			echo "$row[Engine] <a href=\"mysql_stats.php?convert_table=$row[Name]\">Convert</a>";
		}
		echo "</td>";
		echo "</tr>";
	}
}
require "footer.php";
?>
