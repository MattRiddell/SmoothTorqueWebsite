<?php
include "admin/db_config.php";
mysql_select_db("SineDialer", $link);


include("./jpgraph.php");
include("./jpgraph_pie.php");
include("./jpgraph_pie3d.php");

$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status!="new" and status!="dialing" and status!="dialed"';
$result2=mysql_query($sql, $link) or die (mysql_error());;
$total=mysql_result($result2,0,'count(*)');

$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status="dialing"';
$result2=mysql_query($sql, $link) or die (mysql_error());;
$dialing=mysql_result($result2,0,'count(*)');

$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status="busy"';
$result2=mysql_query($sql, $link) or die (mysql_error());;
$busy=mysql_result($result2,0,'count(*)');

$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status="amd"';
$result2=mysql_query($sql, $link) or die (mysql_error());;
$amd=mysql_result($result2,0,'count(*)');

$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status="answered"';
$result2=mysql_query($sql, $link) or die (mysql_error());;
$answered=mysql_result($result2,0,'count(*)');

$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status="hungup"';
$result2=mysql_query($sql, $link) or die (mysql_error());;
$hungup=mysql_result($result2,0,'count(*)');

$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status="congested"';
$result2=mysql_query($sql, $link) or die (mysql_error());;
$congested=mysql_result($result2,0,'count(*)');

$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status="timeout"';
$result2=mysql_query($sql, $link) or die (mysql_error());;
$timeout=mysql_result($result2,0,'count(*)');

$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status like "unknown-%"';
$result2=mysql_query($sql, $link) or die (mysql_error());;
$unknown=mysql_result($result2,0,'count(*)');

$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status="dialed"';
$result2=mysql_query($sql, $link) or die (mysql_error());;
$dialed=mysql_result($result2,0,'count(*)');

$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status="new"';
$result2=mysql_query($sql, $link) or die (mysql_error());;
$new=mysql_result($result2,0,'count(*)');

$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status="indnc"';
$result2=mysql_query($sql, $link) or die (mysql_error());;
$indnc=mysql_result($result2,0,'count(*)');

$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status="pressed1"';
$result2=mysql_query($sql, $link) or die (mysql_error());;
$pressed1=mysql_result($result2,0,'count(*)');

//$targets=array("list.php?campaignid=$_GET[id]&type=pressed1", "list.php?campaignid=$_GET[id]&type=dialed", "list.php?campaignid=$_GET[id]&type=busy", "list.php?campaignid=$_GET[id]&type=answered", "list.php?campaignid=$_GET[id]&type=hungup", "list.php?campaignid=$_GET[id]&type=congested", "list.php?campaignid=$_GET[id]&type=dialed", "list.php?campaignid=$_GET[id]&type=unknown",  "list.php?campaignid=$_GET[id]&type=indnc");


$graph = new PieGraph(730, 450,  "car");
$graph -> SetScale("textlin");
$data=array(
 $pressed1/$total*100,
 $amd/$total*100,
/* $dialed/$total*100,*/
 $busy/$total*100,
 $answered/$total*100,
 $hungup/$total*100,
 $congested/$total*100,
 $timeout/$total*100,
 $unknown/$total*100,
 $indnc/$total*100
);
$browserx=array(
 "pressed1 ($pressed1 ".round((($pressed1/$total)*100),2)."%%)",
 "amd ($amd ".round((($amd/$total)*100),2)."%%)",
/* "dialed ($dialed ".round((($dialed/$total)*100),2)."%%)",*/
 "busy ($busy ".round((($busy/$total)*100),2)."%%)",
 "answered ($answered ".round((($answered/$total)*100),2)."%%)",
 "hungup ($hungup ".round((($hungup/$total)*100),2)."%%)",
 "congested ($congested ".round((($congested/$total)*100),2)."%%)",
 "timeout ($timeout ".round((($timeout/$total)*100),2)."%%)",
 "unknown ($unknown ".round((($unknown/$total)*100),2)."%%)",
 "indnc ($indnc ".round((($indnc/$total)*100),2)."%%)");

$p1 = new PiePlot3D($data);
$p1->SetSliceColors(array(
 '#00ff00',
 '#0000ff',
 "#000000",
 "#00ffff",
 "#ffff00",
 "#ff0000",
 "#888888",
 "#ff8888",
 "#880000",
 "#ffffff")     );
$p1->SetLegends($browserx);
$p1->SetAngle(70);
$p1->SetCenter(0.38,0.5);
$p1->SetSize(240);
$graph -> Add($p1);            //    <------- this line
//$graph->setShadow();
$graph->title->set("Remaining Numbers: $new/".($total+$new+$dialing+$dialed)." (dialing $dialing)");
$graph->title->SetFont( FF_FONT1, FS_BOLD);
$graph->SetColor("#eeeeee@0.5");
$graph->img->SetAntiAliasing();
$graph->SetFrame(false,'darkblue',2);
//$graph->SetBackgroundGradient('#0000ff@0.7','white@0.1',GRAD_HOR,BGRAD_PLOT);
//$graph->StrokeCSIM("graph2.php");
$graph->Stroke();
?>
