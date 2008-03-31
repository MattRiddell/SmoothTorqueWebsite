<html>
<head><title>SmoothTorque Linux Help Manual<title></head>


<script language="JavaScript" type="text/JavaScript">
function hideshow(name){
	var element = document.getElementById(name);
	if(element.style.display == "none")
		element.style.display="";
	else
		element.style.display = "none";
}

</script>
<body>
<?php
echo '<h1>Normal User</h1>';
echo '<H2 onClick=hideshow("quick_start")>Quick Start</h2>';
echo '<div id="quick_start">';
include 'src/quickstart.php';
echo '</div>';
echo '<h2>Messages</h2>';


?>
</body>
</html>
