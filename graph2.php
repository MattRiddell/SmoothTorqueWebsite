<?php
include "admin/db_config.php";
mysql_select_db("SineDialer", $link);
$edit = $_GET[type];
include("./jpgraph.php");
include("./jpgraph_pie.php");
include("./jpgraph_pie3d.php");

if ($edit == "today") {
    $timedate = " and date(datetime)=curdate()";
} else if ($edit == "yesterday") {
    $timedate = " and date(datetime)<curdate() and date(datetime)>DATE_ADD(CURDATE(), INTERVAL -2 DAY)";
}

$sql = 'SELECT context from campaign where id='.$_GET[id];
$result2=mysql_query($sql, $link) or die (mysql_error());;
$type_of_campaign=mysql_result($result2,0,'context');

if ($type_of_campaign == 8) {




$found = 0;
$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status!="new" and status!="dialing" and status!="dialed" and status!="answered" '.$timedate;
$result2=mysql_query($sql, $link) or die (mysql_error());;
$total=mysql_result($result2,0,'count(*)');
if ($total<1) {
    $total = 0;
}
$sql = 'SELECT count(*) from number where campaignid='.$_GET[id];
$result2=mysql_query($sql, $link) or die (mysql_error());;
$total2=mysql_result($result2,0,'count(*)');
if ($total2<1) {
    $total2 = 0;
}

$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status="dialing"'.$timedate;
$result2=mysql_query($sql, $link) or die (mysql_error());;
$dialing=mysql_result($result2,0,'count(*)');
if ($dialing<1) {
    $dialing = 0;
} else {
    $found = 1;
}

$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status="busy"'.$timedate;
$result2=mysql_query($sql, $link) or die (mysql_error());;
$busy=mysql_result($result2,0,'count(*)');
if ($busy<1) {
    $busy = 0;
} else {
    $found = 1;
}

$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status="nophasee"'.$timedate;
$result2=mysql_query($sql, $link) or die (mysql_error());;
$amd=mysql_result($result2,0,'count(*)');
if ($amd<1) {
    $amd = 0;
} else {
    $found = 1;
}

$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status="answered"'.$timedate;
$result2=mysql_query($sql, $link) or die (mysql_error());;
$answered=mysql_result($result2,0,'count(*)');
if ($answered<1) {
    $answered = 0;
} else {
    $found = 1;
}

$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status="hungup"'.$timedate;
$result2=mysql_query($sql, $link) or die (mysql_error());;
$hungup=mysql_result($result2,0,'count(*)');
if ($hungup<1) {
    $hungup = 0;
} else {
    $found = 1;
}

$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status="congested"'.$timedate;
$result2=mysql_query($sql, $link) or die (mysql_error());;
$congested=mysql_result($result2,0,'count(*)');
if ($congested<1) {
    $congested = 0;
} else {
    $found = 1;
}

$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status="timeout"'.$timedate;
$result2=mysql_query($sql, $link) or die (mysql_error());;
$timeout=mysql_result($result2,0,'count(*)');
if ($timeout<1) {
    $timeout = 0;
} else {
    $found = 1;
}

$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status like "Fax:%"'.$timedate;
$result2=mysql_query($sql, $link) or die (mysql_error());;
$unknown=mysql_result($result2,0,'count(*)');
if ($unknown<1) {
    $unknown = 0;
} else {
    $found = 1;
}

$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status="dialed"'.$timedate;
$result2=mysql_query($sql, $link) or die (mysql_error());;
$dialed=mysql_result($result2,0,'count(*)');
if ($dialed<1) {
    $dialed = 0;
} else {
    $found = 1;
}

$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status="new"';
$result2=mysql_query($sql, $link) or die (mysql_error());;
$new=mysql_result($result2,0,'count(*)');
if ($new<1) {
    $new = 0;
}

$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status="indnc"'.$timedate;
$result3=mysql_query($sql, $link) or die (mysql_error());;
$indnc=mysql_result($result3,0,'count(*)');
if ($indnc<1) {
    $indnc = 0;
} else {
    $found = 1;
}

$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status="faxsent"'.$timedate;
$result2=mysql_query($sql, $link) or die (mysql_error());;
$pressed1=mysql_result($result2,0,'count(*)');
if ($pressed1<1) {
    $pressed1 = 0;
} else {
    $found = 1;
}

$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status="calldropped"'.$timedate;
$result2=mysql_query($sql, $link) or die (mysql_error());;
$calldropped=mysql_result($result2,0,'count(*)');
if ($calldropped<1) {
    $calldropped = 0;
} else {
    $found = 1;
}

$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status="maxretries"'.$timedate;
$result2=mysql_query($sql, $link) or die (mysql_error());;
$maxretries=mysql_result($result2,0,'count(*)');
if ($maxretries<1) {
    $maxretries = 0;
} else {
    $found = 1;
}

$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status="badtiff"'.$timedate;
$result2=mysql_query($sql, $link) or die (mysql_error());;
$badtiff=mysql_result($result2,0,'count(*)');
if ($badtiff<1) {
    $badtiff = 0;
} else {
    $found = 1;
}



//$targets=array("list.php?campaignid=$_GET[id]&type=pressed1", "list.php?campaignid=$_GET[id]&type=dialed", "list.php?campaignid=$_GET[id]&type=busy", "list.php?campaignid=$_GET[id]&type=answered", "list.php?campaignid=$_GET[id]&type=hungup", "list.php?campaignid=$_GET[id]&type=congested", "list.php?campaignid=$_GET[id]&type=dialed", "list.php?campaignid=$_GET[id]&type=unknown",  "list.php?campaignid=$_GET[id]&type=indnc");


$graph = new PieGraph(730, 450,  "car");
$graph -> SetScale("textlin");
if ($found == 0) {
    $unknown = 1;
    $total = 1;
    $txt=new Text( "\n\n  There is no data for this time period \n\n");
    $txt->Pos( 272,180);
    $txt->SetAlign("center","","");
    $txt->SetFont(FF_FONT2,FS_BOLD);
    $txt->SetBox('#ddddee@0','navy@0.9','#000000@0.7',0,5);
    $graph->AddText( $txt);

}


$data=array(
 $pressed1/$total*100,
 $amd/$total*100,
/* $dialed/$total*100,*/
 $busy/$total*100,
/* $answered/$total*100,*/
 $hungup/$total*100,
 $congested/$total*100,
 $timeout/$total*100,
 $unknown/$total*100,
 $indnc/$total*100,
 $calldropped/$total*100,
 $maxretries/$total*100,
 $badtiff/$total*100
);







$browserx=array(
 " Fax Sent ($pressed1 ".round((($pressed1/$total)*100),2)."%%)",
 " No Phase E ($amd ".round((($amd/$total)*100),2)."%%)",
/* "dialed ($dialed ".round((($dialed/$total)*100),2)."%%)",*/
 " Busy ($busy ".round((($busy/$total)*100),2)."%%)",
 /*" Answered ($answered ".round((($answered/$total)*100),2)."%%)",*/
 " Answered ($hungup ".round((($hungup/$total)*100),2)."%%)",
 " Congested ($congested ".round((($congested/$total)*100),2)."%%)",
 " No Answer ($timeout ".round((($timeout/$total)*100),2)."%%)",
 " Unknown ($unknown ".round((($unknown/$total)*100),2)."%%)",
 " In DNC List ($indnc ".round((($indnc/$total)*100),2)."%%)",
 " Call Dropped Prematurely ($calldropped ".round((($calldropped/$total)*100),2)."%%)",
 " Disconnected after permitted retries ($maxretries ".round((($maxretries/$total)*100),2)."%%)",
 " Bad TIFF File ($badtiff ".round((($badtiff/$total)*100),2)."%%)"
);

$p1 = new PiePlot3D($data);
if ($found == 1) {
$p1->SetSliceColors(array(
 '#00ff00',
 '#0000ff',
 "#000000",
 /*"#00ffff",*/
 "#ffff00",
 "#ff0000",
 "#888888",
 "#ff8888",
 "#880000",
 "#ffffff")     );
} else {
    $p1->SetSliceColors(array('#EEEEEE'));
}
$p1->SetLegends($browserx);
$p1->SetAngle(70);
$p1->SetCenter(0.37,0.5);
$p1->SetSize(200);
if ($found == 1){
$p1->ExplodeAll();
}
$graph -> Add($p1);            //    <------- this line
//$graph->setShadow();
$graph->title->set("Dialed: ".($total2-$dialing-$new)." Remaining: $new Total: ".($total2)." Dialing: $dialing");
$graph->title->SetFont( FF_FONT1, FS_BOLD);
$graph->SetColor("#eeeeee@0.5");
$graph->img->SetAntiAliasing();
$graph->SetFrame(false,'darkblue',2);
//$graph->SetBackgroundGradient('#0000ff@0.7','white@0.1',GRAD_HOR,BGRAD_PLOT);
//$graph->StrokeCSIM("graph2.php");
$graph->Stroke();
} else {




$found = 0;
$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status!="new" and status!="dialing" and status!="dialed" and status!="answered" '.$timedate;
$result2=mysql_query($sql, $link) or die (mysql_error());;
$total=mysql_result($result2,0,'count(*)');
if ($total<1) {
    $total = 0;
}
$sql = 'SELECT count(*) from number where campaignid='.$_GET[id];
$result2=mysql_query($sql, $link) or die (mysql_error());;
$total2=mysql_result($result2,0,'count(*)');
if ($total2<1) {
    $total2 = 0;
}

$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status="dialing"'.$timedate;
$result2=mysql_query($sql, $link) or die (mysql_error());;
$dialing=mysql_result($result2,0,'count(*)');
if ($dialing<1) {
    $dialing = 0;
} else {
    $found = 1;
}

$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status="busy"'.$timedate;
$result2=mysql_query($sql, $link) or die (mysql_error());;
$busy=mysql_result($result2,0,'count(*)');
if ($busy<1) {
    $busy = 0;
} else {
    $found = 1;
}

$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status="amd"'.$timedate;
$result2=mysql_query($sql, $link) or die (mysql_error());;
$amd=mysql_result($result2,0,'count(*)');
if ($amd<1) {
    $amd = 0;
} else {
    $found = 1;
}

$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status="answered"'.$timedate;
$result2=mysql_query($sql, $link) or die (mysql_error());;
$answered=mysql_result($result2,0,'count(*)');
if ($answered<1) {
    $answered = 0;
} else {
    $found = 1;
}

$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status="hungup"'.$timedate;
$result2=mysql_query($sql, $link) or die (mysql_error());;
$hungup=mysql_result($result2,0,'count(*)');
if ($hungup<1) {
    $hungup = 0;
} else {
    $found = 1;
}

$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status="congested"'.$timedate;
$result2=mysql_query($sql, $link) or die (mysql_error());;
$congested=mysql_result($result2,0,'count(*)');
if ($congested<1) {
    $congested = 0;
} else {
    $found = 1;
}

$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status="timeout"'.$timedate;
$result2=mysql_query($sql, $link) or die (mysql_error());;
$timeout=mysql_result($result2,0,'count(*)');
if ($timeout<1) {
    $timeout = 0;
} else {
    $found = 1;
}

$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status like "unknown-%"'.$timedate;
$result2=mysql_query($sql, $link) or die (mysql_error());;
$unknown=mysql_result($result2,0,'count(*)');
if ($unknown<1) {
    $unknown = 0;
} else {
    $found = 1;
}

$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status="dialed"'.$timedate;
$result2=mysql_query($sql, $link) or die (mysql_error());;
$dialed=mysql_result($result2,0,'count(*)');
if ($dialed<1) {
    $dialed = 0;
} else {
    $found = 1;
}

$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status="new"';
$result2=mysql_query($sql, $link) or die (mysql_error());;
$new=mysql_result($result2,0,'count(*)');
if ($new<1) {
    $new = 0;
}

$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status="indnc"'.$timedate;
$result3=mysql_query($sql, $link) or die (mysql_error());;
$indnc=mysql_result($result3,0,'count(*)');
if ($indnc<1) {
    $indnc = 0;
} else {
    $found = 1;
}

$sql = 'SELECT count(*) from number where campaignid='.$_GET[id].' and status="pressed1"'.$timedate;
$result2=mysql_query($sql, $link) or die (mysql_error());;
$pressed1=mysql_result($result2,0,'count(*)');
if ($pressed1<1) {
    $pressed1 = 0;
} else {
    $found = 1;
}

//$targets=array("list.php?campaignid=$_GET[id]&type=pressed1", "list.php?campaignid=$_GET[id]&type=dialed", "list.php?campaignid=$_GET[id]&type=busy", "list.php?campaignid=$_GET[id]&type=answered", "list.php?campaignid=$_GET[id]&type=hungup", "list.php?campaignid=$_GET[id]&type=congested", "list.php?campaignid=$_GET[id]&type=dialed", "list.php?campaignid=$_GET[id]&type=unknown",  "list.php?campaignid=$_GET[id]&type=indnc");


$graph = new PieGraph(730, 450,  "car");
$graph -> SetScale("textlin");
if ($found == 0) {
    $unknown = 1;
    $total = 1;
    $txt=new Text( "\n\n  There is no data for this time period \n\n");
    $txt->Pos( 272,180);
    $txt->SetAlign("center","","");
    $txt->SetFont(FF_FONT2,FS_BOLD);
    $txt->SetBox('#ddddee@0','navy@0.9','#000000@0.7',0,5);
    $graph->AddText( $txt);

}


$data=array(
 $pressed1/$total*100,
 $amd/$total*100,
/* $dialed/$total*100,*/
 $busy/$total*100,
/* $answered/$total*100,*/
 $hungup/$total*100,
 $congested/$total*100,
 $timeout/$total*100,
 $unknown/$total*100,
 $indnc/$total*100
);







$browserx=array(
 " Pressed 1 ($pressed1 ".round((($pressed1/$total)*100),2)."%%)",
 " Answer Machine ($amd ".round((($amd/$total)*100),2)."%%)",
/* "dialed ($dialed ".round((($dialed/$total)*100),2)."%%)",*/
 " Busy ($busy ".round((($busy/$total)*100),2)."%%)",
 /*" Answered ($answered ".round((($answered/$total)*100),2)."%%)",*/
 " Answered ($hungup ".round((($hungup/$total)*100),2)."%%)",
 " Congested ($congested ".round((($congested/$total)*100),2)."%%)",
 " No Answer ($timeout ".round((($timeout/$total)*100),2)."%%)",
 " Unknown ($unknown ".round((($unknown/$total)*100),2)."%%)",
 " In DNC List ($indnc ".round((($indnc/$total)*100),2)."%%)");

$p1 = new PiePlot3D($data);
if ($found == 1) {
$p1->SetSliceColors(array(
 '#00ff00',
 '#0000ff',
 "#000000",
 /*"#00ffff",*/
 "#ffff00",
 "#ff0000",
 "#888888",
 "#ff8888",
 "#880000",
 "#ffffff")     );
} else {
    $p1->SetSliceColors(array('#EEEEEE'));
}
$p1->SetLegends($browserx);
$p1->SetAngle(70);
$p1->SetCenter(0.37,0.5);
$p1->SetSize(200);
if ($found == 1){
$p1->ExplodeAll();
}
$graph -> Add($p1);            //    <------- this line
//$graph->setShadow();
$graph->title->set("Dialed: ".($total2-$dialing-$new)." Remaining: $new Total: ".($total2)." Dialing: $dialing");
$graph->title->SetFont( FF_FONT1, FS_BOLD);
$graph->SetColor("#eeeeee@0.5");
$graph->img->SetAntiAliasing();
$graph->SetFrame(false,'darkblue',2);
//$graph->SetBackgroundGradient('#0000ff@0.7','white@0.1',GRAD_HOR,BGRAD_PLOT);
//$graph->StrokeCSIM("graph2.php");
$graph->Stroke();
}
?>
