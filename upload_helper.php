<?php
require_once("read_settings.php");

if(!is_writable($tmp_dir)) {
	die("Error: PHP can't write to temp dir ($tmp_dir).<br />");
}
if(!is_writable($upload_dir)) {
	die("Error: PHP can't write to upload dir ($upload_dir).<br />");
}

/* url to upload to */
function upload_url($sid) {
  global $cgi_dir;
  return $cgi_dir."/upload.cgi?sid=$sid";
}

/* Form targeting hidden iframe necessary for asynchronous uploads */
function async_upload_form($name,$title,$url="") {
	global $cgi_dir;
	$sid = md5(uniqid(rand()));
	if(empty($url)) {
		$url = $cgi_dir."/upload.cgi?sid=$sid";
	}
	?>
	<form enctype="multipart/form-data" action="<?php echo upload_url($sid) ?>" method="post" target="iframe_<?php echo $name; ?>" />
		<div class="inputhead"><?php echo $title; ?></div>
		<input class="input" type="file" name="<?php echo $name; ?>" onchange="beginAsyncUpload(this,'<?php echo $sid; ?>');" />
		<div class="progresscontainer" style="display: none;"><div class="progressbar" id="<?php echo $name ?>_progress"></div></div>
	</form>
	<iframe name="iframe_<?php echo $name; ?>" style="border: 0;width: 0px;height: 0px;"></iframe>
	<?php
}

function async_upload_value($name) {
	?>
	<input id="<?php echo $name; ?>" type="hidden" name="<?php echo $name; ?>" value="" />
	<?php
}

function normal_target($phpfile) {
  return dirname($_SERVER["SCRIPT_NAME"])."/".$phpfile;
}
?>
