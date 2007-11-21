<?php require_once("upload_helper.php"); ?>
<html>
<head>
	<title>File Upload</title>
  <script language="javascript" type="text/javascript" src="upload.js"></script>
	<link rel="stylesheet" href="upload.css" type="text/css" media="screen" title="Upload" charset="utf-8" />
  <script type="text/javascript">
    var uploads_in_progress = 0;

    function beginAsyncUpload(ul,sid) {		
      ul.form.submit();
    	uploads_in_progress = uploads_in_progress + 1;
    	var pb = document.getElementById(ul.name + "_progress");
    	pb.parentNode.style.display='block';
    	new ProgressTracker(sid,{
    		progressBar: pb,
    		onComplete: function() {
    			var inp_id = pb.id.replace("_progress","");
    			uploads_in_progress = uploads_in_progress - 1;
    			var inp = document.getElementById(inp_id);
    			if(inp) {
    				inp.value = sid;
    			}
    			pb.parentNode.style.display='none';
    		},
    		onFailure: function(msg) {
    			pb.parentNode.style.display='none';
    			alert(msg);
    			uploads_in_progress = uploads_in_progress - 1;
    		}
    	});
    }
    
    function submitUpload(frm) {
      if(uploads_in_progress > 0) {
        alert("File upload in progress. Please wait until upload finishes and try again.");
      } else {
        frm.submit();
      }
    }
	</script>
</head>
<body>
	<h1>Asynchronous File Upload Demo</h1>
	<form name="postform" action="receive.php" method="post">
    <input type="hidden" name="async" value="true" />
		<div class="inputhead">Title</div>
		<input class="input" type="text" name="title" /><br/>
		<div class="inputhead">Body</div>
		<textarea class="input" name="body"></textarea><br/>
		<?php echo async_upload_value('file_1');?>
	</form>
	<?php echo async_upload_form('file_1','File 1');?>
	<input type="button" onclick="submitUpload(document.postform);" value="Submit">
</body>
</html>