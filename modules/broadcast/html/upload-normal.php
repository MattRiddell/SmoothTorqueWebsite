<?php 
require_once("upload_helper.php"); 
$sid = md5(uniqid(rand()));
?>
<html>
<head>
	<title>File Upload</title>
	<script language="javascript" type="text/javascript" src="upload.js"></script>
	<link rel="stylesheet" href="upload.css" type="text/css" media="screen" title="Upload" charset="utf-8" />
  <script language="javascript">
    function beginUpload(sid) {
      document.postform.submit();
    	var pb = document.getElementById("progress");
    	pb.parentNode.parentNode.style.display='block';
    	new ProgressTracker(sid,{
    		progressBar: pb,
    		onFailure: function(msg) {
//    			Element.hide(pb.parentNode);
    			alert(msg);
    		}
    	});
    }
  </script>
</head>
<body>
	<h1>File Upload Demo</h1>
	<form enctype="multipart/form-data" name="postform" action="/cgi-bin/upload.cgi?sid=<?php echo $sid; ?>&target=<?php echo normal_target('receive.php') ?>" method="post">
		<div class="inputhead">Title</div>
		<input class="input" type="text" name="title" /><br/>
		<div class="inputhead">Body</div>
		<textarea class="input" name="body"></textarea><br/>
		<div class="inputhead">File 1</div>
		<input type="file" name="file_1" /><br/>
		<div class="inputhead">File 2</div>
		<input type="file" name="file_2" /><br/>
	</form>
	<input type="button" onclick="beginUpload('<?php echo $sid ?>');" value="Submit">
	<div id="progressbox" style="display: none;">
	Progress: <div class="progresscontainer"><div class="progressbar" id="progress"></div></div>
	</div>
</body>
</html>
