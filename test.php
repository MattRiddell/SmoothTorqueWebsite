<?
if (isset($_GET['change_target'])) {
    print_r($_GET);
    echo $_GET['change_target'];
    exit(0);
}
require "header.php";
require "header_campaign.php";
include "admin/db_config.php";
mysql_select_db("SineDialer", $link);
$id=$_GET[id];
$debug=$_GET[debug];

$result = mysql_query("SELECT * FROM SineDialer.config WHERE parameter = 'm_c_stats'");
if (!(mysql_num_rows($result) > 0)) {
    
    
    if (file_exists("/tmp/Sm".$id.".campaignProperties") == false) {
        if (isset($_GET[secondtime])) {
            echo "Your campaign has finished";
        } else {
            echo '<META HTTP-EQUIV=REFRESH CONTENT="3; URL=test.php?id='.$_GET['id'].'&secondtime=yes">';
        }
        exit(0);
    }
    if (file_exists("/tmp/Sm".$id.".console") == false) {
        echo "There is no information for this campaign (Console missing)";
        echo '<META HTTP-EQUIV=REFRESH CONTENT="3; URL=test.php?id='.$_GET['id'].'">';
        exit(0);
    }
    if (file_exists("/tmp/Sm".$id.".console2") == false) {
        echo "There is no information for this campaign (Console2 missing)";
        echo '<META HTTP-EQUIV=REFRESH CONTENT="3; URL=test.php?id='.$_GET['id'].'">';
        exit(0);
    }
    if (file_exists("/tmp/Sm".$id.".console3") == false) {
        echo "There is no information for this campaign (Console3 missing)";
        echo '<META HTTP-EQUIV=REFRESH CONTENT="3; URL=test.php?id='.$_GET['id'].'">';
        exit(0);
    }
    if ($id<1){
        exit(0);
    }
}
?><script>
var webcamimage;
<?
if (file_exists("images/live/campaign_".$id.".png")) {
    if ($debug > 0) {
        ?>
        var imgBase="images/live/debug_<?echo $id;?>.png?x="
        <?
    } else {
        ?>
        var imgBase="images/live/campaign_<?echo $id;?>.png?x="
        <?
    }
} else {
    ?>
    var imgBase="graph.php?id=<?echo $id;?>&debug=<?echo $debug;?>&x="
    <?
}
?>
var c = 0;
var x = 0;
var done_image = 0;
function count()
{
    x = 0;
    waiting = document.getElementById("div_waiting");
    done_image = 0;
    waiting.innerHTML = '<img src="images/ajax-loader.gif">';
    webcamimage.src=imgBase + (++c) + '&rand='+Math.random();
}
function init()
{
    webcamimage = document.getElementById("image_webcamimage");
    if( webcamimage )
    {
        setInterval("count()",10000);
    }
    setInterval("incr()",1000);
    incr();
}
function incr() {
    waiting = document.getElementById("div_waiting");
    if (done_image ==1) {
        waiting.innerHTML = '<font color="#008800"><b>Next update in '+(10-x)+' seconds';
    } else {
        waiting.innerHTML = '<img src="images/ajax-loader.gif">';
    }
    x++;
}
function hide_image()
{
    done_image = 1;
    waiting = document.getElementById("div_waiting");
    waiting.innerHTML = '<font color="#008800"><b>Next update in '+(10-x)+' seconds';
}
window.onload = init;
</script>
<?box_start(600);

//echo $_GET[id];
echo "<center>";
$result = mysql_query("SELECT * FROM campaign where id = ".sanitize($_GET[id])) or die(mysql_error());
if (mysql_num_rows($result) > 0) {
	$row = mysql_fetch_assoc($result);
	echo "<b>".$row[description]."</b>";
} else {
	echo "No name found";
}
?>
<br />
<a href="stopcampaign.php?id=<?echo $id;?>"><img src="images/control_stop_blue.png"  border="0"> Stop This Campaign</a>&nbsp;
<a href="report<?if ($use_new_pie == 1) {echo "2";}?>.php?id=<?echo $id;?>"><img src="images/chart_pie.png"  border="0"> Number Stats</a>&nbsp;
<a href="report3.php?id=<?echo $id;?>"><img src="images/chart_line.png"  border="0"> Daily Number Stats</a>&nbsp;
<br />
<div id="div_waiting" style="height:20px">
<img src="images/ajax-loader.gif">
</div>

<?if ($config_values['use_configurable_target'] == 1) {?>

<link type="text/css" rel="StyleSheet" href="css/bluecurve/bluecurve.css" />

<style type="text/css">


body {
font:		MessageBox;	
font:		Message-Box;
}


input {
width:				50px;
	text-align:			right;
padding:			1px;
	margin-right:		15px;
}

input, select, button {
	vertical-align:		middle;
}

#slider-1 {
margin:	10px;
width:	340px;
border: 1px solid #ccc;
float: left;
}

</style>


<div class="slider" id="slider-1" tabIndex="1">
<input class="slider-input" id="slider-input-1" />
</div>

<p>
Target Percentage: 
<span id="val">Default
</span>
</p>
<br />




<script type="text/javascript">

function getHTTPObject() { if (typeof XMLHttpRequest != 'undefined') { return new XMLHttpRequest(); } try { return new ActiveXObject("Msxml2.XMLHTTP"); } catch (e) { try { return new ActiveXObject("Microsoft.XMLHTTP"); } catch (e) {} } return false; }
var s = new Slider(document.getElementById("slider-1"), document.getElementById("slider-input-1"));
var isMouseDown = false;
document.onmouseup = function () {
    if (isMouseDown == 1) {
        isMouseDown = 0;
        sendData();
    }
}
s.onchange = function () {
    document.getElementById("val").innerHTML = s.getValue();
    isMouseDown = 1;
};

function sendData() {
    var http = getHTTPObject(); 
    try {
        http.open("GET", "test.php?id=<?=$_GET['id']?>&change_target="+s.getValue(), true); 
    } catch (err) {
        alert(err);
    }
    try {
        http.onreadystatechange = function() { 
            if (http.readyState == 4) { 
                alert(http.responseText); 
            } 
            
        } 
    } catch (err) {
        alert(err);
    }
    http.send(null);
}

s.setValue(0);
s.setMaximum(99);
var tx=setTimeout(timedOut,1000);


window.onresize = function () {
	s.recalculate();
};

function timedOut() {
	s.recalculate();
}

</script>


<?
}
?>


<?box_end();?>
<img src="graph.php?id=<?echo $id;?>&debug=<?echo $debug;?>" name="image" id="image_webcamimage" border="0" onload="hide_image();"><br />
<?
require "footer.php";
?>
