<html>
	<head>
	<title>Bindows gauge sample</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

	<!-- Import the bindows gauges javascriptpackage -->
	<script type="text/javascript" src="bindows_gauges/bindows_gauges.js"></script>

	</head>
	<body>

	<!-- Create a div to hold the gauge -->
	<div id="gaugeDiv" style="width: 400; height: 400" ></div>

	<script type="text/javascript">



var xmlhttp;
		// Load the gauge into the div
		var gauge = bindows.loadGaugeIntoDiv("gauges/mine.xml", "gaugeDiv");
// dynamically update the gauge at runtime
	var t = 0;
	function updateGauge() {
		showUser(0);
		//t += 1000;
		//gauge.needle.setValue( 500 + 500 * Math.sin(t/10000) );
	}
	updateGauge();
	setInterval(updateGauge, 5000);		// dynamically update the gauge at runtime
//		setInterval(updateClock, 1000);


function showUser(str)
{
xmlhttp=GetXmlHttpObject();
if (xmlhttp==null)
  {
  alert ("Browser does not support HTTP Request");
  return;
  }
var url="getuser.php";
url=url+"?q="+str;
url=url+"&sid="+Math.random();
xmlhttp.onreadystatechange=stateChanged;
xmlhttp.open("GET",url,true);
xmlhttp.send(null);
}

function stateChanged()
{
if (xmlhttp.readyState==4)
{
//document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
gauge.needle.setValue(xmlhttp.responseText);
}
}

function GetXmlHttpObject()
{
if (window.XMLHttpRequest)
  {
  // code for IE7+, Firefox, Chrome, Opera, Safari
  return new XMLHttpRequest();
  }
if (window.ActiveXObject)
  {
  // code for IE6, IE5
  return new ActiveXObject("Microsoft.XMLHTTP");
  }
return null;
}





	</script>
	</body>
	</html>
