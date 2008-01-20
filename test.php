<?
require "header.php";
require "header_campaign.php";
$id=$_GET[id];
if ($id<1){
        exit(0);
}
?><script> 
var webcamimage; 
var imgBase="/graph.php?id=<?echo $id;?>&" 
var c = 0; 
function count() 
{ 
 webcamimage.src=imgBase + (++c); 
} 
function init() 
{ 
 webcamimage = document.getElementById("webcamimage"); 
 if( webcamimage ) 
 { 
  setInterval("count()",5000); 
 } 
} 
window.onload = init; 
</script>
<img src="graph.php?id=<?echo $id;?>" name="image" id="webcamimage" border="0"><br />
<a href="stopcampaign.php?id=<?echo $id;?>"><img 
src="/images/control_stop_blue.png"  border="0">Stop This Campaign</a>

