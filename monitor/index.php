<html>
    <head>
<!-- CSS -->
<link rel="stylesheet" type="text/css" href="chart/includes/canvaschart.css" />

<!-- JavaScript -->
<script type="text/javascript" src="chart/includes/excanvas.js"></script>
<script type="text/javascript" src="chart/includes/chart.js"></script>
<script type="text/javascript" src="chart/includes/canvaschartpainter.js"></script>
<script type="text/javascript">
    var chChart;
    var chart_data = new Object();
    var chart_data_read = new Array();
    var chart_data_update = new Array();
    var chart_data_del = new Array();
    var chart_data_insert = new Array();
    var chart_data_queue = new Array();
    var labels = new Array();
    for (i = 0;i<100;i++) {
        chart_data_read[i] = 0;
        chart_data_update[i] = 0;
        chart_data_insert[i] = 0;
        chart_data_del[i] = 0;
        chart_data_queue[i] = 0;
    }
    for (i = 0;i<10;i++) {
        labels[i] = 100-i*10;
    }
</script>


    </head>
<body onload="setInterval(sendNext,1500);">
    <!-- Chart display DIV -->
<div id="demo_chart" class="chart"></div>

<script type="text/javascript">
    // Chart initialisation

    chChart = new Chart('demo_chart');

    //chChart.setDefaultType (CHART_AREA |CHART_STACKED);
    chChart.setDefaultType (CHART_BAR|CHART_STACKED);
    chChart.setGridDensity (10, 11);
    chChart.setVerticalRange (0, 1000);
    chChart.setHorizontalLabels (labels);
    // Add data
    chChart.add ('Read', '#40FF40', chart_data_read);
    chChart.add ('Update', '#4040FF', chart_data_update);
    chChart.add ('Delete', '#FF4040', chart_data_del);
    chChart.add ('Insert', '#FFFF00', chart_data_insert);
    chChart.add ('Queue', '#000000', chart_data_queue);
    // Draw chart
    chChart.draw();
</script>
<script type="text/javascript">
    //alert('doing');

    //chChart.add ('Spam2', '#4040FF', [ 24, 25, 28, 22, 28, 24, 22 ]);
    //chChart.draw();
    //alert('done');
    //setInterval(change, 500);

    function rotate(a /*array*/, p /* integer, positive integer rotate to the right, negative to the left... */){ //v1.0

        for(var l = a.length, p = (Math.abs(p) >= l && (p %= l), p < 0 && (p += l), p), i, x; p; p = (Math.ceil(l / p) - 1) * p - l + (l = p))
            for(i = l; i > p; x = a[--i], a[i] = a[i - p], a[i - p] = x);
        return a;
    };

    function change_values( val_read,val_update,val_delete,val_insert,val_queue) {

        chart_data_read = rotate(chart_data_read,-1);
        chart_data_read[99]=val_read;

        chart_data_update = rotate(chart_data_update,-1);
        chart_data_update[99]=val_update;

        chart_data_del = rotate(chart_data_del,-1);
        chart_data_del[99]=val_delete;

        chart_data_insert = rotate(chart_data_insert,-1);
        chart_data_insert[99]=val_insert;

        chart_data_queue = rotate(chart_data_queue,-1);
        chart_data_queue[99]=val_queue;

        chChart.changeSeriesValue('Read', chart_data_read.slice());
        chChart.changeSeriesValue('Update', chart_data_update.slice());
        chChart.changeSeriesValue('Delete', chart_data_del.slice());
        chChart.changeSeriesValue('Insert', chart_data_insert.slice());
        chChart.changeSeriesValue('Queue', chart_data_queue.slice());
        chChart.setVerticalRange (0, 1000);

        chChart.draw();
    }
    //function change(){
    //    change_values() Math.random()*250,Math.random()*250,Math.random()*250,Math.random()*250);
    //}

            var xmlhttp;
            var x = 0;
            var sending=new Array();
            var waits=new Array();
            for (i = 0;i< 6;i++) {
                sending[i] = false;
                waits[i] = 0;
            }

            function sendNext() {
                if (!(x > 0)) {
                    x = 0;
                }
                //alert(x);
                x++;
                if (x > 4) {
                    x = 1;
                }
                x = 4;
                if (!sending[x]) {
                    sending[x] = true;
                    showUser(""+x);
                } else {
                    waits[x]++;
                    // If we've waited too long, just do it again anyway
                    if (waits[x] > 0) {
                        sending[x] = true;
                        showUser(""+x);
                    }
                }
                //alert(x);
                //t = setTimeout(sendNext(x), 10000);
            }

            function showUser(str) {
                waits[str] = 0;
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
                    //alert(xmlhttp.readyState);
                    if (xmlhttp.readyState==4)
                        {
                            //document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
                            var resp = xmlhttp.responseText;
                            var splitted = resp.split(":");
                            sending[splitted[0]] = false;
                            if (splitted[0] == "1") {
//                                alert("Got response from 1");
//                                change_values( splitted[1],0,0,0)
                            } else if (splitted[0] == "2") {
//                                gauge2.needle.setValue(splitted[1]);
                            } else if (splitted[0] == "3") {
  //                              gauge3.needle.setValue(splitted[1]);
                            } else if (splitted[0] == "4") {
                                //alert("Got response from 4"+splitted[1]);
                                change_values( 0+splitted[1],0+splitted[2],0+splitted[3],0+splitted[4],0+splitted[5])

//                            gauge4.needle.setValue(splitted[1]);
                            }/* else if (splitted[0] == "5") {
                                gauge5.needle.setValue(splitted[1]);
                            } else {
                                gauge6.needle.setValue(splitted[1]);
                            }*/
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

<?

exit(0);
?>
<html>
	<head>
            <title>SmoothTorque MySQL Monitor</title>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            <script type="text/javascript" src="bindows_gauges/bindows_gauges.js"></script>
	</head>
	<body bgcolor="#000000" onload="setInterval(sendNext,500);">

<table>
    <tr>
    <td>
        <div id="gaugeDiv" style="width: 300px; height: 300px;" ></div>
    </td>
    <td>
        <div id="gaugeDiv2" style="width: 300px; height: 300px;" ></div>
    </td>
    <td>
        <div id="gaugeDiv3" style="width: 300px; height: 300px;" ></div>
    </td>
    <td>
        <div id="gaugeDiv4" style="width: 300px; height: 300px;" ></div>
    </td>
    </tr>
    <tr>
    <td><?
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
                        $('#ajaxDiv').loadIfModified('/mysql_details.php?ajax=1');  // jquery ajax load into div
                },2000);
        });

