<?php
require_once("read_settings.php");
require_once("receive_helper.php");
?>
<html>
<head>
	<title>File Upload</title>
	<link rel="stylesheet" href="upload.css" type="text/css" media="screen" title="Upload" charset="utf-8" />
</head>
<body>
	<h1>Asynchronous File Upload Demo</h1>
	<?php if(!empty($data)): ?>
  <?php if(isset($data['title'])): ?>
	<div class="inputhead">Title</div>
	<div class="data"><?php echo $data['title']; ?></div>
	<?php endif ?>
	<?php if(isset($data['body'])): ?>
	<div class="inputhead">Body</div>
	<div class="data"><?php echo $data['body']; ?></div>
  <?php endif ?>
  <?php endif ?>
  <? if(!empty($files)): ?>
	<div class="inputhead">Files</div>
	<div class="data">
	  <ul>
	  <?php foreach($files as $file): ?>
	    <li><?php echo $file["name"]; ?> (<?php echo size_hum_read($file["size"])?>, "<?php echo $file["path"] ?>")</li>
	  <?php endforeach ?>
	  </ul>
	</div>
	<?php endif ?>
</body>
</html>