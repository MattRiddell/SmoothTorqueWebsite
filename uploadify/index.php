<?
require "../header.php";
?>
<style>
.uploadifyProgressBar {
	background-color: #0099FF;
	width: 1px;
	height: 3px;
}

.uploadifyQueueItem {
	font: 11px Verdana, Geneva, sans-serif;
	border: 2px solid #E5E5E5;
	background-color: #F5F5F5;
	margin-top: 5px;
	padding: 10px;
	width: 350px;
}
.uploadifyError {
	border: 2px solid #FBCBBC !important;
	background-color: #FDE5DD !important;
}
.uploadifyQueueItem .cancel {
	float: right;
}
.uploadifyProgress {
	background-color: #FFFFFF;
	border-top: 1px solid #808080;
	border-left: 1px solid #808080;
	border-right: 1px solid #C5C5C5;
	border-bottom: 1px solid #C5C5C5;
	margin-top: 10px;
	width: 100%;
}
</style>
<script type="text/javascript" src="jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="swfobject.js"></script>
<script type="text/javascript" src="jquery.uploadify.v2.1.0.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$("#uploadify").uploadify({
		'uploader'       : 'uploadify.swf',
		'script'         : 'uploadify.php?campaignid=<?=$_POST[campaignid]?>',
		'cancelImg'      : 'cancel.png',
		'folder'         : 'uploads',
		'queueID'        : 'fileQueue',
		'auto'           : true,
		'multi'          : false,
		'onError'	 : function() {alert('error');},
		'onComplete'	 : function(event, queueID, fileObj, response, data) {$('#filesUploaded').append('Uploaded: '+fileObj.name+' '+response+'<br>');}
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