</script>
<?} else {?>
<script type="text/javascript" src="/ajax/jquery.js"></script>
<script type="text/javascript">

        $(function(){ // jquery onload
                window.setInterval(
                function(){
                        $('#ajaxDiv').load('/mysql_details.php?ajax=1');  // jquery ajax load into div
                }
                ,2000);
        });
</script>
<?}

 /*
    <tr>
    <td>
        <div id="gaugeDiv5" style="width: 300px; height: 300px;" ></div>
    </td>
    <td>
        <div id="gaugeDiv6" style="width: 300px; height: 300px;" ></div>
    </td>
    </tr>*/?>
<div id="ajaxDiv"></div>
    </td>
    </tr>
</table>


















	<script type="text/javascript">
            var xmlhttp;
            // Load the gauge into the div
            var gauge = bindows.loadGaugeIntoDiv("gauges/g_speedometer_03.xml", "gaugeDiv");
            var gauge2 = bindows.loadGaugeIntoDiv("gauges/g_speedometer_03.xml", "gaugeDiv2");
            var gauge3 = bindows.loadGaugeIntoDiv("gauges/g_speedometer_03.xml", "gaugeDiv3");
            var gauge4 = bindows.loadGaugeIntoDiv("gauges/g_speedometer_03.xml", "gaugeDiv4");
            /*var gauge5 = bindows.loadGaugeIntoDiv("gauges/g_speedometer_03.xml", "gaugeDiv5");
            var gauge6 = bindows.loadGaugeIntoDiv("gauges/g_speedometer_03.xml", "gaugeDiv6");
*/
            // dynamically update the gauge at runtime
            //var t = 0;
            //var interval = 1000;
            /*
            setInterval(showUser('1'), 1000);
            setInterval(showUser('2'), 1111);
            setInterval(showUser('3'), 2222);
            setInterval(showUser('4'), 3333);
            setInterval(showUser('5'), 4444);
            setInterval(showUser('6'), 5555);*/
            //var c = 0;
            //setInterval(sendNext(), 10000);
            //c++;
            var x = 0;
            var sending=new Array();
            var waits=new Array();
            for (i = 0;i< 6;i++) {
                sending[i] = false;
                waits[i] = 0;
            }

            function sendNext() {
                if (!(x > 0)) {
                    x = 0;
                }
                //alert(x);
                x++;
                if (x > 4) {
                    x = 1;
                }
                if (!sending[x]) {
                    sending[x] = true;
                    showUser(""+x);
                } else {
                    waits[x]++;
                    // If we've waited too long, just do it again anyway
                    if (waits[x] > 0) {
                        sending[x] = true;
                        showUser(""+x);
                    }
                }
                //alert(x);
                //t = setTimeout(sendNext(x), 10000);
            }

            function showUser(str) {
                waits[str] = 0;
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
                    //alert(xmlhttp.readyState);
                    if (xmlhttp.readyState==4)
                        {
                            //document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
                            var resp = xmlhttp.responseText;
                            var splitted = resp.split(":");
                            sending[splitted[0]] = false;
                            if (splitted[0] == "1") {
                                gauge.needle.setValue(splitted[1]);
                            } else if (splitted[0] == "2") {
                                gauge2.needle.setValue(splitted[1]);
                            } else if (splitted[0] == "3") {
                                gauge3.needle.setValue(splitted[1]);
                            } else if (splitted[0] == "4") {
                                gauge4.needle.setValue(splitted[1]);
                            }/* else if (splitted[0] == "5") {
                                gauge5.needle.setValue(splitted[1]);
                            } else {
                                gauge6.needle.setValue(splitted[1]);
                            }*/
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
