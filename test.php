<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="refresh" content="3">
		<script src="lib/prototype/prototype.js" type="text/javascript"></script>
		<script src="lib/excanvas/excanvas.js" type="text/javascript"></script>
		<script src="plotr.js" type="text/javascript"></script>
		<style type="text/css">
body{
        font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;
        font-size:16px;
        line-height:20px;
}
         .linechart-value-point {
            background-color: #000;
         }
         
         .linechart-value-element {
            background-color: #000;
            color: #fff;
            border: '1px solid #c2c2c2';
            padding: 0 2px;
         }
		</style>
	</head>

	<body>
<div id="wrapper">
<center><h2>
SmoothTorque Predictive Dialer</h2>
<div><canvas id="lines1" height="250" width="1000"></canvas></div>
</div>
</center><br><center>
		

		
		<script type="text/javascript">
			
			// Define a dataset.
			var dataset = {
				'Rate': [<?
$lines=file("/tmp/Sm1.console2");
$count=-1;
$first=true;
$lastRate=0;
$lastPerc=0;
foreach ($lines as $line_num => $line) {
	if (strlen(trim($line))>0){
	$count++;
	if ($first){
        	echo "[$count,".trim($line)."]";
		$first=false;
	} else {
	        echo ",[$count,".trim($line)."]";
	}
$lastRate=trim($line);
	}
}
echo "]";
?>
,
				'PercentBusy': [<?
$count=-1;
$lines=file("/tmp/Sm1.console");
$first=true;
$avgPerc=0;
foreach ($lines as $line_num => $line) {
	if (strlen(trim($line))>0){
	$avgPerc+=trim($line);
        $count++;
        if ($first){
                echo "[$count,".trim($line)."]"; 
                $first=false;
        } else {
                echo ",[$count,".trim($line)."]";
        }
$lastPerc=trim($line);

	}
}
$avgPerc/=$count;
echo "]";
?>};
			
			// Define options.
var options = 
{
"hoverAll": "true",
"colorScheme": "blue", 
"fillOpacity": "0.2", 
shouldFill: false,
backgroundColor: "#f8f8f8",
"axis": {"labelColor": "#000000", "labelFontSize": "13"},
"stroke": {"hide": false, "shadow": true},
colorScheme: new Hash({	'Rate': '#0000ff','PercentBusy': '#ff0000'}),
               xTicks: [
                          {v:0, label:'7 Minutes Ago'}, 
                          {v:60, label:'6 Minutes Ago'},
                          {v:130, label:'5 Minutes Ago'},
                          {v:180, label:'4 Minutes Ago'},
                          {v:240, label:'3 Minutes Ago'},
                          {v:300, label:'2 Minutes Ago'},
                          {v:360, label:'1 Minute Ago'},
                          {v:419, label:'Now'}
                         ]
               
				}
			
			// Instantiate a new LineCart.
			var line = new Plotr.LineChart('lines1',options);
			// Add a dataset to it.
			line.addDataset(dataset);
			// Render it.
			line.render();		
						
		</script>
	<?
echo "<BR><font color=\"blue\">Current Speed: ".(($lastRate/100)*2000)." ($lastRate%)<BR>";
echo "<font color=\"green\">Average Across Graph: ".round($avgPerc,2)."%<BR>";

$lines2=file("/tmp/Sm1.campaignProperties");
$first=true;
foreach ($lines2 as $line_num => $line) {
	if (strlen(trim($line))>0){
		if (substr(trim($line),0,3)=="Min") {
//			echo "<font color=\"black\">Minimum: ".substr(trim($line),5)."<BR>";
		}
		if (substr(trim($line),0,3)=="Tot") {
echo "<font color=\"red\">Current Percentage on Phone: $lastPerc%<BR><font color=\"black\">";
			echo "Busy Agents: <b>".substr(trim($line),7)."";
		}
		if (substr(trim($line),0,3)=="Max") {
			echo "/".substr(trim($line),5)."</b><BR>";
		}
		if (substr(trim($line),0,4)=="Done") {
			echo "Dialled: ".substr(trim($line),5)."<BR>";
		}
		if (substr(trim($line),0,4)=="Mult") {
			echo "Multiplyer: ".substr(trim($line),5)."<BR>";
		}
	}
}

?>
	</body>
</html>
