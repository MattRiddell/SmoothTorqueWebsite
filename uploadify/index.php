<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Uploadify Example Script</title>
<script type="text/javascript" src="jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="swfobject.js"></script>
<script type="text/javascript" src="jquery.uploadify.v2.1.0.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$("#uploadify").uploadify({
		'uploader'       : 'uploadify.swf',
		'script'         : 'uploadify.php?campaignid=<?=$_GET[',
		'cancelImg'      : 'cancel.png',
		'folder'         : 'uploads',
		'queueID'        : 'fileQueue',
		'auto'           : true,
		'multi'          : false,
		'onComplete'	 : function(event, queueID, fileObj, reposnse, data) {$('#filesUploaded').append('Uploaded: '+fileObj.name+'<br>');}
	});
});


</script>
</head>

<body>
<div id="fileQueue"></div>
<input type="file" name="uploadify" id="uploadify" />
<div id="filesUploaded"></div>
</body>
</html>

